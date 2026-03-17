<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        return Sale::with(['user', 'items.product'])->orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $sale = Sale::create([
                'user_id' => $request->user()->id,
                'total_amount' => 0,
                'status' => 'completed',
            ]);

            $totalAmount = 0;

            foreach ($validated['items'] as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                
                if ($product->quantity < $itemData['quantity']) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'items' => "Insufficient stock for product: {$product->name}",
                    ]);
                }

                $subtotal = $product->selling_price * $itemData['quantity'];
                $totalAmount += $subtotal;

                $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $product->selling_price,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('quantity', $itemData['quantity']);

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $itemData['quantity'],
                    'reference_id' => 'SALE-' . $sale->id,
                    'notes' => 'Sold automatically via Sales Module'
                ]);
            }

            $sale->update(['total_amount' => $totalAmount]);

            return response()->json($sale->load('items.product'), 201);
        });
    }
    
    public function show(Sale $sale)
    {
        return $sale->load(['user', 'items.product']);
    }
}
