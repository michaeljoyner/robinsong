<?php
use App\Stock\Product;
use App\Stock\StockUnit;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 8/6/16
 * Time: 1:21 PM
 */
class StockUnitsControllerTest extends TestCase
{
    use DatabaseMigrations, AsLoggedInUser;

    /**
     *@test
     */
    public function a_stock_unit_can_be_created_for_a_given_product()
    {
        $product = factory(Product::class)->create();
        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/products/' . $product->id . '/stockunits', [
            'name' => 'pack of four',
            'price' => '4.95',
            'weight' => 30
        ]);
        $this->assertEquals(302, $response->status());

        $this->assertEquals('pack of four', $product->stockUnits->first()->name);
    }

    /**
     *@test
     */
    public function an_existing_stock_unit_can_be_edited()
    {
        $unit = factory(StockUnit::class)->create();
        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/stockunits/' . $unit->id, [
            'name' => 'pack of four (updated)',
            'price' => '4.95',
            'weight' => 33
        ]);
        $this->assertEquals(200, $response->status());

        $this->seeInDatabase('stock_units', [
            'id' => $unit->id,
            'name' => 'pack of four (updated)',
            'price' => 495,
            'weight' => 33
        ]);

    }

    /**
     *@test
     */
    public function an_existing_stock_unit_can_be_deleted()
    {
        $unit = factory(StockUnit::class)->create();
        $this->withoutMiddleware();

        $response = $this->call('DELETE', '/admin/stockunits/' . $unit->id);
        $this->assertEquals(200, $response->status());

        $unit = StockUnit::withTrashed()->find($unit->id);
        $this->assertTrue($unit->trashed());

    }

    /**
     *@test
     */
    public function an_existing_stock_units_availability_can_be_set_via_endpoint()
    {
        $unit = factory(StockUnit::class)->create();
        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/stockunits/' . $unit->id . '/availability', [
            'available' => true
        ]);
        $this->assertEquals(200, $response->status());

        $unit = StockUnit::find($unit->id);
        $this->assertTrue($unit->available);
    }
}