<?php
use Illuminate\Support\Facades\Session;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 2/7/16
 * Time: 3:09 PM
 */
class PaypalTellerTest extends TestCase
{

    /**
     *@test
     */
    public function it_correctly_initiates_a_purchase_for_a_given_order_and_amount_via_omnipay()
    {
        Session::shouldReceive('put')->with('paypal_req', Mockery::any())->once();
        Session::shouldReceive('save')->once();

        $order = factory(\App\Orders\Order::class)->make(['order_number' => '12345']);
        $gateway = Mockery::mock(\Omnipay\PayPal\ExpressGateway::class);
        $request = Mockery::mock(\Omnipay\PayPal\Message\ExpressAuthorizeRequest::class);
        $response = Mockery::mock(Omnipay\PayPal\Message\ExpressAuthorizeResponse::class);
        $request->shouldReceive('send')->once()->andReturn($response);
        $response->shouldReceive('isRedirect')->once()->andReturn(true);
        $response->shouldReceive('redirect')->once();
        $gateway->shouldReceive('purchase')->with(Mockery::any())->once()->andReturn($request);
        $gateway->shouldReceive('setUsername')->with(Mockery::any())->once();
        $gateway->shouldReceive('setPassword')->with(Mockery::any())->once();
        $gateway->shouldReceive('setSignature')->with(Mockery::any())->once();
        $gateway->shouldReceive('setTestMode')->with(Mockery::any())->once();
        $gateway->shouldReceive('setLandingPage')->with(Mockery::any())->once();
        $gateway->shouldReceive('setBrandName')->with(Mockery::any())->once();
        $gateway->shouldReceive('setBorderColor')->with(Mockery::any())->once();
        $teller = new \App\Billing\PaypalTeller($gateway);

        $teller->requestPurchase($order, 1000);

    }

    /**
     *@test
     */
    public function it_can_complete_a_purchase_via_omnipay_and_return_a_charge_response()
    {
        Session::shouldReceive('get')->with('paypal_req')->andReturn(['pointless' => 'true']);
        $gateway = Mockery::mock(\Omnipay\PayPal\ExpressGateway::class);
        $request = Mockery::mock(Omnipay\PayPal\Message\ExpressCompleteAuthorizeRequest::class);
        $response = Mockery::mock(Omnipay\PayPal\Message\ExpressAuthorizeResponse::class);
        $gateway->shouldReceive('setUsername')->with(Mockery::any())->once();
        $gateway->shouldReceive('setPassword')->with(Mockery::any())->once();
        $gateway->shouldReceive('setSignature')->with(Mockery::any())->once();
        $gateway->shouldReceive('setTestMode')->with(Mockery::any())->once();
        $gateway->shouldReceive('completePurchase')->with(Mockery::any())->once()->andReturn($request);
        $response->shouldReceive('getData')->once()->andReturn([
            'PAYMENTINFO_0_ACK' => 'Success',
            'PAYMENTINFO_0_AMT' => '10.00',
            'PAYMENTINFO_0_TRANSACTIONID' => '12345'
        ]);
        $request->shouldReceive('send')->once()->andReturn($response);

        $teller = new \App\Billing\PaypalTeller($gateway);

        $charge = $teller->completePurchase();

        $this->assertInstanceOf(App\Billing\ChargeResponse::class, $charge);
        $this->assertEquals(true, $charge->success());
        $this->assertEquals('12345', $charge->chargeId());
    }
}