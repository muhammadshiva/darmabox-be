<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::orderByDesc('id')->paginate(15);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sku' => ['nullable', 'string', 'max:80', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:200'],
            'type' => ['required', 'in:ready,custom'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'stock' => ['nullable', 'integer', 'min:0'],
        ]);
        $data['created_at'] = now();
        $product = Product::create($data);
        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'sku' => ['nullable', 'string', 'max:80', 'unique:products,sku,' . $product->id],
            'name' => ['sometimes', 'string', 'max:200'],
            'type' => ['sometimes', 'in:ready,custom'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'stock' => ['nullable', 'integer', 'min:0'],
        ]);
        $product->update($data);
        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }
}
