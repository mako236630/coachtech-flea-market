@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user/mypage.css') }}">
@endsection

@section('content')
    <main>

        <div class="profile">
            {{-- 1. 画像を選択した場合にJavaScriptでプレビュー表示させる為に、id="preview"を定義しました
                         2. 画像未選択の場合にsrcに透過データ(Base64)をセットし、CSSの背景色でグレーの円を表示させてます --}}
            <img class="profile__image" id="preview"
                src="{{ $user->image ? asset('storage/' . $user->image) : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}">

            <div class="user__name">
                <h1>{{ $user->name }}</h1>
            </div>

            <a class="profile__setting" href="{{ route('profile.edit') }}">プロフィールを編集</a>
        </div>


        <div class="page">
            <a class="page__sell" href="/mypage?page=sell"
                style="{{ $page === 'sell' ? 'color: red; font-weight: bold;' : '' }}">出品した商品</a>
            <a class="page__buy" href="/mypage?page=buy"
                style="{{ $page === 'buy' ? 'color: red; font-weight: bold;' : '' }}">購入した商品</a>
        </div>


        <HR>

        <div class="items__list">
            @foreach ($items as $item)
                <div class="item__list">
                    <a href="/item/{{ $item->id }}"> <img class="item__img"
                            src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}"></a>

                    <P>
                        @if ($item->is_sold)
                            {{ $item->name }} <strong style="color: red;">[sold]</strong>
                        @else
                            {{ $item->name }}
                        @endif
                    </P>
                </div>
            @endforeach
        </div>
    </main>

    <script>
        var fileData = new FileReader();

        fileData.onload = function() {
            // 選択した画像をプレビュー表示してます
            document.getElementById('preview').src = fileData.result;
            // 選択した画像のサイズが崩れないように
            preview.style.width = '100px';
            preview.style.height = '100px';
        };

        fileData.readAsDataURL(input.files[0]);
    </script>
@endsection
