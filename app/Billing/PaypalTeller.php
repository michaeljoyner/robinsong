<?php
/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 2/6/16
 * Time: 9:50 PM
 */

namespace App\Billing;


use App\Billing\ChargeResponse;
use App\Orders\Order;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Omnipay\Omnipay;

class PaypalTeller
{

    private $gateway;
    protected $processUrl = 'http://robin.app:8000/process_paypal/';
    protected $cancelUrl = 'http://robin.app:8000/cancel_paypal';

    public function __construct($gateway = null)
    {
        $this->gateway = $gateway ? $gateway : Omnipay::create('PayPal_Express');
        $this->gateway->setUsername(config('services.paypal.username'));
        $this->gateway->setPassword(config('services.paypal.password'));
        $this->gateway->setSignature(config('services.paypal.signature'));
        $this->gateway->setTestMode(true);
    }

    public function requestPurchase($order, $amount)
    {
        $orderParams = [
            'cancelUrl' => $this->cancelUrl,
            'returnUrl' => $this->processUrl.$order->order_number,
            'description' => 'Robin Song order '.$order->order_number,
            'amount' => number_format(($amount)/100, 2),
            'currency' => 'gbp'
        ];



        Session::put('paypal_req', $orderParams);
        Session::save();

        $this->gateway->setLandingPage(['login']);
        $this->gateway->setBrandName('Robin Song');
        $this->gateway->setBorderColor('F48053');

        $response = $this->gateway->purchase($orderParams)->send();

        if($response->isRedirect()) {
            return $response->redirect();
        } else {
            throw new \Exception($response->getMessage());
        }
    }

    public function completePurchase()
    {
        $response = $this->gateway->completePurchase(Session::get('paypal_req'))->send();
        $paypalResponse = $response->getData();

        if($this->chargeWasSuccessful($paypalResponse)) {
            $charge = new ChargeResponse(
                'paypal',
                true,
                'success',
                $paypalResponse['PAYMENTINFO_0_AMT'] * 100,
                $paypalResponse['PAYMENTINFO_0_TRANSACTIONID']
            );
        } else {
            $charge = new ChargeResponse('paypal', false, 'payment failed', null, null);
        }

        return $charge;
    }

    private function chargeWasSuccessful($paypalResponse)
    {
        return isset($paypalResponse['PAYMENTINFO_0_ACK']) && $paypalResponse['PAYMENTINFO_0_ACK'] === 'Success';
    }

}