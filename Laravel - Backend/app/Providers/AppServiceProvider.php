<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\Repositories\UserProfileRepositoryInterface;
use App\Repositories\UserProfileRepository;

use App\Contracts\Services\UserProfileServiceInterface;
use App\Services\UserProfileService;

use GuzzleHttp\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserProfileRepositoryInterface::class, UserProfileRepository::class);

        $this->app->bind(UserProfileServiceInterface::class, UserProfileService::class);

        $this->app->singleton(Client::class, function ($app) {
            return new Client([
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
