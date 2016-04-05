<?php

namespace App\Http\Controllers\Admin;

use App\Orders\OrderItem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderItemsController extends Controller
{
    public function show($itemId)
    {
        $item = OrderItem::with('options', 'customisations', 'order')->findOrFail($itemId);

        return view('admin.orders.items.show')->with(compact('item'));
    }
}
