<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use App\Actions\Fortify\CreateNewUser;


class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 画面テンプレート
        Fortify::loginView(fn () => view('auth.login'));
        Fortify::registerView(fn () => view('auth.register'));

        Fortify::createUsersUsing(CreateNewUser::class);

        // ── ログイン成功後の遷移先を /admin に変更 ──
        $this->app->singleton(LoginResponse::class, function () {
            return new class implements LoginResponse {
                public function toResponse($request)
                {
                    // ここを管理画面のルート名に合わせて変更
                    // 例) Route::get('/admin', ...)->name('admin');
                    return redirect()->route('admin');
                }
            };
        });

        // ── 登録成功後は /login に遷移 ──
        $this->app->singleton(RegisterResponse::class, function () {
            return new class implements RegisterResponse {
                public function toResponse($request)
                {
                    return redirect()->route('login');
                }
            };
        });
    }
}
