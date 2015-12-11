<?php
use App\Orders\ShoppingBag;
use App\Stock\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery as m;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 12/11/15
 * Time: 9:12 AM
 */
class ShoppingBagTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * @test
     */
    public function it_has_no_weight_when_it_is_empty()
    {
        $shoppingBag = new ShoppingBag(collect([]));

        $this->assertEquals(0, $shoppingBag->shippingWeight());
    }

    /**
     * @test
     */
    public function given_two_shippable_items_of_10g_each_it_has_a_shipping_weight_of_20g()
    {
        $item1 = $this->makeItem(1, 10);
        $item2 = $this->makeItem(2, 10);

        $shoppingBag = new ShoppingBag(collect([$item1, $item2]));

        $this->assertEquals(20, $shoppingBag->shippingWeight(), 'should have shipping weight of 20g');
    }

    private function makeItem($id, $weight)
    {
        factory(Product::class, 2)->create(['weight' => 10]);
        $item = new stdClass();
        $item->id = $id;
        $item->options = ['weight' => $weight];
        return $item;
    }
}