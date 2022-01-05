<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Relationship;

// fetch

it('fetches empty response where no relationships exist', function () {
    $this
        ->jsonApi()
        ->get('/api/testUrlPrefix/relationships')
        ->assertFetchedNone();
});

it('fetches one relationship', function () {
    $relationship = Relationship::factory()
        ->for(Entity::factory(), 'leftEntity')
        ->for(Entity::factory(), 'rightEntity')
        ->create();

    $this
        ->jsonApi()
        ->expects('relationships')
        ->get('/api/testUrlPrefix/relationships/'.$relationship->getRouteKey())
        ->assertFetchedOne($relationship);
});

it('fetches many relationships', function () {
    $relationships = Relationship::factory()
        ->count(3)
        ->for(Entity::factory(), 'leftEntity')
        ->for(Entity::factory(), 'rightEntity')
        ->create();

    $this
        ->jsonApi()
        ->expects('relationships')
        ->get('/api/testUrlPrefix/relationships')
        ->assertFetchedMany($relationships);
});

// fetch & include related

it('fetches many relationships with related left entity', function () {
    $leftEntities = Entity::factory()
        ->count(3)
        ->create();

    $rightEntities = Entity::factory()
        ->count(3)
        ->create();

    $relationships = $leftEntities->map(function ($entity, $index) use ($rightEntities) {
        return Relationship::factory()
            ->for($entity, 'leftEntity')
            ->for($rightEntities[$index], 'rightEntity')
            ->create();
    });

    $this
        ->jsonApi()
        ->expects('relationships')
        ->includePaths('leftEntity')
        ->get('/api/testUrlPrefix/relationships')
        ->assertFetchedMany($relationships);
});

it('fetches many relationships with related right entity', function () {
    $leftEntities = Entity::factory()
        ->count(3)
        ->create();

    $rightEntities = Entity::factory()
        ->count(3)
        ->create();

    $relationships = $leftEntities->map(function ($entity, $index) use ($rightEntities) {
        return Relationship::factory()
            ->for($entity, 'leftEntity')
            ->for($rightEntities[$index], 'rightEntity')
            ->create();
    });

    $this
        ->jsonApi()
        ->expects('relationships')
        ->includePaths('rightEntity')
        ->get('/api/testUrlPrefix/relationships')
        ->assertFetchedMany($relationships);
});

// fetch related

it('fetches the left entity related to the relationship', function () {
    $leftEntity = Entity::factory()->create();
    $rightEntity = Entity::factory()->create();

    $relationship = Relationship::factory()
        ->for($leftEntity, 'leftEntity')
        ->for($rightEntity, 'rightEntity')
        ->create();

    $this
        ->jsonApi()
        ->expects('entities')
        ->get('/api/testUrlPrefix/relationships/'.$relationship->getRouteKey().'/left-entity')
        ->assertFetchedOne($leftEntity);
});

it('fetches the right entity related to the relationship', function () {
    $leftEntity = Entity::factory()->create();
    $rightEntity = Entity::factory()->create();

    $relationship = Relationship::factory()
        ->for($leftEntity, 'leftEntity')
        ->for($rightEntity, 'rightEntity')
        ->create();

    $this
        ->jsonApi()
        ->expects('entities')
        ->get('/api/testUrlPrefix/relationships/'.$relationship->getRouteKey().'/right-entity')
        ->assertFetchedOne($rightEntity);
});

// TODO: tests for store & update

// delete

it('deletes the relationship', function () {
    $relationship = Relationship::factory()
        ->for(Entity::factory(), 'leftEntity')
        ->for(Entity::factory(), 'rightEntity')
        ->create();

    $this
        ->jsonApi()
        ->delete('/api/testUrlPrefix/relationships/'.$relationship->getRouteKey())
        ->assertNoContent();

    $this
        ->jsonApi()
        ->get('/api/testUrlPrefix/relationships')
        ->assertFetchedNone();
});


