<?php
use App\Shipping\ShippingLocation;
use App\Shipping\WeightClass;
use App\Stock\Product;
use App\Stock\StockUnit;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 12/10/15
 * Time: 9:19 AM
 */
class ShoppingCartTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * @test
     */
    public function an_item_can_be_added_to_the_cart_via_ajax_endpoint()
    {
        $product = factory(StockUnit::class)->create();
        $this->withoutMiddleware();

        $response = $this->call('POST', '/api/cart', [
            'unit_id' => $product->id,
            'quantity' => 2,
            'options' => []
        ]);

        $this->assertEquals(200, $response->status());
        $this->assertEquals(1, Cart::count(false), 'Cart should have one row');
        $this->assertEquals(2, Cart::count(), 'Cart should have two items');
    }

    /**
     *@test
     */
    public function a_stock_unit_can_be_added_to_the_cart_via_endpoint()
    {
        $unit = factory(StockUnit::class)->create();
        $this->withoutMiddleware();

        $response = $this->call('POST', '/api/cart', [
            'unit_id' => $unit->id,
            'quantity' => 1,
            'options' => []
        ]);
        $this->assertEquals(200, $response->status());
        $this->assertEquals(1, Cart::count(false), 'Cart should have one row');
        $this->assertEquals($unit->product->name . ' - ' . $unit->name, Cart::content()->first()->name);

    }

    /**
     * @test
     */
    public function the_carts_items_can_be_retrieved_via_ajax_call()
    {
        $this->fillCartWithTwoItems();

        $response = $this->call('GET', 'api/cart');
        $this->assertEquals(200, $response->status());

        $this->assertContains('rowid', $response->getContent());
        $this->assertContains('"name":"Wedding Book"', $response->getContent(), 'Should have wedding book');
        $this->assertContains('"name":"Baby Book"', $response->getContent(), 'Should have baby book');
        $this->assertEquals(2, count(json_decode($response->getContent(), true)), 'response should be array of two items');
    }

    /**
     * @test
     */
    public function an_items_quantity_can_be_updated_via_ajax_call()
    {
        $this->fillCartWithTwoItems();
        $item = Cart::content()->first();

        $this->assertEquals(1, $item->qty, 'Item has initial qty of 1');
        $initialId = $item->rowid;

        $this->withoutMiddleware();
        $response = $this->call('POST', '/api/cart/'.$item->rowid, [
            'rowid' => $item->rowid,
            'qty' => 3
        ]);
        $this->assertEquals(200, $response->status());

        $updatedItem = Cart::content()->first();
        $this->assertEquals(3, $updatedItem->qty, 'Item should have qty of 3');
        $this->assertEquals($initialId, $updatedItem->rowid, 'Should be same item with same row id');
    }

    /**
     * @test
     */
    public function an_item_can_be_removed_from_the_cart_via_ajax_call()
    {
        $this->fillCartWithTwoItems();
        $item = Cart::content()->first();
        $rowid = $item->rowid;

        $this->withoutMiddleware();
        $response = $this->call('DELETE', '/api/cart/'.$item->rowid);
        $this->assertEquals(200, $response->status());

        $this->assertEquals(1, Cart::count(false), 'cart should only have one item now');
        $this->assertFalse(Cart::content()->contains('rowid', $rowid), 'item should not be in collection anymore');
    }

    /**
     * @test
     */
    public function a_summary_of_the_cart_info_can_be_retrieved_as_json()
    {
        $this->fillCartWithTwoItems();
        $this->setUpShippingLocationsandWeights();

        $response = $this->call('GET', '/api/cart/summary');
        $this->assertEquals(200, $response->status());
        $this->assertJson($response->getContent());

        $result = json_decode($response->getContent(), true);
        $this->assertContains('"item_count":2', $response->getContent(), 'should have summary of number of items');
        $this->assertContains('"product_count":2', $response->getContent(), 'should have summary of number of products');
        $this->assertEquals(55, $result['total_price'], 'should have correct total price');
    }

    /**
     * @test
     */
    public function shipping_prices_for_the_carts_content_can_be_fetched_via_api_endpoint()
    {
        $this->fillCartWithTwoItems();
        $this->setUpShippingLocationsandWeights();

        $response = $this->call('GET', '/api/cart/shipping');
        $this->assertEquals(200, $response->status());
        $this->assertJson($response->getContent());

        $expected = [
            ['name' => 'United Kingdom', 'price' => 700, 'location_id' => 1],
            ['name' => 'International', 'price' => 1100, 'location_id' => 2]
        ];
        $this->assertEquals($expected, json_decode($response->getContent(), true));
    }

    /**
     * @test
     */
    public function it_calculates_by_the_correct_weight_when_there_is_multilpe_quantities_of_a_product()
    {
        $this->fillCartWithTwoItems();
        foreach(range(1,3) as $index) {
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
        }

        $this->setUpShippingLocationsandWeights();

        $expectedShippingInfo = [
            ['name' => 'United Kingdom', 'price' => 1000, 'location_id' => 1],
            ['name' => 'International', 'price' => 1500, 'location_id' => 2]
        ];

        $response = $this->call('GET', '/api/cart/shipping');
        $this->assertEquals(200, $response->status());

        $this->assertEquals($expectedShippingInfo, json_decode($response->getContent(), true));
    }

    /**
     * @test
     */
    public function the_cart_can_be_emptied_via_api_endpoint()
    {
        $this->fillCartWithTwoItems();

        $response = $this->call('GET', '/api/cart/empty');
        $this->assertEquals(200, $response->status());
        $this->assertEquals(0, Cart::count());
    }

    private function fillCartWithTwoItems()
    {
        factory(StockUnit::class, 2)->create(['weight' => 25]);
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
        Cart::add(2, 'Baby Book', 1, 30, [
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

    private function setUpShippingLocationsandWeights()
    {
        $location = factory(ShippingLocation::class)->create(['name' => 'United Kingdom']);
        $location2 = factory(ShippingLocation::class)->create(['name' => 'International']);

        factory(WeightClass::class)->create(['weight_limit' => 100, 'price' => 700, 'shipping_location_id' => $location->id]);
        factory(WeightClass::class)->create(['weight_limit' => 200, 'price' => 1000, 'shipping_location_id' => $location->id]);

        factory(WeightClass::class)->create(['weight_limit' => 100, 'price' => 1100, 'shipping_location_id' => $location2->id]);
        factory(WeightClass::class)->create(['weight_limit' => 200, 'price' => 1500, 'shipping_location_id' => $location2->id]);

        ShippingLocation::all()->each(function($item) {
            if($item->id > 2) {
                $item->delete();
            }
        });
    }

}