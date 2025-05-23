<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $startOfWeek = Carbon::now()->startOfWeek(); // Monday
        $endOfWeek = Carbon::now()->endOfWeek();     // Sunday

        $data = Category::with(['products.orderItems.order' => function ($query) use ($startOfWeek, $endOfWeek) {
            $query->whereIn('status', ['completed', 'rated'])
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
        $totalOrderValue = Order::whereIn('status', ["completed","rated"])->sum('total');
        $sumTotal = Order::whereIn('status', ['completed', 'rated'])->sum('total');
        $orderCountCompleted = Order::whereIn('status', ['completed', 'rated'])->count();

        $orderCountIncomplete = Order::whereNotIn('status', ['completed', 'rated'])->count();
        $totalIncompleteValue = Order::whereNotIn('status', ['completed', 'rated'])->sum('total');

        $title = 'Tổng đơn đang có';
        $titleCompleted = 'Đơn đã hoàn thành';
        $titleIncomplete = 'Đơn chưa hoàn thành';

        // Lấy filter từ request (default: week)
        $filter = $request->input('filter', 'week');

        // Thống kê doanh thu theo filter
        if ($filter == 'day') {
            $from = Carbon::now()->startOfDay();
            $to = Carbon::now()->endOfDay();
            $groupFormat = '%Y-%m-%d';
        } elseif ($filter == 'month') {
            $from = Carbon::now()->startOfMonth();
            $to = Carbon::now()->endOfMonth();
            $groupFormat = '%Y-%m-%d';
        } else { // week
            $from = Carbon::now()->startOfWeek();
            $to = Carbon::now()->endOfWeek();
            $groupFormat = '%Y-%m-%d';
        }

        // Doanh thu theo từng ngày trong khoảng
        $revenues = Order::select(
                DB::raw("DATE_FORMAT(created_at, '$groupFormat') as date"),
                DB::raw("SUM(total) as total")
            )
            ->whereIn('status', ['completed', 'rated'])
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top sản phẩm bán chạy trong khoảng
        $topProducts = Product::select('products.id', 'products.name', DB::raw('SUM(order_details.quantity) as sold'))
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['completed', 'rated'])
            ->whereBetween('orders.created_at', [$from, $to])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('sold')
            ->limit(5)
            ->get();

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
            'totalIncompleteValue',
            'revenues',
            'filter',
            'topProducts'
        ));
    }

    public function getOrderStats()
    {
        $orderCount = Order::count();
        $totalOrderValue = Order::whereIn('status', ["completed","rated"])->sum('total');

        return view('admin.index', compact('orderCount', 'totalOrderValue'));
    }
}
