<?php

namespace App\Http\Controllers\shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // Lấy các sản phẩm với thông tin về variants
        //
        $products = Product::with('variants')->paginate(12);

        // Xử lý giá cho từng sản phẩm
        foreach ($products as $product) {
            $this->prepareProductPrice($product);
        }

        // Lấy tất cả các danh mục
        $categories = Category::all();

        // Thêm 4 sản phẩm đang sale để đổ vào sidebar
        $onSaleProducts = Product::where('discount_value', '>', 0)
            ->orderByDesc('discount_value')
            ->take(5)
            ->get();

        $topSalesProducts = Product::orderByDesc('sales')->take(5)->get();

        // Xử lý giá cho các sản phẩm đang sale
        foreach ($onSaleProducts as $product) {
            $originalPrice = $product->price;

            if ($product->discount_type === 'percentage') {
                $product->final_price = $originalPrice - ($originalPrice * $product->discount_value / 100);
            } else {
                $product->final_price = $originalPrice - $product->discount_value;
            }
        }

        // Xử lý giá cho các sản phẩm top bán chạy
        foreach ($topSalesProducts as $product) {
            $originalPrice = $product->price;

            if ($product->discount_type === 'percentage') {
                $product->final_price = $originalPrice - ($originalPrice * $product->discount_value / 100);
            } else {
                $product->final_price = $originalPrice - $product->discount_value;
            }
        }

        // Trả về view với các biến cần thiết
        return view('shop.blocks.main', compact('products', 'categories', 'onSaleProducts', 'topSalesProducts'));
    }





public function search(Request $request)
{
    $query = $request->input('query');
//
    $products = Product::with('variants')
    //
        ->where('name', 'like', "%$query%")
        ->paginate(12);
//
    foreach ($products as $product) {
        $this->prepareProductPrice($product);
    }

    $categories = Category::all();

    return view('shop.blocks.main', compact('products', 'categories'));
}
//
//
//

    public function getCategories()
{
    $categories = Category::all();

    return view('shop.blocks.sidebar', compact('categories'));
}

public function getProductsByCategory($id)
{
    $products = Product::with('variants')
        ->where('category_id', $id)
        ->paginate(12);

    foreach ($products as $product) {
        $this->prepareProductPrice($product);
    }

    $categories = Category::all();

    return view('shop.blocks.main', compact('products', 'categories'));
}

private function prepareProductPrice($product)
{
    $cheapestVariant = $product->getCheapestVariant();

    if ($cheapestVariant) {
        $product->display_price = $cheapestVariant->price;
        $product->cheapest_variant = $cheapestVariant;

        if ($product->discount_type === 'percentage') {
            $product->final_price = $cheapestVariant->price - ($cheapestVariant->price * ($product->discount_value / 100));
        } else {
            $product->final_price = $cheapestVariant->price - $product->discount_value;
        }
    } else {
        $product->display_price = $product->price;

        if ($product->discount_type === 'percentage') {
            $product->final_price = $product->price - ($product->price * ($product->discount_value / 100));
        } else {
            $product->final_price = $product->price - $product->discount_value;
        }
    }
}



}
