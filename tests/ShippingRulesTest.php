<?php
use App\Shipping\ShippingLocation;
use App\Shipping\WeightClass;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 12/16/15
 * Time: 8:39 AM
 */
class ShippingRulesTest extends TestCase
{
    use DatabaseMigrations, AsLoggedInUser;

    /**
     * @test
     */
    public function a_shipping_rule_location_can_be_added()
    {
        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/shipping/locations', [
            'name' => 'Mesopetamia'
        ]);
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('shipping_locations', [
            'name' => 'Mesopetamia'
        ]);
    }

    /**
     * @test
     */
    public function a_shipping_rule_location_name_can_be_edited()
    {
        $location = factory(ShippingLocation::class)->create();
        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/shipping/locations/' . $location->id, [
            'name' => 'Mooztown'
        ]);
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('shipping_locations', [
            'id'   => $location->id,
            'name' => 'Mooztown'
        ]);
    }

    /**
     * @test
     */
    public function a_shipping_rule_location_can_be_deleted()
    {
        $location = factory(ShippingLocation::class)->create();
        $this->withoutMiddleware();

        $response = $this->call('DELETE', '/admin/shipping/locations/' . $location->id);

        $this->assertEquals(200, $response->status());
        $this->notSeeInDatabase('shipping_locations', [
            'id' => $location->id
        ]);
    }

    /**
     * @test
     */
    public function a_weight_class_can_be_added_to_a_shipping_location()
    {
        $location = factory(ShippingLocation::class)->create();
        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/shipping/locations/' . $location->id . '/weightclasses', [
            'weight_limit' => 200,
            'price'        => 1000
        ]);

        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('weight_classes', [
            'shipping_location_id' => $location->id,
            'weight_limit'         => 200,
            'price'                => 1000
        ]);
    }

    /**
     * @test
     */
    public function a_weight_classes_weight_limit_and_price_can_be_updated()
    {
        $class = factory(WeightClass::class)->create();
        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/shipping/weightclasses/' . $class->id, [
            'weight_limit' => 919,
            'price'        => 919
        ]);

        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('weight_classes', [
            'id'           => $class->id,
            'weight_limit' => 919,
            'price'        => 919
        ]);
    }

    /**
     * @test
     */
    public function a_weight_class_can_be_deleted()
    {
        $class = factory(WeightClass::class)->create();
        $this->withoutMiddleware();

        $response = $this->call('DELETE', '/admin/shipping/weightclasses/' . $class->id);

        $this->assertEquals(200, $response->status());
        $this->notSeeInDatabase('weight_classes', [
            'id' => $class->id
        ]);
    }

    /**
     * @test
     */
    public function all_shipping_locations_can_be_fetched_and_returned_as_json()
    {
        $locations = factory(ShippingLocation::class, 3)->create();
        $this->withoutMiddleware();

        $response = $this->call('GET', '/admin/shipping/locations');

        $this->assertEquals(200, $response->status());
        $this->assertJson($response->getContent(), 'response should be json');
        $this->assertContains('"id":"'.$locations->first()->id.'"', $response->getContent());
        $this->assertContains('"name":"'.$locations->first()->name.'"', $response->getContent());
        $this->assertContains('"id":"'.$locations->last()->id.'"', $response->getContent());
        $this->assertContains('"name":"'.$locations->last()->name.'"', $response->getContent());
        $this->assertEquals(3, count(json_decode($response->getContent(), true)), 'should be three results');
    }

    /**
     * @test
     */
    public function a_locations_weight_classes_can_be_fetched_and_returned_as_json()
    {
        $location = factory(ShippingLocation::class)->create();
        $classes = factory(WeightClass::class, 5)->create(['shipping_location_id' => $location->id]);
        $this->withoutMiddleware();
        
        $response = $this->call('GET', '/admin/shipping/locations/'.$location->id.'/weightclasses');

        $this->assertEquals(200, $response->status());
        $this->assertJson($response->getContent(), 'response should be json');
        $this->assertContains('"id":"'.$classes->first()->id.'"', $response->getContent());
        $this->assertContains('"shipping_location_id":"'.$classes->first()->shipping_location_id.'"', $response->getContent());
        $this->assertContains('"weight_limit":"'.$classes->first()->weight_limit.'"', $response->getContent());
        $this->assertContains('"price":"'.$classes->first()->price.'"', $response->getContent());
        $this->assertContains('"id":"'.$classes->last()->id.'"', $response->getContent());
        $this->assertContains('"shipping_location_id":"'.$classes->last()->shipping_location_id.'"', $response->getContent());
        $this->assertContains('"weight_limit":"'.$classes->last()->weight_limit.'"', $response->getContent());
        $this->assertContains('"price":"'.$classes->last()->price.'"', $response->getContent());
        $this->assertEquals(5, count(json_decode($response->getContent(), true)), 'should be five results');
    }

    /**
     * @test
     */
    public function a_shipping_location_can_be_assigned_a_price_above_which_shipping_is_free()
    {
        $location = factory(ShippingLocation::class)->create();
        $this->assertNull($location->free_shipping_above);

        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/shipping/locations/'.$location->id.'/setfreeprice', [
            'free_shipping_above' => 5000
        ]);

        $this->assertEquals(200, $response->status());
        $this->assertJson($response->getContent(), 'response body should be json');
        $this->assertContains('"free_shipping_above":5000', $response->getContent());
        $this->seeInDatabase('shipping_locations', [
            'id' => $location->id,
            'free_shipping_above' => 5000
        ]);
    }

    /**
     * @test
     */
    public function a_locations_free_price_can_be_fetched_and_returned_as_json()
    {
        $location = factory(ShippingLocation::class)->create(['free_shipping_above' => 4300]);

        $response = $this->call('GET', '/admin/shipping/locations/'.$location->id.'/getfreeprice');

        $this->assertEquals(200, $response->status());
        $this->assertJson($response->getContent());
        $this->assertContains('"free_shipping_above":"4300"', $response->getContent());
    }

    /**
     * @test
     */
    public function a_locations_free_shipping_price_can_be_reset_to_null()
    {
        $location = factory(ShippingLocation::class)->create(['free_shipping_above' => 4300]);
        $this->withoutMiddleware();

        $response = $this->call('DELETE', '/admin/shipping/locations/'.$location->id.'/removefreeprice');

        $this->assertEquals(200, $response->status());
        $this->assertNull(ShippingLocation::findOrFail($location->id)->free_shipping_above);
    }

}