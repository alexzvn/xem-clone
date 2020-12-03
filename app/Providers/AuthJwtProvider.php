<?php

namespace App\Providers;

use App\Extensions\JwtGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AuthJwtProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Auth::extend('jwt', function ($app, $name, array $config)
        {
            return new JwtGuard(
                Auth::createUserProvider($config['provider']),
                $this->app['request'],
                $config['secret']
            );
        });
    }
}
