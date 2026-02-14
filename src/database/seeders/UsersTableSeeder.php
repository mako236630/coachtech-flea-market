<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 商品ダミーデータの出品者用
        $user1 = User::create([
            "name" => "商品出品者用テストユーザー",
            "email" => "test@example.com",
            "password" => Hash::make("password123"),
            'email_verified_at' => now(),
        ]);

        $user2 = User::create([
            "name" => "機能確認用テストユーザー",
            "email" => "test2@example.com",
            "password" => Hash::make("password789"),
            "image" => "https://picsum.photos/200/200",
            'email_verified_at' => now(),
        ]);

        Address::create([
            "user_id" => $user1->id,
            "postcode" => "123-4567",
            "address" => "宮城県仙台市1-2-3",
            "building" => "センダイ105",
        ]);

        Address::create([
            "user_id" => $user2->id,
            "postcode" => "000-1111",
            "address" => "宮城県仙台市センダイ1-2-3",
            "building" => "センダイ101",

        ]);
    }
}
