<?php

namespace BonsaiCms\MetamodelJsonApi;

use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Core\Server\Server as BaseServer;

class Server extends BaseServer
{
    /**
     * The base URI namespace for this server.
     *
     * @return string
     */
    protected function baseUri(): string
    {
        return Config::get('bonsaicms-metamodel-jsonapi.baseUri');
    }

    /**
     * Bootstrap the server when it is handling an HTTP request.
     *
     * @return void
     */
    public function serving(): void
    {
        // no-op
    }

    /**
     * Get the server's list of schemas.
     *
     * @return array
     */
    protected function allSchemas(): array
    {
        return [
            Entities\EntitySchema::class,
            Attributes\AttributeSchema::class,
            Relationships\RelationshipSchema::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public function authorizable(): bool
    {
        return Config::get('bonsaicms-metamodel-jsonapi.authorizable');
    }
}
