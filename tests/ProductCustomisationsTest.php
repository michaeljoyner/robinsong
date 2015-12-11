<?php
use App\Stock\Customisation;
use App\Stock\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 11/29/15
 * Time: 2:03 PM
 */
class ProductCustomisationsTest extends TestCase
{
    use DatabaseMigrations, AsLoggedInUser;

    /**
     * @test
     */
    public function a_custom_field_can_be_associated_with_a_product()
    {
        $product = factory(Product::class)->create();

        $this->withoutMiddleware();
        $response = $this->call('POST', '/admin/products/'.$product->id.'/customisations', [
            'name' => 'Couple names'
        ]);
        $this->assertEquals(200, $response->status());

        $this->seeInDatabase('customisations', [
            'product_id' => $product->id,
            'name' => 'Couple names'
        ]);
    }

    /**
     * @test
     */
    public function the_custom_fields_for_a_product_can_be_fetched()
    {
        $product = factory(Product::class)->create();
        $customisations = factory(Customisation::class, 3)->create(['product_id' => $product->id]);

        $this->withoutMiddleware();

        $response = $this->call('GET', '/admin/products/'.$product->id.'/customisations');
        $this->assertEquals(200, $response->status());

        $this->assertContains($customisations[0]->name, $response->getContent(), 'should have first name');
        $this->assertContains($customisations[1]->name, $response->getContent(), 'should have second name');
        $this->assertContains($customisations[2]->name, $response->getContent(), 'should have third name');

    }

    /**
     * @test
     */
    public function a_custom_field_can_be_deleted()
    {
        $customisation = factory(Customisation::class)->create();

        $this->withoutMiddleware();
        $response = $this->call('DELETE', '/admin/customisations/'.$customisation->id);
        $this->assertEquals(200, $response->status());

        $this->notSeeInDatabase('customisations', [
            'id' => $customisation->id
        ]);
    }

}