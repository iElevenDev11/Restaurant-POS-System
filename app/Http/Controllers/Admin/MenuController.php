<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MenuController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::with('category')->get();
        return view('admin.menu.index', compact('menuItems'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.menu.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
        ]);

        $data = $request->except('image');
        $data['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();

            // Store the original image
            $path = $image->storeAs('menu-items', $filename, 'public');

            // Create a thumbnail
            $thumbnail = Image::make($image)->resize(300, 200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $thumbnailPath = 'menu-items/thumbnails/' . $filename;
            Storage::disk('public')->put($thumbnailPath, (string) $thumbnail->encode());

            $data['image'] = $path;
        }

        MenuItem::create($data);

        return redirect()->route('menu.index')->with('success', 'Menu item created successfully');
    }

    public function show(MenuItem $menu)
    {
        return view('admin.menu.show', compact('menu'));
    }

    public function edit(MenuItem $menu)
    {
        $categories = Category::all();
        return view('admin.menu.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, MenuItem $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
        ]);

        $data = $request->except('image');
        $data['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);

                // Also delete thumbnail if it exists
                $thumbnailPath = str_replace('menu-items/', 'menu-items/thumbnails/', $menu->image);
                if (Storage::disk('public')->exists($thumbnailPath)) {
                    Storage::disk('public')->delete($thumbnailPath);
                }
            }

            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();

            // Store the original image
            $path = $image->storeAs('menu-items', $filename, 'public');

            // Create a thumbnail
            $thumbnail = Image::make($image)->resize(300, 200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $thumbnailPath = 'menu-items/thumbnails/' . $filename;
            Storage::disk('public')->put($thumbnailPath, (string) $thumbnail->encode());

            $data['image'] = $path;
        }

        $menu->update($data);

        return redirect()->route('menu.index')->with('success', 'Menu item updated successfully');
    }

    public function destroy(MenuItem $menu)
    {
        // Check if the menu item is used in any orders
        $hasOrders = $menu->orderItems()->exists();

        if ($hasOrders) {
            return redirect()->route('menu.index')->with('error', 'Cannot delete menu item as it is used in orders');
        }

        // Delete image if it exists
        if ($menu->image && Storage::disk('public')->exists($menu->image)) {
            Storage::disk('public')->delete($menu->image);

            // Also delete thumbnail if it exists
            $thumbnailPath = str_replace('menu-items/', 'menu-items/thumbnails/', $menu->image);
            if (Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
        }

        $menu->delete();

        return redirect()->route('menu.index')->with('success', 'Menu item deleted successfully');
    }
}
