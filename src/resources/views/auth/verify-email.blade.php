@extends('layouts.auth')
@section('content')
    <main>
        @if (session('status') == 'verification-link-sent')
            <div>
                <h1>認証メールを送信しました！</h1>
                <p>メールが届かない場合は、「認証メールを再送信する」を押してください</p>
            </div>
        @endif

        @if (session('status') != 'verification-link-sent')
            <h1>まだ会員登録は完了していません。</h1>
            <p>登録を完了するには、メール認証が必要です。「認証はこちら」を押してメールをご確認ください</p>
        @endif


        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit">
                @if (session('status') == 'verification-link-sent')
                    認証メールを再送信する
                @else
                    認証はこちら
                @endif
            </button>
        </form>

    </main>
@endsection
