<?php

namespace App\Orders;

use App\Stock\Product;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'product_id',
        'unit_id',
        'quantity',
        'description',
        'package',
        'price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function options()
    {
        return $this->hasMany(OrderItemOption::class, 'order_item_id');
    }

    public function addOption($name, $value)
    {
        return $this->options()->create(['name' => $name, 'value' => $value]);
    }

    public function customisations()
    {
        return $this->hasMany(OrderItemCustomisation::class, 'order_item_id');
    }

    public function addCustomisation($name, $value)
    {
        return $this->customisations()->create(['name' => $name, 'value' => $value]);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function isCustomised()
    {
        return $this->options->count() > 0 || $this->customisations->count() > 0;
    }

    public function hasCustomisations()
    {
        $options = $this->options->filter(function($option) {
            return $option->value;
        });
        $customisations = $this->customisations->filter(function($customisation) {
            return $customisation->value;
        });

        return !! ($options->count() > 0 || $customisations->count() > 0);
    }
}
