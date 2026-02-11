<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Requests\RegisterRequest;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_なにも入力されていない場合のバリデーションエラー()
    {
        $response = $this->post("/register", [
            "name" => "",
            "email" => "",
            "password" => "",
        ]);

        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください',
            'email' => 'メールアドレスを入力してください',
            'password' => 'パスワードを入力してください',
        ]);
    }

    public function test_パスワードが7文字以下の場合のバリデーションエラー()
    {
        $response = $this->post("/register", [
            "name" => "テスト",
            "email" => "test12345@example.com",
            "password" => "1234567",
        ]);

        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            "password" => "パスワードは8文字以上で入力してください"
        ]);
    }

    public function test_パスワードが確認用パスワードと統一しない場合のバリデーションエラー()
    {
        $response = $this->post("/register", [
            "name" => "テスト",
            "email" => "test@example.com",
            "password" => "12345678",
            "password_confirmation" => "11111111",

        ]);

        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            "password_confirmation" => "パスワードと一致しません",
        ]);
    }

    public function test_会員登録後プロフィール設定画面に遷移()
    {
        // メール認証済みのユーザーを作成してプロフィール設定画面に遷移するかをテストします
        $user = User::factory()->create([
            "email_verified_at" => now(),
        ]);

        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)->get("/mypage/profile");

        $response->assertStatus(200);
    }
}
