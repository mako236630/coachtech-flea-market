<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_なにも入力されていない場合のバリデーションエラー()
    {
        $response = $this->post("/login", [
            "email" => "",
            "password" => "",
        ]);

        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
            'password' => 'パスワードを入力してください',
        ]);
    }


    public function test_入力情報が間違っている場合のエラー()
    {
        $user = User::factory()->create([
            'email' => "test-login" . time() . "@example.com",
            "password" => "password123",
        ]);

        $response = $this->post("/login", [
            'email' => 'test-login@example.com',
            "password" => "password789",
        ]);

        $response->assertSessionHasErrors([
            "email" => "ログイン情報が登録されていません",
        ]);

        $this->assertGuest();
    }

     public function test_ログイン処理の実行()
    {
        $user = User::create([
            "name" => "テスト",
            "email" => "test-login@example.com",
            "password" => bcrypt('password123'),
        ]);
        

        $response = $this->post("/login", [
            "email" => "test-login@example.com",
            "password" => "password123",
        ]);

        $response->assertStatus(302);

        $this->assertAuthenticatedAs($user);

        $response->assertSessionHasNoErrors();
    }

    public function test_ログアウト処理の実行() {
        $user = User::create([
            "name" => "テスト",
            "email" => "test-login@example.com",
            "password" => bcrypt('password123'),
        ]);

        $response = $this->actingAs($user)->post("/logout");

        $response->assertRedirect("/login");

        $this->assertGuest();
    }
}
