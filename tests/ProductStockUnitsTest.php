<?php
use App\Stock\Product;
use App\Stock\StockUnit;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 8/6/16
 * Time: 12:31 PM
 */
class ProductStockUnitsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     *@test
     */
    public function a_product_has_stock_units()
    {
        $product = factory(Product::class)->create();
        factory(StockUnit::class, 2)->create(['product_id' => $product->id]);

        $this->assertCount(2, $product->stockUnits);
    }

    /**
     *@test
     */
    public function a_stock_unit_can_be_added_to_a_product()
    {
        $product = factory(Product::class)->create();

        $unit = $product->addStockUnit(['name' => 'pack of four', 'price' => 1000, 'weight' => 30]);

        $this->assertInstanceOf(StockUnit::class, $unit);
        $this->assertEquals('pack of four', $unit->name);
        $this->assertEquals(1000, $unit->price->inCents());
        $this->assertEquals(30, $unit->weight);
        $this->assertEquals($product->id, $unit->product->id);
    }

    /**
     *@test
     */
    public function a_product_knows_the_lowest_price_available_of_its_stock_units()
    {
        $product = factory(Product::class)->create();
        $product->addStockUnit(['name' => 'pack of five', 'price' => 5000, 'weight' => 30, 'available' => 1]);
        $product->addStockUnit(['name' => 'pack of four', 'price' => 4000, 'weight' => 30, 'available' => 1]);
        $product->addStockUnit(['name' => 'pack of six', 'price' => 6000, 'weight' => 30, 'available' => 1]);

        $this->assertEquals('40.00', $product->lowestPriceString());
    }
}