<?php
use App\Orders\Order;
use App\Orders\OrdersService;
use App\Stock\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 1/3/16
 * Time: 11:30 AM
 */
class OrdersServiceTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_can_create_an_order()
    {
        $formData = factory(Order::class)->make()->toArray();

        $orderService = new OrdersService($formData);
        $orderService->makeOrder();

        $this->assertCount(1, Order::all());
        $this->assertEquals($formData['customer_name'], Order::first()->customer_name);
    }

    /**
     * @test
     */
    public function it_creates_the_order_number_before_saving_to_database()
    {
        $formData = factory(Order::class)->make()->toArray();

        $orderService = new OrdersService($formData);

        $this->assertNotFalse($orderService->orderNumber());
        $this->assertContains(\Carbon\Carbon::now()->format('ymd'), $orderService->orderNumber());
        $this->assertCount(0, Order::all(), 'should be no orders in db');
    }

    /**
     * @test
     */
    public function it_commits_the_order_and_adds_all_cart_items()
    {
        $formData = factory(Order::class)->make()->toArray();
        $orderService = new OrdersService($formData);
        $this->fillCartWithTwoItems();
        $chargeResult = new \App\Billing\ChargeResponse('stripe', true, 'success', 1000, 123);
        $shippingInfo = ['location' => 'International', 'price' => 900];

        $orderService->commitOrder(Cart::content(), $chargeResult, $shippingInfo);

        $order = Order::findOrFail($orderService->orderId());

        $this->assertCount(2, $order->items);
        $this->assertEquals(2, $order->items->last()->quantity);
    }

    private function fillCartWithTwoItems()
    {
        factory(Product::class, 2)->create(['weight' => 25]);
        Cart::add(1, 'Wedding Book', 1, 25, [
            'choice options' => [
                'ribbon colour' => 'silver',
                'cover colour' => 'ivory'
            ],
            'text options' => [
                'names' => 'Jane and Jack',
                'wedding date' => '20 December 2003'
            ]
        ]);
        Cart::add(2, 'Baby Book', 2, 30, [
            'choice options' => [
                'ribbon colour' => 'blue',
                'cover colour' => 'white'
            ],
            'text options' => [
                'names' => 'Bobby',
                'birth date' => '20 December 2003'
            ]
        ]);
    }
}