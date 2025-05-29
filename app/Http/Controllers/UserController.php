<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Hiển thị danh sách người dùng (Admin)
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    // Hiển thị form tạo người dùng (Admin)
    public function create()
    {
        return view('admin.users.create');
    }

    // Lưu người dùng mới (Admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,admin',
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']); // Mã hóa mật khẩu
        $user->role = $validated['role'];
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Người dùng mới đã được thêm!');
    }

    // Hiển thị chi tiết người dùng (Admin)
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'Không tìm thấy người dùng!');
        }
        return view('admin.users.show', compact('user'));
    }

    // Hiển thị form chỉnh sửa người dùng (Admin)
    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'Không tìm thấy người dùng!');
        }
        return view('admin.users.edit', compact('user'));
    }

    // Cập nhật người dùng (Admin)
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'Không tìm thấy người dùng!');
        }

        // Nếu chỉ gửi lên trường role (từ trang show)
        if ($request->has('role') && $request->keys() === ['_token', '_method', 'role']) {
            $validated = $request->validate([
                'role' => 'required|in:user,admin',
            ]);
            $user->role = $validated['role'];
            $user->save();

            return redirect()->route('admin.users.show', $user->id)->with('success', 'Cập nhật vai trò thành công!');
        }

        // Nếu gửi đủ các trường (từ trang edit)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:user,admin',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        $user->role = $validated['role'];
        $user->save();

        return redirect()->route('admin.users.show', $user->id)->with('success', 'Cập nhật người dùng thành công!');
    }

    // Xóa người dùng (Admin)
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'Không tìm thấy người dùng!');
        }
        // Không cho phép xóa chính mình
        if (auth()->id() == $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể tự xóa chính mình!');
        }
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Đã xóa người dùng thành công');
    }

    // ========================
    // Các chức năng dành cho Client
    // ========================

    // Hiển thị chi tiết người dùng (Client)
    public function showClient($id)
    {
        $user = User::findOrFail($id);
        return view('client.users.show', compact('user'));
    }

    // Hiển thị form chỉnh sửa người dùng (Client)
    public function editClient($id)
    {
        $user = User::findOrFail($id);
        return view('client.users.edit', compact('user'));
    }

    // Cập nhật người dùng (Client)
    public function updateClient(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.show', $user->id)->with('success', 'Cập nhật thành công!');
    }
}
