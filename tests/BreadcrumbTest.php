<?php
use App\Services\Breadcrumbs;
use App\Stock\Category;
use App\Stock\Collection;
use App\Stock\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 1/23/16
 * Time: 3:17 PM
 */
class BreadcrumbTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_makes_an_list_of_breadcrumb_links_for_a_product()
    {
        $weddings = factory(Collection::class)->create(['name' => 'weddings']);
        $books = factory(Category::class)->create(['collection_id' => $weddings->id, 'name' => 'guest books']);
        $product = factory(Product::class)->create(['name' => 'my product', 'category_id' => $books->id]);

        $expected = [
            ['name' => 'shop', 'url' => '/collections'],
            ['name' => 'weddings', 'url' => '/collections/weddings'],
            ['name' => 'guest books', 'url' => '/categories/guest-books'],
            ['name' => 'my product', 'url' => '/product/my-product']
        ];

        $actual = Breadcrumbs::makeFor($product);

        $this->assertEquals($expected, $actual, 'breadcrumb list not as expected');
    }

    /**
     * @test
     */
    public function it_makes_an_list_of_breadcrumb_links_for_a_category()
    {
        $weddings = factory(Collection::class)->create(['name' => 'weddings']);
        $books = factory(Category::class)->create(['collection_id' => $weddings->id, 'name' => 'guest books']);

        $expected = [
            ['name' => 'shop', 'url' => '/collections'],
            ['name' => 'weddings', 'url' => '/collections/weddings'],
            ['name' => 'guest books', 'url' => '/categories/guest-books'],
        ];

        $actual = Breadcrumbs::makeFor($books);

        $this->assertEquals($expected, $actual, 'breadcrumb list not as expected');
    }

    /**
     * @test
     */
    public function it_makes_an_list_of_breadcrumb_links_for_a_collection()
    {
        $weddings = factory(Collection::class)->create(['name' => 'weddings']);

        $expected = [
            ['name' => 'shop', 'url' => '/collections'],
            ['name' => 'weddings', 'url' => '/collections/weddings'],
        ];

        $actual = Breadcrumbs::makeFor($weddings);

        $this->assertEquals($expected, $actual, 'breadcrumb list not as expected');
    }
}