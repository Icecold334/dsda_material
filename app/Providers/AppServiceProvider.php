<?php

namespace App\Providers;

use App\Models\User;
use App\Services\ApprovalService;
use App\Services\StockLedgerService;
use Illuminate\Support\Facades\Auth;
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
        // Auth::login(User::where('email', 'superadmin@dsda.test')->first());
        // Auth::login(User::where('email', 'kepala-satuan-pelaksanatebetjakarta-barat@test.com')->first());
        // Auth::login(User::where('email', 'kepala-seksipemeliharaanjakarta-barat@test.com')->first());
        // Auth::login(User::where('email', 'kepala-suku-dinasjakarta-barat@test.com')->first());
        // Auth::login(User::where('email', 'kepala-sub-bagiantata-usahajakarta-barat@test.com')->first());
        Auth::login(User::where('email', 'pengurus-barangjakarta-barat@test.com')->first());
    }
}
