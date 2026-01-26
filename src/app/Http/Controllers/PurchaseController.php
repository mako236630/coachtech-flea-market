<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\paymentMethod;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class PurchaseController extends Controller
{
    public function index($item_id)
    {
        $item = Item::findOrFail($item_id);
        $address = Address::where('user_id', Auth::id())->first();


        return view("item.purchase", compact("item", "address"));
    }


    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

    }

    public function checkout(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $paymentMethod = $request->input("payment_method");

        $stripeMethod = ($paymentMethod === 'convenience') ? 'konbini' : 'card';

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        \Stripe\Stripe::setVerifySslCerts(false);

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => [$stripeMethod],
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'jpy',
                    'product_data' => ['name' => $item->name],
                    'unit_amount'  => $item->price,
                ],
                'quantity' => 1,
            ]],

            'mode' => 'payment',
            'success_url' => route('purchase.success', ['item_id' => $item->id]),
            'cancel_url'  => route('item.show', ['item_id' => $item->id]),
        ]);

        return redirect()->away($session->url);
    }

    public function success($item_id)
    {
        $item = Item::findOrFail($item_id);
        $item->update([
            'is_sold' => true,
            'buyer_id' => Auth::id(),
            ]);

        return redirect()->route('item.show', ['item_id' => $item_id]);
    }
}
