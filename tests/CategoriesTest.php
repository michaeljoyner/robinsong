<?php
use App\Stock\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 11/3/15
 * Time: 11:10 AM
 */
class CategoriesTest extends TestCase
{

    use DatabaseMigrations, AsLoggedInUser, TestsImageUploads;

    /**
     * @test
     */
    public function a_category_can_be_added_to_a_collection()
    {
        $collection = factory(\App\Stock\Collection::class)->create();
        $categoryData = factory(Category::class)->make();

        $this->visit('/admin/collections/'.$collection->id.'/categories/create')
            ->submitForm('Add category', [
                'name'        => $categoryData->name,
                'description' => $categoryData->description
            ])->seeInDatabase('categories', [
                'name'        => $categoryData->name,
                'description' => $categoryData->description
            ]);
    }

    /**
     * @test
     */
    public function a_category_name_and_description_can_be_edited()
    {
        $category = factory(Category::class)->create();

        $this->visit('/admin/categories/'.$category->id.'/edit')
            ->type('books', 'name')
            ->type('beautiful books', 'description')
            ->press('Save Changes')
            ->seeInDatabase('categories', [
                'id' => $category->id,
                'name' => 'books',
                'description' => 'beautiful books'
            ]);
    }

    /**
     * @test
     */
    public function a_category_can_be_deleted()
    {
        $category = factory(Category::class)->create();
        $this->withoutMiddleware();

        $response = $this->call('DELETE', '/admin/categories/'.$category->id);
        $this->assertEquals(302, $response->status(), 'should be a redirect response on success');

        $category = Category::withTrashed()->findOrFail($category->id);

        $this->assertTrue($category->trashed());
    }

    /**
     * @test
     */
    public function a_cover_image_can_be_uploaded_to_be_assosiated_with_a_category()
    {
        $category = factory(Category::class)->create();
        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/uploads/categories/'.$category->id.'/cover', [], [], [
            'file' => $this->prepareFileUpload('tests/testpic1.png', 'testpic1.png')
        ]);
        $this->assertEquals(200, $response->status());

        $this->assertContains('media', $category->coverPic(), 'the image is in the media collection');
        $this->assertContains('testpic1', $category->coverPic(), 'the correct image is associated');

        $category->clearMediaCollection();
    }

}