<?php

namespace App\Shipping;

use Illuminate\Database\Eloquent\Model;

class ShippingLocation extends Model
{
    protected $table = 'shipping_locations';

    protected $fillable = ['name'];

    public function weightClasses()
    {
        return $this->hasMany(WeightClass::class, 'shipping_location_id');
    }

    public function addWeightClass($data)
    {
        return $this->weightClasses()->create($data);
    }

    public function shippingPrice($weight, $orderPrice)
    {
        $weightClasses = $this->weightClasses;
        if($weightClasses->count() < 1 || $this->qualifiesForFreeShipping($orderPrice)) {
            return 0;
        }

        return $weightClasses->reduce(function($carry, $item) use ($weight) {
            return ($item->weight_limit >= $weight) && ($carry == 0 || ($carry > $item->price)) ? $item->price : $carry;
        }, 0);
    }

    private function qualifiesForFreeShipping($orderPrice)
    {
        if((! is_null($this->free_shipping_above)) && ($orderPrice > $this->free_shipping_above)) {
            return true;
        }
        return false;
    }
}
