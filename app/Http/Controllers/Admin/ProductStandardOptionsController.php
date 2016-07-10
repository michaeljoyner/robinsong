<?php

namespace App\Http\Controllers\Admin;

use App\Stock\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductStandardOptionsController extends Controller
{
    public function addToProduct(Request $request, $productId)
    {
        $this->validate($request, ['standard_option_id' => 'required|exists:standard_options,id']);

        return Product::findOrFail($productId)->useStandardOption($request->standard_option_id);
    }
}
