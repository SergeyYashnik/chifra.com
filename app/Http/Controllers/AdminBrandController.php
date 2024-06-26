<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();

        return view('admin.brand.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        $brand = new Brand();
        $brand->name = $request->name;

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('brand_logos', 'public');
            $brand->logo = $path;
        }

        $brand->save();

        return redirect()->route('admin.brand.index')->with('success', 'Бренд добавлен');
    }
    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brand.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        $brand = Brand::findOrFail($id);
        $brand->name = $request->name;

        if ($request->hasFile('logo')) {
            // Удаление старого изображения
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $path = $request->file('logo')->store('brand_logos', 'public');
            $brand->logo = $path;
        }

        $brand->save();

        return redirect()->route('admin.brand.index')->with('success', 'Бренд обновлен');
    }

    public function destroyLogo($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
            $brand->logo = null;
            $brand->save();
        }

        return redirect()->route('admin.brand.edit', $id)->with('success', 'Логотип удален');
    }

}
