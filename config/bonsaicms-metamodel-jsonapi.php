<?php

return [
    /*
     * The name of the server.
     */
    'server' => 'metamodel',

    /*
     * Determine if the server is authorizable.
     */
    'authorizable' => true,

    /*
     * The base URI namespace for the server.
     */
    'baseUri' => '/api/jsonapi-metamodel',

    /*
     * This array will be passed as the first argument to Route::group()
     */
    'routesConfig' => [
        'middleware' => [
            'api',
            'auth',
        ],
    ],

    /*
     * JSON:API resource types.
     */
    'types' => [
        'entity' => 'metamodel-entities',
        'attribute' => 'metamodel-attributes',
        'relationship' => 'metamodel-relationships',
    ],
];
