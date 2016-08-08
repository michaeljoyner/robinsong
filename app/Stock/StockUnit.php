<?php

namespace App\Stock;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockUnit extends Model
{
    use SoftDeletes;

    protected $table = 'stock_units';

    protected $fillable = [
        'name',
        'price',
        'weight',
        'available'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = ['available' => 'boolean'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getPriceAttribute($priceValue)
    {
        return Price::fromCents($priceValue);
    }


    public function setAvailability($isAvailable)
    {
        $this->available = $isAvailable;
        $this->save();

        return $this->available;
    }
}
