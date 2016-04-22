<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 4/22/16
 * Time: 6:48 AM
 */
class ProductWriteupTest extends TestCase
{

    use DatabaseMigrations, AsLoggedInUser;

    /**
     *@test
     */
    public function a_product_can_have_a_writeup()
    {
        $product = factory(\App\Stock\Product::class)->create();

        $this->assertArrayHasKey('writeup', $product->attributesToArray());
    }

    /**
     *@test
     */
    public function a_product_writeup_can_be_updated_by_posting_to_an_endpoint()
    {
        $product = factory(\App\Stock\Product::class)->create();

        $this->withoutMiddleware();
        $response = $this->call('POST', 'admin/products/'.$product->id.'/writeup', [
            'writeup' => 'This is a super cool product'
        ]);
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('products', [
            'id' => $product->id,
            'writeup' => 'This is a super cool product'
        ]);
    }

    /**
     *@test
     */
    public function a_product_knows_if_it_has_a_writeup()
    {
        $product = factory(\App\Stock\Product::class)->create();
        $this->assertTrue($product->hasWriteup());

        $product->writeup = '';
        $this->assertFalse($product->hasWriteup());

        $product->writeup = null;
        $this->assertFalse($product->hasWriteup());

    }

}