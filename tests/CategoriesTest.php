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

    use DatabaseMigrations, AsLoggedInUser;

    /**
     * @test
     */
    public function it_creates_a_new_category()
    {
        $categoryData = factory(Category::class)->make();

        $this->visit('/admin/categories/create')
            ->submitForm('Add category', [
                'name'        => $categoryData->name,
                'description' => $categoryData->description
            ])->seeInDatabase('categories', [
                'name'        => $categoryData->name,
                'description' => $categoryData->description
            ]);
    }

}