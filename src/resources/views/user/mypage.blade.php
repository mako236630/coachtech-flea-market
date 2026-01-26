@extends('layouts.app')
@section('content')
    <main>
        <div>
            {{-- 画像をプレビュー表示する為、srcでimageのパスを取得しています　--}}
            <img id="preview"src="{{ $user->image ? asset('storage/' . $user->image) : asset('images/no-image.png') }}"
                width="60">
        </div>

        <div>
            <h1>{{ $user->name }}</h1>
        </div>

        <div>
            <a href="{{ route('profile.edit') }}">プロフィールを編集</a>
        </div>

        <div>
            <div style="margin: 20px;">
                <a href="/mypage?page=sell" style="{{ $page === 'sell' ? 'color: red; font-weight: bold;' : '' }}">出品した商品</a>
                <a href="/mypage?page=buy" style="{{ $page === 'buy' ? 'color: red; font-weight: bold;' : '' }}">購入した商品</a>
            </div>
        </div>

        <HR>

        <div style="display: flex; flex-wrap: wrap;">
            @foreach ($items as $item)
                <div style="margin: 10px; width: 200px;">
                    <a href="/item/{{ $item->id }}"> <img
                            src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}"
                            width="200">
                    </a>
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
        };

        fileData.readAsDataURL(input.files[0]);
    </script>
@endsection
