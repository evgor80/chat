<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepository;
use App\Services\UserService;
use App\Interfaces\IUserRepository;
use App\Interfaces\IUserService;
use App\Interfaces\IRoomRepository;
use App\Repositories\RoomRepository;
use App\Interfaces\IRoomService;
use App\Services\RoomService;
use App\Interfaces\IChatService;
use App\Services\ChatService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IRoomRepository::class, RoomRepository::class);
        $this->app->bind(IRoomService::class, RoomService::class);
        $this->app->bind(IChatService::class, ChatService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
