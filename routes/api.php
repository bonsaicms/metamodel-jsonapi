<?php

use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;

JsonApiRoute::server(Config::get('bonsaicms-metamodel-jsonapi.server'))
    ->prefix(Config::get('bonsaicms-metamodel-jsonapi.baseUri'))
    ->resources(function ($server) {
        $server->resource('entities', JsonApiController::class);
        $server->resource('attributes', JsonApiController::class);
        $server->resource('relationships', JsonApiController::class);
    });
