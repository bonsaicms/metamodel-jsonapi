<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Attribute;

// fetch

it('fetches empty response where no attributes exist', function () {
    $this
        ->jsonApi()
        ->get('/api/testUrlPrefix/attributes')
        ->assertFetchedNone();
});

it('fetches one attribute', function () {
    $attribute = Attribute::factory()
        ->for(Entity::factory())
        ->create();

    $this
        ->jsonApi()
        ->expects('attributes')
        ->get('/api/testUrlPrefix/attributes/'.$attribute->getRouteKey())
        ->assertFetchedOne($attribute);
});

it('fetches many attributes', function () {
    $attributes = Attribute::factory()
        ->count(3)
        ->for(Entity::factory())
        ->create();

    $this
        ->jsonApi()
        ->expects('attributes')
        ->get('/api/testUrlPrefix/attributes')
        ->assertFetchedMany($attributes);
});

// fetch & include related

it('fetches many attributes with related entity', function () {
    $attributes = Attribute::factory()
        ->count(3)
        ->for(Entity::factory())
        ->create();

    $this
        ->jsonApi()
        ->expects('attributes')
        ->includePaths('entity')
        ->get('/api/testUrlPrefix/attributes')
        ->assertFetchedMany($attributes);
});

// fetch related

it('fetches the entity related to the attribute', function () {
    $entity = Entity::factory()
        ->create();

    $attribute = Attribute::factory()
        ->for($entity)
        ->create();

    $this
        ->jsonApi()
        ->expects('entities')
        ->get('/api/testUrlPrefix/attributes/'.$attribute->getRouteKey().'/entity')
        ->assertFetchedOne($entity);
});

// store

it('stores a new attribute', function () {
    $entity = Entity::factory()->create();

    $attribute = Attribute::factory()->make([
        'nullable' => true,
    ]);

    $data = [
        'type' => 'attributes',
        'attributes' => [
            'name' => $attribute->name,
            'column' => $attribute->column,
            'dataType' => $attribute->type,
            'default' => $attribute->default,
            'nullable' => $attribute->nullable,
        ],
        'relationships' => [
            'entity' => [
                'data' => [
                    'id' => (string) $entity->getRouteKey(),
                    'type' => 'entities',
                ],
            ],
        ],
    ];

    $this
        ->jsonApi()
        ->expects('attribute')
        ->withData($data)
        ->includePaths('entity')
        ->post('/api/testUrlPrefix/attributes')
        ->assertCreatedWithServerId('http://localhost/api/testUrlPrefix/attributes', $data);
});

it('can store two attributes with the same column field for two different entities', function () {
    $entity1 = Entity::factory()->create();
    $entity2 = Entity::factory()->create();

    $attribute1 = Attribute::factory()->make([
        'column' => 'some_column',
        'nullable' => true,
    ]);
    $attribute2 = Attribute::factory()->make([
        'column' => 'some_column',
        'nullable' => true,
    ]);

    $data1 = [
        'type' => 'attributes',
        'attributes' => [
            'name' => $attribute1->name,
            'column' => $attribute1->column,
            'dataType' => $attribute1->type,
            'default' => $attribute1->default,
            'nullable' => $attribute1->nullable,
        ],
        'relationships' => [
            'entity' => [
                'data' => [
                    'id' => (string) $entity1->getRouteKey(),
                    'type' => 'entities',
                ],
            ],
        ],
    ];

    $this
        ->jsonApi()
        ->expects('attribute')
        ->withData($data1)
        ->includePaths('entity')
        ->post('/api/testUrlPrefix/attributes')
        ->assertCreatedWithServerId('http://localhost/api/testUrlPrefix/attributes', $data1);

    $data2 = [
        'type' => 'attributes',
        'attributes' => [
            'name' => $attribute2->name,
            'column' => $attribute2->column,
            'dataType' => $attribute2->type,
            'default' => $attribute2->default,
            'nullable' => $attribute2->nullable,
        ],
        'relationships' => [
            'entity' => [
                'data' => [
                    'id' => (string) $entity2->getRouteKey(),
                    'type' => 'entities',
                ],
            ],
        ],
    ];

    $this
        ->jsonApi()
        ->expects('attribute')
        ->withData($data2)
        ->includePaths('entity')
        ->post('/api/testUrlPrefix/attributes')
        ->assertCreatedWithServerId('http://localhost/api/testUrlPrefix/attributes', $data2);
});

