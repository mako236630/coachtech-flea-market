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

class ProfileTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_ユーザーの必要な情報が取得できる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        // 商品を作成する為、必要なユーザー
        $user2 = User::factory()->create();

        $this->seed(ConditionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        Address::factory()->create([
            "user_id" => $user->id,
            "postcode" => "123-4567",
            "address" => "仙台市センダイ1-2-3",
        ]);

        $item = Item::factory()->create([
            "name" => "時計",
            "brand_name" => "テスト",
            "description" => "テスト説明",
            "price" => 1000,
            "image" => "test.jpg",
            "condition_id" => 1,
            "user_id" => $user->id,
            "is_sold" => false,
        ]);

        $item = Item::factory()->create([
            "name" => "PC",
            "brand_name" => "テスト",
            "description" => "テスト説明",
            "price" => 1000,
            "image" => "test.jpg",
            "condition_id" => 1,
            "user_id" => $user2->id,
            "is_sold" => true,
            "buyer_id" => $user->id,
        ]);

        /** @var \App\Models\User $user */
        $response = $this->ActingAs($user)->get("/mypage");

        $response->assertSee($user->image);
        $response->assertSee($user->name);
        $response->assertSee($item->user_id);
        $response->assertSee($item->buyer_id);
    }

    public function test_ユーザー情報変更項目が初期値として過去設定されている()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        Address::factory()->create([
            "user_id" => $user->id,
            "postcode" => "123-4567",
            "address" => "仙台市センダイ1-2-3",
        ]);

        /** @var \App\Models\User $user */
        $response = $this->ActingAs($user)->get("/mypage/profile");

        $response->assertSee($user->name);
        $response->assertSee('value="123-4567"', false);
        $response->assertSee('value="仙台市センダイ1-2-3"', false);
    }
}
