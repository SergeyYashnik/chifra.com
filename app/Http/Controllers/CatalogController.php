<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Catalog;
use App\Models\Filter;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $catalogs = Catalog::where('lvl', 1)->get();
        return view('catalog.index', compact('catalogs'));
    }

    public function show(Request $request)
    {
        $catalogs_lvl_1 = null;
        $catalogs_lvl_2 = null;
        $catalogs_lvl_3 = null;
        $filtersQuery = null;
        $queryProducts = Product::query();
        $requestCatalogLvl1 = $request->input('catalog_lvl_1') ?? null;
        $requestCatalogLvl2 = $request->input('catalog_lvl_2') ?? null;
        $requestCatalogLvl3 = $request->input('catalog_lvl_3') ?? null;

        if ($requestCatalogLvl1) {
            $catalogs_lvl_1 = Catalog::where('name', $requestCatalogLvl1)
                ->withCount('productsLvl1 as count_1_lvl')
                ->get();

            if ($requestCatalogLvl2) {
                $catalogs_lvl_2 = Catalog::where('id_catalog', $catalogs_lvl_1->first()->id)
                    ->where('name', $requestCatalogLvl2)
                    ->withCount('productsLvl2 as count_2_lvl')
                    ->get();

                if ($requestCatalogLvl3) {
                    $catalogs_lvl_3 = Catalog::where('id_catalog', $catalogs_lvl_2->first()->id)
                        ->where('name', $requestCatalogLvl3)
                        ->withCount('productsLvl3 as count_3_lvl')
                        ->get();

                    if ($catalogs_lvl_3->isNotEmpty()){
                        $filtersQuery = Filter::where('id_catalog', $catalogs_lvl_3->first()->id);
                    }
                    $queryProducts = Product::where('catalogs_lvl_3', $catalogs_lvl_3->first()->id);

                } else {
                    $catalogs_lvl_3 = Catalog::where('id_catalog', $catalogs_lvl_2->first()->id)
                        ->withCount('productsLvl3 as count_3_lvl')
                        ->get();

                    if ($catalogs_lvl_3->isNotEmpty()) {
                        $filtersQuery = Filter::where('id_catalog', $catalogs_lvl_2->first()->id);
                    }
                    $queryProducts = Product::where('catalogs_lvl_2', $catalogs_lvl_2->first()->id);
                }
            } else {
                $catalogs_lvl_2 = Catalog::where('id_catalog', $catalogs_lvl_1->first()->id)
                    ->withCount('productsLvl2 as count_2_lvl')
                    ->get();

                if ($catalogs_lvl_2->isNotEmpty()) {
                    $filtersQuery = Filter::where('id_catalog', $catalogs_lvl_2->first()->id);
                }
                $queryProducts = Product::where('catalogs_lvl_1', $catalogs_lvl_1->first()->id);
            }
        } else {
            $catalogs_lvl_1 = Catalog::where('lvl', 1)
                ->withCount('productsLvl1 as count_1_lvl')
                ->get();
        }

        $massForBrand = $queryProducts->pluck('brand')->unique()->toArray();
        $brands = Brand::whereIn('id', $massForBrand)->get();
        $requestBrands = $request->input('brands') ?? null;

        $minPrice = $queryProducts->min('price');
        $requestMinPrice = $request->input('min_price') ?? null;
        $maxPrice = $queryProducts->max('price');
        $requestMaxPrice = $request->input('max_price') ?? null;


        if ($filtersQuery && $filtersQuery->exists()) {
            $filters = $filtersQuery
                ->where(function ($query) {
                    $query->where('required_to_fill_out', 1)
                        ->orWhereNull('required_to_fill_out');
                })
                ->with(['requiredSubfilters.filterValue' => function ($query) use ($queryProducts) {
                    $query->whereHas('connectionProductFilterValue', function ($subQuery) use ($queryProducts) {
                        $subQuery->whereIn('product_id', $queryProducts->pluck('id')->toArray());
                    });
                }, 'filterValue' => function ($query) use ($queryProducts) {
                    $query->whereHas('connectionProductFilterValue', function ($subQuery) use ($queryProducts) {
                        $subQuery->whereIn('product_id', $queryProducts->pluck('id')->toArray());
                    });
                }])
                ->get();
        } else {
            $filters = null;
        }




        if ($request->input('filter_id')){
            $filter_id = $request->input('filter_id');

            foreach ($filter_id as $filterValueId) {
                $queryProducts->whereHas('connectionProductFilterValues', function ($query) use ($filterValueId) {
                    $query->whereIn('filter_value_id', array_values($filterValueId));
                });
            }
        } else {
            $filter_id = null;
        }

        if ($requestBrands){
            $queryProducts->whereIn('brand', array_values($requestBrands));
        }
        if ($requestMinPrice){
            $queryProducts->where('price', '>=', $requestMinPrice);
        }
        if ($requestMaxPrice){
            $queryProducts->where('price', '<=', $requestMaxPrice);
        }

        $products = $queryProducts->with('oneImage')->orderByDesc('visits')->get();

        $count = Product::count();
        return view('catalog.show', compact(['catalogs_lvl_1', 'catalogs_lvl_2', 'catalogs_lvl_3', 'products', 'count', 'filters', 'filter_id', 'brands', 'requestCatalogLvl1', 'requestCatalogLvl2', 'requestCatalogLvl3', 'requestBrands', 'minPrice', 'maxPrice']));
    }


    public function create()
    {
        $catalogsArr = [
            [
                'name' => 'Название какое-то',
                'image' => 'imagePath.jpg'
            ]
        ];

        Catalog::create($catalogsArr);

    }

    public function update()
    {
        $post = Catalog::find(1);
        dump($post->name);
        $post->update([
            'name' => 'Главная2',
        ]);
        dump($post->name);


    }

    public function delete()
    {
        $post = Catalog::find(3);
        dump($post->name);
        $post->delete();
        dump($post->name);

//        Для поиска в "мусорке"
//        $post = Catalog::withTrashed()->find(3);
//        dump($post->name);
//        $post->restore();
//        dump($post->name);

    }

    public function firstOrCreate()
    {
        $catalog = Catalog::firstOrCreate(
            [
                'name' => 'Нет Название какое-то',
            ],
            [
                'name' => 'Нет Название какое-то',
                'image' => 'imagePath.jpg'
            ]
        );

        dump($catalog->name);
    }

    public function updateOrCreate()
    {
        $catalog = Catalog::updateOrCreate(
            [
                'name' => 'Нет Название какое-то',
            ],
            [
                'name' => 'KFKFKFНазвание какое-то',
                'image' => 'imagePath.jpg'
            ]
        );

        dump($catalog->name);
    }

}
