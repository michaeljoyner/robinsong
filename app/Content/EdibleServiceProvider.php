<?php

namespace App\Content;

use ContentWriterTest;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Yaml\Parser;

class EdibleServiceProvider extends ServiceProvider
{

    protected $defer = true;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ContentWriter::class, function($app) {
            $fileSnapshot = (new ContentSnapshotFactory(new Parser()))->makeSnapshotFromYmlFile(base_path('edible.yml'));
            $databaseSnapshot = (new ContentSnapshotFactory(new Parser()))->makeSnapshotFromRepo(new ContentRepository());
            return new ContentWriter($fileSnapshot, $databaseSnapshot);
        });
    }

    public function provides()
    {
        return [ContentWriterTest::class];
    }
}
