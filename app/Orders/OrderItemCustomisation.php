<?php

namespace App\Orders;

use Illuminate\Database\Eloquent\Model;

class OrderItemCustomisation extends Model
{
    protected $table = 'order_item_customisations';

    protected $fillable = [
        'name',
        'value'
    ];
}
