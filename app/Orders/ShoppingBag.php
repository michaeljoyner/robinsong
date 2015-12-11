<?php
/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 12/11/15
 * Time: 9:21 AM
 */

namespace App\Orders;


use App\Stock\Product;
use Illuminate\Support\Collection;

class ShoppingBag
{

    /**
     * @var Collection
     */
    private $items;

    public function __construct(Collection $items)
    {
        $this->items = $items;
    }

    public function shippingWeight()
    {
        if($this->items->isEmpty()) {
            return 0;
        }

        return Product::findOrFail($this->items->map(function($item) {
            return $item->id;
        })->toArray())->reduce(function($sum, $product) {
            return $sum + $product->weight;
        });


    }

}