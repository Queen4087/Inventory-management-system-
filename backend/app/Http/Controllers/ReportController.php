<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function dashboardStats()
    {
        $totalProducts = Product::count();
        $lowStockItems = Product::whereColumn('quantity', '<=', 'minimum_stock_level')->count();
        
        $totalRevenue = Sale::sum('total_amount');
        $totalSalesCount = Sale::count();
        $inventoryValue = Product::selectRaw('SUM(quantity * purchase_price) as value')->value('value') ?? 0;

        return response()->json([
            'total_products' => $totalProducts,
            'low_stock_items' => $lowStockItems,
            'total_revenue' => (float)$totalRevenue,
            'total_sales' => $totalSalesCount,
            'inventory_value' => (float)$inventoryValue,
        ]);
    }

    public function dailySales(Request $request)
    {
        $days = $request->query('days', 7);
        $startDate = Carbon::today()->subDays($days - 1);

        $sales = Sale::where('created_at', '>=', $startDate)
            ->selectRaw("strftime('%Y-%m-%d', created_at) as date, SUM(total_amount) as total, COUNT(id) as count")
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($sales);
    }

    public function monthlyRevenue()
    {
        $results = Sale::selectRaw("strftime('%Y', created_at) as year, strftime('%m', created_at) as month, SUM(total_amount) as total")
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get()
            ->reverse()
            ->values();

        return response()->json($results);
    }

    public function inventoryByCategory()
    {
        $results = \App\Models\Category::with(['products' => function($query) {
                $query->select('category_id', 'quantity', 'purchase_price');
            }])
            ->get()
            ->map(function ($category) {
                $value = $category->products->sum(function ($product) {
                    return $product->quantity * $product->purchase_price;
                });
                return [
                    'name' => $category->name,
                    'value' => (float)$value
                ];
            })
            ->filter(fn($item) => $item['value'] > 0)
            ->values();

        return response()->json($results);
    }

    public function topProducts(Request $request)
    {
        $limit = $request->query('limit', 5);
        $type = $request->query('type', 'revenue'); // revenue or quantity

        if ($type === 'revenue') {
            $products = \App\Models\SaleItem::selectRaw('product_id, SUM(quantity * unit_price) as total_revenue, SUM(quantity) as total_sold')
                ->with('product:id,name,sku')
                ->groupBy('product_id')
                ->orderBy('total_revenue', 'desc')
                ->limit($limit)
                ->get();
        } else {
            $products = \App\Models\SaleItem::selectRaw('product_id, SUM(quantity) as total_sold, SUM(quantity * unit_price) as total_revenue')
                ->with('product:id,name,sku')
                ->groupBy('product_id')
                ->orderBy('total_sold', 'desc')
                ->limit($limit)
                ->get();
        }

        return response()->json($products);
    }

    public function lowStockAlerts()
    {
        $products = Product::whereColumn('quantity', '<=', 'minimum_stock_level')
            ->select('id', 'name', 'sku', 'quantity', 'minimum_stock_level')
            ->limit(10)
            ->get();

        return response()->json($products);
    }
}
