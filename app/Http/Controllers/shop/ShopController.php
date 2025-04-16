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
        $products = Product::paginate(12);

        foreach ($products as $product) {
            if ($product->discount_type === 'percentage') {
                $product->final_price = $product->price - ($product->price * ($product->discount_value / 100));
            } else {
                $product->final_price = $product->price - $product->discount_value;
            }
        }

        $categories = Category::all(); // Fetch all categories
        return view('shop.blocks.main', compact('products', 'categories'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where('name', 'like', "%$query%")->paginate(12);

        foreach ($products as $product) {
            if ($product->discount_type === 'percentage') {
                $product->final_price = $product->price - ($product->price * ($product->discount_value / 100));
            } else {
                $product->final_price = $product->price - $product->discount_value;
            }
        }

        $categories = Category::all();

        return view('shop.blocks.main', compact('products', 'categories'));
    }

    public function getCategories()
{
    $categories = Category::all();

    return view('shop.blocks.sidebar', compact('categories'));
}

public function getProductsByCategory($id)
{
    $products = Product::where('category_id', $id)->paginate(12);
    $categories = Category::all(); // Thêm dòng này

    return view('shop.blocks.main', [
        'products' => $products,
        'categories' => $categories // Truyền biến categories vào view main
    ]);
}

}
