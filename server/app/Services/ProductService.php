<?php

namespace App\Services;

use App\DTOs\Product\StoreDTO;
use App\DTOs\Product\UpdateDTO;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    /**
     * @param StoreDTO $data
     * @return Product
     */
    public function store(StoreDTO $data): Product
    {
        $product = new Product();
        $product->name = $data->name;
        $product->slug = $data->slug;
        $product->description = $data->description;
        $product->price = $data->price;
        $product->stock = $data->stock;
        $product->image = $data->image;
        $product->save();
        return $product;
    }

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function index(int $perPage = 10): LengthAwarePaginator
    {
        return Product::latest()->paginate($perPage);
    }

    /**
     * @param int $id
     * @return Product
     */
    public function show(int $id): Product
    {
        return Product::find($id);
    }

    /**
     * @param int $id
     * @param UpdateDTO $data
     * @return Product
     */
    public function update(int $id, UpdateDTO $data): Product
    {
        $product = Product::find($id);
        $product->name = $data->name;
        $product->slug = $data->slug;
        $product->description = $data->description;
        $product->price = $data->price;
        $product->stock = $data->stock;
        if (isset($data->image) && $data->image != null) {
            $product->image = $data->image;
        }
        $product->save();
        return $product;
    }

    /**
     * @param Product $product
     * @return void
     */
    public function destroy(Product $product): void
    {
        $product->delete();
    }

    /**
     * @param string $filePath
     * @return void
     */
    public function removeFile(string $filePath): void
    {
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }
}
