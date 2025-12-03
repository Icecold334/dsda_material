<?php

namespace App\Providers;

use App\Services\ApprovalService;
use App\Services\StockLedgerService;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ApprovalService::class);
        $this->app->singleton(StockLedgerService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::addPersistentMiddleware('plt');
    }
}
