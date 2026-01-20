
@extends('layouts.app')
@section('content')

<body>

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
@endsection
