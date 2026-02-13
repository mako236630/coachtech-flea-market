<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;

class VerifyEmailTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_会員登録後に認証メールが送信される()
    {
        Notification::fake();

        $this->post("/register", [
            "name" => "テスト",
            "email" => "test@test.com",
            "password" => "12345678",
            "password_confirmation" => "12345678",
        ]);

        $user = User::where('email', 'test@test.com')->first();

        Notification::assertSentTo($user, \Illuminate\Auth\Notifications\VerifyEmail::class);
    }

    public function test_認証はこちらボタンを押すとメール認証サイトに遷移する()
    {
        $response = $this->post("/register", [
            "name" => "テスト",
            "email" => "test@test.com",
            "password" => "12345678",
            "password_confirmation" => "12345678",
        ]);

        $response->assertRedirect("/email/verify");

        // 外部サイト（Mailtrap）の画面の中身までは、テストでは確認できないので、
        // ボタンに設定されているURLが正しい移動先（Mailtrap）に
        // なっているかどうかをチェックすることで、正しく遷移できることを確認します。

        $user = User::where('email', 'test@test.com')->first();
        $page = $this->actingAs($user)->get("/email/verify");

        $expectedUrl = 'https://mailtrap.io/inboxes/4359147/messages/5325507066';
        $page->assertSee($expectedUrl, false);
        $page->assertSee('認証はこちらから');
    }

    public function test_メール認証を完了するとプロフィール設定画面に遷移する()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        //　認証画面のURL作成
        $verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // $verificationUrlのURLで認証ページアクセス
        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)->get($verificationUrl);
        $response->assertRedirect('/mypage/profile?verified=1');
    }
}
