<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録画面</title>
</head>

<body>
    <h1>会員登録</h1>

    <form action="{{ route('register') }}" method="post" novalidate>
        @csrf

        <div>
            <label>ユーザ名</label>
            <input type="text" name="name" value="{{ old('name') }}">
        </div>
        <div>
            @error('name')
            {{ $message }}
            @enderror
        </div>

        <div>
            <label>メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}">
        </div>
        <div>
            @error('email')
            {{ $message }}
            @enderror
        </div>
        <div>
            <label>パスワード</label>
            <input type="password" name="password" value="{{ old('password') }}">
        </div>
        <div>
            @error('password')
            {{ $message }}
            @enderror
        </div>
        <div>
            <label>確認用パスワード</label>
            <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}">
        </div>
        <div>
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

</html>