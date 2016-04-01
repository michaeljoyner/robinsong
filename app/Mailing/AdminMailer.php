<?php
/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 3/22/16
 * Time: 9:14 AM
 */

namespace App\Mailing;


use App\Orders\Order;

class AdminMailer extends AbstractMailer
{

    public function sendSiteMessage($fields)
    {
        $to = ['joyner.michael@gmail' => 'Michael Joyner'];
        $from = [$fields['email'] => $fields['name']];
        $view = 'emails.sitemessage';
        $subject = 'RobinSong site message from '.$fields['name'];

        $this->sendTo($to, $from, $subject, $view, compact('fields'));
    }

    public function notifyOfNewOrder(Order $order)
    {
        $to = ['joyner.michael@gmail' => 'Michael Joyner'];
        $from = ['site@robinsong.co.uk' => 'Robin Song'];
        $subject = 'New online order ' . $order->order_number;
        $view = 'emails.admin.order';
        $data = [
            'amount'        => $order->amount,
            'order_number' => $order->order_number,
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email,
            'shipping_fee' => $order->shipping_amount,
            'items'         => $order->items()->with('product', 'options', 'customisations')->get()->toArray()
        ];

        $this->sendTo($to, $from, $subject, $view, compact('data'));
    }

}