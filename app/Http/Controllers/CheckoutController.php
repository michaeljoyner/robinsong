<?php

namespace App\Http\Controllers;

use App\Billing\StripeTeller;
use App\Cart\CartRepository;
use App\Orders\OrdersService;
use App\Shipping\ShippingCalculatorFactory;
use App\Shipping\ShippingLocation;
use App\Shipping\ShippingService;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{

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

    public function doCheckout(Request $request, StripeTeller $teller)
    {
        $ordersService = new OrdersService($request->all());
        $shippingInfo = $this->getShippingInfo($request->shipping_location);

        $payment = $teller->charge($request->stripeToken, $this->cartRepository->totalPrice() + $shippingInfo['price'], [
            'order_number' => $ordersService->orderNumber()
        ]);

        if($payment->success()) {
            $ordersService->commitOrder($this->cartRepository->cartContents(), $payment, $shippingInfo);
            $this->cartRepository->clearCart();

            return redirect('/thanks')->with('order_number', $ordersService->orderNumber());
        } else {
            $paymentError = $payment->message();
            Session::flash('payment.error', $paymentError);
            return redirect()->back()->withInput();
        }
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

    private function getTotalOrderPrice($shipping_location)
    {
        $shippingLocation = ShippingLocation::where('name', $shipping_location)->first();
        if($shippingLocation) {
            $shippingPrice = $shippingLocation->shippingPrice($this->cartRepository->weightOfItemsInCart(), $this->cartRepository->totalPrice());
        } else {
            $shippingPrice = 0;
        }

        return $this->cartRepository->totalPrice() + $shippingPrice;
    }

}
