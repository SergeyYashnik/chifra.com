<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use App\Models\FilterValues;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminFilterController extends Controller
{
    public function edit($id)
    {
        $filter = Filter::findOrFail($id);
        $subfilters = Filter::where('id_filter', $id)->get();
        $values = FilterValues::where('id_filter', $id)->get();

        return view('admin.filter.edit', compact('filter', 'subfilters', 'values'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_custom_input' => 'nullable|string|in:on',
            'required_to_fill_out' => 'nullable|string|in:on',
        ]);

        $filter = Filter::findOrFail($id);

        $request->validate([
            'name' => Rule::unique('filters')->where(function ($query) use ($request, $filter) {
                    return $query->where('id_filter', $filter->id_filter);
                })
                ->ignore($filter->id),
        ]);

        $filter->name = $request->name;
        $filter->is_custom_input = $request->has('is_custom_input');
        $filter->required_to_fill_out = $request->has('required_to_fill_out');
        $filter->save();

        return redirect()->route('admin.filter.edit', ['id' => $id])->with('success', 'Фильтр обновлен');
    }

    public function addSubfilter(Request $request, $id)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('filters')->where(function ($query) use ($id) {
                    return $query->where('id_filter', $id);
                })
            ],
            'is_custom_input' => 'nullable|string|in:on',
            'required_to_fill_out' => 'nullable|string|in:on',
        ]);

        $filter = Filter::findOrFail($id);
        $filter->is_custom_input = null;
        $filter->required_to_fill_out = null;
        $filter->save();

        $subfilter = new Filter();
        $subfilter->name = $request->name;
        $subfilter->id_filter = $id;
        $subfilter->lvl = 2;
        $subfilter->is_custom_input = $request->has('is_custom_input');
        $subfilter->required_to_fill_out = $request->has('required_to_fill_out');
        $subfilter->save();

        return redirect()->route('admin.filter.edit', ['id' => $id])->with('success', 'Подфильтр добавлен');
    }

    public function addValue(Request $request, $id)
    {
        $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $value = new FilterValues();
        $value->id_filter = $id;
        $value->value = $request->value;
        $value->user_input = 0;
        $value->save();

        return redirect()->route('admin.filter.edit', ['id' => $id])->with('success', 'Значение добавлено');
    }

    public function destroy($id)
    {
        $filter = Filter::findOrFail($id);
        $filter->delete();

        return redirect()->back()->with('success', 'Фильтр удален');
    }

    public function removeValue($id, $valueId)
    {
        $value = FilterValues::where('id_filter', $id)->where('id', $valueId)->firstOrFail();
        $value->delete();

        return redirect()->route('admin.filter.edit', ['id' => $id])->with('success', 'Значение удалено');
    }
}