it('cannot store two attributes with the same column field for the same entity', function () {
    $entity = Entity::factory()->create();

    $attribute1 = Attribute::factory()->make([
        'column' => 'some_column',
        'nullable' => true,
    ]);
    $attribute2 = Attribute::factory()->make([
        'column' => 'some_column',
        'nullable' => true,
    ]);

    $data1 = [
        'type' => 'attributes',
        'attributes' => [
            'name' => $attribute1->name,
            'column' => $attribute1->column,
            'dataType' => $attribute1->type,
            'default' => $attribute1->default,
            'nullable' => $attribute1->nullable,
        ],
        'relationships' => [
            'entity' => [
                'data' => [
                    'id' => (string) $entity->getRouteKey(),
                    'type' => 'entities',
                ],
            ],
        ],
    ];

    $this
        ->jsonApi()
        ->expects('attribute')
        ->withData($data1)
        ->includePaths('entity')
        ->post('/api/testUrlPrefix/attributes')
        ->assertCreatedWithServerId('http://localhost/api/testUrlPrefix/attributes', $data1);

    $data2 = [
        'type' => 'attributes',
        'attributes' => [
            'name' => $attribute2->name,
            'column' => $attribute2->column,
            'dataType' => $attribute2->type,
            'default' => $attribute2->default,
            'nullable' => $attribute2->nullable,
        ],
        'relationships' => [
            'entity' => [
                'data' => [
                    'id' => (string) $entity->getRouteKey(),
                    'type' => 'entities',
                ],
            ],
        ],
    ];

    $this
        ->jsonApi()
        ->expects('attribute')
        ->withData($data2)
        ->includePaths('entity')
        ->post('/api/testUrlPrefix/attributes')
        ->assertErrorStatus([
            'status' => '422',
            'title' => 'Unprocessable Entity',
            'source' => [
                'pointer' => '/data/attributes/column',
            ],
        ]);
});

// update

it('updates the attribute\'s name field', function () {
    $attribute = Attribute::factory()
        ->for(Entity::factory())
        ->create([
            'name' => 'original',
        ]);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'id' => $attribute->getRouteKey(),
        'name' => 'original',
    ]);

    $data = [
        'id' => (string) $attribute->getRouteKey(),
        'type' => 'attributes',
        'attributes' => [
            'name' => 'changed',
        ],
    ];

    $this
        ->jsonApi()
        ->expects('attributes')
        ->withData($data)
        ->patch('/api/testUrlPrefix/attributes/'.$attribute->getRouteKey())
        ->assertFetchedOne($data);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'id' => $attribute->getRouteKey(),
        'name' => 'changed',
    ]);
});

it('updates the attribute\'s column field', function () {
    $attribute = Attribute::factory()
        ->for(Entity::factory())
        ->create([
            'column' => 'original',
        ]);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'id' => $attribute->getRouteKey(),
        'column' => 'original',
    ]);

    $data = [
        'id' => (string) $attribute->getRouteKey(),
        'type' => 'attributes',
        'attributes' => [
            'column' => 'changed',
        ],
    ];

    $this
        ->jsonApi()
        ->expects('attributes')
        ->withData($data)
        ->patch('/api/testUrlPrefix/attributes/'.$attribute->getRouteKey())
        ->assertFetchedOne($data);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'id' => $attribute->getRouteKey(),
        'column' => 'changed',
    ]);
});

it('updates the attribute\'s data type field', function () {
    $attribute = Attribute::factory()
        ->for(Entity::factory())
        ->create([
            'type' => 'boolean',
        ]);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'id' => $attribute->getRouteKey(),
        'type' => 'boolean',
    ]);

    $data = [
        'id' => (string) $attribute->getRouteKey(),
        'type' => 'attributes',
        'attributes' => [
            'dataType' => 'string',
        ],
    ];

    $this
        ->jsonApi()
        ->expects('attributes')
        ->withData($data)
        ->patch('/api/testUrlPrefix/attributes/'.$attribute->getRouteKey())
        ->assertFetchedOne($data);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'id' => $attribute->getRouteKey(),
        'type' => 'string',
    ]);
});

