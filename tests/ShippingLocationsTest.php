<?php
use App\Shipping\ShippingLocation;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 1/4/16
 * Time: 8:51 AM
 */
class ShippingLocationsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function a_shipping_location_with_no_weight_classes_will_return_shipping_price_of_zero()
    {
        $location = factory(ShippingLocation::class)->make();
        $this->assertCount(0, $location->weightClasses);

        $this->assertEquals(0, $location->shippingPrice(50, 2000));
    }

    /**
     * @test
     */
    public function given_appropriate_weight_classes_it_returns_the_correct_shipping_price()
    {
        $location = factory(ShippingLocation::class)->create();
        $location->addWeightClass(['weight_limit' => 100, 'price' => 1500]);
        $location->addWeightClass(['weight_limit' => 200, 'price' => 2500]);
        $location->addWeightClass(['weight_limit' => 300, 'price' => 3500]);

        $this->assertEquals(2500, $location->shippingPrice(150, 2000));
    }

    /**
     * @test
     */
    public function it_returns_the_correct_shipping_price_when_weight_is_on_cusp_of_limit()
    {
        $location = factory(ShippingLocation::class)->create();
        $location->addWeightClass(['weight_limit' => 100, 'price' => 1500]);
        $location->addWeightClass(['weight_limit' => 200, 'price' => 2500]);
        $location->addWeightClass(['weight_limit' => 300, 'price' => 3500]);

        $this->assertEquals(2500, $location->shippingPrice(200, 2000));
    }

    /**
     * @test
     */
    public function given_unordered_weight_classes_it_still_gives_the_correct_shipping_price()
    {
        $location = factory(ShippingLocation::class)->create();
        $location->addWeightClass(['weight_limit' => 400, 'price' => 5500]);
        $location->addWeightClass(['weight_limit' => 200, 'price' => 2500]);
        $location->addWeightClass(['weight_limit' => 100, 'price' => 1500]);
        $location->addWeightClass(['weight_limit' => 300, 'price' => 3500]);

        $this->assertEquals(2500, $location->shippingPrice(150, 2000));
    }

    /**
     * @test
     */
    public function given_an_order_price_greater_than_the_free_above_limit_shipping_is_free()
    {
        $location = factory(ShippingLocation::class)->create(['free_shipping_above' => 2000]);
        $location->addWeightClass(['weight_limit' => 100, 'price' => 1500]);
        $location->addWeightClass(['weight_limit' => 200, 'price' => 2500]);
        $location->addWeightClass(['weight_limit' => 300, 'price' => 3500]);

        $this->assertEquals(0, $location->shippingPrice(150, 2100));
    }

}