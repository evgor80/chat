<?php

namespace App\Providers;

use App\Jwt\JwToken;
use Illuminate\Support\ServiceProvider;

class JwTokenFacadeProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('jwtoken', function () {
            return new JwToken();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
