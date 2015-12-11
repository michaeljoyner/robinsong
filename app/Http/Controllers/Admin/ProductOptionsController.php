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
        $option = Product::findOrFail($productId)->addOption($request->name);

        return response()->json($option);
    }

    public function delete($optionId)
    {
        ProductOption::findOrFail($optionId)->delete();

        return response()->json('ok');
    }
}
