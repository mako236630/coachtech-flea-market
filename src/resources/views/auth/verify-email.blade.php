@extends('layouts.auth')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/user/verify-email.css') }}">
@endsection

@section('content')
    <div class="verify__email">
        <strong>登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。</strong>
    </div>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <div class="verify__button">
            <a href="https://mailtrap.io/" class="verify__email-button" target="_blank">認証はこちらから
            </a>
            <div class="resend__verify-email">
                <button class="resend__email" type="submit">認証メールを再送する</button>
            </div>
        </div>
    </form>
@endsection
