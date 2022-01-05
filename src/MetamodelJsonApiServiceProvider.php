<?php

namespace BonsaiCms\MetamodelJsonApi;

use Illuminate\Support\ServiceProvider;

class MetamodelJsonApiServiceProvider extends ServiceProvider
{
    /**
     * Register package.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/bonsaicms-metamodel-jsonapi.php', 'bonsaicms-metamodel-jsonapi'
        );
    }

    /**
     * Bootstrap package.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        $this->publishes([
            __DIR__.'/../config/bonsaicms-metamodel-jsonapi.php' => $this->app->configPath('bonsaicms-metamodel-jsonapi.php'),
        ], 'bonsaicms-metamodel-jsonapi-config');
    }
}
