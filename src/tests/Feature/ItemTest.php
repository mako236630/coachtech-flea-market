<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Address;
use App\Models\Favorite;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\UsersTableSeeder;

class ItemTest extends TestCase

{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_商品一覧で全商品が表示、購入済みの商品はsoldが表示される()
    {
        User::factory()->create();

        $this->seed(UsersTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ConditionsTableSeeder::class);

        $this->seed(ItemsTableSeeder::class);

        $response = $this->get("/");

        $response->assertStatus(200);
        $response->assertSee("sold");
    }

    public function test_自分が出品した商品は表示されない()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            ]);

        Address::factory()->create([
            "user_id" => $user->id,
            "postcode" => "123-4567",
            "address" => "東京都...",
        ]);

        $this->seed(ConditionsTableSeeder::class);

        $item = Item::create([
            "name" => "自分が出した商品",
            "brand_name" => "テスト",
            "description" => "テスト説明",
            "price" => 1000,
            "image" => "test.jpg",
            "condition_id" => 1,
            "user_id" => $user->id,
            "is_sold" => false,
        ]);

        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        $response->assertDontSee($item->name);
    }

    public function test_マイリストいいねした商品だけが表示・購入済みはsoldと表示()
    {
        // ログインユーザー
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        // 出品者
        $user2 = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        Address::factory()->create([
            "user_id" => $user->id,
            "postcode" => "123-4567",
            "address" => "東京都...",
        ]);

        $this->seed(ConditionsTableSeeder::class);

        // いいねした商品(販売中)
        $item = Item::create([
            "name" => "いいねした商品",
            "brand_name" => "テスト",
            "description" => "テスト説明",
            "price" => 1000,
            "image" => "test.jpg",
            "condition_id" => 1,
            "user_id" => $user2->id,
            "is_sold" => false,
        ]);

        // いいねした商品(sold)
        $item2 = Item::create([
            "name" => "いいねした商品2",
            "brand_name" => "テスト",
            "description" => "テスト説明",
            "price" => 1000,
            "image" => "test.jpg",
            "condition_id" => 1,
            "user_id" => $user2->id,
            "is_sold" => true,
            "buyer_id" => $user->id,
        ]);

        // $item2のいいねした人
        Favorite::create([
            "user_id" => $user->id,
            "item_id" => $item2->id,
        ]);

        // $itemのいいねした人
        Favorite::create([
            "user_id" => $user->id,
            "item_id" => $item->id,
        ]);

        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)->get('/?tab=mylist');
        $response->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSee("sold");
    }

    public function test_未認証の場合はマイリストに何も表示されない()
    {

        // ゲストがマイリストに遷移した場合、マイリストは会員ユーザーのみ利用できる為ログイン画面に誘導しています。
        // ↑なので今回は、ゲストがマイリストに遷移した場合、ログイン画面に誘導するテストを実行しました
        $response = $this->get('/?tab=mylist');
        $response->assertRedirect('/login');
    }

    public function test_商品名で部分一致検索ができる()
    {
        $user = User::factory()->create();

        $this->seed(ConditionsTableSeeder::class);

        $item = Item::create([
            "name" => "時計",
            "brand_name" => "テスト",
            "description" => "テスト説明",
            "price" => 1000,
            "image" => "test.jpg",
            "condition_id" => 1,
            "user_id" => $user->id,
            "is_sold" => false,
        ]);

        $item2 = Item::create([
            "name" => "鞄",
            "brand_name" => "テスト",
            "description" => "テスト説明",
            "price" => 1000,
            "image" => "test.jpg",
            "condition_id" => 1,
            "user_id" => $user->id,
            "is_sold" => true,
            "buyer_id" => $user->id,
        ]);

        $response = $this->get('/?keyword=時');
        $response->assertStatus(200);
        $response->assertSee("時計");
        $response->assertDontSee("鞄");
    }

    public function test_マイリストでも検索状態が保持()
    {
        // ログインユーザー
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        
        // 出品者
        $user2 = User::factory()->create();

        Address::factory()->create([
            "user_id" => $user->id,
            "postcode" => "123-4567",
            "address" => "東京都...",
        ]);

        $this->seed(ConditionsTableSeeder::class);

        $item = Item::create([
            "name" => "時計",
            "brand_name" => "テスト",
            "description" => "テスト説明",
            "price" => 1000,
            "image" => "test.jpg",
            "condition_id" => 1,
            "user_id" => $user2->id,
            "is_sold" => false,
        ]);

        $item2 = Item::create([
            "name" => "鞄",
            "brand_name" => "テスト",
            "description" => "テスト説明",
            "price" => 1000,
            "image" => "test.jpg",
            "condition_id" => 1,
            "user_id" => $user2->id,
            "is_sold" => true,
            "buyer_id" => $user->id,
        ]);

        // いいねした商品が検索に引っかかるかテストします
        Favorite::create([
            "user_id" => $user->id,
            "item_id" => $item->id,
        ]);

        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)->get('/?keyword=時&tab=mylist');
        $response->assertStatus(200);
        $response->assertSee("時計");
        $response->assertDontSee("鞄");
    }

}
