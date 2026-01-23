@extends('layouts.auth')
@section('content')
    <h1>ログイン</h1>

    <div style="color: red">
    @error('email')
    @if ( $message === 'ログイン情報が登録されていません')
    {{ $message }}
    @endif
    @enderror
    </div>

    <form action="{{ route('login') }}" method="post" novalidate>
        @csrf

        <div>
            <label>メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}">
        </div>
        <div style="color: red">
            @error('email')
            @if ( $message !== 'ログイン情報が登録されていません')
            {{ $message }}
            @endif
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
            <button type="submit">ログインする</button>
        </div>
    </form>
    <a href="{{ route('register') }}">会員登録はこちら</a>

@endsection