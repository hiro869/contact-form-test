<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;


Route::get('/', [ContactController::class, 'create'])->name('contact.create');      // 入力ページ
Route::post('/confirm', [ContactController::class, 'confirm'])->name('contact.confirm'); // 確認ページ
Route::post('/thanks', [ContactController::class, 'store'])->name('contact.store');     // 保存→サンクス
Route::get('/admin', [ContactController::class, 'admin'])->name('admin.index');         // 管理画面

Route::view('/register', 'auth.register')->name('register');
Route::view('/login', 'auth.login')->name('login');


