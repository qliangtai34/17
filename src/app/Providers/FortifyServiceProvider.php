<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\LoginResponse;
use App\Actions\Fortify\RegisterResponse;
use Laravel\Fortify\Fortify;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class FortifyServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Fortifyにアクションをバインド
        $this->app->singleton(CreatesNewUsers::class, CreateNewUser::class);
    }

    public function boot()
    {
        /*
        |--------------------------------------------------------------------------
        | ★★ ここで Fortify のリクエスト制限（RateLimiter）を無効化 ★★
        |--------------------------------------------------------------------------
        */
        RateLimiter::for('login', function (Request $request) {
            return null; // 制限なし
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return null; // 制限なし
        });

        // Fortify のビュー設定
        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });
    }
}
