<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->hasFile('image')) {
            $user->image = $request->file('image')->store('avatars', 'public');
        }
        $user->save();
        return back()->with('success', 'Cập nhật thông tin thành công!');
    }
} 