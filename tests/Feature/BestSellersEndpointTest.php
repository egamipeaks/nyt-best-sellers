<?php

beforeEach(function () {
    disableNytTestMode();
    fakeBestSellersData();
});

it('returns best sellers data for an authenticated user', function () {
    $this->actingAs(getTestUser(), 'sanctum')
        ->getJson(getEndpointUrl(['author' => 'John Doe']))
        ->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'results',
        ]);
});

it('passes validation with a valid ISBN', function () {
    $this->actingAs(getTestUser(), 'sanctum')
        ->getJson(getEndpointUrl(['isbn[]' => '1234567890']))
        ->assertStatus(200);
});

it('returns best sellers data for multiple query parameters', function () {
    $this->actingAs(getTestUser(), 'sanctum')
        ->getJson(getEndpointUrl([
            'author' => 'John Doe',
            'title' => 'Book Title',
            'isbn[]' => '1234567890',
            'offset' => 20,
        ]))
        ->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'results',
        ]);
});

it('passes validation with 0 offset', function () {
    $this->actingAs(getTestUser(), 'sanctum')
        ->getJson(getEndpointUrl(['offset' => 0]))
        ->assertStatus(200);
});

it('passes validation with with an offset that is a multiple of 20', function () {
    $this->actingAs(getTestUser(), 'sanctum')
        ->getJson(getEndpointUrl(['offset' => 40]))
        ->assertStatus(200);
});

it('returns a validation error for an invalid offset', function () {
    $this->actingAs(getTestUser(), 'sanctum')
        ->getJson(getEndpointUrl(['offset' => 15]))
        ->assertStatus(422);
});

it('returns unauthorized for unauthenticated requests', function () {
    $this->getJson(getEndpointUrl(['author' => 'John Doe']))
        ->assertUnauthorized();
});
