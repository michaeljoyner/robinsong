<?php
use App\Content\ContentRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 1/26/16
 * Time: 10:39 AM
 */
class ContentGalleriesTest extends TestCase
{
    use DatabaseMigrations;

    /**
     *@test
     */
    public function an_image_can_be_added_to_a_gallery()
    {
        $file = 'tests/testpic1.png';
        $page = ContentRepository::makePage('test');
        $gallery = $page->addGallery('test gallery', 'images');

        $gallery->addImage($file);

        $this->assertCount(1, $gallery->getMedia());

        $gallery->clearMediaCollection();
    }

    /**
     *@test
     */
    public function a_single_image_gallery_only_ever_has_one_image_which_is_the_latest_added()
    {
        $file = 'tests/testpic1.png';
        $file2 = 'tests/testpic2.png';
        $page = ContentRepository::makePage('test');
        $gallery = $page->addGallery('test gallery', 'images', true);

        $gallery->addImage($file);
        $gallery->addImage($file2);

        $this->assertCount(1, $gallery->getMedia(), 'gallery should only have one image');
        $this->assertContains('testpic2', $gallery->getMedia()->first()->getUrl());

        $gallery->clearMediaCollection();
    }

    /**
     * @test
     */
    public function a_default_image_of_the_galleries_first_image_src_can_be_fetched()
    {
        $page = ContentRepository::makePage('test');
        $gallery = $page->addGallery('test gallery', 'images', true);

        $this->assertEquals('/images/assets/default.png', $gallery->defaultSrc());
    }
}