<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Comment;
use App\Models\Address;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\ConditionsTableSeeder;


class PurchaseTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    //コンビニ決済の場合
    public function test_購入するボタンを押すと購入が完了する()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        // 出品者
        $user2 = User::factory()->create();

        Address::create([
            "user_id" => $user->id,
            "postcode" => "123-4567",
            "address" => "テスト1-2-3",
        ]);

        $this->seed(ConditionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        $item = Item::factory()->create([
            "name" => "時計",
            "brand_name" => "テスト",
            "description" => "テスト説明",
            "price" => 1000,
            "image" => "test.jpg",
            "condition_id" => 1,
            "user_id" => $user2->id,
            "is_sold" => false,
        ]);

        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)->post("/purchase/checkout/{$item->id}", [
            "payment_method" => "convenience",
        ]);

        $this->assertDatabaseHas("items", [
            "id" => $item->id,
            "is_sold" => true,
            "buyer_id" => $user->id,
        ]);

        $response->assertRedirect("/");
    }

    public function test_カード決済で購入するボタンを押すと購入が完了する()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        // 出品者
        $user2 = User::factory()->create();

        Address::create([
            "user_id" => $user->id,
            "postcode" => "123-4567",
            "address" => "テスト1-2-3",
        ]);

        $this->seed(ConditionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        $item = Item::factory()->create([
            "name" => "時計",
            "brand_name" => "テスト",
            "description" => "テスト説明",
            "price" => 1000,
            "image" => "test.jpg",
            "condition_id" => 1,
            "user_id" => $user2->id,
            "is_sold" => false,
        ]);

        $this->mock('alias:Stripe\Checkout\Session', function ($mock) {
            $mock->shouldReceive('create')->andReturn((object)['url' => 'http://dummy-url']);
            $mock->shouldReceive('retrieve')->andReturn((object)['payment_status' => 'paid']);
        });

        // 決済成功したことにして、DBが更新されるかチェック
        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)->get("/purchase/success/{$item->id}?session_id=test_id");

        $this->assertDatabaseHas("items", [
            "id" => $item->id,
            "is_sold" => true,
            "buyer_id" => $user->id,
        ]);

        $response->assertRedirect("/");
    }

    public function test_購入した商品は商品一覧でsoldと表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        // 出品者
        $user2 = User::factory()->create();

        Address::create([
            "user_id" => $user->id,
            "postcode" => "123-4567",
            "address" => "テスト1-2-3",
        ]);

        $this->seed(ConditionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        $item = Item::factory()->create([
            "name" => "時計",
            "brand_name" => "テスト",
            "description" => "テスト説明",
            "price" => 1000,
            "image" => "test.jpg",
            "condition_id" => 1,
            "user_id" => $user2->id,
            "is_sold" => false,
        ]);

        $this->mock('alias:Stripe\Checkout\Session', function ($mock) {
            $mock->shouldReceive('create')->andReturn((object)['url' => 'http://dummy-url']);
            $mock->shouldReceive('retrieve')->andReturn((object)['payment_status' => 'paid']);
        });

        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)->get("/purchase/success/{$item->id}?session_id=test_id");
        $response->assertRedirect("/");

        $topPageResponse = $this->get("/");
        $topPageResponse->assertSee("sold");
    }

    public function test_プロフィールで購入した商品一覧に追加されている()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        // 出品者
        $user2 = User::factory()->create();

        Address::create([
            "user_id" => $user->id,
            "postcode" => "123-4567",
            "address" => "テスト1-2-3",
        ]);

        $this->seed(ConditionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        $item = Item::factory()->create([
            "name" => "時計",
            "brand_name" => "テスト",
            "description" => "テスト説明",
            "price" => 1000,
            "image" => "test.jpg",
            "condition_id" => 1,
            "user_id" => $user2->id,
            "is_sold" => false,
        ]);

        $this->mock('alias:Stripe\Checkout\Session', function ($mock) {
            $mock->shouldReceive('create')->andReturn((object)['url' => 'http://dummy-url']);
            $mock->shouldReceive('retrieve')->andReturn((object)['payment_status' => 'paid']);
        });

        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)->get("/purchase/success/{$item->id}?session_id=test_id");

        $response = $this->actingAs($user)->get("/mypage?page=buy");
        $response->assertSee($item->name);
    }

    // 「小計画面で支払方法が反映・変更される」
    // この画面ではJavaScriptを使用して、プルダウンの選択内容を
    // リアルタイムで小計欄に反映させています。
    // そのため、テストでは「反映先のHTML要素（id）」が存在することを確認します。
    public function test_小計画面で支払方法が反映・変更される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        // 出品者
        $user2 = User::factory()->create();

        Address::create([
            "user_id" => $user->id,
            "postcode" => "123-4567",
            "address" => "テスト1-2-3",
        ]);

        $this->seed(ConditionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        $item = Item::factory()->create([
            "name" => "時計",
            "brand_name" => "テスト",
            "description" => "テスト説明",
            "price" => 1000,
            "image" => "test.jpg",
            "condition_id" => 1,
            "user_id" => $user2->id,
            "is_sold" => false,
        ]);
        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)->get("/purchase/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('id="payment_method-display"', false);
    }

    public function test_送付先住所変更画面で登録した住所が商品購入画面に反映されてる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        // 出品者
        $user2 = User::factory()->create();

        Address::create([
            "user_id" => $user->id,
            "postcode" => "123-4567",
            "address" => "テスト1-2-3",
        ]);

        $this->seed(ConditionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        $item = Item::factory()->create([
            "name" => "時計",
            "brand_name" => "テスト",
            "description" => "テスト説明",
            "price" => 1000,
            "image" => "test.jpg",
            "condition_id" => 1,
            "user_id" => $user2->id,
            "is_sold" => false,
        ]);

        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)->post("/purchase/address/{$item->id}", [
            "postcode" => "789-4561",
            "address" => "宮城県仙台市1-2-3",
            "building" => "テストビル101",
        ]);

        $response->assertSessionHas('new_shipping', [
            "postcode" => "789-4561",
            "address" => "宮城県仙台市1-2-3",
            "building" => "テストビル101",
        ]);

        $response = $this->actingAs($user)->get("/purchase/{$item->id}");
        $response->assertSee("789-4561");
        $response->assertSee("宮城県仙台市1-2-3");
    }

    public function test_購入した商品に送付先住所が紐づいて登録される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        // 出品者
        $user2 = User::factory()->create();

        Address::create([
            "user_id" => $user->id,
            "postcode" => "123-4567",
            "address" => "テスト1-2-3",
        ]);

        $this->seed(ConditionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        $item = Item::factory()->create([
            "name" => "時計",
            "brand_name" => "テスト",
            "description" => "テスト説明",
            "price" => 1000,
            "image" => "test.jpg",
            "condition_id" => 1,
            "user_id" => $user2->id,
            "is_sold" => false,
        ]);

        $this->mock('alias:Stripe\Checkout\Session', function ($mock) {
            $mock->shouldReceive('create')->andReturn((object)['url' => 'http://dummy-url']);
            $mock->shouldReceive('retrieve')->andReturn((object)['payment_status' => 'paid']);
        });

        /** @var \App\Models\User $user */
        $this->actingAs($user)->post("/purchase/address/{$item->id}", [
            "postcode" => "789-4561",
            "address" => "宮城県仙台市1-2-3",
        ]);

        $this->actingAs($user)->get("/purchase/success/{$item->id}?session_id=test_session");

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'buyer_id' => $user->id,
            'is_sold' => true,
            'shipping_postcode' => "789-4561",
            'shipping_address' => "宮城県仙台市1-2-3",
        ]);
    }
}
