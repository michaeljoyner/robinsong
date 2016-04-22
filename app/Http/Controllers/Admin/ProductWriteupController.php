<?php

namespace App\Http\Controllers\Admin;

use App\Stock\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductWriteupController extends Controller
{
    public function setWriteup($productId, Request $request)
    {
        $this->validate($request, ['writeup' => 'required']);

        $product = Product::findOrFail($productId);

        $product->setWriteup($request->writeup);

        return response()->json($product->toJson());
    }
}
