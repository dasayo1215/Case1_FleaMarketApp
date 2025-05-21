<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

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

// こういうの、認証してたらこのボタン、してないならこのボタン、とかはmiddleware使わない？
Route::get('/', [ItemController::class, 'index']);

Route::get('/register', [RegisterController::class, 'showRegistrationForm']);
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/login', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/item/{item_id}', [ItemController::class, 'show']);
Route::post('/item/{item_id}/comment', [ItemController::class, 'storeComment']);
Route::post('/item/{item_id}/like', [ItemController::class, 'toggleLike']);

// Route::middleware('auth')->group(function(){
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'showPurchaseForm']);
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'purchase']);
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'showAddressForm']);
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress']);
    Route::get('/sell', [ItemController::class, 'showSellForm']);
    Route::post('/sell', [ItemController::class, 'store']);
    Route::get('/mypage', [UserController::class, 'showProfile']);
    Route::get('/mypage/profile', [UserController::class, 'editProfile']);
    Route::patch('/mypage/profile', [UserController::class, 'updateProfile']);
// });