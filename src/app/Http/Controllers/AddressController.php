<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index($item_id)
    {
        return view("user.purchase-address", compact("item_id"));
    }

    public function update(AddressRequest $request, $item_id)
    {
        session([
            'new_shipping' => [
                'postcode' => $request->postcode,
                'address' => $request->address,
                'building' => $request->building,
            ]
        ]);

        return redirect()->route("item.purchase",["item_id" => $item_id]);
    }
}
