<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Variant;
use App\Models\VariantOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('admin.products.listProduct', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $variants = Variant::with('options')->get();

        return view('admin.products.addProduct', compact('categories', 'variants'));
    }

    public function store(Request $request)
    {
        // Validate dữ liệu cơ bản
        $validatedData = $request->validate([
            // 'name' => 'required|string|max:255',
            // 'description' => 'nullable|string',
            // 'category_id' => 'required|exists:categories,id',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Validate dữ liệu variant
        $variantData = $request->validate([
            'variant.price' => 'required|numeric|min:0',
            'variant.quantity' => 'required|integer|min:0',
        ]);

        // Thêm validation cho các variant options
        $variants = Variant::all();
        foreach ($variants as $variant) {
            $request->validate([
                'variant.'.strtolower($variant->name) => 'required|exists:variant_options,id',
            ]);
        }

        // Lưu sản phẩm chính
        $imagePath = $request->file('image')->store('products', 'public');
        $product = Product::create([
            'name' => $request['name'],
            'description' => $request['description'],
            'category_id' => $request['category_id'],
            'image' => $imagePath,
            'price' => $variantData['variant']['price'],
            'quantity' => $variantData['variant']['quantity'],
        ]);

        // Lưu biến thể
        $variant = $product->variants()->create([
            'price' => $variantData['variant']['price'],
            'quantity' => $variantData['variant']['quantity'],
            'combination_code' => $this->generateSkuFromVariantData($product, $request->variant),
        ]);

        // Gán các tùy chọn biến thể
        $variantOptions = [];
        foreach ($variants as $variantModel) {
            $key = strtolower($variantModel->name);
            if (isset($request->variant[$key])) {
                $variantOptions[] = $request->variant[$key];
            }
        }

        if (!empty($variantOptions)) {
            $variant->options()->attach($variantOptions);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product added successfully!');
    }

    public function show(string $id)
{
    // Lấy sản phẩm cùng với các biến thể và tùy chọn biến thể
    $product = Product::with(['variants', 'variants.options'])->findOrFail($id);

    // Xử lý để tạo chuỗi combination_code cho từng biến thể
    foreach ($product->variants as $variant) {
        $options = $variant->options->map(function ($option) {
            return strtoupper($option->variant->name) . '(' . $option->value . ')';
        })->toArray();

        $variant->formatted_combination_code = implode('-', $options);
    }

    // Trả về view hiển thị chi tiết sản phẩm
    return view('admin.products.showProduct', compact('product'));
}

public function update(Request $request, string $id)
{
    $products = Product::findOrFail($id);

    // Validate dữ liệu cơ bản
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'quantity' => 'nullable|integer|min:0',
        'sales' => 'nullable|integer|min:0',
        'description' => 'nullable|string',
        'price' => 'nullable|numeric|min:0',
        'discount_start' => 'nullable|date',
        'discount_end' => 'nullable|date|after_or_equal:discount_start',
        'discount_type' => 'nullable|in:percentage,fixed',
        'discount_value' => 'nullable|numeric|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ]);

    // Xử lý ảnh
    if ($request->hasFile('image')) {
        Storage::disk('public')->delete($products->image);
        $imagePath = $request->file('image')->store('products', 'public');
        $validatedData['image'] = $imagePath;
    }

    // Cập nhật sản phẩm
    $products->update($validatedData);

    // // Validate dữ liệu variant
    // $variantData = $request->validate([
    //     'variant.price' => 'required|numeric|min:0',
    //     'variant.quantity' => 'required|integer|min:0',
    // ]);

    // // Thêm validation cho các variant options
    // $variants = Variant::all();
    // foreach ($variants as $variant) {
    //     $request->validate([
    //         'variant.'.strtolower($variant->name) => 'required|exists:variant_options,id',
    //     ]);
    // }

    // // Xử lý biến thể (giả sử mỗi sản phẩm chỉ có 1 variant trong trường hợp này)
    // $variant = $products->variants()->firstOrNew();
    // $variant->fill([
    //     'price' => $variantData['variant']['price'],
    //     'quantity' => $variantData['variant']['quantity'],
    //     'combination_code' => $this->generateSkuFromVariantData($products, $request->variant),
    // ])->save();

    // // Gán các tùy chọn biến thể
    // $variantOptions = [];
    // foreach ($variants as $variantModel) {
    //     $key = strtolower($variantModel->name);
    //     if (isset($request->variant[$key])) {
    //         $variantOptions[] = $request->variant[$key];
    //     }
    // }

    // if (!empty($variantOptions)) {
    //     $variant->options()->sync($variantOptions);
    // }

    return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
}


    private function generateSkuFromVariantData(Product $product, array $variantData): string
{
    // Mảng để lưu các phần tử của combination_code
    $skuParts = [];

    // Lấy tất cả các biến thể
    $variants = Variant::all();
    foreach ($variants as $variant) {
        $key = strtolower($variant->name);
        if (isset($variantData[$key])) {
            // Tìm giá trị của tùy chọn biến thể
            $option = VariantOption::find($variantData[$key]);
            if ($option) {
                // Thêm tên biến thể và giá trị vào combination_code
                $skuParts[] = strtoupper($variant->name) . '(' . $option->value . ')';
            }
        }
    }

    // Kết hợp các phần tử thành chuỗi combination_code
    return implode('-', $skuParts);
}

    public function edit(string $id)
    {
        $products = Product::with(['variants', 'variants.options'])->findOrFail($id);
        $categories = Category::all();
        $variants = Variant::with('options')->get();

        return view('admin.products.editProduct', compact('products', 'categories', 'variants'));
    }



    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        Storage::disk('public')->delete($product->image);
        $product->variants()->delete();
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }

    /**
     * Generate SKU from variant data
     */


    /**
     * Update product's total quantity and min price
     */
    private function updateProductSummary(Product $product): void
    {
        if ($product->variants()->exists()) {
            $product->update([
                'quantity' => $product->variants()->sum('quantity'),
                'price' => $product->variants()->min('price')
            ]);
        }
    }
}
