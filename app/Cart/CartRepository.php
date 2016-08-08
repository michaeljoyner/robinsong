<?php
/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 1/2/16
 * Time: 4:26 PM
 */

namespace App\Cart;


use App\Stock\Product;
use App\Stock\StockUnit;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartRepository
{
    public function addProductToCart(StockUnit $unit, $qty, $options)
    {
        Cart::add(
            $unit->id,
            $unit->product->name . ' - ' . $unit->name,
            $qty,
            $unit->price->inCents(),
            array_merge($options, [
            'weight' => $unit->weight,
            'thumbnail' => $unit->product->coverPic('thumb')
        ]));
    }

    public function updateProductQty($rowId, $quantity)
    {
        return Cart::update($rowId, $quantity);
    }

    public function cartContents($asSimpleArray = false)
    {
        return $asSimpleArray ? array_values(Cart::content()->toArray()) : Cart::content();
    }

    public function itemCount()
    {
        return Cart::count();
    }

    public function productCount()
    {
        return Cart::count(false);
    }

    public function totalPrice()
    {
        return Cart::total();
    }

    public function weightOfItemsInCart()
    {
        return $this->getTotalWeightForCartContents($this->cartContents());
    }

    public function deleteRow($rowId)
    {
        return Cart::remove($rowId);
    }

    public function clearCart()
    {
        return Cart::destroy();
    }

    private function getTotalWeightForCartContents($content)
    {
        $productIds = $content->map(function($item) {
            return $item->id;
        })->toArray();

        $idToWeights = StockUnit::findOrFail($productIds)->reduce(function($array, $product) {
            $array[$product->id] = $product->weight;
            return $array;
        }, []);

        return $content->reduce(function($carry, $item) use ($idToWeights) {
            return $carry + ($item->qty * $idToWeights[$item->id]);
        }, 0);
    }
}