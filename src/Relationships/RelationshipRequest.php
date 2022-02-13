<?php

namespace BonsaiCms\MetamodelJsonApi\Relationships;

use Illuminate\Validation\Rule;
use BonsaiCms\Metamodel\Models\Relationship;
use LaravelJsonApi\Validation\Rule as JsonApiRule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class RelationshipRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'cardinality' => [
                'required',
                'string',
                Rule::in(Relationship::CARDINALITIES),
            ],
            'pivotTable' => [
                'nullable',
                'required_if:cardinality,manyToMany',
                'prohibited_unless:cardinality,manyToMany',
                'string',
                'max:255',
                'regex:/^[a-z][a-z0-9_]*$/',
            ],
            'leftForeignKey' => [
                'nullable',
                'required_if:cardinality,manyToMany',
                'prohibited_unless:cardinality,manyToMany', // TODO: napisat k tomuto testy
                'string',
                'max:255',
                'regex:/^[a-z][a-z0-9_]*$/',
            ],
            'rightForeignKey' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z][a-z0-9_]*$/',
            ],
            'leftRelationshipName' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z][a-z0-9]*([A-Z][a-z0-9]*)*$/', // TODO: spravit z regexov konstanty ?
                // TODO: pridat pravidlo aby nazov metody nemohol byt PHP keyword
                // TODO: pridat pravidlo aby nazov metody nemohol byt nazov nejakej existujucej eloquent metody
                // TODO: pridat pravidlo aby nazov metody bol unique v ramci danej entity
                // TODO: ale proaidne to premysliet, lebo na to ma asi aj vplyv hodnota cardinality
                // TODO: To iste plati pre rightRelationshipName
            ],
            'rightRelationshipName' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z][a-z0-9]*([A-Z][a-z0-9]*)*$/',
            ],

            'leftEntity' => [
                'required',
                JsonApiRule::toOne(),
            ],
            'rightEntity' => [
                'required',
                JsonApiRule::toOne(),
            ],
        ];
    }
}
