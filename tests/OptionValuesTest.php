<?php
use App\Stock\OptionValue;
use App\Stock\ProductOption;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 11/28/15
 * Time: 10:40 PM
 */
class OptionValuesTest extends Testcase
{
    use DatabaseMigrations, AsLoggedInUser;

    /**
     * @test
     */
    public function an_option_value_can_be_added_to_a_product()
    {
        $option = factory(ProductOption::class)->create();

        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/productoptions/'.$option->id.'/values', [
            'name' => 'Blueish'
        ]);
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('option_values', [
            'product_option_id' => $option->id,
            'name' => 'Blueish'
        ]);
    }

    /**
     * @test
     */
    public function an_option_value_can_be_deleted()
    {
        $value = factory(OptionValue::class)->create();

        $this->withoutMiddleware();
        $response = $this->call('DELETE', '/admin/optionvalues/'.$value->id);
        $this->assertEquals(200, $response->status());

        $this->notSeeInDatabase('option_values', [
            'id' => $value->id
        ]);
    }

}