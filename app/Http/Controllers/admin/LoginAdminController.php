<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.logins.formLogin');
    }

    public function postLogin(Request $request){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'admin'])) {
            return redirect()->route('admin.home')->with('success', 'Đăng nhập thành công!');
    }
    return redirect()->back()->with('error', 'Đăng nhập thất bại! Vui lòng kiểm tra lại thông tin đăng nhập.');
    }
}
