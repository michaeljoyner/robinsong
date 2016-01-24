<?php

namespace App\Orders;

use Illuminate\Database\Eloquent\Model;

class OrderItemOption extends Model
{
    protected $table = 'order_item_options';

    protected $fillable = [
        'name',
        'value'
    ];
}
