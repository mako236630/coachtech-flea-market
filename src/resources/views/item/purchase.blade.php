@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/item/purchase.css') }}">
@endsection
@section('content')
    <form action="{{ route('purchase.checkout', $item->id) }}" method="post">
        @csrf

        <div class="purchase-wrapper">
            <div class="item__purchase-content">
                <div class="item__purchase">
                    <div class="item__purchase-image">
                        <img
                            src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}">
                    </div>

                    <div>
                        <h1>{{ $item->name }}</h1>
                        <span>￥<span class="item__price">{{ number_format($item->price) }}</span></span>
                    </div>
                </div>

                <hr>

                <div class="item__payment-method">
                    <div class="payment__method">
                        <label>支払い方法</label>
                    </div>
                    <div class="payment__method-select">
                        <select name="payment_method" id="payment_method" class="form-control">
                            <option value="" disabled selected>選択してください</option>
                            <option value="convenience">コンビニ支払い</option>
                            <option value="card">カード支払い</option>
                        </select>
                    </div>
                </div>

                <div class="error">
                    @error('payment_method')
                        {{ $message }}
                    @enderror
                </div>

                <hr>

                <div class="item__purchase-address">

                    <div class="purchase__address">
                        <strong class="item__address">配送先</strong>
                        <a class="address__setting"
                            href="{{ route('purchase.address', ['item_id' => $item->id]) }}">変更する</a>
                    </div>

                    <div class="user__address">
                        <div class="address__postcode">
                            <strong>〒{{ $displayAddress->postcode }}</strong>
                        </div>
                        <strong>{{ $displayAddress->address }}</strong>
                        <strong>　{{ $displayAddress->building }}</strong>
                    </div>
                </div>
                <hr>
            </div>

            <aside class="aside">

                <div class="subtotal">
                    <table class="subtotal__table">
                        <tr>
                            <td>商品代金</td>
                            <td>￥{{ number_format($item->price) }}</td>
                        </tr>
                        <tr>
                            <td>支払い方法</td>
                            <td><span id="payment_method-display"></span></td>
                        </tr>
                    </table>
                </div>


                <div>
                    <button class="button" type="submit">購入する</button>
                </div>

            </aside>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const selectElement = document.getElementById('payment_method');
            const displayElement = document.getElementById('payment_method-display');

            selectElement.addEventListener('change', function() {

                const selectedText = selectElement.options[selectElement.selectedIndex].text;

                displayElement.textContent = selectedText;
            });
        });
    </script>
@endsection
