<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('category')->orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'required|string|unique:products,sku|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'minimum_stock_level' => 'required|integer|min:0',
            'image' => 'nullable|string',
        ]);

        $product = Product::create($validated);
        return response()->json($product->load('category'), 201);
    }

    public function show(Product $product)
    {
        return $product->load('category');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'required|string|unique:products,sku,' . $product->id . '|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'minimum_stock_level' => 'required|integer|min:0',
            'image' => 'nullable|string',
        ]);

        $product->update($validated);
        return response()->json($product->load('category'));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function lowStock()
    {
        $products = Product::whereColumn('quantity', '<=', 'minimum_stock_level')
                           ->with('category')
                           ->get();
        return response()->json($products);
    }
}
