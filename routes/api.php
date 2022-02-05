<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;

Route::group(Config::get('bonsaicms-metamodel-jsonapi.routesConfig'), function () {
    JsonApiRoute::server(Config::get('bonsaicms-metamodel-jsonapi.server'))
        ->prefix(Config::get('bonsaicms-metamodel-jsonapi.baseUri'))
        ->resources(function ($server) {
            // TODO: tu by som mal asi pouzivat vlastny kontroler ktory mi umozni veci ako "regenerate eloquent model" a pod.
            $server->resource(Config::get('bonsaicms-metamodel-jsonapi.types.entity'), JsonApiController::class)
                ->relationships(function ($relationships) {
                    $relationships->hasMany('attributes');
                    $relationships->hasMany('leftRelationships');
                    $relationships->hasMany('rightRelationships');
                });
            $server->resource(Config::get('bonsaicms-metamodel-jsonapi.types.attribute'), JsonApiController::class)
                ->relationships(function ($relationships) {
                    $relationships->hasOne('entity');
                });
            $server->resource(Config::get('bonsaicms-metamodel-jsonapi.types.relationship'), JsonApiController::class)
                ->relationships(function ($relationships) {
                    $relationships->hasOne('leftEntity');
                    $relationships->hasOne('rightEntity');
                });
        });
});
