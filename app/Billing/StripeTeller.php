<?php

namespace App\Billing;

use App\Billing\Contracts\TellerInterface;
use Carbon\Carbon;
use Stripe\Charge;
use Stripe\Error\Card;
use Stripe\Stripe;

class StripeTeller
{


    public function __construct()
    {
        Stripe::setApiKey('sk_test_o0emXGebfKr5etUIRODDL8u6');
    }

    public function charge($source, $price, $metadata = [])
    {
        $payload = [
            'source' => $source,
            'currency' => 'gbp',
            'amount' => $price,
            'description' => 'Purchase from Robin Song Creations '.Carbon::now()->toFormattedDateString(),
            'metadata' => $metadata
        ];

        try {
             $response = Charge::create($payload);

            return new ChargeResponse(true, 'charge successful', $response);

        } catch(Card $cardError) {
            return new ChargeResponse(false, $cardError->getMessage());
        } catch(\Exception $e) {
            return new ChargeResponse(false, $e->getMessage());
        }
    }
}
