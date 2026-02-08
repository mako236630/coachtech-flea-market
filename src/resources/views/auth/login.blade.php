@extends('layouts.auth')
@section('content')

    <div class="login">

        <div class="title">
            <h1>ログイン</h1>
        </div>
        <div style="color: red">
            @error('email')
                @if ($message === 'ログイン情報が登録されていません')
                    {{ $message }}
                @endif
            @enderror
        </div>

        <form action="{{ route('login') }}" method="post" novalidate>
            @csrf

            <div>
                <label class="label">メールアドレス</label>
                <input class="input" type="email" name="email" value="{{ old('email') }}">
            </div>
            <div class="error" style="color: red">
                @error('email')
                    @if ($message !== 'ログイン情報が登録されていません')
                        {{ $message }}
                    @endif
                @enderror
            </div>
            <div>
                <label class="label">パスワード</label>
                <input class="input" type="password" name="password" value="{{ old('password') }}">
            </div>
            <div class="error" style="color: red">
                @error('password')
                    {{ $message }}
                @enderror
            </div>
            <div class="button__container">
                <button class="button" type="submit">ログインする</button>
            </div>
        </form>
        <a class="rink" href="{{ route('register') }}">会員登録はこちら</a>

    </div>

@endsection
