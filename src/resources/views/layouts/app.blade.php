<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>

    <header class="header">
        <div class="header__inner">

            <div class="header__logo">
                <a href="/"><img src="{{ asset('images/COACHTECH.png') }}" alt="logo"></a>
            </div>

            <div class="header__seach">
                <form action="{{ route('item.list') }}" method="get">
                    <input type="text" name="keyword" placeholder="なにをお探しですか" value="{{ request('keyword') }}">
                </form>
            </div>

            <nav class="header__nav">
                @auth
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button style="submit">ログアウト</button>
                    </form>
                    <a href="">
                        <button style="submit">マイページ</button>
                    </a>
                    <a href="">
                        <button style="submit" class="sell__button">出品</button>
                    </a>
                @endauth

                @guest
                    <a href="{{ route('login') }}">
                        <button style="submit">ログイン</button>
                    </a>
                    <a href="">
                        <button style="submit">マイページ</button>
                    </a>
                    <a href="">
                        <button style="submit" class="sell__button">出品</button>
                    </a>
                </nav>
            @endguest
        </div>
    </header>

    <main>
        @yield('content')
    </main>

</body>

</html>
