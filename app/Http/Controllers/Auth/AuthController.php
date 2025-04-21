<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('client.auth.register');
    }

    public function register(Request $request)
{
    // Validate dữ liệu đầu vào (nếu cần)
    // $request->validate([
    //     'name' => 'required|string|max:255',
    //     'email' => 'required|string|email|max:255|unique:users',
    //     'password' => 'required|string|min:6|confirmed',
    //     'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    // ]);

    // Xử lý upload ảnh
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('profile_images', 'public');
    }

    // Tạo user mới với role mặc định là 'user'
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'image' => $imagePath,
        'role' => 'user', // Thêm trường role với giá trị mặc định
    ]);

    return redirect()->route('login')->with('success', 'Đăng ký thành công!');
}

    public function showLoginForm()
    {
        return view('client.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('client-home')->with('success', 'Login successful!');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
