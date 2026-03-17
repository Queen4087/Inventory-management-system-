<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index()
    {
        return StockMovement::with('product')->orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'reference_id' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated) {
            $movement = StockMovement::create($validated);

            $product = Product::find($validated['product_id']);
            if ($validated['type'] === 'in') {
                $product->increment('quantity', $validated['quantity']);
            } else {
                $product->decrement('quantity', $validated['quantity']);
            }

            return response()->json($movement->load('product'), 201);
        });
    }
}
