<?php
use App\Content\Page;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 1/30/16
 * Time: 2:03 PM
 */
class EdiblesControllerTest extends TestCase
{

    use DatabaseMigrations, AsLoggedInUser, TestsImageUploads;

    /**
     *@test
     */
    public function given_some_content_exists_it_can_be_viewed_in_the_admin_section()
    {
        $home = Page::create(['name' => 'home', 'description' => 'The homepage']);
        $about = Page::create(['name' => 'about', 'description' => 'The about page']);
        $intro = $home->addTextblock('intro', 'the homepage intro', 0, 'Welcome to my website');
        $slider = $home->addGallery('slider', 'banner images');
        $about->addTextblock('intro', 'the about intro', 1, 'This is a story all about me');

        $this->visit('/admin/site-content/pages/'.$home->id)
            ->see('The homepage')
            ->see('The homepage intro')
            ->see('banner images')
            ->see('/admin/site-content/pages/' . $home->id . '/textblocks/' . $intro->id .'/edit')
            ->see('/admin/site-content/pages/' . $slider->id . '/galleries/' . $slider->id .'/edit');
    }

    /**
     *@test
     */
    public function the_content_of_an_existing_textblock_may_be_edited()
    {
        $home = Page::create(['name' => 'home', 'description' => 'The homepage']);
        $about = Page::create(['name' => 'about', 'description' => 'The about page']);
        $intro = $home->addTextblock('intro', 'the homepage intro', 0, 'Welcome to my website');
        $slider = $home->addGallery('slider', 'banner images');
        $about->addTextblock('intro', 'the about intro', 1, 'This is a story all about me');

        $this->visit('/admin/site-content/pages/' . $home->id . '/textblocks/' . $intro->id .'/edit')
            ->type('This is a newly updated block of text', 'content')
            ->press('Save Changes')
            ->seeInDatabase('ec_textblocks', [
                'id' => $intro->id,
                'content' => 'This is a newly updated block of text'
            ]);
    }

    /**
     *@test
     */
    public function images_can_be_uploaded_to_a_category()
    {
        $home = Page::create(['name' => 'home', 'description' => 'The homepage']);
        $about = Page::create(['name' => 'about', 'description' => 'The about page']);
        $intro = $home->addTextblock('intro', 'the homepage intro', 0, 'Welcome to my website');
        $slider = $home->addGallery('slider', 'banner images');
        $about->addTextblock('intro', 'the about intro', 1, 'This is a story all about me');

        $this->withoutMiddleware();
        $response = $this->call('POST', '/admin/site-content/galleries/'.$slider->id.'/uploads', [], [], [
            'file' => $this->prepareFileUpload('tests/testpic1.png', 'testpic1.png')
        ]);

        $this->assertEquals(200, $response->status());
        $this->assertCount(1, $slider->getMedia());

        $slider->clearMediaCollection();
    }

}