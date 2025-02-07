<?php

namespace App\Providers;

use App\Clients\BestSellers\LiveBestSellersClient;
use App\Clients\BestSellers\TestBestSellersClient;
use App\Contracts\BestSellersClientContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BestSellersClientContract::class, function () {
            if (config('services.nyt.test_mode', false)) {
                return new TestBestSellersClient;
            }

            return new LiveBestSellersClient;
        });
    }

    public function boot(): void
    {
        //
    }
}
