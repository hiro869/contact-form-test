<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/', [ContactController::class, 'create'])->name('contact.create');
Route::post('/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');
Route::post('/thanks', [ContactController::class, 'store'])->name('contact.store');

Route::get('/admin', [ContactController::class, 'admin'])->name('admin.index');               // 一覧・検索
Route::get('/admin/export', [ContactController::class, 'export'])->name('admin.export');       // CSV
Route::delete('/admin/{contact}', [ContactController::class, 'destroy'])->name('admin.destroy'); // 削除

// （ログイン/登録は後でFortify等に差し替え）
Route::view('/register', 'auth.register')->name('register');
Route::view('/login', 'auth.login')->name('login');


