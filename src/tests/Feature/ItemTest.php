<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Address;
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
}
