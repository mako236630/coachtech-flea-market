<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store($item_id)
    {
        $user_id = Auth::id();

        $favorite = Favorite::where('item_id', $item_id)->where('user_id', $user_id)->first();

        if(!$favorite) {
            Favorite::create([
                "item_id" => "$item_id",
                "user_id" => "$user_id",
            ]);

        } else {
            $favorite->delete();
        }

        return back();
    }

}
