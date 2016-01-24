<?php
use App\Stock\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 12/21/15
 * Time: 11:38 AM
 */
class ProductTagsTest extends TestCase
{
    use DatabaseMigrations, AsLoggedInUser;

    /**
     * @test
     */
    public function an_array_of_tags_can_be_associated_with_a_product()
    {
        $product = factory(Product::class)->create();
        $tagsArray = ['classic', 'handmade'];

        $product->setTags($tagsArray);

        $this->assertEquals($tagsArray, $product->getTagsList(), 'product should have tags');
    }

    /**
     * @test
     */
    public function a_products_tags_are_only_synced_so_passing_a_new_array_to_set_tags_removes_old_ones()
    {
        $product = factory(Product::class)->create();
        $oldTagsArray = ['classic', 'handmade', 'african'];
        $newTagsArray = ['classic', 'fresh'];

        $product->setTags($oldTagsArray);
        $this->assertEquals($oldTagsArray, $product->getTagsList(), 'product should have tags');

        $product = Product::findOrFail($product->id);

        $product->setTags($newTagsArray);
        $this->assertEquals($newTagsArray, $product->getTagsList(), 'tags are now only new ones');
    }

    /**
     * @test
     */
    public function get_tags_list_returns_an_empty_array_for_a_product_with_no_tags()
    {
        $product = factory(Product::class)->create();

        $this->assertEquals([], $product->getTagsList());
    }

    /**
     * @test
     */
    public function a_products_tags_can_be_set_via_api_endpoint_and_returns_new_tags_as_json()
    {
        $product = factory(Product::class)->create();
        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/products/'.$product->id.'/tags', [
            'tags' => ['classic', 'fresh']
        ]);
        $this->assertEquals(200, $response->status());
        $this->assertJson($response->getContent(), 'response should be json');
        $this->assertContains('"tags":["classic","fresh"]', $response->getContent());

        $product = Product::findOrFail($product->id);
        $this->assertEquals(['classic', 'fresh'], $product->getTagsList());
    }

    /**
     * @test
     */
    public function a_products_tags_can_be_fetched_via_api_endpoint()
    {
        $product = factory(Product::class)->create();
        $product->setTags(['classic', 'fresh']);

        $response = $this->call('GET', '/admin/products/'.$product->id.'/tags');

        $this->assertEquals(200, $response->status());
        $this->assertJson($response->getContent(), 'response should be json');
        $this->assertContains('"tags":["classic","fresh"]', $response->getContent());
    }

}