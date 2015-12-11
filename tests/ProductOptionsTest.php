<?php
use App\Stock\Product;
use App\Stock\ProductOption;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 11/28/15
 * Time: 8:44 PM
 */
class ProductOptionsTest extends TestCase
{
    use DatabaseMigrations, AsLoggedInUser;

    /**
     * @test
     */
    public function a_product_option_can_be_added_to_a_product()
    {
        $product = factory(Product::class)->create();

        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/products/'.$product->id.'/options', [
            'name' => 'Ribbon Colour'
        ]);
        $this->assertEquals(200, $response->status());

        $this->assertEquals(1, $product->options->count(), 'product should have one option');
        $this->assertEquals('Ribbon Colour', $product->options->first()->name, 'option should have given name');
    }

    /**
     * @test
     */
    public function a_product_option_can_be_deleted()
    {
        $option = factory(ProductOption::class)->create();

        $this->withoutMiddleware();
        $response = $this->call('DELETE', '/admin/productoptions/'.$option->id);
        $this->assertEquals(200, $response->status());

        $this->notSeeInDatabase('product_options', [
            'id' => $option->id
        ]);
    }

}