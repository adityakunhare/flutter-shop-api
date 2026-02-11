<?php

use App\Models\Brand;
use App\Models\Category;

it('has paginated products list', function () {
    /** @var \Test\TestCase $this */
    $response = $this->get('/api/V1/products');

    $response->assertStatus(200);

    // Json structure assertsions
    expect($response->json())->toHaveKey('data');
    expect($response->json())->toHaveKey('links');
    expect($response->json())->toHaveKey('meta');
});

it('has products with category filter', function () {
    /** @var \Test\TestCase $this */

    $category = Category::first();

    $response = $this->get('/api/V1/products?category=' . $category->id);

    $response->assertStatus(200);
    expect($response->json('data.0.type'))->toBe('product');
    expect($response->json('data.1.attributes.category'))->toBe($category->name);
    expect($response->json('data.2.attributes.category'))->toBe($category->name);
    expect($response->json('data.3.attributes.category'))->toBe($category->name);
});



it('has products with brands filter', function () {
    /** @var \Test\TestCase $this */

    $brand = Brand::first();

    $response = $this->get('/api/V1/products?brand=' . $brand->id);

    $response->assertStatus(200);
    expect($response->json('data.0.type'))->toBe('product');
    expect($response->json('data.1.attributes.brand'))->toBe($brand->name);
    expect($response->json('data.2.attributes.brand'))->toBe($brand->name);
    expect($response->json('data.3.attributes.brand'))->toBe($brand->name);
});
