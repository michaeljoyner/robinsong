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
        $product = factory(\App\Stock\Product::class)->create(['name' => 'Super Gru']);
        $unit = factory(\App\Stock\StockUnit::class)->create(['product_id' => $product->id]);
        $order = factory(\App\Orders\Order::class)->create();

        $orderItem = $order->addItem($unit->id, 2);

        $this->seeInDatabase('order_items', [
            'id' => $orderItem->id,
            'description' => 'Super Gru',
            'package' => $unit->name,
            'price' => $unit->price->inCents() * 2
        ]);
    }

    /**
     *@test
     */
    public function an_order_item_can_be_checked_if_it_has_customisations()
    {
        $blank = factory(\App\Orders\OrderItem::class)->create();

        $justOption = factory(\App\Orders\OrderItem::class)->create();
        $justOption->addOption('colour', 'blue');

        $justCustomisation = factory(\App\Orders\OrderItem::class)->create();
        $justCustomisation->addCustomisation('names', 'Jack and Jill');

        $both = factory(\App\Orders\OrderItem::class)->create();
        $both->addOption('size', 'large');
        $both->addCustomisation('intro', 'once upon a time');

        $this->assertFalse($blank->isCustomised());
        $this->assertTrue($justOption->isCustomised(), 'having at least one option is customised');
        $this->assertTrue($justCustomisation->isCustomised(), 'having at least one customisation is customised');
        $this->assertTrue($both->isCustomised(), 'both options and customisations is customised');
    }

    /**
     *@test
     */
    public function an_order_item_knows_if_its_options_and_customisations_are_empty()
    {
        $orderItem = factory(\App\Orders\OrderItem::class)->create();
        $orderItem->addOption('ribbon color', '');
        $orderItem->addOption('paper', '');
        $orderItem->addCustomisation('names', '');
        $orderItem->addCustomisation('dates', '');

        $this->assertFalse($orderItem->hasCustomisations());
    }

    /**
     * @test
     */
    public function an_order_item_knows_if_its_options_and_customisations_are_not_empty()
    {
        $orderItem = factory(\App\Orders\OrderItem::class)->create();
        $orderItem->addOption('ribbon color', '');
        $orderItem->addOption('paper', '');
        $orderItem->addCustomisation('names', 'bob and bill');
        $orderItem->addCustomisation('dates', '');

        $this->assertTrue($orderItem->hasCustomisations());
    }
}