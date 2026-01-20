<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

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
    </header>

    <main>
        @yield('content')
    </main>

</body>

</html>
