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
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:0'
        ]);
        Category::findOrFail($categoryId)->addProduct($request->only(['name', 'description', 'price', 'weight']));

        return redirect('admin/categories/'.$categoryId);
    }

    public function edit($productId)
    {
        $product = Product::findOrFail($productId);

        return view('admin.products.edit')->with(compact('product'));
    }

    public function update($productId, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:0'
        ]);

        $product = Product::findOrFail($productId);
        $product->update($request->all());

        return redirect('admin/products/'.$product->id);
    }

    public function delete($productId)
    {
        $product = Product::findOrFail($productId);
        $categoryId = $product->category->id;
        $product->delete();

        return redirect('admin/categories/'.$categoryId);
    }

    public function storeCoverPic($productId, Request $request)
    {
        $this->validate($request, ['file' => 'required|image']);
        $uploadedImage = Product::findOrFail($productId)->setModelImage($request->file('file'));

        return response()->json($uploadedImage);
    }

    public function setAvailability($productId, Request $request)
    {
        $this->validate($request, ['available' => 'required|boolean']);
        $product = Product::findOrFail($productId);
        $originalStatus = $product->available;
        $set = $product->setAvailability($request->available);
        return response()->json(['new_state' => $set ? $request->available : $originalStatus]);
    }

}
