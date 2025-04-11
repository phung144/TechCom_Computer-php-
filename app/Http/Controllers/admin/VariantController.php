<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Variant;

class VariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $variants = Variant::all();
        return view('admin.variants.listVariants', compact('variants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.variants.addVariant');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Variant::create($request->only('name'));

        return redirect()->route('admin.variants.index')->with('success', 'Variant created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $variant = Variant::findOrFail($id);
        $variantOptions = $variant->options;
        return view('admin.variants.showVariant', compact('variant', 'variantOptions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $variant = Variant::findOrFail($id);
        return view('admin.variants.editVariant', compact('variant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $variant = Variant::findOrFail($id);
        $variant->update($request->only('name'));

        return redirect()->route('admin.variants.index')->with('success', 'Variant updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $variant = Variant::findOrFail($id);
        $variant->delete();

        return redirect()->route('admin.variants.index')->with('success', 'Variant deleted successfully.');
    }
}
