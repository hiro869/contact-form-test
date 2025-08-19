<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::middleware('guest')->group(function () {
    Route::get('/register', fn () => view('auth.register'))->name('register');
    Route::get('/login', fn () => view('auth.login'))->name('login');

Route::get('/', [ContactController::class,'create'])->name('contact.create');
Route::post('/confirm', [ContactController::class,'confirm'])->name('contact.confirm');
Route::post('/thanks',  [ContactController::class,'store'])->name('contact.store');

Route::get('admin',         [ContactController::class,'admin'])->name('admin.index');
Route::get('admin/export',  [ContactController::class,'export'])->name('admin.export'); // ★ 先に置く
Route::get('admin/{contact}', [ContactController::class,'show'])->name('admin.show');
Route::delete('admin/{contact}', [ContactController::class,'destroy'])->name('admin.destroy');


});