it('updates the attribute\'s default field', function () {
    // initialize with value 'original'
    $attribute = Attribute::factory()
        ->for(Entity::factory())
        ->create([
            'default' => 'original',
        ]);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'id' => $attribute->getRouteKey(),
        'default' => '"original"',
    ]);

    expect(Attribute::findOrFail($attribute->getKey())->default)->toBe('original');

    // change the value to 'changed'

    $data = [
        'id' => (string) $attribute->getRouteKey(),
        'type' => 'attributes',
        'attributes' => [
            'default' => 'changed',
        ],
    ];

    $this
        ->jsonApi()
        ->expects('attributes')
        ->withData($data)
        ->patch('/api/testUrlPrefix/attributes/'.$attribute->getRouteKey())
        ->assertFetchedOne($data);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'id' => $attribute->getRouteKey(),
        'default' => '"changed"',
    ]);

    expect(Attribute::findOrFail($attribute->getKey())->default)->toBe('changed');

    // change the value to 'null' (string)

    $data = [
        'id' => (string) $attribute->getRouteKey(),
        'type' => 'attributes',
        'attributes' => [
            'default' => 'null',
        ],
    ];

    $this
        ->jsonApi()
        ->expects('attributes')
        ->withData($data)
        ->patch('/api/testUrlPrefix/attributes/'.$attribute->getRouteKey())
        ->assertFetchedOne($data);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'id' => $attribute->getRouteKey(),
        'default' => '"null"',
    ]);

    expect(Attribute::findOrFail($attribute->getKey())->default)->toBe('null');

    // change the value to null

    $data = [
        'id' => (string) $attribute->getRouteKey(),
        'type' => 'attributes',
        'attributes' => [
            'default' => null,
        ],
    ];

    $this
        ->jsonApi()
        ->expects('attributes')
        ->withData($data)
        ->patch('/api/testUrlPrefix/attributes/'.$attribute->getRouteKey())
        ->assertFetchedOne($data);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'id' => $attribute->getRouteKey(),
        'default' => null,
    ]);

    expect(Attribute::findOrFail($attribute->getKey())->default)->toBeNull();

    // change the value to true

    $data = [
        'id' => (string) $attribute->getRouteKey(),
        'type' => 'attributes',
        'attributes' => [
            'default' => true,
        ],
    ];

    $this
        ->jsonApi()
        ->expects('attributes')
        ->withData($data)
        ->patch('/api/testUrlPrefix/attributes/'.$attribute->getRouteKey())
        ->assertFetchedOne($data);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'id' => $attribute->getRouteKey(),
        'default' => 'true',
    ]);

    expect(Attribute::findOrFail($attribute->getKey())->default)->toBe(true);

    // change the value to 123

    $data = [
        'id' => (string) $attribute->getRouteKey(),
        'type' => 'attributes',
        'attributes' => [
            'default' => 123,
        ],
    ];

    $this
        ->jsonApi()
        ->expects('attributes')
        ->withData($data)
        ->patch('/api/testUrlPrefix/attributes/'.$attribute->getRouteKey())
        ->assertFetchedOne($data);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'id' => $attribute->getRouteKey(),
        'default' => '123',
    ]);

    expect(Attribute::findOrFail($attribute->getKey())->default)->toBe(123);

    // change the value to 123.456

    $data = [
        'id' => (string) $attribute->getRouteKey(),
        'type' => 'attributes',
        'attributes' => [
            'default' => 123.456,
        ],
    ];

    $this
        ->jsonApi()
        ->expects('attributes')
        ->withData($data)
        ->patch('/api/testUrlPrefix/attributes/'.$attribute->getRouteKey())
        ->assertFetchedOne($data);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'id' => $attribute->getRouteKey(),
        'default' => '123.456',
    ]);

    expect(Attribute::findOrFail($attribute->getKey())->default)->toBe(123.456);

    // change the value to []

    $data = [
        'id' => (string) $attribute->getRouteKey(),
        'type' => 'attributes',
        'attributes' => [
            'default' => [],
        ],
    ];

    $this
        ->jsonApi()
        ->expects('attributes')
        ->withData($data)
        ->patch('/api/testUrlPrefix/attributes/'.$attribute->getRouteKey())
        ->assertFetchedOne($data);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'id' => $attribute->getRouteKey(),
        'default' => '[]',
    ]);

    expect(Attribute::findOrFail($attribute->getKey())->default)->toBe([]);

    // change the value to ["a","b",1,2,3.4,true,false,null]

    $data = [
        'id' => (string) $attribute->getRouteKey(),
        'type' => 'attributes',
        'attributes' => [
            'default' => ["a","b",1,2,3.4,true,false,null],
        ],
    ];

    $this
        ->jsonApi()
        ->expects('attributes')
        ->withData($data)
        ->patch('/api/testUrlPrefix/attributes/'.$attribute->getRouteKey())
        ->assertFetchedOne($data);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'id' => $attribute->getRouteKey(),
        'default' => '["a","b",1,2,3.4,true,false,null]',
    ]);

    expect(Attribute::findOrFail($attribute->getKey())->default)->toBe(["a","b",1,2,3.4,true,false,null]);
});

it('updates the attribute\'s nullable field', function () {
    $attribute = Attribute::factory()
        ->for(Entity::factory())
        ->create([
            'nullable' => false,
        ]);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'id' => $attribute->getRouteKey(),
        'nullable' => false,
    ]);

    $data = [
        'id' => (string) $attribute->getRouteKey(),
        'type' => 'attributes',
        'attributes' => [
            'nullable' => true,
        ],
    ];

    $this
        ->jsonApi()
        ->expects('attributes')
        ->withData($data)
        ->patch('/api/testUrlPrefix/attributes/'.$attribute->getRouteKey())
        ->assertFetchedOne($data);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'id' => $attribute->getRouteKey(),
        'nullable' => true,
    ]);
});

// TODO: tests for store & update

// TODO: tests for store & update related

// delete

it('deletes the attribute', function () {
    $attribute = Attribute::factory()
        ->for(Entity::factory())
        ->create();

    $this
        ->jsonApi()
        ->delete('/api/testUrlPrefix/attributes/'.$attribute->getRouteKey())
        ->assertNoContent();

    $this
        ->jsonApi()
        ->get('/api/testUrlPrefix/attributes')
        ->assertFetchedNone();
});

