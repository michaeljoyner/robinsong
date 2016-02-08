<?php

namespace App\Http\Controllers\Admin;

use App\Stock\Category;
use App\Stock\Collection;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CollectionsController extends Controller
{
    public function index()
    {
        $newCollection = new Collection();
        $collections = Collection::all();

        return view('admin.collections.index')->with(compact('collections', 'newCollection'));
    }

    public function show($collectionId)
    {
        $newCategory = new Category();
        $collection = Collection::with('categories')->findOrFail($collectionId);

        return view('admin.collections.show')->with(compact('collection', 'newCategory'));
    }

    public function create()
    {
        $collection = new Collection();
        return view('admin.collections.create')->with(compact('collection'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required'
        ]);
        $collection = Collection::create($request->only(['name', 'description']));

        return redirect('admin/collections/'.$collection->id);
    }

    public function edit($collectionId)
    {
        $collection = Collection::findOrFail($collectionId);

        return view('admin.collections.edit')->with(compact('collection'));
    }

    public function update($collectionId, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required'
        ]);

        $collection = Collection::findOrFail($collectionId);
        $collection->update($request->only(['name', 'description']));

        return redirect('admin/collections/'.$collectionId);
    }

    public function delete($collectionId)
    {
        Collection::findOrFail($collectionId)->delete();

        return redirect('admin/collections');
    }

    public function storeCoverPic($collectionId, Request $request)
    {
        $this->validate($request, ['file' => 'required|image']);

        $uploadedImage = Collection::findOrFail($collectionId)->setModelImage($request->file('file'));

        return response()->json($uploadedImage);
    }
}
