<?php

namespace App\Listeners;

use App\Events\OrderFulfilled;
use App\Mailing\CustomerMailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyCustomerOfShipping
{
    /**
     * @var CustomerMailer
     */
    private $customerMailer;

    /**
     * Create the event listener.
     *
     * @param CustomerMailer $customerMailer
     */
    public function __construct(CustomerMailer $customerMailer)
    {
        $this->customerMailer = $customerMailer;
    }

    /**
     * Handle the event.
     *
     * @param  OrderFulfilled  $event
     * @return void
     */
    public function handle(OrderFulfilled $event)
    {
        $this->customerMailer->notifyAboutShipping($event->order);
    }
}
