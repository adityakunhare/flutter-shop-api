<?php

it('has list of brands', function () {
    /** @var \Tests\TestCase $this */

    $this->get('/api/V1/brands')
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
