<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

// フロントのお問い合わせ（誰でも可）


Route::get('/',[ContactController::class, 'create'])->name('contact.create');
Route::post('/confirm',[ContactController::class, 'confirm'])->name('contact.confirm');
Route::post('/thanks', [ContactController::class, 'store'])->name('contact.store');
Route::get('/thanks',  fn() => view('contact.thanks'))->name('contact.thanks');


// 管理画面（要ログイン）
Route::middleware('auth')->group(function () {
    Route::get('/admin',                  [ContactController::class, 'admin'])->name('admin.index');
    Route::get('/admin/contacts/{contact}',[ContactController::class, 'show'])->name('admin.show');
    Route::delete('/admin/contacts/{contact}',     [ContactController::class, 'destroy'])->name('admin.destroy');
    Route::get('/admin/export',           [ContactController::class, 'export'])->name('admin.export');
});
