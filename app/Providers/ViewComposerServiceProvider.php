<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Michaeljoyner\Edible\ContentRepository;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('admin.partials.navbar', function ($view) {
            $ediblePages = (new ContentRepository())->getPageListWithUrls();
            return $view->with(compact('ediblePages'));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
