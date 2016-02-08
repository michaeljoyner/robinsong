<?php

namespace App\Http\Controllers;

use App\Billing\ChargeResponse;
use App\Billing\PaypalTeller;
use App\Billing\RedirectsBasedOnChargeResponse;
use App\Orders\Order;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class PaypalController extends Controller
{
    use RedirectsBasedOnChargeResponse;

    public function processPaypalPayment($orderNumber, PaypalTeller $paypalTeller)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        $payment = $paypalTeller->completePurchase();
        $order->setChargeResult($payment);

        return $this->handlePaymentResult($payment, $orderNumber);
    }

    public function handleCanceledPayment()
    {
        $paymentError = 'You cancelled your paypal payment';
        Session::flash('payment.error', $paymentError);
        return redirect('checkout');
    }
}
