<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function list(): AnonymousResourceCollection
    {
        return ProductResource::collection(app('product')->listAll());
    }

    public function show(Request $request): ProductResource
    {
        return new ProductResource(app('product')->show($request->code));
    }

    public function update(Request $request, ProductRequest $productRequest): ProductResource
    {
        return new ProductResource(app('product')
            ->update($productRequest, $request->code));
    }

    public function delete(Request $request): ProductResource
    {
        return new ProductResource(app('product')->delete($request->code));
    }
}
