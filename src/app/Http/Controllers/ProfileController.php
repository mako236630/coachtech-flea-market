<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Models\Item;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $page = $request->query('page', 'sell');

        if($page === 'buy'){
            // Userモデルで設定したリレーションで購入済みの商品を取得します
            $items = $user->purchasedItems;

        }else{
            // user_id(出品者)が現在ログインしているユーザーと一致するものを取得します
            $items = Item::where("user_id", $user->id)->get();
        }

        return view("user.mypage", compact("user", "items", "page"));
    }

    // プロフィール更新画面
    public function edit()
    {
        $user = Auth::user();
        
        $address = Address::where("user_id", $user->id)->first();

        return view("user.profile-settings", compact("user","address"));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        // ユーザーがプロフィールの初期設定と更新ができるように定義しました
        Address::updateOrCreate(
            ["user_id" => $user->id],
            [
                "postcode" => $request->postcode,
                "address" => $request->address,
                "building" => $request->building,
            ]
        );

        // $userをモデルだと認識しないので、@varを使用してuserモデルだと認識させ、メゾットエラーを解消させました

        /** @var \App\Models\User $user */ 
        $user->update(
            ["name" => $request->name]);

            if($request->hasFile("image")){
                $path = $request->file("image")->store("profile_images", "public");

                $user->update([
                    "image" => $path,
                ]);
            }

        return redirect()->route("item.list");
    }
}
