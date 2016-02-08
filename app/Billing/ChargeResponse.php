<?php
/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 1/2/16
 * Time: 5:36 PM
 */

namespace App\Billing;


class ChargeResponse
{
    private $success;
    private $message;

    private $chargeResponse;
    public $gateway;
    private $amount;
    private $id;

    public function __construct($gateway, $success, $message, $amount, $id)
    {
        $this->success = $success;
        $this->message = $message;
        $this->gateway = $gateway;
        $this->amount = $amount;
        $this->id = $id;
    }

    public function success()
    {
        return $this->success;
    }

    public function message()
    {
        return $this->message;
    }

    public function amount()
    {
        return $this->amount;
    }

    public function chargeId()
    {
        return $this->id;
    }
}