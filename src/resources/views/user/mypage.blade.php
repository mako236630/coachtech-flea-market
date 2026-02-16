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
            <div id="image-preview-area">
                @if ($user->image)
                    <img class="profile__image" src="{{ asset('storage/' . $user->image) }}">
                @else
                    <div class="user-no-img"></div>
                @endif
            </div>
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
        function previewFile(input) {
            if (input.files && input.files[0]) {
                var fileData = new FileReader();

                fileData.onload = function() {
                    // 選択した画像をプレビュー表示してます
                    var area = document.getElementById('image-preview-area');
                    area.innerHTML = '<img class="profile__image" src="' + fileData.result + '">';
                };

                fileData.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
