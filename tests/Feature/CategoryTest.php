<?php

it('has list of categories', function () {
    /** @var \Tests\TestCase $this */
    $this->get('/api/V1/categories')
        ->assertStatus(200)
        ->assertJsonStructure([
            "data" => ['*' => [
                "type",
                "id",
                "attributes" => [
                    "title"
                ]
            ]]
        ]);
});

it('');
