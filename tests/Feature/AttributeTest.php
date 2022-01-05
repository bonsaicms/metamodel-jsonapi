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

// TODO: tests for store & update

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

