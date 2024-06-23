<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id');
        $products = Product::where('id_catalog', $id)->get();
        $filters = Filter::where('id_catalog', $id)->with('subfilters')->get();
        return view('admin.product.index',compact(['products','filters']));
    }
}
