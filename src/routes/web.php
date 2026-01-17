<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
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
Route::post("/item/{item_id}/comment", [CommentController::class, "store"])->name("comment.store")->middleware('auth');
