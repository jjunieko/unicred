<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Cooperado\Repositories\CooperadoRepository;
use App\Infrastructure\Persistence\Eloquent\CooperadoRepositoryEloquent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CooperadoRepository::class, CooperadoRepositoryEloquent::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
