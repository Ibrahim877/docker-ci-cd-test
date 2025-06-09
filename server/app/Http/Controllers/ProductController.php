<?php

namespace App\Http\Controllers;

use App\DTOs\Product\StoreDTO;
use App\DTOs\Product\UpdateDTO;
use App\Http\Requests\Products\GetRequest;
use App\Http\Requests\Products\StoreRequest;
use App\Http\Requests\Products\UpdateRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $service
    )
    {
        parent::__construct();
    }

    /**
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();
            $data['image'] = Storage::disk('public')->put('products', $request->file('new_image'));

            $dto = StoreDTO::fromArray($data);
            $product = $this->service->store($dto);
            $this->success('Məhsul əlavə edildi');
            $this->res->product = $product;
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        return $this->response();
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = $this->service->index();
        return response()->json([
            'products' => $products,
        ]);
    }

    public function show(GetRequest $request): JsonResponse
    {
        $product = $this->service->show($request->id);
        return response()->json([
            'product' => $product,
        ]);
    }

    /**
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('new_image')) {
                $data['image'] = Storage::disk('public')->put('products', $request->file('new_image'));
            }

            $dto = UpdateDTO::fromArray($data);
            $product = $this->service->update($request->id, $dto);
            $this->success('Məhsul update edildi');
            $this->res->product = $product;
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        return $this->response();
    }

    /**
     * @param GetRequest $request
     * @return JsonResponse
     */
    public function destroy(GetRequest $request): JsonResponse
    {
        try {
            $product = Product::find($request->id);
            DB::transaction(function () use ($product) {
                $this->service->destroy($product);
                $this->service->removeFile($product->image);
            });
            $this->success('Məhsul silindi');;
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        return $this->response();
    }
}
