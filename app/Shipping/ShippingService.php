<?php
/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 1/4/16
 * Time: 9:41 AM
 */

namespace App\Shipping;


class ShippingService
{

    public function quote($weight, $orderPrice)
    {
        return ShippingLocation::all()->map(function($item) use ($weight, $orderPrice) {
            return [
                'name' => $item->name,
                'price' => $item->shippingPrice($weight, $orderPrice),
                'location_id' => $item->id
            ];
        })->toArray();
    }
}