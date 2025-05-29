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
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'email.unique' => 'Email đã được sử dụng.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            // Các thông báo khác nếu muốn
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
        $request->session()->regenerate();

        // Nếu có redirect_to trong URL, ưu tiên dùng nó
        if ($request->has('redirect_to')) {
            return redirect($request->input('redirect_to'))->with('success', 'Đăng nhập thành công!');
        }

        // Ngược lại dùng intended (nếu có)
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
