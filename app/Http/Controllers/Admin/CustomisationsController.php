<?php

namespace App\Http\Controllers\Admin;

use App\Stock\Customisation;
use App\Stock\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CustomisationsController extends Controller
{

    public function index($productId)
    {
        $customisations = Product::findOrFail($productId)->customisations->map(function($item) {
           return [
               'id' => $item->id,
               'name' => $item->name,
               'longform' => $item->longform
           ];
        });

        return response()->json($customisations);
    }

    public function store($productId, Request $request)
    {
        $customisation = Product::findOrFail($productId)->addCustomisation($request->name, $request->longform);

        return response()->json([
            'id' => $customisation->id,
            'name' => $customisation->name,
            'longform' => $customisation->longform
        ]);
    }

    public function delete($customisationId)
    {
        Customisation::findOrFail($customisationId)->delete();

        return response()->json('ok');
    }
}
