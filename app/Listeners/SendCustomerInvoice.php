<?php

namespace App\Listeners;

use App\Events\OrderPaidUp;
use App\Mailing\CustomerMailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCustomerInvoice
{
    /**
     * @var CustomerMailer
     */
    private $customerMailer;

    /**
     * Create the event listener.
     * @param CustomerMailer $customerMailer
     */
    public function __construct(CustomerMailer $customerMailer)
    {
        //
        $this->customerMailer = $customerMailer;
    }

    /**
     * Handle the event.
     *
     * @param  OrderPaidUp  $event
     * @return void
     */
    public function handle(OrderPaidUp $event)
    {
        $this->customerMailer->sendInvoiceFor($event->order);
    }
}
