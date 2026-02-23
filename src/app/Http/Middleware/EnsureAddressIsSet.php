<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAddressIsSet
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        // ①ログインしている②メール認証している③住所登録をしていない場合
        if ($user && $user->hasVerifiedEmail() && empty($user->address)) {
            // かつ、mypage/profile以外のページに移動しようとした（ヘッダーの出品画面や、ロゴから商品一覧画面）場合
            // 購入時の住所表示エラーを防ぐため、住所登録を必須化しています
            if (!$request->is('mypage/profile*')) {
                return redirect('/mypage/profile')->with('error', 'プロフィール設定をしてください');
            }
        }
        return $next($request);
    }
}
