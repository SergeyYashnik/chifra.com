<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Catalog;
use App\Models\ConnectionProductFilterValue;
use App\Models\Filter;
use App\Models\FilterValues;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($id, Request $request)
    {
        $product = Product::with('images')->findOrFail($id);
        $product->visits = $product->visits ? $product->visits + 1 : 1;
        $product->save();
        $name = $request->input('name');
        if ($product->catalogs_lvl_1) {
            $catalogs_lvl_1 = Catalog::findOrFail($product->catalogs_lvl_1);
            if ($product->catalogs_lvl_2) {
                $catalogs_lvl_2 = Catalog::findOrFail($product->catalogs_lvl_2);
                if ($product->catalogs_lvl_3) {
                    $catalogs_lvl_3 = Catalog::findOrFail($product->catalogs_lvl_3);
                } else {
                    $catalogs_lvl_3 = null;
                }
            } else {
                $catalogs_lvl_2 = null;
                $catalogs_lvl_3 = null;
            }
        } else {
            $catalogs_lvl_1 = null;
            $catalogs_lvl_2 = null;
            $catalogs_lvl_3 = null;
        }



        $brand = Brand::findOrFail($product->brand_id);


        $filters = Filter::where('id_catalog', $product->id_catalog)
            ->with(['subfilters.filterValue' => function ($query) use ($product) {
                $query->whereHas('connectionProductFilterValue', function ($query) use ($product) {
                    $query->where('product_id', $product->id);
                });
            }, 'filterValue' => function ($query) use ($product) {
                $query->whereHas('connectionProductFilterValue', function ($query) use ($product) {
                    $query->where('product_id', $product->id);
                });
            }])
            ->get();

        $maxHeight = 0;
        foreach ($product->images as $image) {
            $imageHeight = getimagesize(asset('storage/' . $image->path))[1];
            $maxHeight = max($maxHeight, $imageHeight);
        }


        return view('product.show', compact(['product', 'name', 'catalogs_lvl_1', 'catalogs_lvl_2', 'catalogs_lvl_3', 'filters', 'brand', 'maxHeight']));
    }
}
