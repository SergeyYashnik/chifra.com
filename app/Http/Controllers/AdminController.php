<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    function index()
    {
        return view('admin.index');
    }
}
