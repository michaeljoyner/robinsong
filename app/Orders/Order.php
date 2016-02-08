<?php

namespace App\Orders;

use App\Billing\ChargeResponse;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'order_number',
        'address_line1',
        'address_line2',
        'address_city',
        'address_state',
        'address_zip',
        'address_country'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function addItem($productId, $quantity)
    {
        return $this->items()->create(['product_id' => $productId, 'quantity' => $quantity]);
    }

    public function isFulfilled()
    {
        return !! $this->fulfilled;
    }

    public function fulfill()
    {
        $this->fulfilled = 1;
        return $this->save();
    }

    public function isCancelled()
    {
        return !! $this->cancelled;
    }

    public function cancel()
    {
        $this->cancelled = 1;
        return $this->save();
    }

    public function restore()
    {
        $this->cancelled = 0;
        return $this->save();
    }

    public function unfulfill()
    {
        $this->fulfilled = 0;
        return $this->save();
    }

    public function getStatus()
    {
        if($this->cancelled === 1) {
            return 'cancelled';
        }

        return $this->fulfilled === 1 ? 'fulfilled' : 'ongoing';
    }

    public function setChargeResult(ChargeResponse $charge)
    {
        $this->paid = $charge->success();
        $this->gateway = $charge->gateway;
        $this->amount = $charge->amount();
        $this->charge_id = $charge->chargeId();
        $this->save();
    }
}
