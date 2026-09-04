<?php

namespace App\Providers;

use App\Models\Penduduk;
use App\Models\Rumah;
use App\Models\Surat;
use App\Policies\PendudukPolicy;
use App\Policies\RumahPolicy;
use App\Policies\SuratPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Penduduk::class, PendudukPolicy::class);
        Gate::policy(Rumah::class, RumahPolicy::class);
        Gate::policy(Surat::class, SuratPolicy::class);
    }
}
