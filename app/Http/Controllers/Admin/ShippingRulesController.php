<?php

namespace App\Http\Controllers\Admin;

use App\Shipping\ShippingLocation;
use App\Shipping\WeightClass;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ShippingRulesController extends Controller
{

    public function show()
    {
        return view('admin.shipping.showrules');
    }

    public function getLocations()
    {
        $locations = ShippingLocation::all()->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->name
            ];
        })->toArray();

        return response()->json($locations);
    }

    public function getLocationWeightClasses($locationId)
    {
        $classes = ShippingLocation::findOrFail($locationId)->weightClasses()->orderBy('weight_limit')->get()->map(function($item) {
            return [
                'id' => $item->id,
                'shipping_location_id' => $item->shipping_location_id,
                'weight_limit' => $item->weight_limit,
                'price' => $item->price
            ];
        })->toArray();

        return response()->json($classes);
    }

    public function addRuleLocation(Request $request)
    {
        $this->validate($request, ['name' => 'required|unique:shipping_locations']);

        $shippingLocation = ShippingLocation::create(['name' => $request->name]);

        return response()->json([
            'id' => $shippingLocation->id,
            'name' => $shippingLocation->name
        ]);
    }

    public function updateRuleLocation(Request $request, $id)
    {
        $this->validate($request, ['name' => 'required|unique:shipping_locations']);

        $location = ShippingLocation::findOrFail($id);

        $location->update(['name' => $request->name]);

        return response()->json([
            'id' => $location->id,
            'name' => $location->name
        ]);
    }

    public function deleteRuleLocation($id)
    {
        ShippingLocation::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }

    public function createWeightClass(Request $request, $locationId)
    {
        $this->validate($request, [
            'weight_limit' => 'required|integer|between:0,10000000',
            'price' => 'required|integer|between:0,10000000'
        ]);

        $weightclass = ShippingLocation::findOrFail($locationId)->addWeightClass($request->only(['weight_limit', 'price']));

        return response()->json([
            'id' => $weightclass->id,
            'shipping_location_id' => $weightclass->shipping_location_id,
            'weight_limit' => $weightclass->weight_limit,
            'price' => $weightclass->price
        ]);
    }

    public function updateWeightClass($classId, Request $request)
    {
        $this->validate($request, [
            'weight_limit' => 'required|integer|between:0,10000000',
            'price' => 'required|integer|between:0,10000000'
        ]);
        
        $weightClass = WeightClass::findOrFail($classId);
        $weightClass->update($request->only(['weight_limit', 'price']));
        
        return response()->json([
            'id' => $weightClass->id,
            'shipping_location_id' => $weightClass->shipping_location_id,
            'weight_limit' => $weightClass->weight_limit,
            'price' => $weightClass->price
        ]);
    }

    public function deleteWeightClass($classId)
    {
        WeightClass::findOrFail($classId)->delete();

        return response()->json(['success' => true]);
    }

    public function getFreeShippingPrice($locationId)
    {
        $location = ShippingLocation::findOrFail($locationId);

        return response()->json([
            'id' => $location->id,
            'free_shipping_above' => $location->free_shipping_above
        ]);
    }

    public function setFreeShippingPrice(Request $request, $locationId)
    {
        $this->validate($request, ['free_shipping_above' => 'required|integer|between:0,99999999']);

        $location = ShippingLocation::findOrFail($locationId);
        $location->free_shipping_above = $request->free_shipping_above;
        $location->save();

        return response()->json([
            'id' => $location->id,
            'free_shipping_above' => intval($location->free_shipping_above)
        ]);
    }

    public function removeFreeShippingPrice($locationId)
    {
        $location = ShippingLocation::findOrFail($locationId);
        $location->free_shipping_above = null;
        $success = $location->save();

        return response()->json(['success' => $success]);
    }


}
