<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Variant;
use App\Models\VariantOption;
use Illuminate\Http\Request;

class VariantOptionProductController extends Controller
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
    public function create($productId)
    {
        $product = Product::findOrFail($productId);
    $variants = Variant::with('options')->get();

    return view('admin.products.addVariantProduct', compact('product', 'variants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

    // Validate giá và số lượng
    $validated = $request->validate([
        'variant.price' => 'required|numeric|min:0',
        'variant.quantity' => 'required|integer|min:0',
    ]);

    // Validate các tùy chọn biến thể
    $variants = Variant::all();
    foreach ($variants as $variant) {
        $request->validate([
            'variant.' . strtolower($variant->name) => 'required|exists:variant_options,id',
        ]);
    }

    // Tạo mã SKU từ các tùy chọn
    $combinationCode = $this->generateSkuFromVariantData($product, $request->variant);

    // Kiểm tra trùng combination_code trong sản phẩm này
    $exists = $product->variants()->where('combination_code', $combinationCode)->exists();
    if ($exists) {
        return back()->withErrors(['duplicate' => 'Biến thể với cấu hình này đã tồn tại.'])->withInput();
    }

    // Lưu biến thể
    $variant = $product->variants()->create([
        'price' => $validated['variant']['price'],
        'quantity' => $validated['variant']['quantity'],
        'combination_code' => $combinationCode,
    ]);

    // Gán variant_options
    $optionIds = [];
    foreach ($variants as $variantModel) {
        $key = strtolower($variantModel->name);
        if (isset($request->variant[$key])) {
            $optionIds[] = $request->variant[$key];
        }
    }

    if (!empty($optionIds)) {
        $variant->options()->attach($optionIds);
    }

    return redirect()->route('admin.products.show', $product->id)->with('success', 'Variant added successfully!');
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

    private function generateSkuFromVariantData(Product $product, array $variantData): string
{
    $skuParts = [];
    $variants = Variant::all();

    foreach ($variants as $variant) {
        $key = strtolower($variant->name);
        if (isset($variantData[$key])) {
            $option = VariantOption::find($variantData[$key]);
            if ($option) {
                $skuParts[] = strtoupper($variant->name) . '(' . $option->value . ')';
            }
        }
    }

    return implode('-', $skuParts); // Ví dụ: CPU(Core i5)-RAM(8GB)-SSD(256GB)
}

}
