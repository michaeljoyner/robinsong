<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StockUnitFormRequest;
use App\Stock\Price;
use App\Stock\Product;
use App\Stock\StockUnit;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StockUnitsController extends Controller
{

    public function showApp($productId)
    {
        $product = Product::findOrFail($productId);

        return view('admin.products.stockunits')->with(compact('product'));
    }

    public function index($productId)
    {
        return Product::findOrFail($productId)->stockUnits->map(function($unit) {
            return (object) [
                'id' => $unit->id,
                'name' => $unit->name,
                'price' => $unit->price->asCurrencyString(),
                'weight' => $unit->weight,
                'available' => $unit->available
            ];
        });
    }

    public function store(StockUnitFormRequest $request, $productId)
    {
        Product::findOrFail($productId)->addStockUnit($request->requiredAttributes());

        return redirect('admin/products/' . $productId . '/stockunits/app');
    }

    public function update(StockUnitFormRequest $request, $unitId)
    {
        $unit = StockUnit::findOrFail($unitId);
        $unit->update($request->requiredAttributes());

        return response()->json([
            'id' => $unit->id,
            'name' => $unit->name,
            'price' => $unit->price->asCurrencyString(),
            'weight' => $unit->weight,
            'available' => $unit->available
        ]);
    }

    public function delete($unitId)
    {
        $unit = StockUnit::findOrFail($unitId);
        $deleted = $unit->delete();

        return response()->json(['deleted' => $deleted]);
    }

    public function setAvailability(Request $request, $unitId)
    {
        $this->validate($request, ['available' => 'required|boolean']);

        $newState = StockUnit::findOrFail($unitId)->setAvailability(!! $request->available);

        return response()->json(['new_state' => $newState]);
    }

}
