<?php

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 1/2/16
 * Time: 3:47 PM
 */
class TellerTest extends TestCase
{

    /**
     * @test
     */
    public function it_correctly_creates_a_charge_with_stripe()
    {
        $teller = new \App\Billing\StripeTeller();

        $result = $teller->charge($this->makeTestCardFor('success'), 1000);

        $this->assertTrue($result->success());
    }

    /**
     * @test
     */
    public function a_successful_charge_has_an_amount_charged_and_a_charge_id()
    {
        $teller = new \App\Billing\StripeTeller();
        $result = $teller->charge($this->makeTestCardFor('success'), 1000);

        $this->assertTrue($result->success());
        $this->assertEquals(1000, $result->amount());
        $this->assertNotEmpty($result->chargeId());
    }

    /**
     * @test
     */
    public function it_returns_a_charge_response_with_message_on_failure()
    {
        $teller = new \App\Billing\StripeTeller();
        $result = $teller->charge($this->makeTestCardFor('bad_ccv'), 1000);

        $this->assertFalse($result->success());
        $this->assertEquals('Your card\'s security code is incorrect.', $result->message());
    }

    private function makeTestCardFor($test)
    {
        switch($test) {
            case 'success':
                $number = '4242424242424242';
                break;
            case 'bad_ccv':
                $number = '4000000000000127';
                break;
            case 'no_funds':
                $number = '4000000000000002';
                break;
            case 'exp_card':
                $number = '4000000000000069';
                break;
            default:
                $number = '4000000000000119';
        }
        return [
            'object' => 'card',
            'number' => $number,
            'cvc' => '123',
            'exp_month' => '03',
            'exp_year' => '2017'
        ];
    }

}