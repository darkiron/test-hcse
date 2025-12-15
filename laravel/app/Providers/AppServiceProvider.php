<?php

namespace App\Providers;

use App\Domain\Offers\Repositories\OfferReadRepository;
use App\Infrastructure\Offers\OfferReadRepositoryEloquent;
use App\Domain\Products\Repositories\ProductReadRepository;
use App\Infrastructure\Products\ProductReadRepositoryEloquent;
use App\Domain\Products\Repositories\ProductWriteRepository;
use App\Infrastructure\Products\ProductWriteRepositoryEloquent;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // DDD bindings
        $this->app->bind(OfferReadRepository::class, OfferReadRepositoryEloquent::class);
        $this->app->bind(ProductReadRepository::class, ProductReadRepositoryEloquent::class);
        $this->app->bind(ProductWriteRepository::class, ProductWriteRepositoryEloquent::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // API Resources: pas d'enveloppe "data" pour matcher le contrat existant
        JsonResource::withoutWrapping();
    }
}
