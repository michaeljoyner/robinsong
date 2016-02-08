<?php

namespace App\Http\Controllers\Admin;

use App\Stock\OptionValue;
use App\Stock\ProductOption;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductOptionValuesController extends Controller
{
    public function index($optionId)
    {
        $values = ProductOption::findOrFail($optionId)->values->map(function($value) {
            return [
                'id' => $value->id,
                'name' => $value->name
            ];
        });

        return response()->json($values);
    }

    public function store($optionId, Request $request)
    {
        $this->validate($request, ['name' => 'required|max:255']);

        $value = ProductOption::findOrFail($optionId)->addValue($request->name);

        return response()->json([
            'id' => $value->id,
            'name' => $value->name
        ]);
    }

    public function delete($valueId)
    {
        OptionValue::findOrFail($valueId)->delete();

        return response()->json('ok');
    }
}
