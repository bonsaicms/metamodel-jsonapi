<?php

use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;

// TODO: config (middleware, auth, namespace, prefix etc.)
JsonApiRoute::server(Config::get('bonsaicms-metamodel-jsonapi.server'))
    ->prefix(Config::get('bonsaicms-metamodel-jsonapi.baseUri'))
    ->resources(function ($server) {
        $server->resource('entities', JsonApiController::class)
            ->relationships(function ($relationships) {
                $relationships->hasMany('attributes');
                $relationships->hasMany('leftRelationships');
                $relationships->hasMany('rightRelationships');
            });
        $server->resource('attributes', JsonApiController::class)
            ->relationships(function ($relationships) {
                $relationships->hasOne('entity');
            });
        $server->resource('relationships', JsonApiController::class)
            ->relationships(function ($relationships) {
                $relationships->hasOne('leftEntity');
                $relationships->hasOne('rightEntity');
            });
    });
