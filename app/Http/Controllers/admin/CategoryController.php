<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import Storage

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách danh mục.
     */
    public function index()
    {
        // Lấy tất cả danh mục từ bảng categories
        $categories = Category::all();

        // Truyền danh mục vào view listCategory
        return view('admin.categories.listCategory', compact('categories'));
    }

    /**
     * Hiển thị form tạo danh mục mới.
     */
    public function create()
    {
        // Trả về view thêm danh mục
        return view('admin.categories.addCategory');
    }

    /**
     * Lưu danh mục mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255', // Tên danh mục là bắt buộc
            'description' => 'nullable|string', // Mô tả có thể để trống
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ảnh phải đúng định dạng
        ]);

        // Tạo một danh mục mới
        $category = new Category();
        $category->name = $request->input('name');
        $category->description = $request->input('description');

        // Xử lý upload ảnh nếu có
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/categories', 'public');
            $category->image = $imagePath;
        }

        // Lưu danh mục vào cơ sở dữ liệu
        $category->save();

        // Chuyển hướng về danh sách danh mục với thông báo thành công
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được tạo thành công.');
    }

    /**
     * Hiển thị form chỉnh sửa danh mục.
     */
    public function edit(string $id)
    {
        // Tìm danh mục theo ID
        $category = Category::findOrFail($id);

        // Trả về view chỉnh sửa danh mục
        return view('admin.categories.editCategory', compact('category'));
    }

    /**
     * Cập nhật danh mục trong cơ sở dữ liệu.
     */
    public function update(Request $request, string $id)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255', // Tên danh mục là bắt buộc
            'description' => 'nullable|string', // Mô tả có thể để trống
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ảnh phải đúng định dạng
        ]);

        // Tìm danh mục theo ID
        $category = Category::findOrFail($id);
        $category->name = $request->input('name');
        $category->description = $request->input('description');

        // Xử lý upload ảnh nếu có
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            // Lưu ảnh mới
            $imagePath = $request->file('image')->store('images/categories', 'public');
            $category->image = $imagePath;
        }

        // Lưu danh mục đã chỉnh sửa vào cơ sở dữ liệu
        $category->save();

        // Chuyển hướng về danh sách danh mục với thông báo thành công
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật thành công.');
    }

    /**
     * Xóa danh mục khỏi cơ sở dữ liệu.
     */
    public function destroy(string $id)
    {
        // Tìm danh mục theo ID và xóa
        $category = Category::findOrFail($id);

        // Xóa ảnh nếu tồn tại
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        // Xóa danh mục
        $category->delete();

        // Chuyển hướng về danh sách danh mục với thông báo thành công
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được xóa thành công.');
    }
}
