<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider {
    public function register(): void {}
    public function boot(): void {
        Model::preventLazyLoading(app()->isLocal());
        Paginator::useBootstrapFive();
        // Use Tailwind-styled pagination (custom view)
        Paginator::defaultView('partials.pagination');
        Paginator::defaultSimpleView('partials.pagination-simple');
    }
}
