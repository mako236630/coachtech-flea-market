<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>商品詳細ページ</title>
</head>

<body>
    <div style="margin: 10px; width: 200px;">
        <img src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}"
            width="350">
        <h1>{{ $item->name }}</h1>
        <p>ブランド名 {{ $item->brand_name }}</p>
        <span>￥</span><BIG>{{ number_format($item->price) }}</BIG><span>（税込み）</span>
        <button style="submit">購入手続きへ</button>

        <h2>商品説明</h2>
        <p>{{ $item->description }}</p>
        <h2>商品の情報</h2>
        <span>
            <strong>カテゴリ</strong>
            @foreach($item->categories as $category)
            <p>{{ $category->name }}</p>
            @endforeach
        </span>

        <span>
            <strong>商品の状態</strong>
            <p>{{$item->condition->name }}</p>
        </span>

    </div>
</body>

</html>
