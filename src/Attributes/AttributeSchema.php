<?php

namespace BonsaiCms\MetamodelJsonApi\Attributes;

use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Attribute;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use BonsaiCms\MetamodelJsonApi\Fields\Json;

class AttributeSchema extends Schema
{
    /**
     * Get the JSON:API resource type.
     *
     * @return string
     */
    public static function type(): string
    {
        return Config::get('bonsaicms-metamodel-jsonapi.types.attribute');
    }

    /**
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static string $model = Attribute::class;

    /**
     * Get the resource fields.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            ID::make(),
            Str::make('name')->sortable(),
            Str::make('column')->sortable(),
            Str::make('dataType')->sortable(),
            Json::make('default')->sortable(),
            Boolean::make('nullable')->sortable(),
            DateTime::make('createdAt')->sortable()->readOnly(),
            DateTime::make('updatedAt')->sortable()->readOnly(),

            BelongsTo::make('entity')->type(Config::get('bonsaicms-metamodel-jsonapi.types.entity')),
        ];
    }

    /**
     * Get the resource filters.
     *
     * @return array
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this),
        ];
    }

    /**
     * Get the resource paginator.
     *
     * @return Paginator|null
     */
    public function pagination(): ?Paginator
    {
        return PagePagination::make();
    }
}
