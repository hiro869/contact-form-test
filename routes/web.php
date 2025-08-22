<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;


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


Route::post('/login', function (LoginRequest $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('admin.index');  // ログイン後の遷移先（必要に応じて /admin などに変更）
    }

    return back()->withErrors([
        'email' => 'ログイン情報が正しくありません。',
    ]);
})->name('login');
