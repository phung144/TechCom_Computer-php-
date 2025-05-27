<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('client.profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'old_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|same:new_password_confirmation',
            'new_password_confirmation' => 'nullable|string|min:6',
        ]);

        // Xử lý đổi mật khẩu nếu có nhập đủ các trường
        if ($request->filled('old_password') || $request->filled('new_password') || $request->filled('new_password_confirmation')) {
            // Kiểm tra nhập đủ cả 3 trường
            if (!$request->filled('old_password') || !$request->filled('new_password') || !$request->filled('new_password_confirmation')) {
                return back()->withErrors(['new_password' => 'Vui lòng nhập đầy đủ thông tin đổi mật khẩu.'])->withInput();
            }
            // Kiểm tra mật khẩu cũ
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Mật khẩu cũ không đúng.'])->withInput();
            }
            // Kiểm tra xác nhận mật khẩu mới
            if ($request->new_password !== $request->new_password_confirmation) {
                return back()->withErrors(['new_password' => 'Xác nhận mật khẩu mới không khớp.'])->withInput();
            }
            // Đổi mật khẩu
            $user->password = Hash::make($request->new_password);
        }

        $user->name = $request->name;
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có và không phải ảnh mặc định
            if (!empty($user->image) && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            // Lưu ảnh mới
            $user->image = $request->file('image')->store('avatars', 'public');
        }
        $user->save();
        return back()->with('success', 'Cập nhật thông tin thành công!');
    }
}
