<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use App\Models\Product;

uses(RefreshDatabase::class);

test('Product Store Test', function () {
    Storage::fake('public');
    $image = UploadedFile::fake()->image('image.jpg');
    $productSlug = Str::slug('Product 1');
    $payload = [
        'name' => 'Product 1',
        'slug' => $productSlug,
        'description' => 'Product 1 description',
        'price' => 100,
        'stock' => 10,
        'new_image' => $image,
    ];

    $response = $this->postJson('api/products', $payload);

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

    Storage::disk('public')->assertExists('products/' . $image->hashName());
    expect(Product::where('slug', $productSlug)->exists())->toBeTrue();
});

// Validations

test('Product Store Required Validations', function () {
    $payload = [];

    $response = $this->postJson('api/products', $payload);
    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name',
            'slug',
            'stock',
            'price',
            'new_image',
        ]);
});


test('Product Store ', function () {
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

    $response = $this->postJson('api/products', $payload);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['slug']);
});


test('Product Store Price-Stock Integer Validation', function () {
    $payload = [
        'price' => 'asda',
        'stock' => 'dqwd',
    ];

    $response = $this->postJson('api/products', $payload);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['stock', 'price']);
});


test('Product Store Price-Stock Unisigned Integer Validation', function () {
    $payload = [
        'price' => -100,
        'stock' => -90,
    ];

    $response = $this->postJson('api/products', $payload);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['stock', 'price']);
});

test('Product Store Image File Type Validation', function () {
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


test('Product Store Image Mime Type Validation', function () {
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


test('Product Store Image Size Validation', function () {
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
