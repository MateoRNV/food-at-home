<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
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

        return response()->json(['Product created successfully', $product, 201]);
    }

    public function uploadPhoto(Request $request){
        $request->validate(['photo_file' => 'image|mimes:jpeg,png,jpg']);

        $path = Storage::putFile('public/products', $request->file('photo_file'));

        return response()->json(['location' => '/storage/products/'.$request->file('photo_file')->hashName(), 'filename' => $request->file('photo_file')->hashName()], 201);
    }

    public function update(UpdateProductRequest $request, $id){

        $validated = $request->validated();
        $product = Product::findOrFail($id);

        $product->fill($validated);

        $product->save();

        return response()->json(['Product with ID '. $request->id . ' updated successfully', $product], 200);

    }

    public function updatePhoto(Request $request, $id){
        $request->validate(['photo_file' => 'image|mimes:jpeg,png,jpg']);
        
        $product = Product::findOrFail($id);

        // Delete previous photo
        Storage::disk('public')->delete('products/'.$product->photo_url);

        $path = Storage::putFile('public/products', $request->file('photo_file'));

        return response()->json(['location' => '/storage/products/'.$request->file('photo_file')->hashName(), 'filename' => $request->file('photo_file')->hashName()], 201);
    }

    public function delete($id){
        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json(['Product with ID ' . $product->id . ' deleted successfully', $product], 200);
    }
}
