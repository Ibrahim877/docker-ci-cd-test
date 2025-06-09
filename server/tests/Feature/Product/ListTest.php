<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Product List Paginate', function () {
    Product::factory()->count(25)->create();
    $response = $this->getJson('/api/products?page=1')
        ->assertStatus(200)
        ->assertJsonStructure([
            'products' => [
                'current_page',
                'data'
            ]
        ]);

    $this->assertCount(10, $response['products']['data']);
    $this->assertEquals(1, $response['products']['current_page']);
});

test('Product List Paginate - Second page', function () {
    Product::factory()->count(25)->create();
    $response = $this->getJson('/api/products?page=2')
        ->assertStatus(200)
        ->assertJsonStructure([
            'products' => [
                'current_page',
                'data'
            ]
        ]);

    $this->assertCount(10, $response['products']['data']);
    $this->assertEquals(2, $response['products']['current_page']);
});
