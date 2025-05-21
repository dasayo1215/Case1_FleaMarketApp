<?php

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

Route::view('/', 'items.index');
Route::view('/item', 'items.show');
Route::view('/sell', 'items.create');
Route::view('/purchase', 'purchases.create');
Route::view('/purchase/address', 'purchases.address');
Route::view('/purchases.create', 'purchases.create');
Route::view('/mypage/profile', 'users.edit');
Route::view('/mypage', 'users.show');
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::view('/mypage', 'users.show');
