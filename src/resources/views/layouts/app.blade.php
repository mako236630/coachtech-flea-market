<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        header {
            display: flex;
            padding: 20px;
            margin: 0px;
            background-color: black;
            align-items: center;

        }
    </style>
</head>

<body>

    <header>
        <div>
            <a href="/"><img src="{{ asset('images/COACHTECH.png') }}" alt="logo" style="height: 30px;"></a>
        </div>

        <div>
            <form action="{{ route('item.list') }}" method="get">

                <input type="text" name="keyword" placeholder="なにをお探しですか" value="{{ request('keyword') }}">
            </form>
        </div>

        <div style="display: flex; flex-wrap: wrap;">
            <nav>
                @auth
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button style="submit">ログアウト</button>
                    </form>
                    <a href="">
                        <button style="submit">マイページ</button>
                    </a>
                    <a href="">
                        <button style="submit">出品</button>
                    </a>
                </nav>
            </div>
        @endauth

        @guest
            <div style="display: flex; flex-wrap: wrap;">
                <nav>
                    <a href="{{ route('login') }}">
                        <button style="submit">ログイン</button>
                    </a>
                    <a href="">
                        <button style="submit">マイページ</button>
                    </a>
                    <a href="">
                        <button style="submit">出品</button>
                    </a>
                </nav>
            </div>
        @endguest
    </header>

    <main>
        @yield('content')
    </main>

</body>

</html>
