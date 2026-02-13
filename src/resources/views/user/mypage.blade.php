@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user/mypage.css') }}?v={{ time() }}">
@endsection

@section('content')
    @if (session('message'))
        <div class="alert__success">
            {{ session('message') }}
        </div>
    @endif

    <main>

        <div class="profile">
            {{-- 1. 画像を選択した場合にJavaScriptでプレビュー表示させる為に、id="preview"を定義しました
                         2. 画像未選択の場合にsrcに透過データ(Base64)をセットし、CSSの背景色でグレーの円を表示させてます --}}
            @if ($user->image)
                <img class="profile__image" id="preview" src="{{ $user->image }}">
            @else
                <div class="user-no-img"></div>
            @endif

            <div class="user__name">
                <strong>{{ $user->name }}</strong>
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

        @if ($items->isEmpty())
            @if ($page === 'sell')
                <p class="item__sell">出品した商品はありません</p>
            @else
                <p class="item__buy">購入した商品はありません</p>
            @endif
        @else
            <div class="items__list">
                @foreach ($items as $item)
                    <div class="item__list">
                        <a href="/item/{{ $item->id }}"> <img class="item__img"
                                src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}"></a>

                        <P>
                            @if ($item->is_sold)
                                {{ $item->name }} <strong class="item__sold">sold</strong>
                            @else
                                {{ $item->name }}
                            @endif
                        </P>
                    </div>
                @endforeach
        @endif
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
