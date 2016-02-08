<?php


use App\Stock\Category;
use App\Stock\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProductsTest extends TestCase
{
    use DatabaseMigrations, AsLoggedInUser, TestsImageUploads;

    /**
     * @test
     */
    public function a_product_cab_be_added_to_a_category()
    {
        $category = factory(Category::class)->create();
        $this->visit('/admin/categories/' . $category->id . '/products/create')
            ->submitForm('Add Product', [
                'name'        => 'reception book',
                'description' => 'a hand crafted book',
                'price'       => 15,
                'weight'      => 25
            ])->seeInDatabase('products', [
                'name'        => 'reception book',
                'category_id' => $category->id,
                'description' => 'a hand crafted book',
                'price'       => 1500,
                'weight'      => 25
            ]);
    }

    /**
     * @test
     */
    public function a_product_is_unavailable_by_default()
    {
        $product = factory(Product::class)->create();

        $this->assertFalse($product->available);
    }

    /**
     * @test
     */
    public function a_product_availability_can_be_set_by_posting_to_api_endpoint()
    {
        $product = factory(Product::class)->create();
        $this->assertFalse($product->available);

        $this->withoutMiddleware();
        $response = $this->call('POST', '/admin/api/products/'.$product->id.'/availability', ['available' => true]);
        $this->assertEquals(200, $response->status());

        $product = Product::findOrFail($product->id);
        $this->assertTrue($product->available);
    }

    /**
     * @test
     */
    public function a_product_name_description_and_price_can_be_edited()
    {
        $product = factory(Product::class)->create();

        $this->visit('/admin/products/' . $product->id . '/edit')
            ->type('book of mooz', 'name')
            ->type('a revered tome', 'description')
            ->type('12', 'price')
            ->press('Save Changes')
            ->seeInDatabase('products', [
                'id'          => $product->id,
                'name'        => 'book of mooz',
                'description' => 'a revered tome',
                'price'       => 1200
            ]);
    }

    /**
     * @test
     */
    public function a_product_can_be_deleted()
    {
        $product = factory(Product::class)->create();
        $this->withoutMiddleware();

        $response = $this->call('DELETE', '/admin/products/' . $product->id);
        $this->assertEquals(302, $response->status(), 'successful deletion should result in redirect');

        $this->notSeeInDatabase('products', [
            'id' => $product->id
        ]);
    }

    /**
     * @test
     */
    public function a_product_has_a_main_image_associated_with_it_and_which_can_be_uploaded()
    {
        $product = factory(Product::class)->create();
        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/uploads/products/' . $product->id . '/cover', [], [], [
            'file' => $this->prepareFileUpload('tests/testpic1.png', 'testpic1.png')
        ]);
        $this->assertEquals(200, $response->status());

        $this->assertContains('media', $product->coverPic(), 'the image is in the media collection');
        $this->assertContains('testpic1', $product->coverPic(), 'the correct image is associated with the product');

        $product->clearMediaCollection();
    }

    /**
     * @test
     */
    public function a_product_has_a_gallery_associated_with_it_on_creation()
    {
        $product = factory(Product::class)->create();

        $this->assertEquals(1, $product->galleries->count(), 'the product should have precisely one gallery');
    }

    /**
     * @test
     */
    public function products_can_searched_for_by_name_with_a_like_term()
    {
        factory(Product::class)->create(['name' => 'foobar']);
        factory(Product::class)->create(['name' => 'foobaz']);
        factory(Product::class)->create(['name' => 'bazbar']);

        $this->withoutMiddleware();
        $response = $this->call('GET', '/admin/api/products/search/foo');
        $this->assertEquals(200, $response->status());
        $this->assertCount(2, json_decode($response->getContent(), true), 'result should have 2 items');
        $this->assertContains('foobar', $response->getContent());
        $this->assertContains('foobaz', $response->getContent());
        $this->assertNotContains('bazbar', $response->getContent());
    }

}