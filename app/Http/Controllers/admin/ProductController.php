<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category; // Import model Category
use App\Models\Product; // Import model Product
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import Storage facade

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy toàn bộ sản phẩm từ bảng products
        $products = Product::all();

        // Truyền danh sách sản phẩm vào view
        return view('admin.products.listProduct', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Lấy danh sách danh mục
        $categories = Category::all();
        $variants = Variant::all();

        // Trả về view thêm sản phẩm và truyền danh sách danh mục
        return view('admin.products.addProduct', compact('categories', 'variants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_start' => 'nullable|date',
            'discount_end' => 'nullable|date|after_or_equal:discount_start',
            'sales' => 'nullable|integer|min:0',
            'variant_combinations' => 'nullable|array',
            'variant_combinations.*.price' => 'required_with:variant_combinations|numeric|min:0',
            'variant_combinations.*.quantity' => 'required_with:variant_combinations|integer|min:0',
        ]);

        // Lưu ảnh nếu có
        $imagePath = $request->file('image')->store('products', 'public');

        // Lưu sản phẩm vào database
        $product = Product::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'category_id' => $validatedData['category_id'],
            'price' => $validatedData['price'],
            'quantity' => $validatedData['quantity'],
            'image' => $imagePath,
            'discount_type' => $validatedData['discount_type'],
            'discount_value' => $validatedData['discount_value'],
            'discount_start' => $validatedData['discount_start'],
            'discount_end' => $validatedData['discount_end'],
            'sales' => $validatedData['sales'] ?? 0,
        ]);

        // Lưu thông tin biến thể vào bảng product_stocks
        if ($request->has('variant_combinations')) {
            foreach ($request->variant_combinations as $combination => $data) {
                $product->stocks()->create([
                    'product_id' => $product->id, // ID sản phẩm
                    'variant' => $combination, // Tên biến thể
                    'price' => $data['price'], // Giá
                    'quantity' => $data['quantity'], // Số lượng
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product added successfully!');
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
        // Tìm sản phẩm theo ID
        $products = Product::findOrFail($id);

        // Lấy danh sách danh mục
        $categories = Category::all();

        // Trả về view chỉnh sửa sản phẩm và truyền danh sách danh mục
        return view('admin.products.editProduct', compact('products', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_start' => 'nullable|date',
            'discount_end' => 'nullable|date|after_or_equal:discount_start',
            'sales' => 'nullable|integer|min:0',
            'variant' => 'nullable|array',
        ]);

        // Tìm sản phẩm
        $product = Product::findOrFail($id);

        // Nếu có ảnh mới, xử lý upload
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            Storage::disk('public')->delete($product->image);

            // Upload ảnh mới
            $imagePath = $request->file('image')->store('products', 'public');
        } else {
            $imagePath = $product->image; // Giữ nguyên ảnh cũ nếu không có ảnh mới
        }

        // Cập nhật dữ liệu sản phẩm
        $product->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'category_id' => $validatedData['category_id'],
            'price' => $validatedData['price'],
            'quantity' => $validatedData['quantity'],
            'image' => $imagePath,
            'discount_type' => $validatedData['discount_type'],
            'discount_value' => $validatedData['discount_value'],
            'discount_start' => $validatedData['discount_start'],
            'discount_end' => $validatedData['discount_end'],
            'sales' => $validatedData['sales'] ?? $product->sales,
        ]);

        // Cập nhật variant nếu có
        if (!empty($validatedData['variant'])) {
            $variants = [];
            foreach ($validatedData['variant'] as $variantId) {
                $variant = Variant::find($variantId);
                if ($variant) {
                    $variants[] = [
                        'id' => $variant->id,
                        'name' => $variant->name,
                        'value' => $variant->value,
                    ];
                }
            }
            $product->variant = json_encode($variants);
            $product->save();
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Tìm sản phẩm theo ID
        $product = Product::findOrFail($id);

        // Xóa ảnh của sản phẩm nếu tồn tại
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Xóa sản phẩm khỏi cơ sở dữ liệu
        $product->delete();

        // Chuyển hướng về danh sách sản phẩm với thông báo thành công
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }

    /**
     * Get product stock price based on variant.
     */
    public function getProductStockPrice(Request $request)
    {
        $variant = $request->query('variant');
        $productId = $request->query('product_id');

        $stock = \App\Models\ProductStock::where('product_id', $productId)
            ->where('variant', $variant)
            ->first();

        return response()->json([
            'price' => $stock ? $stock->price : null,
        ]);
    }
}
