@extends('layouts.auth')
@section('content')

<body>
    <h1>会員登録</h1>

    <form action="{{ route('register') }}" method="post" novalidate>
        @csrf

        <div>
            <label>ユーザー名</label>
            <input type="text" name="name" value="{{ old('name') }}">
        </div>
        <div style="color: red">
            @error('name')
            {{ $message }}
            @enderror
        </div>

        <div>
            <label>メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}">
        </div>
        <div style="color: red">
            @error('email')
            {{ $message }}
            @enderror
        </div>
        <div>
            <label>パスワード</label>
            <input type="password" name="password" value="{{ old('password') }}">
        </div>
        <div style="color: red">
            @error('password')
            {{ $message }}
            @enderror
        </div>
        <div>
            <label>確認用パスワード</label>
            <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}">
        </div>
        <div style="color: red">
            @error('password_confirmation')
            {{ $message }}
            @enderror
        </div>
        <div>
            <button type="submit">登録する</button>
        </div>
    </form>

    <a href="{{ route('login') }}">ログインはこちら</a>
</body>
@endsection