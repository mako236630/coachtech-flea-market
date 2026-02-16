<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class PurchaseController extends Controller
{
    public function index($item_id)
    {
        $user = Auth::user();
        $item = Item::find($item_id);

        $displayAddress = $user->address;

        if (session()->has('new_shipping')) {
            $displayAddress = (object) session('new_shipping');
        }

        return view("item.purchase", compact("item", "displayAddress"));
    }

    // Stripe決済ページへ繋げます
    public function checkout(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $paymentMethod = $request->input("payment_method");

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        \Stripe\Stripe::setVerifySslCerts(false);

        //コンビニ決済を選択した場合、テスト環境では実際にコンビニで支払うことができない為、今回は購入するボタンを押したら購入完了の動きにしました。
        if ($paymentMethod === "convenience") {

            $item = Item::findOrFail($item_id);
            $user = Auth::user();

            if (session()->has('new_shipping')) {
                $shipping = session('new_shipping');
                $postcode = $shipping['postcode'];
                $address = $shipping['address'];
                $building = $shipping['building'];
            } else {

                $postcode = $user->address->postcode;
                $address = $user->address->address;
                $building = $user->address->building;
            }

            $item->update([
                'is_sold' => true,
                'buyer_id' => Auth::id(),
                'shipping_postcode' => $postcode,
                'shipping_address' => $address,
                'shipping_building' => $building,
                "payment_method" => "convenience",
            ]);

            session()->forget('new_shipping');

            return redirect()->route("item.list");

        } else {

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'jpy',
                        'product_data' => ['name' => $item->name],
                        'unit_amount'  => $item->price,
                    ],
                    'quantity' => 1,
                ]],

                'mode' => 'payment',
                // 支払い後にsession_idを持たせて、後で支払済みか確認します
                'success_url' => route('purchase.success', ['item_id' => $item->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('item.show', ['item_id' => $item->id]),
            ]);

            return redirect()->away($session->url);
        }
    }


    public function success(Request $request, $item_id)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        \Stripe\Stripe::setVerifySslCerts(false);
        // session_idを受け取ります
        $sessionId = $request->get('session_id');
        // Stripeに照会し、支払い済みか確認
        $session = \Stripe\Checkout\Session::retrieve($sessionId);

        // 未払い(paidではなかったら)、エラーにします
        if ($session->payment_status !== 'paid') {
            return redirect()->route('item.show', $item_id)->with('error', '決済が完了していません');
        }

        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        if (session()->has('new_shipping')) {
            $shipping = session('new_shipping');
            $postcode = $shipping['postcode'];
            $address = $shipping['address'];
            $building = $shipping['building'];
        } else {

            $postcode = $user->address->postcode;
            $address = $user->address->address;
            $building = $user->address->building;
        }

        $item->update([
            'is_sold' => true,
            'buyer_id' => Auth::id(),
            'shipping_postcode' => $postcode,
            'shipping_address' => $address,
            'shipping_building' => $building,
            "payment_method" => "card",
        ]);

        session()->forget('new_shipping');

        return redirect()->route('item.list');
    }
}
