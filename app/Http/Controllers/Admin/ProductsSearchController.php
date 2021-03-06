<?php

namespace App\Http\Controllers\Admin;

use App\Stock\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductsSearchController extends Controller
{
    public function showSearchPage()
    {
        $products = Product::with('category')->orderBy('name')->get();
        return view('admin.products.search')->with(compact('products'));
    }

    public function search($term)
    {
        if (!$term) {
            return null;
        }

        $products = Product::with('category')->where('name', 'LIKE', '%' . $term . '%')->get()->map(function ($product) {
            return [
                'id'    => $product->id,
                'name'  => $product->name,
                'slug'  => $product->slug,
                'price' => $product->price,
                'thumb' => $product->coverPic('thumb'),
                'category' => $product->category->name
            ];
        });

        return response()->json($products);
    }
}
