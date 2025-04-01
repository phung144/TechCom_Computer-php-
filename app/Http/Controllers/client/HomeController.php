<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\Product;
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

        return view('client.blocks.main', compact('products', 'discountedProducts'));
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
