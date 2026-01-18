<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧画面</title>
</head>

<body>
    <header>
        <form action="{{ route('item.list') }}" method="get">
            <input type="text" name="keyword" placeholder="なにをお探しですか" value="{{ request('keyword') }}">
        </form>
        <nav>
            <a href="{{ route('login') }}">ログイン</a>
            <a href="">マイページ</a>
            <a href="">出品</a>
        </nav>
    </header>

    <div>
        <div style="margin: 20px;">
            <a href="{{ url('/?' . http_build_query(array_merge(request()->query(), ['tab' => '']))) }}"
                style="{{ $tab !== 'mylist' ? 'color: red; font-weight: bold;' : '' }}">おすすめ</a>

            <a href="{{ url('/?' . http_build_query(array_merge(request()->query(), ['tab' => 'mylist']))) }}"
                style="{{ $tab === 'mylist' ? 'color: red; font-weight: bold;' : '' }}">マイリスト</a>
        </div>
    </div>

    <div>
        @if ($items->isEmpty())
            <p>いいねした商品がありません</p>
        @else
            <div style="display: flex; flex-wrap: wrap;">
                @foreach ($items as $item)
                    <div style="margin: 10px; width: 200px;">
                        <a href="/item/{{ $item->id }}"> <img
                                src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}"
                                width="200">
                        </a>
                        <p>{{ $item->name }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>

</html>
