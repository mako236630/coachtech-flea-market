<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Item;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\ConditionsTableSeeder;
use Illuminate\Http\UploadedFile;

class SellTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_商品出品画面で必要な情報が保存できる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        Address::factory()->create([
            "user_id" => $user->id,
            "postcode" => "123-4567",
            "address" => "仙台市センダイ1-2-3",
        ]);

        $this->seed(ConditionsTableSeeder::class);
        $this->seed(categoriesTableSeeder::class);

        /** @var \App\Models\User $user */
        $response = $this->ActingAs($user)->post("/sell", [
            "id" => 1,
            "name" => "テスト用出品商品",
            "brand_name" => "テスト",
            "description" => "テスト説明",
            "price" => 1000,
            "image" => UploadedFile::fake()->create('test.jpg'),
            "condition_id" => 1,
            "category_ids" => [1, 2],
            "user_id" => $user->id,
            "is_sold" => false,
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas("items", [
            "name" => "テスト用出品商品",
            "brand_name" => "テスト",
            "description" => "テスト説明",
            "price" => 1000,
            "condition_id" => 1,
            "user_id" => $user->id,
            "is_sold" => false,
        ]);

        $item = Item::first();

        $this->assertStringStartsWith('image/', $item->image);
        $this->assertTrue($item->categories->contains(1));
        $this->assertTrue($item->categories->contains(2));
    }
}
