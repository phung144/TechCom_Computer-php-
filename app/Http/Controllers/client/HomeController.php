<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category; // Import the Category model
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::paginate(12);

        foreach ($products as $product) {
            if ($product->discount_type === 'percentage') {
                $product->final_price = $product->price - ($product->price * ($product->discount_value / 100));
            } else {
                $product->final_price = $product->price - $product->discount_value;
            }
        }

        $discountedProducts = Product::where('discount_value', '>', 0)->get();

        foreach ($discountedProducts as $product) {
            if ($product->discount_type === 'percentage') {
                $product->final_price = $product->price - ($product->price * ($product->discount_value / 100));
            } else {
                $product->final_price = $product->price - $product->discount_value;
            }
        }

        $topSalesProducts = Product::orderBy('sales', 'desc')->take(12)->get();

        foreach ($topSalesProducts as $product) {
            if ($product->discount_type === 'percentage') {
                $product->final_price = $product->price - ($product->price * ($product->discount_value / 100));
            } else {
                $product->final_price = $product->price - $product->discount_value;
            }
        }

        $categories = Category::all(); // Fetch all categories

        return view('client.blocks.main', compact('products', 'discountedProducts', 'topSalesProducts', 'categories'));
    }

    public function searchCategory(Request $request)
    {
        $categoryId = $request->input('category_id');

        // Kiểm tra nếu không có category_id
        if (!$categoryId) {
            return redirect()->route('client-home')->with('error', 'Danh mục không hợp lệ.');
        }

        $products = Product::where('category_id', $categoryId)->paginate(12);

        foreach ($products as $product) {
            if ($product->discount_type === 'percentage') {
                $product->final_price = $product->price - ($product->price * ($product->discount_value / 100));
            } else {
                $product->final_price = $product->price - $product->discount_value;
            }
        }

        $discountedProducts = Product::where('category_id', $categoryId)
            ->where('discount_value', '>', 0)
            ->get();

        foreach ($discountedProducts as $product) {
            if ($product->discount_type === 'percentage') {
                $product->final_price = $product->price - ($product->price * ($product->discount_value / 100));
            } else {
                $product->final_price = $product->price - $product->discount_value;
            }
        }

        $topSalesProducts = Product::where('category_id', $categoryId)
            ->orderBy('sales', 'desc')
            ->take(12)
            ->get();

        foreach ($topSalesProducts as $product) {
            if ($product->discount_type === 'percentage') {
                $product->final_price = $product->price - ($product->price * ($product->discount_value / 100));
            } else {
                $product->final_price = $product->price - $product->discount_value;
            }
        }

        $categories = Category::all(); // Fetch all categories

        return view('client.blocks.main', compact('products', 'discountedProducts', 'topSalesProducts', 'categories'));
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
        

        $discountedProducts = Product::where('name', 'like', "%$query%")
            ->where('discount_value', '>', 0)
            ->get();

        foreach ($discountedProducts as $product) {
            if ($product->discount_type === 'percentage') {
                $product->final_price = $product->price - ($product->price * ($product->discount_value / 100));
            } else {
                $product->final_price = $product->price - $product->discount_value;
            }
        }

        $topSalesProducts = Product::where('name', 'like', "%$query%")
            ->orderBy('sales', 'desc')
            ->take(12)
            ->get();

        foreach ($topSalesProducts as $product) {
            if ($product->discount_type === 'percentage') {
                $product->final_price = $product->price - ($product->price * ($product->discount_value / 100));
            } else {
                $product->final_price = $product->price - $product->discount_value;
            }
        }

        $categories = Category::all();

        return view('client.blocks.main', compact('products', 'discountedProducts', 'topSalesProducts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
