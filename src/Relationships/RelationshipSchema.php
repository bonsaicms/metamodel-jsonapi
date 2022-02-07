<?php

namespace BonsaiCms\MetamodelJsonApi\Relationships;

use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use BonsaiCms\Metamodel\Models\Relationship;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;

class RelationshipSchema extends Schema
{
    /**
     * Get the JSON:API resource type.
     *
     * @return string
     */
    public static function type(): string
    {
        return Config::get('bonsaicms-metamodel-jsonapi.types.relationship');
    }

    /**
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static string $model = Relationship::class;

    /**
     * Get the resource fields.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            ID::make(),
            Str::make('cardinality')->sortable(),
            Str::make('pivotTable')->sortable(),
            Str::make('leftForeignKey')->sortable(),
            Str::make('rightForeignKey')->sortable(),
            Str::make('leftRelationshipName')->sortable(),
            Str::make('rightRelationshipName')->sortable(),
            DateTime::make('createdAt')->sortable()->readOnly(),
            DateTime::make('updatedAt')->sortable()->readOnly(),

            BelongsTo::make('leftEntity')->type(Config::get('bonsaicms-metamodel-jsonapi.types.entity')),
            BelongsTo::make('rightEntity')->type(Config::get('bonsaicms-metamodel-jsonapi.types.entity')),
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
