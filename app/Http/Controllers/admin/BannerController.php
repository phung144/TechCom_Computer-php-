<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        return view('admin.banner.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banner.created');
    }

    public function store(Request $request)
    {
        $imagePath = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => $request->title,
            'image' => $imagePath,
            'link' => $request->link,
            'is_active' => $request->is_active,
            'position' => $request->position,
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully!');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banner.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($banner->image);
            $banner->image = $request->file('image')->store('banners', 'public');
        }

        $banner->update([
            'title' => $request->title,
            'image' => $banner->image,
            'link' => $request->link,
            'is_active' => $request->is_active,
            'position' => $request->position,
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully!');
    }

    public function destroy(Banner $banner)
    {
        Storage::disk('public')->delete($banner->image);
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully!');
    }

    public function toggleStatus(Banner $banner)
    {
        $banner->update(['is_active' => !$banner->is_active]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner status updated successfully!');
    }
}
