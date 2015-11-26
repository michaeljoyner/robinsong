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
                'price'       => 1500
            ])->seeInDatabase('products', [
                'name'        => 'reception book',
                'category_id' => $category->id,
                'description' => 'a hand crafted book',
                'price'       => 1500
            ]);
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
            ->type('1200', 'price')
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

        $response = $this->call('DELETE', '/admin/products/'.$product->id);
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

        $response = $this->call('POST', '/admin/uploads/products/'.$product->id.'/cover', [], [], [
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
    public function images_can_be_uploaded_to_a_product_gallery()
    {
        $product = factory(Product::class)->create();
        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/uploads/products/'.$product->id.'/gallery', [], [], [
            'file' => $this->prepareFileUpload('tests/testpic1.png', 'testpic1.png')
        ]);
        $this->assertEquals(200, $response->status());
        $response = $this->call('POST', '/admin/uploads/products/'.$product->id.'/gallery', [], [], [
            'file' => $this->prepareFileUpload('tests/testpic2.png', 'testpic2.png')
        ]);
        $this->assertEquals(200, $response->status());

        $this->assertEquals(2, $product->galleryImages()->count(), 'gallery should have 2 images');
        $this->assertContains('testpic1', $product->galleryImages()[0]->getUrl());
        $this->assertContains('testpic2', $product->galleryImages()[1]->getUrl());

        $product->galleries()->first()->clearMediaCollection();
    }
}