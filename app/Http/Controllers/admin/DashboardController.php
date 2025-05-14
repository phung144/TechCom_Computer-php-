<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $startOfWeek = Carbon::now()->startOfWeek(); // Monday
        $endOfWeek = Carbon::now()->endOfWeek();     // Sunday

        $data = Category::with(['products.orderItems.order' => function ($query) use ($startOfWeek, $endOfWeek) {
            $query->where('status', 'completed')
                  ->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
        }])->get();

        $result = [];

        foreach ($data as $category) {
            $total = 0;

            foreach ($category->products as $product) {
                foreach ($product->orderItems as $orderItem) {
                    if ($orderItem->order) {
                        $total += $orderItem->quantity * $orderItem->price;
                    }
                }
            }

            $result[] = [
                'category' => $category->name,
                'revenue' => $total,
            ];
        }

        $orderCount = Order::count();
        $totalOrderValue = Order::sum('total');
        $sumTotal = Order::where('status', 'completed')->sum('total');
        $orderCountCompleted = Order::where('status', 'completed')->count();

        $orderCountIncomplete = Order::where('status', '!=', 'completed')->count();
        $totalIncompleteValue = Order::where('status', '!=', 'completed')->sum('total');

        $title = 'Tổng đơn đang có';
        $titleCompleted = 'Đơn đã hoàn thành';
        $titleIncomplete = 'Đơn chưa hoàn thành';

        return view('admin.index', compact(
            'title',
            'result',
            'orderCount',
            'totalOrderValue',
            'sumTotal',
            'titleCompleted',
            'orderCountCompleted',
            'titleIncomplete',
            'orderCountIncomplete',
            'totalIncompleteValue'
        ));
    }

    public function getOrderStats()
    {
        $orderCount = Order::count();
        $totalOrderValue = Order::where('status', 'completed')->sum('total_price');

        return view('admin.index', compact('orderCount', 'totalOrderValue'));
    }
}
