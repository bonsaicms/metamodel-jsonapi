<?php

namespace BonsaiCms\MetamodelJsonApi\Entities;

use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Entity;
use LaravelJsonApi\Contracts\Schema\Sortable;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Sorting\SortCountable;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;

class EntitySchema extends Schema
{
    /**
     * Get the JSON:API resource type.
     *
     * @return string
     */
    public static function type(): string
    {
        return Config::get('bonsaicms-metamodel-jsonapi.types.entity');
    }

    /**
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static string $model = Entity::class;

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
            Str::make('table')->sortable(),
            Str::make('realTableName')->sortable()->readOnly(),
            DateTime::make('createdAt')->sortable()->readOnly(),
            DateTime::make('updatedAt')->sortable()->readOnly(),

            HasMany::make('attributes')->type(Config::get('bonsaicms-metamodel-jsonapi.types.attribute'))->canCount(),
            HasMany::make('leftRelationships')->type(Config::get('bonsaicms-metamodel-jsonapi.types.relationship'))->canCount(),
            HasMany::make('rightRelationships')->type(Config::get('bonsaicms-metamodel-jsonapi.types.relationship'))->canCount(),
        ];
    }

    /**
     * Get additional sortables.
     *
     * Get sortables that are not the resource ID or a resource attribute.
     *
     * @return Sortable[]|iterable
     */
    public function sortables(): iterable
    {
        return [
            SortCountable::make($this, 'attributes'),
            SortCountable::make($this, 'leftRelationships'),
            SortCountable::make($this, 'rightRelationships'),
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
