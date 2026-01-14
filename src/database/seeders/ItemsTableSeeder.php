<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                "name" => "腕時計",
                "brand_name" => "Rolax",
                "description" => "スタイリッシュなデザインのメンズ腕時計",
                "price" => "15000",
                "image" => "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg",
                "category_id" => [1, 5],
                "condition_id" => 1,
                "user_id" => 1,
            ],
            [
                "name" => "HDD",
                "brand_name" => "西芝",
                "description" => "高速で信頼性の高いハードディスク",
                "price" => "5000",
                "image" => "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg",
                "category_id" => [2],
                "condition_id" => 2,
                "user_id" => 1,
            ],
            [
                "name" => "玉ねぎ3束",
                "brand_name" => "なし",
                "description" => "新鮮な玉ねぎ3束のセット",
                "price" => "300",
                "image" => "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg",
                "category_id" => [10],
                "condition_id" => 3,
                "user_id" => 1,
            ],
            [
                "name" => "革靴",
                "brand_name" => "",
                "description" => "クラシックなデザインの革靴",
                "price" => "4000",
                "image" => "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg",
                "category_id" => [1, 5],
                "condition_id" => 4,
                "user_id" => 1,
            ],
            [
                "name" => "ノートPC",
                "brand_name" => "",
                "description" => "高性能なノートパソコン",
                "price" => "45000",
                "image" => "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg",
                "category_id" => [2],
                "condition_id" => 1,
                "user_id" => 1,
            ],
            [
                "name" => "マイク",
                "brand_name" => "なし",
                "description" => "高音質のレコーディング用マイク",
                "price" => "8000",
                "image" => "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg",
                "category_id" => [2],
                "condition_id" => 2,
                "user_id" => 1,
            ],
            [
                "name" => "ショルダーバッグ",
                "brand_name" => "",
                "description" => "おしゃれなショルダーバッグ",
                "price" => "3500",
                "image" => "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg",
                "category_id" => [1, 3],
                "condition_id" => 3,
                "user_id" => 1,
            ],
            [
                "name" => "タンブラー",
                "brand_name" => "なし",
                "description" => "使いやすいタンブラー",
                "price" => "500",
                "image" => "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg",
                "category_id" => [10],
                "condition_id" => 4,
                "user_id" => 1,
            ],
            [
                "name" => "コーヒーミル",
                "brand_name" => "Starbacks",
                "description" => "手動のコーヒーミル",
                "price" => "4000",
                "image" => "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg",
                "category_id" => [10],
                "condition_id" => 1,
                "user_id" => 1,
            ],
            [
                "name" => "メイクセット",
                "brand_name" => "",
                "description" => "便利なメイクアップセット",
                "price" => "2500",
                "image" => "https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg",
                "category_id" => [4, 6],
                "condition_id" => 2,
                "user_id" => 1,
            ],
        ];

        foreach ($items as $itemData) {
            $categoryIds = $itemData['category_id'];
            unset($itemData['category_id']);
            $item = \App\Models\Item::create($itemData);

            $item->categories()->attach($categoryIds);
        }
    }
}
