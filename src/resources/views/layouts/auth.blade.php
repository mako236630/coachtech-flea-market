<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>

       <header class="header">
        <div class="header__inner">

            <div class="header__logo">
                <a href="/"><img src="{{ asset('images/COACHTECH.png') }}" alt="logo"></a>
            </div>
        </div>
       </header>

    <main>
        @yield('content')
    </main>

</body>

</html>
