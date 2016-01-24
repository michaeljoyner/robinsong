@extends('admin.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
@stop

@section('content')
    <div class="rs-page-header clearfix">
        <h1 class="pull-left">Order #{{ $order->order_number }}</h1>
        <div class="rs-header-actions pull-right">
            <toggle-button url="/admin/api/orders/{{ $order->id }}/fulfill"
                           initial="{{ $order->isFulfilled() ? 1 : 0 }}"
                           toggleprop="fulfill"
                           onclass=""
                           offclass="btn-danger"
                           offtext="Mark as Fulfilled"
                           ontext="Mark as Open"></toggle-button>
            <toggle-button url="/admin/api/orders/{{ $order->id }}/cancel"
                           initial="{{ $order->isCancelled() ? 1 : 0 }}"
                           toggleprop="cancel"
                           onclass="btn-danger"
                           offclass=""
                           offtext="Cancel"
                           ontext="Restore"></toggle-button>
        </div>
        <hr>
    </div>
    <div class="order-detail">
        <div class="row">
            <div class="col-md-6 customer-detail">
                <p class="lead"><span class="detail-label">Customer: </span> {{ $order->customer_name }}</p>
                <p class="lead"><span class="detail-label">Email Address: </span> {{ $order->customer_email }}</p>
                <p class="lead"><span class="detail-label">Phone number: </span> {{ $order->customer_phone ? $order->customer_phone : 'Not given' }}</p>
                <p class="lead"><span class="detail-label"> Delivery Address: </span></p>
                <p>{{ $order->address_line1 }}</p>
                <p>{{ $order->address_line2 }}</p>
                <p>{{ $order->address_city }}</p>
                <p>{{ $order->address_state }}</p>
                <p>{{ $order->address_country }}</p>
                <p>{{ $order->address_zip }}</p>
            </div>
            <div class="col-md-6 order-detail">
                <p class="lead"><strong>Amount charged: </strong>&pound;{{ $order->amount / 100 }}</p>
                <p class="lead"><strong>Stripe Charge Id: </strong>{{ $order->charge_id }}</p>
                <p class="lead"><strong>Shipping fee paid: </strong>&pound;{{ $order->shipping_amount / 100 }}</p>
                <p class="lead"><strong>Shipping Location: </strong>{{ $order->shipping_location }}</p>
            </div>
        </div>
        <div class="ordered-items">
            <hr>
            <h2>Ordered Items</h2>
            <ul>
                @foreach($order->items as $item)
                    <li>
                        <h4>{{ $item->product->name }}</h4>
                        <p>Qty: {{ $item->quantity }}</p>
                        @foreach($item->options as $option)
                            <p><strong>{{ $option->name }}: </strong>{{ $option->value }}</p>
                        @endforeach
                        @foreach($item->customisations as $customisation)
                            <p><strong>{{ $customisation->name }}: </strong>{{ $customisation->value }}</p>
                        @endforeach
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection

@section('bodyscripts')
    <script>
        new Vue({el: 'body'});
    </script>
@endsection