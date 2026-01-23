@extends('layouts.app')
@section('content')
    <main>
        <form action="{{ route('address.update', ['item_id' => $item_id]) }}" method="post">
            @csrf

            <div>
                <h1>住所の変更</h1>
            </div>

            <div>
                <strong>郵便番号</strong><br>
                <input type="text" name="postcode" value="{{ old('postcode') }}"><br>

                <div style="color: red">
                    @error('postcode')
                        {{ $message }}
                    @enderror
                </div>

                <strong>住所</strong><br>
                <input type="text" name="address" value="{{ old('address') }}"><br>

                <div style="color: red">
                    @error('address')
                        {{ $message }}
                    @enderror
                </div>
                
                <strong>建物名</strong><br>
                <input type="text" name="building" value="{{ old('building') }}"><br>
            </div>

            <div>
                <button type="submit" name="submit">更新する</button>
            </div>
        </form>
    @endsection
