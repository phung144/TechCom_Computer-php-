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
    $products = Product::with('variants')->paginate(12);

    foreach ($products as $product) {
        $this->prepareProductPrice($product);
    }

    $discountedProducts = Product::with('variants')->where('discount_value', '>', 0)->get();

    foreach ($discountedProducts as $product) {
        $this->prepareProductPrice($product);
    }

    $topSalesProducts = Product::with('variants')->orderBy('sales', 'desc')->take(12)->get();

    foreach ($topSalesProducts as $product) {
        $this->prepareProductPrice($product);
    }

    $categories = Category::all();

    return view('client.blocks.main', compact('products', 'discountedProducts', 'topSalesProducts', 'categories'));
}

public function searchCategory(Request $request)
{
    $categoryId = $request->input('category_id');

    if (!$categoryId) {
        return redirect()->route('client-home')->with('error', 'Danh mục không hợp lệ.');
    }

    $products = Product::with('variants')
        ->where('category_id', $categoryId)
        ->paginate(12);

    foreach ($products as $product) {
        $this->prepareProductPrice($product);
    }

    $discountedProducts = Product::with('variants')
        ->where('category_id', $categoryId)
        ->where('discount_value', '>', 0)
        ->get();

    foreach ($discountedProducts as $product) {
        $this->prepareProductPrice($product);
    }

    $topSalesProducts = Product::with('variants')
        ->where('category_id', $categoryId)
        ->orderBy('sales', 'desc')
        ->take(12)
        ->get();

    foreach ($topSalesProducts as $product) {
        $this->prepareProductPrice($product);
    }

    $categories = Category::all();

    return view('client.blocks.main', compact('products', 'discountedProducts', 'topSalesProducts', 'categories'));
}

public function search(Request $request)
{
    $query = $request->input('query');

    $products = Product::with('variants')
        ->where('name', 'like', "%$query%")
        ->paginate(12);

    foreach ($products as $product) {
        $this->prepareProductPrice($product);
    }

    $discountedProducts = Product::with('variants')
        ->where('name', 'like', "%$query%")
        ->where('discount_value', '>', 0)
        ->get();

    foreach ($discountedProducts as $product) {
        $this->prepareProductPrice($product);
    }

    $topSalesProducts = Product::with('variants')
        ->where('name', 'like', "%$query%")
        ->orderBy('sales', 'desc')
        ->take(12)
        ->get();

    foreach ($topSalesProducts as $product) {
        $this->prepareProductPrice($product);
    }

    $categories = Category::all();

    return view('client.blocks.main', compact('products', 'discountedProducts', 'topSalesProducts', 'categories'));
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
