<?php

namespace App\Providers;

use App\Models\User;
use App\View\Composers\NavigationComposer;
use App\View\Composers\PanelComposer;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Tabel model_has_roles/model_has_permissions menyimpan 'App\User' (nama kelas Laravel 5.8).
        // Tanpa pemetaan ini semua peran terbaca kosong dan tidak ada yang bisa login.
        Relation::morphMap(['App\User' => User::class]);
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
        Paginator::useBootstrapFive();
        Carbon::setLocale('id');

        View::composer(['layouts.navbar', 'layouts.footer'], NavigationComposer::class);
        View::composer('panel.layouts.app', PanelComposer::class);
    }
}
