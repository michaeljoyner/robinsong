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

    public function __construct($success, $message, $chargeResponse = null)
    {
        $this->success = $success;
        $this->message = $message;
        $this->chargeResponse = $chargeResponse;
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
        return $this->chargeResponse ? $this->chargeResponse->amount : null;
    }

    public function chargeId()
    {
        return $this->chargeResponse ? $this->chargeResponse->id : null;
    }
}