<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Http\Resources\Product as ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    public function create(CreateProductRequest $request){
        $product = new Product;
        $validated = $request->validated();

        $product->fill($validated);
        $product->save();

        return response()->json(['Product created successfully. ' . $product, 201]);
    }

    public function update(){

    }

    public function delete(){}
}
