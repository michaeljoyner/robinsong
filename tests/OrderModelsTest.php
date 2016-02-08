<?php
use App\Orders\Order;
use App\Orders\OrderItem;
use App\Stock\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 1/3/16
 * Time: 10:49 AM
 */
class OrderModelsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function an_order_can_be_created()
    {
        $order = Order::create([
            'customer_name' => 'Mooz Joyner',
            'customer_email' => 'joe@example.com',
            'order_number' => 'mkidl89wes5',
            'address_line1' => '5 les de jager rd',
            'address_line2' => 'lincoln meade',
            'address_city' => 'pietermaritzburg',
            'address_state' => 'kzn',
            'address_zip' => '3201',
            'address_country' => 'South Africa'
        ]);

        $this->assertEquals('Mooz Joyner', $order->customer_name);
    }

    /**
     * @test
     */
    public function items_can_be_added_to_an_order()
    {
        $products = factory(Product::class, 2)->create();
        $order = factory(Order::class)->create();

        $order->addItem($products[0]->id, 2);
        $order->addItem($products[1]->id, 1);

        $this->assertCount(2, $order->items, 'Order should have two order items');
    }

    /**
     *@test
     */
    public function an_order_can_be_set_as_paid_by_passing_a_charge_response_to_the_pay_via_method()
    {
        $order = factory(Order::class)->create();
        $charge = new \App\Billing\ChargeResponse('stripe', true, 'success', 1000, '1234567');

        $order->setChargeResult($charge);

        $order = Order::findOrFail($order->id);

        $this->seeInDatabase('orders', [
            'id' => $order->id,
            'paid' => 1,
            'amount' => 1000,
            'charge_id' => '1234567'
        ]);
    }

    /**
     * @test
     */
    public function options_can_be_associated_with_an_order_item()
    {
        $orderItem = factory(OrderItem::class)->create();

        $orderItem->addOption('ribbon colour', 'blue');

        $this->assertCount(1, $orderItem->options);
        $this->assertEquals('blue', $orderItem->options->first()->value);
    }

    /**
     * @test
     */
    public function customisations_can_be_associated_with_an_order_item()
    {
        $orderItem = factory(OrderItem::class)->create();

        $orderItem->addCustomisation('Couple names', 'Jane and John');

        $this->assertCount(1, $orderItem->customisations);
        $this->assertEquals('Jane and John', $orderItem->customisations->first()->value);
    }

    /**
     * @test
     */
    public function an_order_is_not_fulfilled_by_default()
    {
        $order = factory(Order::class)->create();

        $this->assertFalse($order->isFulfilled());
    }

    /**
     * @test
     */
    public function an_order_can_be_fulfilled()
    {
        $order = factory(Order::class)->create();

        $order->fulfill();

        $this->assertTrue($order->isFulfilled());
    }

    /**
     * @test
     */
    public function an_order_can_be_set_from_fulfilled_to_unfulfilled()
    {
        $order = factory(Order::class)->create();
        $order->fulfill();

        $this->assertTrue($order->isFulfilled());

        $order->unfulfill();
        $this->assertFalse($order->isFulfilled());
    }

    /**
     * @test
     */
    public function an_order_can_be_marked_as_cancelled()
    {
        $order = factory(Order::class)->create();
        $this->assertFalse($order->isCancelled());

        $order->cancel();
        $this->assertTrue($order->isCancelled());
    }

    /**
     * @test
     */
    public function a_cancelled_order_can_be_reopened()
    {
        $order = factory(Order::class)->create();
        $order->cancel();
        $this->assertTrue($order->isCancelled());

        $order->restore();
        $this->assertFalse($order->isCancelled());
    }

}