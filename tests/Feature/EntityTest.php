<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\Metamodel\Models\Relationship;

// fetch

it('fetches empty response where no entities exist', function () {
    $this
        ->jsonApi()
        ->get('/api/testUrlPrefix/entities')
        ->assertFetchedNone();
});

it('fetches one entity', function () {
    $entity = Entity::factory()
        ->create();

    $this
        ->jsonApi()
        ->expects('entities')
        ->get('/api/testUrlPrefix/entities/'.$entity->getRouteKey())
        ->assertFetchedOne($entity);
});

it('fetches many entities', function () {
    $entities = Entity::factory()
        ->count(3)
        ->create();

    $this
        ->jsonApi()
        ->expects('entities')
        ->get('/api/testUrlPrefix/entities')
        ->assertFetchedMany($entities);
});

// fetch & include related

it('fetches many entities with related attributes', function () {
    $entities = Entity::factory()
        ->count(3)
        ->has(Attribute::factory()->count(3))
        ->create();

    $this
        ->jsonApi()
        ->expects('entities')
        ->includePaths('attributes')
        ->get('/api/testUrlPrefix/entities')
        ->assertFetchedMany($entities);
});

it('fetches many entities with related left relationships', function () {
    $leftEntities = Entity::factory()
        ->count(3)
        ->create();

    $rightEntities = Entity::factory()
        ->count(3)
        ->create();

    $leftEntities->each(function ($entity, $index) use ($rightEntities) {
        Relationship::factory()
            ->for($entity, 'leftEntity')
            ->for($rightEntities[$index], 'rightEntity')
            ->create();
    });

    $entities = $leftEntities->merge($rightEntities);

    $this
        ->jsonApi()
        ->expects('entities')
        ->includePaths('leftRelationships')
        ->get('/api/testUrlPrefix/entities')
        ->assertFetchedMany($entities);
});

it('fetches many entities with related right relationships', function () {
    $leftEntities = Entity::factory()
        ->count(3)
        ->create();

    $rightEntities = Entity::factory()
        ->count(3)
        ->create();

    $leftEntities->each(function ($entity, $index) use ($rightEntities) {
        Relationship::factory()
            ->for($entity, 'leftEntity')
            ->for($rightEntities[$index], 'rightEntity')
            ->create();
    });

    $entities = $leftEntities->merge($rightEntities);

    $this
        ->jsonApi()
        ->expects('entities')
        ->includePaths('rightRelationships')
        ->get('/api/testUrlPrefix/entities')
        ->assertFetchedMany($entities);
});

// fetch related

it('fetches attributes related to an entity', function () {
    $entity = Entity::factory()
        ->create();

    $attributes = Attribute::factory()
        ->count(3)
        ->for($entity)
        ->create();

    $this
        ->jsonApi()
        ->expects('attributes')
        ->get('/api/testUrlPrefix/entities/'.$entity->getRouteKey().'/attributes')
        ->assertFetchedMany($attributes);
});

it('fetches left relationships related to an entity', function () {
    $entity = Entity::factory()
        ->create();

    $relationships = Relationship::factory()
        ->count(3)
        ->for($entity, 'leftEntity')
        ->for(Entity::factory()->create(), 'rightEntity')
        ->create();

    $this
        ->jsonApi()
        ->expects('relationships')
        ->get('/api/testUrlPrefix/entities/'.$entity->getRouteKey().'/left-relationships')
        ->assertFetchedMany($relationships);
});

it('fetches right relationships related to an entity', function () {
    $entity = Entity::factory()
        ->create();

    $relationships = Relationship::factory()
        ->count(3)
        ->for(Entity::factory()->create(), 'leftEntity')
        ->for($entity, 'rightEntity')
        ->create();

    $this
        ->jsonApi()
        ->expects('relationships')
        ->get('/api/testUrlPrefix/entities/'.$entity->getRouteKey().'/right-relationships')
        ->assertFetchedMany($relationships);
});

// TODO: tests for store & update

// delete

it('deletes the entity', function () {
    $entity = Entity::factory()
        ->create();

    $this
        ->jsonApi()
        ->delete('/api/testUrlPrefix/entities/'.$entity->getRouteKey())
        ->assertNoContent();

    $this
        ->jsonApi()
        ->get('/api/testUrlPrefix/entities')
        ->assertFetchedNone();
});
