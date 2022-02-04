<?php

namespace BonsaiCms\MetamodelJsonApi\Entities;

use Illuminate\Validation\Rule;
use BonsaiCms\Metamodel\Models\Entity;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class EntityRequest extends ResourceRequest
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
                'regex:/^([A-Z][a-z0-9]*)*$/',
                Rule::unique((new Entity)->getTable())->ignore($this->model()),
            ],
            'table' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z][a-z0-9_]*$/',
                Rule::unique((new Entity)->getTable())->ignore($this->model()),
            ],
        ];
    }
}
