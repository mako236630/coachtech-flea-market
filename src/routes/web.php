<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellController;
use App\Models\Item;
use Symfony\Component\Routing\Route as RoutingRoute;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", [ItemController::class, "index"])->name("item.list")->middleware('address.check');
Route::get("/item/{item_id}", [ItemController::class, "show"])->name("item.show");

Route::middleware(['auth', 'verified', 'address.check'])->group(function (){

Route::post("/item/{item_id}/comment", [CommentController::class, "store"])->name("comment.store")->middleware("auth");
Route::post("/item/{item_id}/favorite", [FavoriteController::class, "store"])->name("favorite.store")->middleware("auth");
Route::get("/purchase/{item_id}", [PurchaseController::class, "index"])->name("item.purchase")->middleware("auth");
Route::get("/purchase/address/{item_id}", [AddressController::class, "index"])->name("purchase.address");
Route::post("/purchase/address/{item_id}", [AddressController::class, "update"])->name("address.update");
Route::post("/purchase/checkout/{item_id}", [PurchaseController::class, "checkout"])->name("purchase.checkout");
Route::get("/purchase/success/{item_id}", [PurchaseController::class, "success"])->name("purchase.success");
Route::get("/mypage", [ProfileController::class, "index"])->name("mypage.index")->middleware("auth");
Route::get("/mypage/profile", [ProfileController::class, "edit"])->name("profile.edit")->middleware("auth", 'verified');
Route::post("/mypage/profile", [ProfileController::class, "update"])->name("profile.update");
Route::get("/sell", [ItemController::class, "create"])->name("sell.create")->middleware("auth");
Route::post("/sell", [ItemController::class, "store"])->name("sell.store");
});

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    // メール認証したことをDBに書き込み
    $request->fulfill();

    // メール認証前に見ていたページに戻るのではなく、必ずプロフィール編集画面に飛ばし、住所登録させます（intendedさせません）
    return redirect('/mypage/profile?verified=1');
})->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');