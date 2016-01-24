<?php
use App\Shipping\ShippingLocation;
use App\Shipping\ShippingService;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 1/4/16
 * Time: 9:31 AM
 */
class ShippingServiceTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_returns_an_array_of_shipping_prices_with_locations()
    {
        $this->setUpShippingLocations();

        $expected = [
            ['name' => 'United Kingdom', 'price' => 2500, 'location_id' => 1],
            ['name' => 'United States', 'price' => 3500, 'location_id' => 2],
            ['name' => 'International', 'price' => 4500, 'location_id' => 3]
        ];
        $shippingService = new ShippingService();
        $this->assertEquals($expected, $shippingService->quote(150, 2500));
    }

    protected function setUpShippingLocations()
    {
        $location1 = factory(ShippingLocation::class)->create(['name' => 'United Kingdom']);
        $location1->addWeightClass(['weight_limit' => 100, 'price' => 1500]);
        $location1->addWeightClass(['weight_limit' => 200, 'price' => 2500]);
        $location1->addWeightClass(['weight_limit' => 300, 'price' => 3500]);

        $location2 = factory(ShippingLocation::class)->create(['name' => 'United States']);
        $location2->addWeightClass(['weight_limit' => 100, 'price' => 2500]);
        $location2->addWeightClass(['weight_limit' => 200, 'price' => 3500]);
        $location2->addWeightClass(['weight_limit' => 300, 'price' => 4500]);

        $location3 = factory(ShippingLocation::class)->create(['name' => 'International']);
        $location3->addWeightClass(['weight_limit' => 100, 'price' => 3500]);
        $location3->addWeightClass(['weight_limit' => 200, 'price' => 4500]);
        $location3->addWeightClass(['weight_limit' => 300, 'price' => 5500]);
    }

}