<?php

namespace App\Providers;

use App\Models\KodeBarang;
use App\Policies\KodeBarangPolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    protected $policies = [
        KodeBarang::class => KodeBarangPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
        Paginator::useTailwind();
        Paginator::defaultSimpleView('vendor.pagination.simple-tailwind');
    }
}
