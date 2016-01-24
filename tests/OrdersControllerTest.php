<?php
use App\Orders\Order;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 1/13/16
 * Time: 9:23 AM
 */
class OrdersControllerTest extends TestCase
{
    use DatabaseMigrations, AsLoggedInUser;

    /**
     * @test
     */
    public function an_order_can_be_marked_as_fulfilled_via_api_endpoint()
    {
        $order = factory(Order::class)->create();

        $this->withoutMiddleware();
        $response = $this->call('POST', '/admin/api/orders/'.$order->id.'/fulfill', [
            'fulfill' => true
        ]);
        $this->assertEquals(200, $response->status());

        $order = Order::findOrFail($order->id);
        $this->assertTrue($order->isFulfilled());
    }

    /**
     * @test
     */
    public function an_order_can_be_marked_as_unfulfilled_via_api_endpoint()
    {
        $order = factory(Order::class)->create();
        $order->fulfill();

        $this->withoutMiddleware();
        $response = $this->call('POST', '/admin/api/orders/'.$order->id.'/fulfill', [
            'fulfill' => false
        ]);

        $this->assertEquals(200, $response->status());

        $order = Order::findOrFail($order->id);
        $this->assertFalse($order->isFulfilled());
    }

    /**
     * @test
     */
    public function an_order_can_be_cancelled_via_api_endpoint()
    {
        $order = factory(Order::class)->create();

        $this->withoutMiddleware();
        $response = $this->call('POST', '/admin/api/orders/'.$order->id.'/cancel', [
            'cancel' => true
        ]);
        $this->assertEquals(200, $response->status());

        $order = Order::findOrFail($order->id);
        $this->assertTrue($order->isCancelled());

    }

    /**
     * @test
     */
    public function a_cancelled_order_can_be_restored_via_api_endpoint()
    {
        $order = factory(Order::class)->create();
        $order->cancel();

        $this->withoutMiddleware();
        $response = $this->call('POST', '/admin/api/orders/'.$order->id.'/cancel', [
            'cancel' => false
        ]);
        $this->assertEquals(200, $response->status());

        $order = Order::findOrFail($order->id);
        $this->assertFalse($order->isCancelled());

    }

}