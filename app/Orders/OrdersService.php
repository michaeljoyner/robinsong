<?php
/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 1/3/16
 * Time: 11:39 AM
 */

namespace App\Orders;


use Carbon\Carbon;

class OrdersService
{

    private $formData;
    private $orderNumber;
    private $orderModel;

    public function __construct($formData)
    {
        $this->formData = $formData;
        $this->orderNumber = $this->generateOrderNumber();
        $this->orderModel = null;
    }

    public function makeOrder()
    {
        $attributes = array_merge($this->formData, ['order_number' => $this->orderNumber]);
        $this->orderModel = Order::create($attributes);
    }

    public function commitOrder($cartContents, $chargeResult, $shippingInfo)
    {
        if(is_null($this->orderModel)) {
            $this->makeOrder();
        }

        $this->setOrderShippingInfo($shippingInfo);
        if($chargeResult) {
            $this->setOrderChargeResults($chargeResult);
        }

        $this->addCartItemsToOrder($cartContents);

        return $this;
    }

    public function getOrderModel()
    {
        return $this->orderModel;
    }

    public function orderNumber()
    {
        return $this->orderNumber;
    }

    public function orderId()
    {
        if($this->orderModel) {
            return $this->orderModel->id;
        }

        throw new \Exception('Order not persisted');
    }

    protected function generateOrderNumber()
    {
        $datestring = Carbon::now()->format('ymd');
        return $datestring . str_random(4);
    }

    private function addCartItemsToOrder($cartContents)
    {
        $cartContents->each(function($item) {
            $orderItem = $this->orderModel->addItem($item->id, $item->qty);
            if(isset($item->options['options'])) {
                $options = collect($item->options['options'])->collapse()->toArray();
                foreach($options as $key => $option) {
                    $orderItem->addOption($key, $option);
                }
            }

            if(isset($item->options['customisations'])) {
                $customisations = collect($item->options['customisations'])->collapse()->toArray();
                foreach($customisations as $key => $value) {
                    $orderItem->addCustomisation($key, $value);
                }
            }
        });
    }

    private function setOrderChargeResults($chargeResult)
    {
        $this->orderModel->amount = $chargeResult->amount();
        $this->orderModel->charge_id = $chargeResult->chargeId();
        $this->orderModel->save();
    }

    private function setOrderShippingInfo($shippingInfo)
    {
        $this->orderModel->shipping_location = $shippingInfo['location'];
        $this->orderModel->shipping_amount = $shippingInfo['price'];
        $this->orderModel->save();
    }

}