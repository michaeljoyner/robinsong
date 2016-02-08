<?php

namespace App\Http\Controllers;

use App\Billing\PaypalTeller;
use App\Billing\RedirectsBasedOnChargeResponse;
use App\Billing\StripeTeller;
use App\Cart\CartRepository;
use App\Http\Requests\PaymentRequest;
use App\Orders\OrdersService;
use App\Shipping\ShippingCalculatorFactory;
use App\Shipping\ShippingLocation;
use App\Shipping\ShippingService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    use RedirectsBasedOnChargeResponse;

    /**
     * @var CartRepository
     */
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function showCheckout(ShippingService $shippingService)
    {
        $shippingInfo = $shippingService->quote($this->cartRepository->weightOfItemsInCart(), $this->cartRepository->totalPrice());
        $items = $this->cartRepository->cartContents();
        $subtotal = $this->cartRepository->totalPrice();

        return view('front.pages.checkout')->with(compact('items', 'subtotal', 'shippingInfo'));
    }

    public function doCheckout(PaymentRequest $request)
    {
        $ordersService = new OrdersService($request->all());
        $shippingInfo = $this->getShippingInfo($request->shipping_location);
        $order = $ordersService->commitOrder($this->cartRepository->cartContents(), null, $shippingInfo)->getOrderModel();

        if($request->forPayPal()) {
             return $this->handlePayPalRequest($order);
        }

        return $this->handleStripePayment($request->stripeToken, $this->cartRepository->totalPrice() + $shippingInfo['price'], $order);
    }

    private function handlePayPalRequest($order)
    {
        $teller = new PaypalTeller();
        return $teller->requestPurchase($order, $this->cartRepository->totalPrice() + $order->shipping_amount);
    }

    private function handleStripePayment($stripeToken, $totalAmount, $order)
    {
        $teller = new StripeTeller();
        $payment = $teller->charge($stripeToken, $totalAmount, [
            'order_number' => $order->order_number
        ]);
        $order->setChargeResult($payment);

        return $this->handlePaymentResult($payment, $order->order_number);
    }

    private function getShippingInfo($shipping_location)
    {
        $shippingLocation = ShippingLocation::where('name', $shipping_location)->first();
        if($shippingLocation) {
            $shippingInfo = [
                'location' => $shippingLocation->name,
                'price' => $shippingLocation->shippingPrice($this->cartRepository->weightOfItemsInCart(), $this->cartRepository->totalPrice())
            ];
        } else {
            $shippingInfo = [
                'location' => 'not found',
                'price' => 0
            ];
        }

        return $shippingInfo;
    }
}
