<?php

namespace App\Providers;

use App\Content\ContentRepository;
use Illuminate\Support\ServiceProvider;

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
