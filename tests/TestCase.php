<?php

namespace BonsaiCms\MetamodelJsonApi\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [
            \LaravelJsonApi\Laravel\ServiceProvider::class,
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
                'metamodel' => \BonsaiCms\MetamodelJsonApi\Server::class,
            ],
        ]);
//        config()->set('bonsaicms-metamodel-eloquent', [
//            'bind' => [
//                'modelManager' => true,
//            ],
//            'observeModels' => [
//                'entity' => true,
//                'attribute' => true,
//                'relationship' => true,
//            ],
//            'generate' => [
//                'folder' => __DIR__.'/../vendor/orchestra/testbench-core/laravel/app/Models',
//                'modelFileSuffix' => '.generated.php',
//                'namespace' => 'TestApp\\Models',
//                'parentModel' => 'Some\\Namespace\\ParentModel',
//            ],
//        ]);
    }

//    protected function setUp(): void
//    {
//        parent::setUp();
//
//        $this->deleteGeneratedFiles();
//    }
//
//    protected function tearDown(): void
//    {
//        parent::tearDown();
//
//        $this->deleteGeneratedFiles();
//    }
//
//    protected function deleteGeneratedFiles()
//    {
//        $files = glob(__DIR__.'/../vendor/orchestra/testbench-core/laravel/app/Models/*.generated.php');
//
//        foreach ($files as $file) {
//            if(is_file($file)) {
//                unlink($file);
//            }
//        }
//    }
}
