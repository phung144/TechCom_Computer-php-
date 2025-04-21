<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Variant;
use Illuminate\Http\Request;

class VariantOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $variants = Variant::all();
        return view('admin.variantOptions.addOption', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate dữ liệu
    $validatedData = $request->validate([
        'variant_id' => 'required|exists:variants,id',
        'value' => 'required|string|max:255',
    ]);

    // Tạo mới variant option
    \App\Models\VariantOption::create($validatedData);

    // Chuyển hướng với thông báo thành công
    return redirect()->route('admin.variants.index')->with('success', 'Variant option created successfully!');
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
        // Tìm variant option theo ID
        $variantOption = \App\Models\VariantOption::findOrFail($id);

        // Xóa variant option
        $variantOption->delete();

        // Chuyển hướng với thông báo thành công
        return redirect()->route('admin.variants.index')->with('success', 'Variant option deleted successfully!');
    }
}
