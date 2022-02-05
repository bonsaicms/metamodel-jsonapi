<?php

namespace BonsaiCms\MetamodelJsonApi\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use LaravelJsonApi\Testing\MakesJsonApiRequests;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestCase extends Orchestra
{
    use RefreshDatabase;
    use MakesJsonApiRequests;

    protected function getPackageProviders($app)
    {
        return [
            \LaravelJsonApi\Laravel\ServiceProvider::class,
            \LaravelJsonApi\Encoder\Neomerx\ServiceProvider::class,
            \LaravelJsonApi\Spec\ServiceProvider::class,
            \LaravelJsonApi\Validation\ServiceProvider::class,
            \BonsaiCms\Metamodel\MetamodelServiceProvider::class,
            \BonsaiCms\MetamodelJsonApi\MetamodelJsonApiServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'pgsql',
            'url' => null,
            'host' => '127.0.0.1',
            'port' => '5432',
            'database' => 'testing',
            'username' => 'postgres',
            'password' => 'postgres',
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ]);
        config()->set('bonsaicms-metamodel', [
            'entityTableName' => 'pre_met_entities_suf_met',
            'attributeTableName' => 'pre_met_attributes_suf_met',
            'relationshipTableName' => 'pre_met_relationships_suf_met',

            'generatedTablePrefix' => 'pre_gen_',
            'generatedTableSuffix' => '_suf_gen',
        ]);
        config()->set('jsonapi', [
            'servers' => [
                'testServerName' => \BonsaiCms\MetamodelJsonApi\Server::class,
            ],
        ]);
        config()->set('bonsaicms-metamodel-jsonapi', [
            'server' => 'testServerName',
            'authorizable' => false,
            'baseUri' => '/api/testUrlPrefix',
            'routesConfig' => [],
            'types' => [
                'entity' => 'entities',
                'attribute' => 'attributes',
                'relationship' => 'relationships',
            ],
        ]);
    }
}
