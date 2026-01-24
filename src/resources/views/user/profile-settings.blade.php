@extends('layouts.app')
@section('content')
    <main>
        <div>
            <h1>プロフィール設定</h1>
        </div>

        <div>
            <label>ユーザ名</label>
            <input type="text" name="name" value="{{ old('name') }}"><br>

            <label>郵便番号</label>
            <input type="text" name="postcode" value="{{ old('postcode') }}"><br>

            <label>住所</label>
            <input type="text" name="address" value="{{ old('address') }}"><br>

            <label>建物名</label>
            <input type="text" name="building" value="{{ old('building') }}">
        </div>

        <div>
            <button type="submit">更新する</button>
    </main>
@endsection
