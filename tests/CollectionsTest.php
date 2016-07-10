<?php
use App\Stock\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 11/24/15
 * Time: 10:14 AM
 */
class CollectionsTest extends TestCase
{
    use DatabaseMigrations, AsLoggedInUser, TestsImageUploads;

    /**
     * @test
     */
    public function a_collection_can_be_created()
    {

        $this->visit('/admin/collections/create')
            ->submitForm('Add Collection', [
                'name' => 'weddings',
                'description' => 'all that hubris'
            ])->seeInDatabase('collections', [
                'name' => 'weddings',
                'description' => 'all that hubris'
            ]);
    }

    /**
     * @test
     */
    public function a_collections_name_and_description_can_be_edited()
    {
        $collection = factory(Collection::class)->create();

        $this->visit('/admin/collections/'.$collection->id.'/edit')
            ->type('dereilique', 'name')
            ->type('a humorous collection', 'description')
            ->press('Save Changes')
            ->seeInDatabase('collections', [
                'id' => $collection->id,
                'name' => 'dereilique',
                'description' => 'a humorous collection'
            ]);
    }

    /**
     * @test
     */
    public function a_collection_may_be_deleted()
    {
        $this->withoutMiddleware();

        $collection = factory(Collection::class)->create();

        $response = $this->call('DELETE', '/admin/collections/'.$collection->id);
        $this->assertEquals(302, $response->status(), 'should redirect on successful deletion');

        $collection = Collection::withTrashed()->findOrFail($collection->id);

        $this->assertTrue($collection->trashed());
    }

    /**
     * @test
     */
    public function an_image_can_be_uploaded_to_be_assiosiated_with_a_collection()
    {
        $this->withoutMiddleware();

        $collection = factory(Collection::class)->create();

        $response = $this->call('POST', '/admin/uploads/collections/'.$collection->id.'/cover', [], [], [
            'file' => $this->prepareFileUpload(realpath('tests/testpic1.png'), 'testpic1.png')
        ]);
        $this->assertEquals(200, $response->status());

        $this->assertContains('media', $collection->coverPic(), 'the image is in the media collection');
        $this->assertContains('testpic1', $collection->coverPic(), 'the correct image is assosiated');

        $collection->clearMediaCollection();
    }

    /**
     * @test
     */
    public function only_the_last_image_assiosiated_with_the_collection_is_kept_and_returned_as_cover_pic()
    {
        $this->withoutMiddleware();

        $collection = factory(Collection::class)->create();

        $response = $this->call('POST', '/admin/uploads/collections/'.$collection->id.'/cover', [], [], [
            'file' => $this->prepareFileUpload(realpath('tests/testpic1.png'), 'testpic1.png')
        ]);
        $this->assertEquals(200, $response->status());

        $response = $this->call('POST', '/admin/uploads/collections/'.$collection->id.'/cover', [], [], [
            'file' => $this->prepareFileUpload(realpath('tests/testpic2.png'), 'testpic2.png')
        ]);
        $this->assertEquals(200, $response->status());

        $this->assertContains('media', $collection->coverPic(), 'the image is in the media collection');
        $this->assertContains('testpic2', $collection->coverPic(), 'the correct image is assosiated');

        $collection->clearMediaCollection();
    }

    /**
     *@test
     */
    public function deleting_a_collection_also_deletes_its_categories()
    {
        $collection = factory(Collection::class)->create();
        $categories = factory(\App\Stock\Category::class, 3)->create(['collection_id' => $collection->id]);

        $collection->delete();
        $this->assertNotNull($collection->deleted_at);

        $categories->each(function($category) {
            $category = \App\Stock\Category::withTrashed()->findOrFail($category->id);
            $this->assertTrue($category->trashed());
        });
    }

}