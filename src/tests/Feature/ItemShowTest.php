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

class ItemShowTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_商品詳細の必要な情報が表示される()
    {
        User::factory()->create();

        $this->seed(UsersTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ConditionsTableSeeder::class);

        $this->seed(ItemsTableSeeder::class);

        //　特定の商品の詳細を確認したいので最初の1件を取得してみます
        $item = Item::with(["categories", "condition", "favorites", "comments.user"])->first();

        $comment = $item->comments->first();

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);
        // 最初の1件の腕時計が表示されるかテストします
        $response->assertSee($item->image);
        $response->assertSee("腕時計");
        $response->assertSee($item->brand_name);
        $response->assertSee(number_format($item->price));
        $response->assertSee($item->favorites->count());
        $response->assertSee($item->comments->count());
        $response->assertSee($item->description);
        $response->assertSee($item->condition->name);
        $response->assertSee($comment->user->image);
        $response->assertSee($comment->user->name);
        $response->assertSee($comment->comment);

        // 複数のカテゴリーが表示されてるか
        foreach ($item->categories as $category) {
            $response->assertSee($category->name);
        }
    }

    public function test_いいねを登録することが出来る()
    {
        // いいねするユーザー
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        // 今回の取得する商品はコメントも入っているので、コメントをしたユーザーも作成しないとエラーになります
        User::factory()->create();

        Address::factory()->create([
            "user_id" => $user->id,
            "postcode" => "123-4567",
            "address" => "東京都...",
        ]);

        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        $item = Item::first();


        /** @var \App\Models\User $user */
        $this->actingAs($user)->post("/item/{$item->id}/favorite");
        $this->assertDatabaseHas("favorites", [
            "user_id" => $user->id,
            "item_id" => $item->id,
        ]);

        $response = $this->get("/item/{$item->id}");
        // freshで最新の言い値情報を表示します
        $response->assertSee($item->fresh()->favorites->count());
    }

    public function test_いいね追加済みのアイコンは色が変化する()
    {
        // いいねするユーザー
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        // 今回の取得する商品はコメントも入っているので、コメントをしたユーザーも作成しないとエラーになります
        User::factory()->create();

        Address::factory()->create([
            "user_id" => $user->id,
            "postcode" => "123-4567",
            "address" => "東京都...",
        ]);

        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        $item = Item::first();


        /** @var \App\Models\User $user */

        // いいねを押す処理
        $response = $this->actingAs($user)->post("/item/{$item->id}/favorite");
        // いいねを押してからもう一度ページを確認
        $response = $this->get("/item/{$item->id}");

        $this->assertDatabaseHas("favorites", [
            "user_id" => $user->id,
            "item_id" => $item->id,
        ]);

        $response->assertSee('images/heart-pink.png');
    }

    public function test_再度いいねを押下するといいねを解除することが出来る()
    {
        // 再度いいねするユーザー
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        // 今回の取得する商品はコメントも入っているので、コメントをしたユーザーも作成しないとエラーになります
        User::factory()->create();

        Address::factory()->create([
            "user_id" => $user->id,
            "postcode" => "123-4567",
            "address" => "東京都...",
        ]);

        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        $item = Item::first();


        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)->post("/item/{$item->id}/favorite");
        // 再度いいねを押す
        $response = $this->actingAs($user)->post("/item/{$item->id}/favorite");
        $response = $this->get("/item/{$item->id}");

        $this->assertDatabaseMissing("favorites", [
            "user_id" => $user->id,
            "item_id" => $item->id,
        ]);

        $response->assertSee('images/heart.png');
    }

    public function test_ログイン済みのユーザーはコメントを送信できる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        // 今回の取得する商品はコメントも入っているので、コメントをしたユーザーも作成しないとエラーになります
        User::factory()->create();

        Address::factory()->create([
            "user_id" => $user->id,
            "postcode" => "123-4567",
            "address" => "東京都...",
        ]);

        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        $item = Item::first();

        $myComment = "テスト専用コメント";

        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)->post("/item/{$item->id}/comment", [
            "comment" => $myComment,
        ]);

        $response = $this->get("/item/{$item->id}");

        $this->assertDatabaseHas("comments", [
            "user_id" => $user->id,
            "item_id" => $item->id,
            "comment" => $myComment,
        ]);

        $response->assertSee($item->fresh()->comments->count());
    }
}
