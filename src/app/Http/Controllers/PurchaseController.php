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
        // 配送先の住所は1件だけ取得できれば良いので、first()を使用しました。ビューでも$address->addressのように使いやすくしています。
        $address = Address::where('user_id', Auth::id())->first();


        return view("item.purchase", compact("item", "address"));
    }

    // Stripe決済ページへ繋げます
    public function checkout(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $paymentMethod = $request->input("payment_method");

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        \Stripe\Stripe::setVerifySslCerts(false);

        //コンビニ決済を選択した場合、テスト環境では実際にコンビニで支払うことができない為、今回は購入するボタンを押したら購入完了の動きにしました。
        if ($paymentMethod === "convenience"){
            $item->update([
                "is_sold" => true,
                "buyer_id" => Auth::id(),
            ]);

            return redirect()->route("item.show", $item->id);

        }else{

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
        $item->update([
            'is_sold' => true,
            // 購入者を保存します
            'buyer_id' => Auth::id(),
            ]);

        return redirect()->route('item.show', ['item_id' => $item_id]);
    }
}
