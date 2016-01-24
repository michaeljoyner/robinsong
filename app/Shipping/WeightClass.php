<?php

namespace App\Shipping;

use Illuminate\Database\Eloquent\Model;

class WeightClass extends Model
{
    protected $table = 'weight_classes';

    protected $fillable = [
        'weight_limit',
        'price'
    ];

    public function forLocation()
    {
        return $this->belongsTo(ShippingLocation::class, 'shipping_location_id');
    }
}
