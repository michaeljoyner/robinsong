<?php

namespace App\Http\Controllers\Admin;

use App\Stock\Category;
use App\Stock\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    public function show($productId)
    {
        $product = Product::findOrFail($productId);
        $gallery = $product->getGallery();

        return view('admin.products.show')->with(compact('product', 'gallery'));
    }

    public function create($categoryId)
    {
        $product = new Product();

        return view('admin.products.create')->with(compact('product', 'categoryId'));
    }

    public function store($categoryId, Request $request)
    {
        $product = Category::findOrFail($categoryId)->addProduct($request->all());

        return redirect('admin/categories/'.$categoryId);
    }

    public function edit($productId)
    {
        $product = Product::findOrFail($productId);

        return view('admin.products.edit')->with(compact('product'));
    }

    public function update($productId, Request $request)
    {
        $product = Product::findOrFail($productId);
        $product->update($request->all());

        return redirect('admin/products/'.$product->id);
    }

    public function delete($productId)
    {
        Product::findOrFail($productId)->delete();

        return redirect('admin');
    }

    public function storeCoverPic($productId, Request $request)
    {
        $uploadedImage = Product::findOrFail($productId)->setModelImage($request->file('file'));

        return response()->json($uploadedImage);
    }

    public function setAvailability($productId, Request $request)
    {
        $product = Product::findOrFail($productId);
        $originalStatus = $product->available;
        $set = $product->setAvailability($request->available);
        return response()->json(['new_state' => $set ? $request->available : $originalStatus]);
    }

}
