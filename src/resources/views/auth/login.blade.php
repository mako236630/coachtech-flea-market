<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面</title>
</head>

<body>
    <h1>ログイン</h1>

    @error('email')
    @if ( $message === 'ログイン情報が登録されていません')
    {{ $message }}
    @endif
    @enderror

    <form action="{{ route('login') }}" method="post" novalidate>
        @csrf

        <div>
            <label>メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}">
        </div>
        <div>
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
        <div>
            @error('password')
            {{ $message }}
            @enderror
        </div>
        <div>
            <button type="submit">ログインする</button>
        </div>
    </form>
    <a href="{{ route('register') }}">会員登録はこちら</a>
</body>

</html>