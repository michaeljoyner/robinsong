@extends('front.base')

@section('content')
    @include('front.partials.basketbar')
    @include('front.partials.altheader')
    @include('front.partials.navbar')
    <div class="w-section">
        <h1 class="h2 collections">Checkout</h1>
    </div>
    <form method="POST" action="/checkout" class="w-container checkout-form" id="payment-form">
        {!! csrf_field() !!}
        <p class="payment-errors">{{ \Illuminate\Support\Facades\Session::get('payment.error', '') }}</p>
        <section class="order-summary">
            <h4 class="form-section-header">Please select your shipping option and check your order</h4>
            <div class="shipping-selections">
                @foreach($shippingInfo as $location)
                    <label>
                        {{ $location['name'] }} &pound;{{ $location['price'] / 100  }}
                        <input type="radio"
                               value="{{ $location['price'] }}"
                               name="shipping_price"
                               v-model="shipping"
                               v-on:click="setLocation('{{ $location['name'] }}')">
                    </label>
                @endforeach
                    <input type="hidden" name="shipping_location" value="{{ $shippingInfo[0]['name'] }}" v-model="shipping_location">
            </div>
            @include('front.partials.ordertable')
        </section>
        <section class="customer w-row">
            <div class="w-col w-col-offset-3 w-col-6">
                <div class="form-input-row">
                    <label for="customer_name">Your Name:</label>
                    <input type="text" name="customer_name" required  value="{{ old('customer_name') }}">
                </div>
                <div class="form-input-row">
                    <label for="customer_name">Your Email Address:</label>
                    <input type="email" name="customer_email" required  value="{{ old('customer_email') }}">
                </div>
                <div class="form-input-row">
                    <label for="customer_phone">Your Phone number (optional):</label>
                    <input type="text" name="customer_phone" value="{{ old('customer_phone') }}">
                </div>
            </div>
        </section>
        <section class="addresses">
            <div class="w-row">
                <div class="w-col w-col-offset-3 w-col-6">
                    <h4 class="form-section-header">Your deliver address:</h4>
                    <div class="form-input-row">
                        <label for="address_line1">Line 1: </label>
                        <input type="text" name="address_line1"  value="{{ old('address_line1') }}" required>
                    </div>
                    <div class="form-input-row">
                        <label for="address_line2">Line 2: </label>
                        <input type="text" name="address_line2" value="{{ old('address_line2') }}">
                    </div>
                    <div class="form-input-row">
                        <label for="address_city">City: </label>
                        <input type="text" name="address_city"  value="{{ old('address_city') }}" required>
                    </div>
                    <div class="w-row">
                        <div class="w-col w-col-6 form-input-row">
                            <label for="address_state">State/Province/County: </label>
                            <input type="text" name="address_state" value="{{ old('address_state') }}" required>
                        </div>
                        <div class="w-col w-col-6 form-input-row">
                            <label for="address_zip">Zip Code: </label>
                            <input type="text" name="address_zip" value="{{ old('address_zip') }}" required>
                        </div>
                    </div>
                    <div class="form-input-row">
                        <label for="address_country">Country: </label>
                        <input type="text" name="address_country" value="{{ old('address_country') }}" required>
                    </div>
                </div>
            </div>

        </section>
        <section class="paypal-section">
            <button id="paypal-submit-btn" type="submit" name="gateway" value="paypal">Pay with Paypal</button>
        </section>
        <section>
            <h4 class="form-section-header">Payment Info</h4>
            <div class="w-row">
                <div class="w-col w-col-6 form-input-row">
                    <label>Credit Card Number:</label>
                    <input type="text" size="4" pattern="[0-9]{13,16}" data-stripe="number" id="cc-number">
                </div>
                <div class="w-col w-col-3 form-input-row">
                    <label for="">CCV Number: </label>
                    <input type="text" size="4" data-stripe="cvc">
                </div>
                <div class="w-col w-col-3 form-input-row">
                    <label for="">Expiry Date:</label>
                    <div>
                        <select class="w-select" data-stripe="exp-month">
                            <option value="">MM</option>
                            <option value="1">01</option>
                            <option value="2">02</option>
                            <option value="3">03</option>
                            <option value="4">04</option>
                            <option value="5">05</option>
                            <option value="6">06</option>
                            <option value="7">07</option>
                            <option value="8">08</option>
                            <option value="9">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                        <select class="w-select" data-stripe="exp-year">
                            <option value="">YYYY</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <button id="form-submit" class="pay-btn" name="gateway" type="submit">Pay Now</button>
        </section>
    </form>
    @include('front.partials.footer')
@endsection

@section('bodyscripts')
    <script>
        new Vue({
            el: 'body',

            data: {
                shipping: {{ $shippingInfo[0]['price'] }},
                subtotal: {{ $subtotal / 100 }},
                shipping_location: '{{ $shippingInfo[0]['name'] }}'
            },

            methods: {
                setLocation: function(name) {
                    this.$set('shipping_location', name);
                }
            }
        });
    </script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script>
        Stripe.setPublishableKey('pk_test_GWpxVebEjE6cfTHnYlpfr6tI');

        function stripeResponseHandler(status, response) {
            var $form = $('#payment-form');

            if (response.error) {
                // Show the errors on the form
                $form.find('.payment-errors').text(response.error.message);
                $form.find('#form-submit').prop('disabled', false);
            } else {
                // response contains id and card, which contains additional card details
                var token = response.id;
                // Insert the token into the form so it gets submitted to the server
                $form.append($('<input type="hidden" name="stripeToken" />').val(token));
                // and submit
                $form.get(0).submit();
            }
        };

        $(function($) {
            $('#payment-form').submit(function(event) {
                var $form = $(this);
                if($form.find('#cc-number').val() !== '') {
                    // Disable the submit button to prevent repeated clicks
                    $form.find('#form-submit').text('Please wait').prop('disabled', true);

                    Stripe.card.createToken($form, stripeResponseHandler);

                    // Prevent the form from submitting with the default action
                    return false;
                }

            });
        });
    </script>
@endsection