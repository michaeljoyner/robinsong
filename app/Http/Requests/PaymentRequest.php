<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PaymentRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_name' => 'required|max:255',
            'customer_email' => 'required|email',
            'address_line1' => 'required',
            'address_city' => 'required',
            'address_state' => 'required',
            'address_zip' => 'required',
            'address_country' => 'required',
            'gateway' => 'required_without:stripeToken',
            'stripeToken' => 'required_without:gateway'
        ];
    }

    public function forPayPal()
    {
        return $this->has('gateway') && $this->gateway === 'paypal';
    }
}
