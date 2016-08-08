@extends('admin.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
@stop

@section('content')
    <div class="rs-page-header clearfix">
        <h1 class="pull-left">Order #{{ $order->order_number }}</h1>
        @if(! $order->trashed())
        <div class="rs-header-actions pull-right">
            <button type="button"
                    class="btn rs-btn btn-solid-danger"
                    data-objectname="{{ $order->order_number }}"
                    data-action="/admin/orders/{{ $order->id }}"
                    data-toggle="modal" data-target="#confirm-delete-modal"
            >
                Archive
            </button>
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
        @else
            <span class="pull-right text-danger lead">Archived</span>
        @endif
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
                <p class="lead"><strong>Gateway Used: </strong>{{ ucfirst($order->gateway) }}</p>
                <p class="lead"><strong>Transaction Id: </strong>{{ $order->charge_id }}</p>
                <p class="lead"><strong>Shipping fee paid: </strong>&pound;{{ $order->shipping_amount / 100 }}</p>
                <p class="lead"><strong>Shipping Location: </strong>{{ $order->shipping_location }}</p>
            </div>
        </div>
        <div class="ordered-items">
            <hr>
            <h2>Ordered Items</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Quantity</th>
                        <th>Item</th>
                        <th>Package</th>
                        <th>Price</th>
                        <th>Customisations</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->package }}</td>
                        <td>&pound;{{ $item->price / 100 }}</td>
                        <td>@if($item->isCustomised()) <a href="/admin/orders/items/{{ $item->id }}">
                                <svg fill="#5fbfad" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                            </a> @else No @endif</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{--<ul>--}}
                {{--@foreach($order->items as $item)--}}
                    {{--<li class="order-show-item-card">--}}
                        {{--<h4 class="order-show-item-card-description">{{ $item->description }}</h4>--}}
                        {{--<p>Qty: {{ $item->quantity }}</p>--}}
                        {{--<p>Price: &pound;{{ $item->price / 100 }}</p>--}}
                        {{--@foreach($item->options as $option)--}}
                            {{--<p><strong>{{ $option->name }}: </strong>{{ $option->value }}</p>--}}
                        {{--@endforeach--}}
                        {{--@foreach($item->customisations as $customisation)--}}
                            {{--<p><strong>{{ $customisation->name }}: </strong>{{ $customisation->value }}</p>--}}
                        {{--@endforeach--}}
                    {{--</li>--}}
                {{--@endforeach--}}
            {{--</ul>--}}
        </div>
    </div>
@endsection
@include('admin.partials.deletemodal')

@section('bodyscripts')
    <script>
        new Vue({el: 'body'});
    </script>
    @include('admin.partials.modalscript')
@endsection