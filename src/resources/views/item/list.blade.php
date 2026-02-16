@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/item/list.css') }}">
@endsection

@section('content')
@if (session('message'))
        <div class="alert__success">
            {{ session('message') }}
        </div>
    @endif

    <div class="tab">
        <a class="tab__list" href="{{ url('/?' . http_build_query(array_merge(request()->query(), ['tab' => '']))) }}"
            style="{{ $tab !== 'mylist' ? 'color: red; font-weight: bold;' : '' }}">おすすめ</a>

        <a class="tab__mylist" href="{{ url('/?' . http_build_query(array_merge(request()->query(), ['tab' => 'mylist']))) }}"
            style="{{ $tab === 'mylist' ? 'color: red; font-weight: bold;' : '' }}">マイリスト</a>
    </div>
    <hr>
    @if ($items->isEmpty())
    @if ($tab === 'mylist')
        <p class="favorite">いいねした商品がありません</p>
        @else
        <p class="item-list">現在、おすすめの商品がありません</p>
        @endif
    @else
        <div class="items__list">
            @foreach ($items as $item)
                <div class="item__list">
                    <a href="/item/{{ $item->id }}"> <img class="item__img" src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}"></a>

                    <P>
                        @if ($item->is_sold)
                            {{ $item->name }} <strong class="item__sold">sold</strong>
                        @else
                            {{ $item->name }}
                        @endif
                    </P>
                </div>
            @endforeach
        </div>
    @endif

@endsection
