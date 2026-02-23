@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/item/show.css') }}">
@endsection
@section('content')

    <div class="item__show">

        <div class="item__image">
            <img src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}">
        </div>

        <div class="item__description">

            <h1>{{ $item->name }}</h1>

            <div class="item__brand">
                <p>ブランド名 {{ $item->brand_name }}</p>
            </div>

            <div class="item__show-price">
                <p>￥<span class="item__price">{{ number_format($item->price) }}</span>（税込み）</p>
            </div>

            <div class="item__favorite-comment">

                <form action="{{ route('favorite.store', $item->id) }}" method="post">
                    @csrf

                    <div class="item__favorite">
                        <button class="favorite__btn" type="submit">
                            @if ($item->is_favorited_by_auth_user())
                                <img src="{{ asset('images/heart-pink.png') }}" width="30">
                            @else
                                <img src="{{ asset('images/heart.png') }}" width="30">
                            @endif
                        </button>
                        <p class="favorite__count">{{ $item->favorites->count() }}</p>
                    </div>
                </form>

                <div class="item__comennt">
                    <img src="{{ asset('images/comment.png') }}" width="30">
                    <p class="comment__count">{{ $item->comments->count() }}</p>
                </div>
            </div>

            {{-- 決済エラー時の表示 --}}
            @if (session('error'))
                <div class="alert__danger"
                    style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            @endif


            <div class="item__purchase-btn">
                @if ($item->is_sold)
                    <p class="item__sold">sold</p>
                @else
                    <form action="{{ route('item.purchase', $item->id) }}" method="get">
                        <button class="button" type="submit">購入手続きへ</button>
                    </form>
                @endif
            </div>

            <div class="item__show-description">
                <h2>商品説明</h2>
            </div>

            <p>{{ $item->description }}</p>

            <div class="item__category">
                <h2>商品の情報</h2>
            </div>

            <div class="item__show-category">
                <strong>カテゴリ</strong>
                <div class="category__list">
                    @foreach ($item->categories as $category)
                        <p class="category__name">{{ $category->name }}</p>
                    @endforeach
                </div>
            </div>

            <div class="item__condition">
                <strong>商品の状態</strong>
                <p>{{ $item->condition->name }}</p>
            </div>

            <div class="item__comments">
                <h3>コメント ({{ $item->comments->count() }})</h3>
            </div>

            @foreach ($item->comments as $comment)
                <div class="comment__user">
                    @if($comment->user->image)
                    <div class="comment__user-image">
                        <img src="{{ asset('storage/' . $comment->user->image) }}">
                    </div>
                    @else
                    <div class="user-no-img"></div>
                    @endif
                    <strong>{{ $comment->user->name }}</strong>
                </div>

                <div class="item__user-comment">
                    <p class="user__comment">{{ $comment->comment }}</p>
                </div>
            @endforeach


            <form action="{{ route('comment.store', $item->id) }}" method="post">
                @csrf

                @if (session('message'))
                    <div class="comment__message">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="comment">
                    <strong>商品へのコメント</strong>
                </div>

                <textarea class="textarea" name="comment" rows="10"></textarea><br>

                <div class="error" style="color: red">
                    @error('comment')
                        {{ $message }}
                    @enderror
                </div>

                <button class="button" type="submit">コメントを送信する</button>
            </form>
        </div>
    </div>
@endsection
