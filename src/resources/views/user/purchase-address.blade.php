@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/user/purchase-address.css') }}">
@endsection
@section('content')
    <div class="address__setting">
        <form action="{{ route('address.update', ['item_id' => $item_id]) }}" method="post">
            @csrf

            <div class="title">
                <h1>住所の変更</h1>
            </div>

            <label class="label">郵便番号</label>
            <input class="input" type="text" name="postcode" value="{{ old('postcode', $user->address->postcode) }}">

            <div class="error">
                @error('postcode')
                    {{ $message }}
                @enderror
            </div>

            <label class="label">住所</label>
            <input class="input" type="text" name="address" value="{{ old('address', $user->address->address) }}">

            <div class="error">
                @error('address')
                    {{ $message }}
                @enderror
            </div>

            <label class="label">建物名</label>
            <input class="input" type="text" name="building" value="{{ old('building', $user->address->building) }}">

            <div class="setting__button">
                <button class="button" type="submit" name="submit">更新する</button>
            </div>
        </form>
    </div>
@endsection
