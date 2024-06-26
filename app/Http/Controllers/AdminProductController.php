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

        // Собираем все айди фильтров и их подфильтров
        $filterIds = $filters->pluck('id')->toArray();
        $subfilterIds = $filters->flatMap(function ($filter) {
            return $filter->subfilters->pluck('id')->toArray();
        })->toArray();
        $allFilterIds = array_merge($filterIds, $subfilterIds);

        // Получаем все значения фильтров на основе собранных айди
        $filtersValue = FilterValues::where('user_input', 0)->whereIn('id_filter', $allFilterIds)->get();

        $brands = Brand::all();

        return view('admin.product.create', compact(['id_catalog', 'filters', 'filtersValue', 'brands']));
    }

    public function store(Request $request)
    {

        // Валидация данных
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'brand' => 'required|exists:brands,id',
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


        // Создание нового товара
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->brand = $request->brand;
        $product->id_catalog = $request->id_catalog;
        $product->quantity = $request->quantity;
        $product->sale = $request->sale;

        $product->catalogs_lvl_1 = $catalogID1;
        $product->catalogs_lvl_2 = $catalogID2;
        $product->catalogs_lvl_3 = $catalogID3;

        $product->save();

        // Сохранение изображений
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product_images', 'public');
                $productImage = new ImageProduct();
                $productImage->path = $path;
                $productImage->id_product = $product->id;
                $productImage->save();
            }
        }

        // Связывание товара с фильтрами
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
                        // Если значение не существует, создаем новое
                        $newFilterValue = new FilterValues();
                        $newFilterValue->id_filter = $filterId;
                        $newFilterValue->value = $filterValue;
                        $newFilterValue->user_input = true; // Метка о том, что пользователь ввел значение
                        $newFilterValue->save();
                        $filterId = $newFilterValue->id;
                    } else {
                        $filterId = $existingValue->id;
                    }

                    // Связываем товар с фильтром
                    $productFilterValue = new ConnectionProductFilterValue();
                    $productFilterValue->product_id = $product->id;
                    $productFilterValue->filter_value_id = $filterId;
                    $productFilterValue->save();
                }
            }
        }

        return redirect()->route('admin.products.index', ['id_catalog' => $request->id_catalog])->with('success', 'Товар успешно добавлен');
    }
}
