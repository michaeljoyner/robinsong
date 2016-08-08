<?php
use App\Stock\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 8/8/16
 * Time: 10:44 AM
 */
class ProductClonesControllerTest extends TestCase
{
    use DatabaseMigrations, AsLoggedInUser;

    /**
     *@test
     */
    public function a_product_can_be_cloned_via_posting_form()
    {
        $product = factory(Product::class)->create();
        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/products/' . $product->id . '/clones', [
            'name' => 'Dolly the Sheep'
        ]);
        $this->assertEquals(302, $response->status());

        $this->seeInDatabase('products', [
            'name' => 'Dolly the Sheep',
            'category_id' => $product->category->id
        ]);
    }
}