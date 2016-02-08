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
        Stripe::setApiKey(config('services.stripe.secret'));
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
            return new ChargeResponse('stripe', true, 'charge successful', $response->amount, $response->id);

        } catch(Card $cardError) {
            return new ChargeResponse('stripe', false, $cardError->getMessage(), null, null);
        } catch(\Exception $e) {
            return new ChargeResponse('stripe', false, $e->getMessage(), null, null);
        }
    }
}
