<?php

namespace App\Http\Controllers\Admin;

use App\Stock\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductClonesController extends Controller
{
    public function store(Request $request, $productId)
    {
        $this->validate($request, ['name' => 'required|max:255']);

        $clone = Product::findOrFail($productId)->cloneAs($request->name);

        return redirect('admin/products/' . $clone->id);
    }
}
