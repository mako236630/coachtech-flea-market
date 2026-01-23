@extends('layouts.app')
@section('content')
    <form action="{{ route('purchase.checkout', $item->id) }}" method="post">
        @csrf

        <main>
            <div>
                <img src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}"
                    width="150">
            </div>
            <div>
                <strong>{{ $item->name }}</strong>
                <p>￥<BIG>{{ number_format($item->price) }}</BIG></p>
            </div>

            <HR>

            <div>
                <label for="payment_method">支払い方法</label><br>
                <select name="payment_method" id="payment_method" class="form-control">
                    <option value="" disabled selected>選択してください</option>
                    <option value="convenience">コンビニ支払い</option>
                    <option value="card">カード支払い</option>
                </select>
            </div>

            <div style="color: red">
                @error('payment_method')
                    {{ $message }}
                @enderror
            </div>

            <HR>

            <div>
                <strong>配送先</strong>
                <a href="{{ route('purchase.address', ['item_id' => $item->id]) }}">変更する</a><br>
                <strong>〒{{ $address->postcode }}</strong><br>
                <strong>{{ $address->address }}</strong><br>
                <strong>{{ $address->building }}</strong>
                <HR>
            </div>

        </main>

        <aside>
            <div>
                <table border>
                    <tr>
                        <td>商品代金</td>
                        <th>￥{{ number_format($item->price) }}</th>
                    </tr>
                    <tr>
                        <td>支払い方法</td>
                        <th><span id="payment_method-display"></span></th>
                    </tr>
                </table>
            </div>

            <div>
                <button type="submit">購入する</button>
            </div>

        </aside>
    </form>

    <script>
        // ページが読み込まれたら実行する
        document.addEventListener('DOMContentLoaded', function() {

            // 操作したい要素をJSの世界に連れてくる
            const selectElement = document.getElementById('payment_method');
            const displayElement = document.getElementById('payment_method-display');

            // プルダウンが「変更(change)」された時に動く処理
            selectElement.addEventListener('change', function() {

                // 現在選ばれている選択肢の「文字」を取得する
                const selectedText = selectElement.options[selectElement.selectedIndex].text;

                // 表示用の場所に、その文字をパッと書き換える！
                displayElement.textContent = selectedText;
            });
        });
    </script>
@endsection
