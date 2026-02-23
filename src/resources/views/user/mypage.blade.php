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
        <a href="/mypage?page=sell" class="page__sell {{ $page === 'sell' ? 'is-active' : '' }}">出品した商品</a>
        <a href="/mypage?page=buy" class="page__buy {{ $page === 'buy' ? 'is-active' : '' }}">購入した商品</a>
    </div>

    <hr>

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
                    <a href="{{ route('item.show', $item->id) }}"> <img class="item__img"
                            src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}"></a>
                    <p>
                        @if ($item->is_sold)
                            {{ $item->name }} <strong class="item__sold">sold</strong>
                        @else
                            {{ $item->name }}
                        @endif
                    </p>
                </div>
            @endforeach
    @endif
    </div>
@endsection
