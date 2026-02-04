<?php

namespace App\Providers;

use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Http\Responses\VerifyEmailResponse;
use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Http\Requests\RegisterRequest as FortifyRegisterRequest;
use Laravel\Fortify\Contracts\RegisterResponse;
use App\Http\Requests\RegisterRequest as MyRegisterRequest;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->singleton(
            \Laravel\Fortify\Http\Requests\LoginRequest::class,
            \App\Http\Requests\LoginRequest::class
        );

        $this->app->alias(
            \App\Http\Requests\LoginRequest::class,
            \Laravel\Fortify\Http\Requests\LoginRequest::class
        );

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::authenticateUsing(function ($request) {

            app(\App\Http\Requests\LoginRequest::class)->validateResolved();


            $user = \App\Models\User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => ['ログイン情報が登録されていません'],
                ]);
            }
            return $user;
        });

        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                return redirect('/login');
            }
        });

        $this->app->instance(\Laravel\Fortify\Contracts\RegisterResponse::class, new class implements \Laravel\Fortify\Contracts\RegisterResponse {
            public function toResponse($request)
            {
                // verification.notice = Fortifyデフォルトのメール認証誘導画面ルート
                return redirect()->route('verification.notice');
            }
        });

        // メール認証誘導画面のビューファイルを指定
        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        // メール認証成功後のリダイレクト先を強制的にプロフィール設定画面に設定。（商品を購入する際に住所を登録していないとエラーになる為）
        $this->app->instance(VerifyEmailResponse::class, new class extends VerifyEmailResponse {
            public function toResponce($request)
            {
                return $request->wantsJson()
                    ? new JsonResponse('', 204)
                    : redirect('/mypage/profile');
            }
        });
    }
}
