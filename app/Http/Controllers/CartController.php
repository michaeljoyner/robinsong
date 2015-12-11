<?php

namespace App\Http\Controllers;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function addItem(Request $request)
    {
        $item = Cart::add($request->id, $request->name, $request->quantity, $request->price, $request->options);

        return response()->json($item);
    }

    public function getCartItems()
    {
        $contents = Cart::content()->toArray();

        return response()->json($contents);
    }

    public function updateItemQuantity($rowid, Request $request)
    {
        $result = Cart::update($rowid, $request->qty);

        if($result) {
            return response()->json('ok');
        }

        return response('failed to update', 500);
    }

    public function deleteItem($rowid)
    {
        Cart::remove($rowid);

        return response()->json('ok');
    }
}
