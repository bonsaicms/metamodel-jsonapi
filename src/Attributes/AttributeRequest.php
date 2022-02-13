<?php

namespace BonsaiCms\MetamodelJsonApi\Attributes;

use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use BonsaiCms\Metamodel\Models\Attribute;
use LaravelJsonApi\Validation\Rule as JsonApiRule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class AttributeRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'column' => [
                'required',
                'string',
                'max:255',
                Rule::notIn([
                    'id',
                    'created_at',
                    'updated_at',
                ]),
                'regex:/^[a-z][a-z0-9_]*$/',
                Rule::unique((new Attribute)->getTable())->where(function ($query) {
                    return $query->where('entity_id', Arr::get($this->validationData(), 'entity.id'));
                })->ignore($this->model()),
            ],
            'dataType' => [
                'required',
                'string',
                Rule::in([
                    // TODO
                    'text',
                    'string',
                    'integer',
                    'boolean',
                    'date',
                    'time',
                    'datetime',
                    'json',
                ]),
            ],
            'default' => [
                // TODO
            ],
            'nullable' => [
                'nullable',
                'boolean',
            ],

            'entity' => [
                'required',
                JsonApiRule::toOne(),
            ],
        ];
    }
}
