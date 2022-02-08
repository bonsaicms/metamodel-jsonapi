<?php

use BonsaiCms\Metamodel\Models\Entity;

// store

it('stores a new oneToOne relationship', function () {
    $leftEntity = Entity::factory()->create();
    $rightEntity = Entity::factory()->create();

    $data = [
        'type' => 'relationships',
        'attributes' => [
            'cardinality' => 'oneToOne',
            'rightForeignKey' => 'left_entity_id',
            'leftRelationshipName' => 'rightEntity',
            'rightRelationshipName' => 'leftEntity',
        ],
        'relationships' => [
            'leftEntity' => [
                'data' => [
                    'id' => (string) $leftEntity->getRouteKey(),
                    'type' => 'entities',
                ],
            ],
            'rightEntity' => [
                'data' => [
                    'id' => (string) $rightEntity->getRouteKey(),
                    'type' => 'entities',
                ],
            ],
        ],
    ];

    $this
        ->jsonApi()
        ->expects('relationships')
        ->withData($data)
        ->includePaths('leftEntity', 'rightEntity')
        ->post('/api/testUrlPrefix/relationships')
        ->assertCreatedWithServerId('http://localhost/api/testUrlPrefix/relationships', $data);
});
