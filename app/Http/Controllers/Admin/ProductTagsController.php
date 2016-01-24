<?php

namespace App\Http\Controllers\Admin;

use App\Stock\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductTagsController extends Controller
{
    public function syncTags($productId, Request $request)
    {
        $product = Product::findOrFail($productId);
        $product->setTags($request->tags);

        return response()->json([
            'tags' => $product->getTagsList()
        ]);
    }

    public function getProductTags($productId)
    {
        $tags = Product::findOrFail($productId)->getTagsList();

        return response()->json([
            'tags' => $tags
        ]);
    }
}
