<?php

namespace App\Http\Controllers;

use App\Stock\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductPurchasingOptionsController extends Controller
{
    public function show($productId)
    {
        $product = Product::with('options.values', 'customisations', 'stockUnits')->findOrFail($productId);

        $data = [
            'stockUnits' => $product->stockUnits->filter(function($unit) { return $unit->available; })->map(function($unit) {
                return [
                    'id' => $unit->id,
                    'name' => $unit->name,
                    'price' => $unit->price->asCurrencyString(),
                    'weight' => $unit->weight
                ];
            })->toArray(),
            'productOptions' => $product->options->toArray(),
            'productCustomisations' => $product->customisations->toArray()
        ];

        return response()->json($data);
    }
}
