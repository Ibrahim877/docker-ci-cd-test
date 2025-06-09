<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use App\Models\Product;

uses(RefreshDatabase::class);

test('Product Update Test', function () {
    Storage::fake('public');

    $product = Product::factory()->create([
        'name' => 'Product 1',
        'slug' => 'product-1',
        'description' => 'Product 1 description',
        'price' => 100,
        'stock' => 10,
        'image' => 'product_image.jpg'
    ]);

    $image = UploadedFile::fake()->image('image.jpg');
    $productSlug = Str::slug('Product 2');
    $payload = [
        'name' => 'Product 2',
        'slug' => $productSlug,
        'description' => 'Product 2 description',
        'price' => 101,
        'stock' => 11,
        'new_image' => $image,
    ];

    $response = $this->postJson('api/products/' . $product->id, $payload);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'product' => [
                'id',
                'name',
                'slug',
                'description',
                'price',
                'stock',
                'image',
                'created_at',
                'updated_at',
            ],
        ]);


    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => $payload['name'],
        'slug' => $productSlug,
        'description' => $payload['description'],
        'price' => $payload['price'],
        'stock' => $payload['stock'],
        'image' => 'products/' . $image->hashName(),
    ]);

    Storage::disk('public')->assertExists('products/' . $image->hashName());
});


// Validations

test('Product Update Required Validations', function () {

    $response = $this->postJson('api/products/1', []);
    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'id',
            'name',
            'slug',
            'stock',
            'price',
        ]);
});

test('Product Update Id is exists validation', function () {
    Product::factory()->create([
        'name' => 'Product 1',
        'slug' => 'product-1',
        'description' => 'Product 1 description',
        'price' => 100,
        'stock' => 10,
        'image' => 'product-1.jpg',
    ]);

    $payload = [
        'name' => 'Product 1',
    ];

    $response = $this->postJson('api/products/2', $payload);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['id']);

});

test('Product Update Slug is unique', function () {
    Product::factory()->create([
        'name' => 'Product 1',
        'slug' => 'product-1',
        'description' => 'Product 1 description',
        'price' => 100,
        'stock' => 10,
        'image' => 'product-1.jpg',
    ]);

    $product = Product::factory()->create([
        'name' => 'Product 2',
        'slug' => 'product-2',
        'description' => 'Product 2 description',
        'price' => 100,
        'stock' => 10,
        'image' => 'product-2.jpg',
    ]);

    $payload = [
        'name' => 'Product 1',
    ];

    $response = $this->postJson('api/products/' . $product->id, $payload);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['slug']);
});

test('Product Update Price-Stock Integer Validation', function () {
    $payload = [
        'price' => 'asda',
        'stock' => 'dqwd',
    ];

    $response = $this->postJson('api/products', $payload);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['stock', 'price']);
});

test('Product Update Price-Stock Unisigned Integer Validation', function () {
    $payload = [
        'price' => -100,
        'stock' => -90,
    ];

    $response = $this->postJson('api/products', $payload);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['stock', 'price']);
});

test('Product Update Image File Type Validation', function () {
    Storage::fake('public');
    $file = UploadedFile::fake()->create('test.pdf', 100);
    $payload = [
        'name' => 'Product 1',
        'new_image' => $file,
    ];

    $response = $this->postJson('api/products', $payload);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['new_image']);
});

test('Product Update Image Mime Type Validation', function () {
    Storage::fake('public');
    $file = UploadedFile::fake()->create('test.svg', 100);
    $payload = [
        'name' => 'Product 1',
        'new_image' => $file,
    ];

    $response = $this->postJson('api/products', $payload);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['new_image']);
});

test('Product Update Image Size Validation', function () {
    Storage::fake('public');
    $file = UploadedFile::fake()->create('test.png', 10241);
    $payload = [
        'name' => 'Product 1',
        'new_image' => $file,
    ];

    $response = $this->postJson('api/products', $payload);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['new_image']);
});
