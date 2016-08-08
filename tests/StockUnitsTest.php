<?php
use App\Stock\StockUnit;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 8/6/16
 * Time: 1:15 PM
 */
class StockUnitsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     *@test
     */
    public function a_stock_units_availability_can_be_set()
    {
        $unit = factory(StockUnit::class)->create();
        $this->assertFalse($unit->available);

        $unit->setAvailability(true);
        $this->assertTrue($unit->available);
    }
}