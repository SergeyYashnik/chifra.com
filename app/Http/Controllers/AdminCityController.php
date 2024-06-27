<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class AdminCityController extends Controller
{
    public function index()
    {
        $cities = City::all();
        return view('admin.city.index', compact('cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'delivery_price' => 'required|integer|min:0',
            'free_delivery_from' => 'required|integer|min:0',
        ]);

        City::create([
            'name' => $request->name,
            'has_store' => $request->has('has_store'),
            'delivery_price' => $request->delivery_price,
            'free_delivery_from' => $request->free_delivery_from,
        ]);

        return redirect()->route('admin.city.index')->with('success', 'Город успешно добавлен.');
    }

    public function edit(City $city)
    {
        return view('admin.city.edit', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'delivery_price' => 'required|integer|min:0',
            'free_delivery_from' => 'required|integer|min:0',
        ]);

        $city->update([
            'name' => $request->name,
            'has_store' => $request->has('has_store'),
            'delivery_price' => $request->delivery_price,
            'free_delivery_from' => $request->free_delivery_from,
        ]);

        return redirect()->route('admin.city.index')->with('success', 'Город успешно обновлён.');
    }

    public function destroy(City $city)
    {
        $city->delete();
        return redirect()->route('admin.city.index')->with('success', 'Город успешно удалён.');
    }
}
