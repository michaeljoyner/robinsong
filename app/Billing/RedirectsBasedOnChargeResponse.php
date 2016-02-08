<?php
/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 2/7/16
 * Time: 11:17 AM
 */

namespace App\Billing;


use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;

trait RedirectsBasedOnChargeResponse
{

    protected function handlePaymentResult(ChargeResponse $payment, $orderNumber)
    {
        if($payment->success()) {
            Cart::destroy();
            return redirect('/thanks')->with('order_number', $orderNumber);
        }

        $paymentError = $payment->message();
        Session::flash('payment.error', $paymentError);
        return redirect('checkout')->withInput();
    }

}