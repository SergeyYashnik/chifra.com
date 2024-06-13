<?php

namespace App\Http\Controllers;

use App\Models\Catalog;

class CatalogController extends Controller
{
    public function index(){
        # $catalogs = Catalog::where('image', null)->get();
        $catalogs = Catalog::all();

//        foreach($catalogs as $catalog){
//            dump($catalog->name);
//        }

        return view('catalog', compact('catalogs'));
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
    public function delete(){
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
