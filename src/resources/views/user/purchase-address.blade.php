@extends('layouts.app')
@section('content')
    <main>
        <form action="{{ route('') }}" method="post">

            <div>
                <h1>住所の変更</h1>
            </div>

            <div>
                <strong>郵便番号</strong><br>
                <input type="text" name="post-code"><br>
                <strong>住所</strong><br>
                <input type="text" name="address"><br>
                <strong>建物名</strong><br>
                <input type="text" name="building"><br>
            </div>

            <div>
                <button type="submit" name="submit">更新する</button>
            </div>
            
        </form>
    @endsection
