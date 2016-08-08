<?php
/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 2/8/16
 * Time: 9:35 AM
 */

namespace App\Mailing;


use App\Orders\Order;

class CustomerMailer extends AbstractMailer
{
    public function sendInvoiceFor(Order $order)
    {
        $to = [$order->customer_email => $order->customer_name];
        $from = ['no-reply@robinsong.com' => 'Robin Song Occasions'];
        $subject = 'Robin Song - Invoice #' . $order->order_number;
        $data = [
            'amount'        => $order->amount,
            'order_number' => $order->order_number,
            'customer_name' => $order->customer_name,
            'shipping_fee' => $order->shipping_amount,
            'items'         => $this->getOrderItemsAsArray($order)
        ];
        $view = 'emails.customer.invoice';

        $this->sendTo($to, $from, $subject, $view, compact('data'));
    }

    public function notifyAboutShipping(Order $order)
    {
        $to = [$order->customer_email => $order->customer_name];
        $from = ['no-reply@robinsong.com' => 'Robin Song Occasions'];
        $subject = 'Robin Song order #'. $order->order_number . ' has been shipped!';
        $data = [
            'order_number' => $order->order_number,
            'customer_name' => $order->customer_name,
        ];
        $view = 'emails.customer.shipped';

        $this->sendTo($to, $from, $subject, $view, compact('data'));
    }

    protected function getOrderItemsAsArray($order)
    {
        return $order->items->map(function($item) {
            return [
                'description' => $item->description,
                'package' => $item->package,
                'price' => $item['price'],
                'quantity' => $item->quantity,
                'has_customisations' => $item->hasCustomisations()
            ];
        })->toArray();
    }
}