<?php

namespace App\Http\Controllers;

use App\Models\Catalog;

class CatalogController extends Controller
{
    public function index(){
        $catalogs = Catalog::where('image', null)->get();

        foreach($catalogs as $catalog){
            dump($catalog->name);
        }

        return view('catalog');
    }

    public function create()
    {
        $catalogsArr = [
            [
                'name' => 'Название какое-то',
                'image' => 'imagePath.jpg'
            ]
        ];

//        Catalog::create([
//            'name' => 'Название какое-то',
//            'image' => 'imagePath.jpg'
//        ]);

    }

}
