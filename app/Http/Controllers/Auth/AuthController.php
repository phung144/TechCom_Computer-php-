<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showRegisterForm()
    {
        return view('client.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profile_images', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'image' => $imagePath,
            'role' => 'user',
        ]);

        Auth::login($user);

        // Quay lại trang trước đó hoặc trang chủ nếu không có
        return redirect()->intended(route('client-home'))->with('success', 'Đăng ký thành công!');
    }

    public function showLoginForm(Request $request)
{
    // Lưu URL trang trước đó (trang chi tiết sản phẩm)
    if (!session()->has('url.intended')) {
        session(['url.intended' => url()->previous()]);
    }

    return view('client.auth.login');
}

public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        // Redirect về trang intended (trang chi tiết sản phẩm) hoặc trang chủ
        return redirect()->intended(route('client-home'))->with('success', 'Đăng nhập thành công!');
    }

    return back()->withErrors([
        'email' => 'Thông tin đăng nhập không chính xác',
    ])->onlyInput('email');
}

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
