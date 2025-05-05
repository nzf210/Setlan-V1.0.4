<?php

namespace App\Providers;

use App\Models\KodeBarangModel;
use App\Models\TahunModel;
use App\Policies\KodeBarangPolicy;
use App\Policies\TahunPolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        KodeBarangModel::class => KodeBarangPolicy::class,
        TahunModel::class => TahunPolicy::class,
    ];

    public function register(): void
    {
        $this->registerPolicies();
    }

    public function boot(): void
    {
        JsonResource::withoutWrapping();
        Paginator::useTailwind();
        Paginator::defaultSimpleView('vendor.pagination.simple-tailwind');
    }
}
