<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\paymentMethod;
use App\Models\Item;

class PurchaseController extends Controller
{
    public function index($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view("item.purchase", compact("item"));
    }


    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

    }
}
