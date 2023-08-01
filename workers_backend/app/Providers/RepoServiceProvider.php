<?php

namespace App\Providers;

use App\Interfaces\Orders\OrderRepoInterface;
use App\Repos\Orders\OrderRepo;
use Illuminate\Support\ServiceProvider;

class RepoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(OrderRepoInterface::class, OrderRepo::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
