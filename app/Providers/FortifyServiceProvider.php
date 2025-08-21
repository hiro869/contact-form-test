<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\RegisterResponse;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::loginView(fn () => view('auth.login'));
        Fortify::registerView(fn () => view('auth.register'));

        Fortify::createUsersUsing(\App\Actions\Fortify\CreateNewUser::class);

        // 登録成功後は /login に返す
        $this->app->singleton(RegisterResponse::class, function () {
            return new class implements RegisterResponse {
                public function toResponse($request)
                {
                    return redirect()->route('login');
                }
            };
        });
        RateLimiter::for('login', function (Request $request) {
            $key = Str::lower((string) $request->input(Fortify::username()))
                    .'|'.$request->ip();
            return Limit::perMinute(5)->by($key);
        });
    }
}
