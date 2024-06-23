<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $catalogs = Catalog::where('lvl', 1)->get();
        # $catalogs = Catalog::all();
        return view('catalog.index', compact('catalogs'));
    }

    public function show(Request $request)
    {
        if ($request->input('catalog_lvl_1')) {
            $catalogs_lvl_1 = Catalog::where('name', $request->input('catalog_lvl_1'))->get();
            if ($request->input('catalog_lvl_2')) {
                $catalogs_lvl_2 = Catalog::where('id_catalog', $catalogs_lvl_1->first()->id)->where('name', $request->input('catalog_lvl_2'))->get();

                if ($request->input('catalog_lvl_3')) {
                    $catalogs_lvl_3 = Catalog::where('id_catalog', $catalogs_lvl_2->first()->id)->where('name', $request->input('catalog_lvl_3'))->get();

                } else {
                    $catalogs_lvl_3 = Catalog::where('id_catalog', $catalogs_lvl_2->first()->id)->get();
                }

            } else {
                $catalogs_lvl_2 = Catalog::where('id_catalog', $catalogs_lvl_1->first()->id)->get();
                $catalogs_lvl_3 = null;
            }

        } else {
            $catalogs_lvl_1 = Catalog::where('lvl', 1)->get();
            $catalogs_lvl_2 = null;
            $catalogs_lvl_3 = null;
        }
        return view('catalog.show', compact(['catalogs_lvl_1', 'catalogs_lvl_2', 'catalogs_lvl_3']));
    }

//    public function show($id)
//    {
//        $catalogs = Catalog::where('id_catalog', $id)->get();
//        return view('catalog.show', compact('catalogs'));
//    }


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
