<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧画面</title>
</head>

<body>
    <header>
        <form action="" method="get">
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
            <a href="/?tab=recommend" style="margin-right: 20px; font-weight: {{ request('tab') != 'mylist' ? 'bold' : 'normal' }};">おすすめ</a>
            <a href="/?tab=mylist" style="font-weight: {{ request('tab') == 'mylist' ? 'bold' : 'normal' }};">マイリスト</a>
        </div>
    </div>
    <div style="display: flex; flex-wrap: wrap;">
        @foreach ($items as $item)
        <div style="margin: 10px; width: 200px;">
            <a href="/items/{{ $item->id }}"> <img src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}" width="200">
                <p>{{ $item->name }}</p>
            </a>

        </div>
        @endforeach
    </div>
</body>

</html>