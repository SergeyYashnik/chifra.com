<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Catalog;
use App\Models\Filter;
use App\Models\FilterValues;
use App\Models\Product;
use App\Models\ConnectionProductFilterValue;
use Illuminate\Http\Request;
use App\Models\ImageProduct;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $id_catalog = $request->input('id_catalog');
        $products = Product::where('id_catalog', $id_catalog)->with('oneImage')->get();
        $filters = Filter::where('id_catalog', $id_catalog)->with('subfilters')->get();
        return view('admin.product.index',compact(['products','filters', 'id_catalog']));
    }
    public function create(Request $request)
    {
        $id_catalog = $request->input('id_catalog');
        $filters = Filter::where('id_catalog', $id_catalog)->with('subfilters')->get();

        $filterIds = $filters->pluck('id')->toArray();
        $subfilterIds = $filters->flatMap(function ($filter) {
            return $filter->subfilters->pluck('id')->toArray();
        })->toArray();
        $allFilterIds = array_merge($filterIds, $subfilterIds);

        $filtersValue = FilterValues::where('user_input', 0)->whereIn('id_filter', $allFilterIds)->get();

        $brands = Brand::all();

        return view('admin.product.create', compact(['id_catalog', 'filters', 'filtersValue', 'brands']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'brand_id' => 'required|exists:brands,id',
            'id_catalog' => 'required|exists:catalogs,id',
            'quantity' => 'required|integer|min:0',
            'sale' => 'nullable|integer|min:0|max:100',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        $catalogLVL = Catalog::where('id', $request->id_catalog)->first();
        if ($catalogLVL->lvl == 3){
            $catalogID3 = $catalogLVL->id;
            $catalogLVL2 = Catalog::where('id', $catalogLVL->id_catalog)->first();
            $catalogID2 = $catalogLVL2->id;
            $catalogLVl1 = Catalog::where('id', $catalogLVL2->id_catalog)->first();
            $catalogID1 = $catalogLVl1->id;
        } elseif ($catalogLVL->lvl == 2){
            $catalogID2 = $catalogLVL->id;
            $catalogLVl1 = Catalog::where('id', $catalogLVL->id_catalog)->first();
            $catalogID1 = $catalogLVl1->id;
            $catalogID3 = null;
        } else {
            $catalogID1 = $catalogLVL->id;
            $catalogID2 = null;
            $catalogID3 = null;

        }

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->brand_id = $request->brand_id;
        $product->id_catalog = $request->id_catalog;
        $product->quantity = $request->quantity;
        $product->sale = $request->sale;

        $product->catalogs_lvl_1 = $catalogID1;
        $product->catalogs_lvl_2 = $catalogID2;
        $product->catalogs_lvl_3 = $catalogID3;

        $product->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product_images', 'public');
                $productImage = new ImageProduct();
                $productImage->path = $path;
                $productImage->id_product = $product->id;
                $productImage->save();
            }
        }

        if ($request->filled('filters')) {
            foreach ($request->input('filters') as $filterId => $filterValue) {
                // Проверяем, существует ли значение фильтра
                if ($filterValue !== null && trim($filterValue) !== ''){
                    $existingValue = FilterValues::where('id_filter', $filterId)
                        ->where(function ($query) use ($filterValue) {
                            $query->where('id', $filterValue)
                                ->orWhere('value', $filterValue);
                        })
                        ->first();

                    if (!$existingValue) {
                        $newFilterValue = new FilterValues();
                        $newFilterValue->id_filter = $filterId;
                        $newFilterValue->value = $filterValue;
                        $newFilterValue->user_input = true;
                        $newFilterValue->save();
                        $filterId = $newFilterValue->id;
                    } else {
                        $filterId = $existingValue->id;
                    }

                    $productFilterValue = new ConnectionProductFilterValue();
                    $productFilterValue->product_id = $product->id;
                    $productFilterValue->filter_value_id = $filterId;
                    $productFilterValue->save();
                }
            }
        }

        return redirect()->route('admin.products.index', ['id_catalog' => $request->id_catalog])->with('success', 'Товар успешно добавлен');
    }





    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $filters = Filter::where('id_catalog', $product->id_catalog)->with('subfilters')->get();

        $filterIds = $filters->pluck('id')->toArray();
        $subfilterIds = $filters->flatMap(function ($filter) {
            return $filter->subfilters->pluck('id')->toArray();
        })->toArray();
        $allFilterIds = array_merge($filterIds, $subfilterIds);

        $filtersValue = FilterValues::where('user_input', 0)->whereIn('id_filter', $allFilterIds)->get();

        $brands = Brand::all();
        $images = ImageProduct::where('id_product', $id)->get();

        $selectedFilterValues = ConnectionProductFilterValue::where('product_id', $id)->get()->pluck('filter_value_id')->toArray();

        $selectedFiltersValue = FilterValues::whereIn('id', $selectedFilterValues)
            ->get()
            ->keyBy('id_filter')
            ->map(function ($filterValue) {
                return $filterValue->value;
            })
            ->toArray();

        return view('admin.product.edit', compact(['product', 'filters', 'filtersValue', 'selectedFiltersValue', 'brands', 'images']));
    }



    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'brand_id' => 'required|exists:brands,id',
            'quantity' => 'required|integer|min:0',
            'sale' => 'nullable|integer|min:0|max:100',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->brand_id = $request->brand_id;
        $product->quantity = $request->quantity;
        $product->sale = $request->sale;

        $product->save();

        // Добавление новых изображений, если они были загружены
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product_images', 'public');
                $productImage = new ImageProduct();
                $productImage->path = $path;
                $productImage->id_product = $product->id;
                $productImage->save();
            }
        }

        // Удаление старых значений фильтров
        ConnectionProductFilterValue::where('product_id', $product->id)->delete();

        // Добавление новых значений фильтров
        if ($request->filled('filters')) {
            foreach ($request->input('filters') as $filterId => $filterValue) {
                if ($filterValue !== null && trim($filterValue) !== '') {
                    $existingValue = FilterValues::where('id_filter', $filterId)
                        ->where(function ($query) use ($filterValue) {
                            $query->where('id', $filterValue)
                                ->orWhere('value', $filterValue);
                        })
                        ->first();

                    if (!$existingValue) {
                        $newFilterValue = new FilterValues();
                        $newFilterValue->id_filter = $filterId;
                        $newFilterValue->value = $filterValue;
                        $newFilterValue->user_input = true;
                        $newFilterValue->save();
                        $filterId = $newFilterValue->id;
                    } else {
                        $filterId = $existingValue->id;
                    }

                    $productFilterValue = new ConnectionProductFilterValue();
                    $productFilterValue->product_id = $product->id;
                    $productFilterValue->filter_value_id = $filterId;
                    $productFilterValue->save();
                }
            }
        }

        return redirect()->route('admin.products.index', ['id_catalog' => $product->id_catalog])->with('success', 'Товар успешно обновлен');
    }



    public function deleteImage($id)
    {
        $image = ImageProduct::findOrFail($id);

        // Удаление изображения из хранилища
        Storage::disk('public')->delete($image->path);

        // Удаление записи из базы данных
        $image->delete();

        return redirect()->back()->with('success', 'Изображение успешно удалено.');
    }

    public function delete($id)
    {
        dd('wef');
        $product = Product::findOrFail($id);

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $product->delete();

        return redirect()->route('admin.products.index', ['id_catalog' => $product->id_catalog])->with('success', 'Товар успешно удален');
    }

}
