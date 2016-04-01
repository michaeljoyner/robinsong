<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 3/22/16
 * Time: 9:51 AM
 */
class OrderItemTest extends TestCase
{
    use DatabaseMigrations;

    /**
     *@test
     */
    public function an_order_item_has_a_description_and_price_taken_from_its_associated_model()
    {
        $product = factory(\App\Stock\Product::class)->create(['name' => 'Super Gru', 'price' => 10]);
        $order = factory(\App\Orders\Order::class)->create();

        $orderItem = $order->addItem($product->id, 2);

        $this->seeInDatabase('order_items', [
            'id' => $orderItem->id,
            'description' => 'Super Gru',
            'price' => 1000
        ]);
    }
}