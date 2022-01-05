<?php

namespace BonsaiCms\MetamodelJsonApi;

use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Entity;
use Illuminate\Support\ServiceProvider;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\Metamodel\Models\Relationship;

class MetamodelJsonApiServiceProvider extends ServiceProvider
{
    /**
     * Register package.
     *
     * @return void
     */
    public function register()
    {
//        $this->mergeConfigFrom(
//            __DIR__.'/../config/bonsaicms-metamodel-eloquent.php', 'bonsaicms-metamodel-eloquent'
//        );
//
//        if (Config::get('bonsaicms-metamodel-eloquent.bind.modelManager')) {
//            $this->app->singleton(ModelManagerContract::class, ModelManager::class);
//        }
    }

    /**
     * Bootstrap package.
     *
     * @return void
     */
    public function boot()
    {
//        if ($this->app->runningInConsole()) {
//            $this->commands([
//                DeleteModels::class,
//                GenerateModels::class,
//                RegenerateModels::class,
//            ]);
//        }
//
//        $this->publishes([
//            __DIR__.'/../config/bonsaicms-metamodel-eloquent.php' => $this->app->configPath('bonsaicms-metamodel-eloquent.php'),
//        ], 'bonsaicms-metamodel-eloquent-config');
//
//        $this->publishes([
//            __DIR__.'/../resources/stubs/' => $this->app->resourcePath('stubs/metamodel-eloquent/'),
//        ], 'bonsaicms-metamodel-eloquent-stubs');
//
//        if (Config::get('bonsaicms-metamodel-eloquent.observeModels.entity')) {
//            Entity::observe(EntityObserver::class);
//        }
//
//        if (Config::get('bonsaicms-metamodel-eloquent.observeModels.attribute')) {
//            Attribute::observe(AttributeObserver::class);
//        }
//
//        if (Config::get('bonsaicms-metamodel-eloquent.observeModels.relationship')) {
//            Relationship::observe(RelationshipObserver::class);
//        }
    }
}
