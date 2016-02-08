<?php

namespace App\Http\Controllers\Admin;

use App\Stock\Product;
use App\Stock\ProductOption;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductOptionsController extends Controller
{
    public function index($productId)
    {
        $options = Product::findOrFail($productId)->options->map(function($option) {
            return [
                'id' => $option->id,
                'name' => $option->name
            ];
        });

        return response()->json($options);
    }

    public function store($productId, Request $request)
    {
        $this->validate($request, ['name' => 'required|max:255']);

        $option = Product::findOrFail($productId)->addOption($request->name);

        return response()->json($option);
    }

    public function delete($optionId)
    {
        ProductOption::findOrFail($optionId)->delete();

        return response()->json('ok');
    }

    public function getOptionsAndValuesForProduct($id)
    {
        $options = Product::findOrFail($id)->options;
        $result = [];
        foreach($options as $option) {
            $result[] = [
                'name' => $option->name,
                'id' => $option->id,
                'selected' => '',
                'values' => $option->values->map(function($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name
                ];
            })->toArray()
                ];
        }

        return response()->json($result);
    }
}
