<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;

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

Route::get("/", [ItemController::class, "index"])->name("item.list");

Route::get("/item/{item_id}", [ItemController::class, "show"])->name("item.show");
Route::post("/item/{item_id}/comment", [CommentController::class, "store"])->name("comment.store")->middleware("auth");
Route::post("/item/{item_id}/favorite", [FavoriteController::class, "store"])->name("favorite.store")->middleware("auth");
Route::get("/purchase/{item_id}", [PurchaseController::class, "index"])->name("item.purchase")->middleware("auth");
Route::post("/purchase/{item_id}", [PurchaseController::class, "store"])->name("purchase.store");
Route::get("/purchase/address/{item_id}", [AddressController::class, "index"])->name("purchase.address");
Route::post("/purchase/address/{item_id}", [AddressController::class, "update"])->name("address.update");
Route::post("/puechase/checkout/{item_id}", [PurchaseController::class, "checkout"])->name("purchase.checkout");
Route::get("/purchase/success/{item_id}", [PurchaseController::class, "success"])->name("purchase.success");