@extends('layouts.auth')
@section('content')

    <main>
        <div class="verify__email">
                <h1>登録していただいたメールアドレスに承認メールを送付しました。<br>
                メール認証を完了してください。</h1>
        </div>
            
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="verify__email-button" type="submit">認証はこちらから
            </button>
            <button type="submit">認証メールを再送する</button>
        </form>

    </main>
@endsection
