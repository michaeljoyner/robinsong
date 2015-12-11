<?php
use App\Stock\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 11/29/15
 * Time: 7:35 PM
 */
class ProductGalleryTest extends TestCase
{
    use DatabaseMigrations, AsLoggedInUser, TestsImageUploads;

    /**
     * @test
     */
    public function images_can_be_uploaded_to_a_gallery()
    {
        $product = factory(Product::class)->create();
        $gallery = $product->getGallery();

        $this->withoutMiddleware();
        $response = $this->call('POST', '/admin/uploads/galleries/'.$gallery->id.'/images', [], [], [
            'file' => $this->prepareFileUpload('tests/testpic1.png', 'testpic1.png')
        ]);
        $this->assertEquals(200, $response->status());
        $this->assertJson($response->getContent(), 'response should be json format');
        $this->assertContains('testpic1.png', $response->getContent(), 'response should contain url');

        $this->assertEquals(1, $gallery->getMedia()->count(), 'gallery should contain 1 image');

        $gallery->clearMediaCollection();
    }

    /**
     * @test
     */
    public function gallery_controller_can_return_a_given_galleries_images_in_json()
    {
        $product = factory(Product::class)->create();
        $gallery = $product->getGallery();

        $this->withoutMiddleware();
        $response = $this->call('POST', '/admin/uploads/galleries/'.$gallery->id.'/images', [], [], [
            'file' => $this->prepareFileUpload('tests/testpic1.png', 'testpic1.png')
        ]);
        $this->assertEquals(200, $response->status());

        $response2 = $this->call('POST', '/admin/uploads/galleries/'.$gallery->id.'/images', [], [], [
            'file' => $this->prepareFileUpload('tests/testpic2.png', 'testpic2.png')
        ]);
        $this->assertEquals(200, $response2->status());

        $response3 = $this->call('GET', '/admin/uploads/galleries/'.$gallery->id.'/images');
        $this->assertEquals(200, $response3->status());
        $this->assertJson($response3->getContent(), 'response should be json format');
        $this->assertContains('testpic1', $response3->getContent());
        $this->assertContains('testpic2', $response3->getContent());

        $gallery->clearMediaCollection();
    }

    /**
     * @test
     */
    public function getting_images_from_an_empty_gallery_returns_empty_json_array()
    {
        $product = factory(Product::class)->create();
        $gallery = $product->getGallery();

        $this->withoutMiddleware();
        $response = $this->call('GET', '/admin/uploads/galleries/'.$gallery->id.'/images');
        $this->assertEquals(200, $response->status());

        $this->assertEquals('[]', $response->getContent());

        $gallery->clearMediaCollection();
    }

    /**
     * @test
     */
    public function an_image_can_be_deleted_from_a_gallery()
    {
        $product = factory(Product::class)->create();
        $gallery = $product->getGallery();

        $this->withoutMiddleware();
        $response = $this->call('POST', '/admin/uploads/galleries/'.$gallery->id.'/images', [], [], [
            'file' => $this->prepareFileUpload('tests/testpic1.png', 'testpic1.png')
        ]);
        $this->assertEquals(200, $response->status());

        $response2 = $this->call('DELETE', '/admin/uploads/galleries/'.$gallery->id.'/images/'.$gallery->getMedia()[0]->id);
        $this->assertEquals(200, $response2->status());

        $this->assertEquals(0, $gallery->getMedia()->count(), 'gallery should now be empty');

        $gallery->clearMediaCollection();
    }
}