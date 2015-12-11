<?php
use Gloudemans\Shoppingcart\Facades\Cart;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 12/10/15
 * Time: 9:19 AM
 */
class ShoppingCartTest extends TestCase
{

    /**
     * @test
     */
    public function an_item_can_be_added_to_the_cart_via_ajax_endpoint()
    {
        $payload = file_get_contents('tests/additemdata.json');
        $this->withoutMiddleware();

        $response = $this->call('POST', '/api/cart', json_decode($payload, true));
        $this->assertEquals(200, $response->status());

        $this->assertEquals(2, Cart::count(), 'Cart should have two items');
        $this->assertEquals(1, Cart::count(false), 'Cart should have one row');
        $this->assertEquals(50, Cart::total(), 'The price of the cart item should match price given');
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

    private function fillCartWithTwoItems()
    {
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

}