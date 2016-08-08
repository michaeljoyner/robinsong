<?php
use App\Stock\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 8/8/16
 * Time: 9:47 AM
 */
class ProductCloningTest extends TestCase
{
    use DatabaseMigrations;

    /**
     *@test
     */
    public function a_product_can_be_cloned_with_a_given_new_name()
    {
        $original = factory(Product::class)->create(['name' => 'Original Sin', 'description' => 'a product', 'writeup' => '']);

        $clone = $original->cloneAs('Dolly the Sheep');

        $this->assertEquals('Original Sin', $original->name);
        $this->assertEquals('Dolly the Sheep', $clone->name);
        $this->assertEquals($original->description, $clone->description);
        $this->assertEquals($original->writeup, $clone->writeup);
        $this->assertEquals($original->category->id, $clone->category->id);
    }

    /**
     *@test
     */
    public function a_cloned_product_is_always_initially_unavailable()
    {
        $original = factory(Product::class)->create(['available' => 1]);
        $clone = $original->cloneAs('Dolly');

        $this->assertTrue($original->available);
        $this->assertFalse($clone->available);
    }

    /**
     *@test
     */
    public function a_cloned_product_has_the_originals_stock_units()
    {
        $names = ['pack of four', 'pack of five', 'pack of six'];
        $original = factory(Product::class)->create();
        $original->addStockUnit(['name' => 'pack of four', 'price' => 1000, 'weight' => 30]);
        $original->addStockUnit(['name' => 'pack of five', 'price' => 2000, 'weight' => 40]);
        $original->addStockUnit(['name' => 'pack of six', 'price' => 3000, 'weight' => 50]);

        $clone = $original->cloneAs('Dolly');

        $this->assertCount(3, $clone->stockUnits);
        $clone->stockUnits->each(function($unit) use ($names) {
           $this->assertTrue(in_array($unit->name, $names));
        });
    }

    /**
     *@test
     */
    public function a_cloned_product_has_the_originals_options_with_values()
    {
        $colours = ['red', 'blue', 'green'];
        $papers = ['blotting', 'tracing', 'papyrus'];

        $original = factory(Product::class)->create();
        $ribbon_option = $original->addOption('ribbon colour');
        $paper_option = $original->addOption('paper');

        $ribbon_option->addValue('red');
        $ribbon_option->addValue('blue');
        $ribbon_option->addValue('green');

        $paper_option->addValue('blotting');
        $paper_option->addValue('tracing');
        $paper_option->addValue('papyrus');

        $clone = $original->cloneAs('Dolly');

        $this->assertCount(2, $clone->options);
        $this->assertEquals('ribbon colour', $clone->options->first()->name);
        $this->assertEquals('paper', $clone->options->last()->name);

        $this->assertCount(3, $clone->options->first()->values);
        $clone->options->first()->values->each(function($value) use ($colours) {
           $this->assertTrue(in_array($value->name, $colours));
        });
    }

    /**
     *@test
     */
    public function a_cloned_product_has_the_originals_customisation_options()
    {
        $product = factory(Product::class)->create();
        $product->addCustomisation('names');
        $product->addCustomisation('story', true);
        $product->addCustomisation('dates');

        $clone = $product->cloneAs('Dolly');

        $this->assertCount(3, $clone->customisations);
        $this->assertEquals('names', $clone->customisations->first()->name);
        $this->assertEquals('story', $clone->customisations[1]->name);
        $this->assertEquals('dates', $clone->customisations->last()->name);

        $this->assertFalse($clone->customisations->first()->longform);
        $this->assertTrue($clone->customisations[1]->longform);
        $this->assertFalse($clone->customisations->last()->longform);
    }
}