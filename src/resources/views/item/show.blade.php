@extends('layouts.app')
@section('content')
    <div>
        <img src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}"
            width="350">
        <h1>{{ $item->name }}</h1>
        <p>ブランド名 {{ $item->brand_name }}</p>
        <p>￥<BIG>{{ number_format($item->price) }}</BIG>（税込み）</p>

        <div style="display: flex; flex-wrap: wrap;">
            <form action="{{ route('favorite.store', $item->id) }}" method="post">
                @csrf
                <button type="submit">
                    @if ($item->is_favorited_by_auth_user())
                        <img src="{{ asset('images/heart-pink.png') }}" width="30">
                    @else
                        <img src="{{ asset('images/heart.png') }}" width="30">
                    @endif
                </button>
                {{ $item->favorites->count() }}
            </form>

            <img src="{{ asset('images/comment.png') }}" width="30">
            {{ $item->comments->count() }}
        </div>
        <div>
            <div style="color: red">
                @if ($item->is_sold)
                    <h2>[ sold ]</h2>
            </div>
        @else
            <div>
                <form action="{{ route('item.purchase', $item->id) }}" method="get">
                    <button class="button" type="submit">購入手続きへ</button>
                </form>
                @endif
            </div>
        </div>

        <h2>商品説明</h2>
        <p>{{ $item->description }}</p>
        <h2>商品の情報</h2>
        <span>
            <strong>カテゴリ</strong>
            @foreach ($item->categories as $category)
                <p>{{ $category->name }}</p>
            @endforeach
        </span>

        <span>
            <strong>商品の状態</strong>
            <p>{{ $item->condition->name }}</p>
        </span>
    </div>
    <div>
        <h4>コメント ({{ $item->comments->count() }})</h4>
    </div>

    @foreach ($item->comments as $comment)
        <div>
            {{-- 画像をプレビュー表示する為、srcでimageのパスを取得しています　--}}
            <img id="preview"src="{{ $comment->user->image ? asset('storage/' . $comment->user->image) : asset('images/no-image.png') }}"
                width="40">
        </div>
        <div>
            <strong>{{ $comment->user->name }}</strong>
            <p>{{ $comment->comment }}</p>
        </div>
    @endforeach

    <form action="{{ route('comment.store', $item->id) }}" method="post">
        @csrf

        @if (session('message'))
            <div style="color: red">
                {{ session('message') }}
            </div>
        @endif

        <div>
            <strong>商品へのコメント</strong><br>
            <textarea name="comment" rows="10"></textarea><br>

            <div style="color: red">
                @error('comment')
                    {{ $message }}
                @enderror
            </div>
            <button class="button" type="submit">コメントを送信する</button>
        </div>

        <script>
            if (input.files && input.files[0]) {
                var fileData = new FileReader();

                fileData.onload = function() {
                    // 選択した画像をプレビュー表示してます
                    document.getElementById('preview').src = fileData.result;
                };

                fileData.readAsDataURL(input.files[0]);
            }
        </script>
    @endsection
