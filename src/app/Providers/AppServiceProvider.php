<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use App\Actions\Fortify\LoginResponse as CustomLoginResponse;
use App\Actions\Fortify\RegisterResponse as CustomRegisterResponse;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // ログイン後のリダイレクトをカスタマイズ
        $this->app->singleton(LoginResponse::class, CustomLoginResponse::class);

        // 新規登録後のリダイレクトをカスタマイズ
        $this->app->singleton(RegisterResponse::class, CustomRegisterResponse::class);
    }

    public function boot()
    {
        //
    }
}