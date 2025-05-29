<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
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

        // Lấy tất cả variants
        $variants = Variant::all();

        // Lấy các optionIds hợp lệ (không rỗng)
        $optionIds = [];
        foreach ($variants as $variant) {
            $key = strtolower($variant->name);
            $optionId = $request->variant[$key] ?? null;
            if (!empty($optionId)) {
                // Kiểm tra tồn tại trong DB
                $exists = \App\Models\VariantOption::where('id', $optionId)->where('variant_id', $variant->id)->exists();
                if (!$exists) {
                    return back()->withErrors(['invalid_option' => 'Tùy chọn không hợp lệ cho ' . $variant->name])->withInput();
                }
                $optionIds[$key] = $optionId;
            }
        }

        // Tạo mã SKU chỉ từ các giá trị được nhập
        $combinationCode = $this->generateSkuFromVariantData($product, $optionIds);

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

        // Gán variant_options nếu có
        if (!empty($optionIds)) {
            $variant->options()->attach(array_values($optionIds));
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
    public function update(Request $request, ProductVariant $variant)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        // Cập nhật variant
        $variant->update($validated);

        // Trả về response dạng JSON (cho AJAX)
        return response()->json([
            'success' => true,
            'message' => 'Variant updated successfully',
            'formatted_price' => number_format($variant->price),
            'quantity' => $variant->quantity
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductVariant $variant)
    {
        // Xóa variant
        $variant->delete();

        // Trả về response dạng JSON (cho AJAX)
        return response()->json([
            'success' => true,
            'message' => 'Variant deleted successfully'
        ]);
    }

    private function generateSkuFromVariantData(Product $product, array $optionIds): string
    {
        $skuParts = [];
        $variants = Variant::all();

        foreach ($variants as $variant) {
            $key = strtolower($variant->name);
            if (isset($optionIds[$key])) {
                $option = VariantOption::find($optionIds[$key]);
                if ($option) {
                    $skuParts[] = strtoupper($variant->name) . '(' . $option->value . ')';
                }
            }
        }

        return implode('-', $skuParts); // Ví dụ: CPU(Core i5)-RAM(8GB)-SSD(256GB)
    }

}
