<?php

namespace App\Http\Controllers\Admin;

use App\Stock\Category;
use App\Stock\Collection;
use App\Stock\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function create($collectionId)
    {
        $category = new Category();

        return view('admin.categories.create')->with(compact('category', 'collectionId'));
    }

    public function show($categoryId)
    {
        $newProduct = new Product();
        $category = Category::with('products')->findOrFail($categoryId);

        return view('admin.categories.show')->with(compact('newProduct', 'category'));
    }

    public function store($collectionId, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required'
        ]);

        Collection::findOrFail($collectionId)->addCategory($request->only(['name', 'description']));
        return redirect('admin/collections/'.$collectionId);
    }

    public function edit($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        return view('admin.categories.edit')->with(compact('category'));
    }

    public function update($categoryId, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required'
        ]);

        $category = Category::findOrFail($categoryId);
        $category->update($request->only(['name', 'description']));

        return redirect('admin/categories/'.$category->id);
    }

    public function delete($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $collectionId = $category->collection->id;
        $category->delete();

        return redirect('admin/collections/'.$collectionId);
    }

    public function storeCoverPic($categoryId, Request $request)
    {
        $this->validate($request, ['file' => 'required|image']);

        $uploadedImage = Category::findOrFail($categoryId)->setModelImage($request->file('file'));

        return response()->json($uploadedImage);
    }
}
