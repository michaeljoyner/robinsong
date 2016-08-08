<?php
use App\Billing\ChargeResponse;
use App\Orders\Order;
use App\Stock\Product;
use App\Stock\StockUnit;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spinen\MailAssertions\MailTracking;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 2/8/16
 * Time: 9:21 AM
 */
class CustomerMailerTest extends TestCase
{
    use DatabaseMigrations, MailTracking;
    /**
     *@test
     */
    public function an_email_is_sent_to_the_customer_on_a_successful_payment()
    {
        $order = factory(Order::class)->create();
        $charge = new ChargeResponse('stripe', true, 'success', 1000, '12345');
        $order->setChargeResult($charge);

        $this->seeEmailTo($order->customer_email);
    }

    /**
     *@test
     */
    public function the_invoice_email_includes_all_items_and_total_price()
    {
        $product1 = factory(Product::class)->create();
        $unit1 = factory(StockUnit::class)->create(['product_id' => $product1->id]);
        $unit2 = factory(StockUnit::class)->create(['product_id' => $product1->id]);
        $order = factory(Order::class)->create(['customer_email' => 'joyner.michael@gmail.com']);
        $orderItem = $order->addItem($unit1->id, 1);
        $order->addItem($unit2->id, 1);
        $orderItem->addOption('colour', 'silver');
        $orderItem->addCustomisation('intro', 'we the people');
        $charge = new ChargeResponse('stripe', true, 'success', 1000, '12345');
        $order->setChargeResult($charge);

        $this->seeEmailContains($product1->name);
        $this->seeEmailContains('£10.00');
        $this->seeEmailContains('with customisations');
    }

    /**
     *@test
     */
    public function it_notifies_a_customer_when_an_order_is_marked_as_fulfilled()
    {
        $order = factory(Order::class)->create();
        $order->fulfill();

        $this->seeEmailTo($order->customer_email);
    }
}