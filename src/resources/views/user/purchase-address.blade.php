@extends('layouts.app')
@section('content')
    <main>
        <form action="{{ route('address.update', ['item_id' => $item_id]) }}" method="post">
            @csrf

            <div>
                <h1>住所の変更</h1>
            </div>

            <div>
                <label>郵便番号</label><br>
                <input type="text" name="postcode" value="{{ old('postcode') }}"><br>

                <div style="color: red">
                    @error('postcode')
                        {{ $message }}
                    @enderror
                </div>

                <label>住所</label><br>
                <input type="text" name="address" value="{{ old('address') }}"><br>

                <div style="color: red">
                    @error('address')
                        {{ $message }}
                    @enderror
                </div>
                
                <label>建物名</label><br>
                <input type="text" name="building" value="{{ old('building') }}"><br>
            </div>

            <div>
                <button type="submit" name="submit">更新する</button>
            </div>
        </form>
    @endsection
