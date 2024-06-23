<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminCatalogController extends Controller
{
    public function index()
    {
        $catalogs = Catalog::where('id_catalog', null)->get();
        return view('admin.catalog.index', compact('catalogs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('catalogs')->where(function ($query) use ($request) {
                        return $query->where('id_catalog', $request->id_catalog);
                })
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        $catalog = new Catalog();
        $catalog->name = $request->name;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('catalog_images', 'public');
            $catalog->image = $path;
        }
        $catalog->lvl = 1;
        $catalog->id_catalog = $request->id_catalog; // Установка родительской категории

        $catalog->save();

        return redirect()->route('admin.catalog.index')->with('success', 'Категория добавлена');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        $catalog = Catalog::findOrFail($id);

        $request->validate([
            'name' => Rule::unique('catalogs')->where(function ($query) use ($request, $catalog) {
                return $query->where('id_catalog', $catalog->id_catalog);
            })->ignore($catalog->id),
        ]);

        $catalog->name = $request->name;

        if ($request->hasFile('image')) {
            // Удаление старого изображения
            if ($catalog->image) {
                Storage::disk('public')->delete($catalog->image);
            }
            $path = $request->file('image')->store('catalog_images', 'public');
            $catalog->image = $path;
        }

        $catalog->save();

        return redirect()->route('admin.catalog.edit', ['id' => $id])->with('success', 'Категория обновлена');
    }

    public function removeImage($id)
    {
        $catalog = Catalog::findOrFail($id);
        if ($catalog->image) {
            Storage::disk('public')->delete($catalog->image);
            $catalog->image = null;
            $catalog->save();
        }

        return redirect()->route('admin.catalog.edit', ['id' => $id])->with('success', 'Изображение удалено');
    }

    public function addSubcatalog(Request $request, $id)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('catalogs')->where(function ($query) use ($id, $request) {
                    return $query->where('id_catalog', $id);
                })
            ],
            'lvl' => 'required|integer|in:2,3',
        ]);

        $subcatalog = new Catalog();
        $subcatalog->name = $request->name;
        $subcatalog->id_catalog = $id;
        $subcatalog->lvl = $request->lvl;
        $subcatalog->save();

        return redirect()->route('admin.catalog.edit', ['id' => $id])->with('success', 'Подкатегория добавлена');
    }

    public function addFilter(Request $request, $id)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('filters')->where(function ($query) use ($id) {
                    return $query->where('id_catalog', $id);
                })
            ],
            'is_custom_input' => 'nullable|string|in:on',
            'required_to_fill_out' => 'nullable|string|in:on',
        ]);

        $filter = new Filter();
        $filter->name = $request->name;
        $filter->id_catalog = $id;
        $filter->lvl = 1;
        $filter->is_custom_input = $request->has('is_custom_input');
        $filter->required_to_fill_out = $request->has('required_to_fill_out');
        $filter->save();

        return redirect()->route('admin.catalog.edit', ['id' => $id])->with('success', 'Фильтр добавлен');
    }

    public function edit($id)
    {
        $catalog = Catalog::findOrFail($id);
        $subcatalogs = Catalog::where('id_catalog', $id)->get();
        $filters = Filter::where('id_catalog', $id)->get();
        return view('admin.catalog.edit', compact('catalog', 'subcatalogs', 'filters'));
    }

    public function destroy($id)
    {
        $catalog = Catalog::findOrFail($id);
        $catalog->image = null;
        if ($catalog->image) {
            // Удаление файла изображения
            Storage::disk('public')->delete($catalog->image);
        }
        $catalog->delete();
        return redirect()->route('admin.catalog.index')->with('success', 'Категория удалена');
    }

}
