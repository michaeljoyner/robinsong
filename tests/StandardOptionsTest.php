<?php
use App\Stock\StandardOption;
use App\Stock\StandardOptionValue;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 7/10/16
 * Time: 11:30 AM
 */
class StandardOptionsTest extends TestCase
{
    use DatabaseMigrations, AsLoggedInUser;

    /**
     *@test
     */
    public function a_new_standard_option_may_be_created()
    {
        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/standard-options/', [
            'name' => 'Ribbon Colour'
        ]);
        $this->assertEquals(200, $response->status());

        $this->seeInDatabase('standard_options', [
            'name' => 'Ribbon Colour'
        ]);
    }

    /**
     * @test
     */
    public function a_standard_option_with_a_non_unique_name_cant_be_created()
    {
        StandardOption::create(['name' => 'option']);
        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/standard-options/', [
            'name' => 'option'
        ]);
        $this->assertNotEquals(200, $response->status());
    }

    /**
     *@test
     */
    public function a_standard_option_can_be_deleted()
    {
        $option = StandardOption::create(['name' => 'option']);

        $this->withoutMiddleware();

        $response = $this->call('DELETE', '/admin/standard-options/' . $option->id);
        $this->assertEquals(200, $response->status());

        $this->notSeeInDatabase('standard_options', [
            'id' => $option->id
        ]);
    }

    /**
     *@test
     */
    public function values_can_be_added_directly_to_a_standard_option()
    {
        $option = StandardOption::create(['name' => 'option']);
        $value = $option->addValue('value');

        $this->assertInstanceOf(StandardOptionValue::class, $value);

        $this->seeInDatabase('standard_option_values', [
            'standard_option_id' => $option->id,
            'name' => 'value'
        ]);
    }

    /**
     *@test
     */
    public function values_can_be_posted_to_a_standard_option()
    {
        $this->withoutMiddleware();

        $option = StandardOption::create(['name' => 'option']);

        $response = $this->call('POST', '/admin/standard-options/' . $option->id . '/values', [
            'name' => 'value'
        ]);
        $this->assertEquals(200, $response->status());

        $this->seeInDatabase('standard_option_values', [
            'standard_option_id' => $option->id,
            'name' => 'value'
        ]);
    }

    /**
     *@test
     */
    public function a_standard_option_value_can_be_deleted()
    {
        $this->withoutMiddleware();
        $value = StandardOption::create(['name' => 'option'])->addValue('value');

        $response = $this->call('DELETE', '/admin/standard-option-values/' . $value->id);
        $this->assertEquals(200, $response->status());

        $this->notSeeInDatabase('standard_option_values', ['id' => $value->id]);
    }

    /**
     *@test
     */
    public function deleting_a_standard_option_deletes_all_its_values()
    {
        $option = StandardOption::create(['name' => 'option']);
        $value1 = $option->addValue('value 1');
        $value2 = $option->addValue('value 2');
        $value3 = $option->addValue('value 3');

        $this->assertCount(3, StandardOptionValue::where('standard_option_id', $option->id)->get());

        $option->delete();
        $this->assertCount(0, StandardOptionValue::where('standard_option_id', $option->id)->get());

        $this->notSeeInDatabase('standard_option_values', ['id' => $value1->id]);
        $this->notSeeInDatabase('standard_option_values', ['id' => $value2->id]);
        $this->notSeeInDatabase('standard_option_values', ['id' => $value3->id]);
    }

    /**
     *@test
     */
    public function a_standard_option_may_be_cloned_into_a_products_option()
    {
        $product = factory(\App\Stock\Product::class)->create();
        $this->assertCount(0, $product->options, 'product should have no options');

        $standardOption = StandardOption::create(['name' => 'standard']);
        $standardOption->addValue('value 1');
        $standardOption->addValue('value 2');
        $standardOption->addValue('value 3');

        $product->useStandardOption($standardOption->id);

        $product = \App\Stock\Product::find($product->id);

        $this->assertCount(1, $product->options);
        $this->assertEquals('standard', $product->options()->first()->name);

        $this->assertCount(3, $product->options->first()->values);
    }

    /**
     *@test
     */
    public function a_standard_option_can_be_added_to_a_product_by_posting_to_an_endpoint()
    {
        $this->withoutMiddleware();
        $product = factory(\App\Stock\Product::class)->create();
        $this->assertCount(0, $product->options, 'product should have no options');

        $standardOption = StandardOption::create(['name' => 'standard']);
        $standardOption->addValue('value 1');
        $standardOption->addValue('value 2');
        $standardOption->addValue('value 3');

        $response = $this->call('POST', '/admin/products/' . $product->id . '/standard-options/add', [
            'standard_option_id' => $standardOption->id
        ]);
        $this->assertEquals(200, $response->status());

        $product = \App\Stock\Product::findOrFail($product->id);
        $this->assertCount(1, $product->options);
        $this->assertCount(3, $product->options->first()->values);

    }


}