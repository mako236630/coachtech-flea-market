@extends('layouts.auth')
@section('content')

        <div class="register">
            <div class="title">
            <h1>会員登録</h1>
            </div>

            <form action="{{ route('register') }}" method="post" novalidate>
                @csrf

                <div>
                    <label class="label">ユーザー名</label>
                    <input class="input" type="text" name="name" value="{{ old('name') }}">
                </div>
                <div class="error" style="color: red">
                    @error('name')
                        {{ $message }}
                    @enderror
                </div>

                <div>
                    <label class="label">メールアドレス</label>
                    <input class="input" type="email" name="email" value="{{ old('email') }}">
                </div>
                <div class="error" style="color: red">
                    @error('email')
                        {{ $message }}
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
                <div>
                    <label class="label">確認用パスワード</label>
                    <input class="input" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}">
                </div>
                <div class="error" style="color: red">
                    @error('password_confirmation')
                        {{ $message }}
                    @enderror
                </div>
                <div class="button__container">
                    <button class="button" type="submit">登録する</button>
                </div>
            </form>

            <a class="rink" href="{{ route('login') }}">ログインはこちら</a>
        </div>
@endsection
