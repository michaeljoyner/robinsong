@extends('admin.base')

@section('content')
    <div class="rs-page-header clearfix">
        <h1 class="pull-left">@if($status) {{ ucfirst($status) }} @endif  Orders</h1>
        <div class="rs-header-actions pull-right">
            @if($status)
                <a href="/admin/orders">
                    <div class="btn rs-btn btn-light">Back to All Orders</div>
                </a>
            @endif
        </div>
        <hr>
    </div>
    <section class="orders-index">
        <table class="table">
            <thead>
                <tr>
                    <td>Order #</td>
                    <td>Date</td>
                    <td>Customer</td>
                    <td>Email address</td>
                    <td>Status</td>
                </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td class="order-number"><a href="/admin/orders/show/{{ $order->id }}">{{ $order->order_number }}</a></td>
                    <td>{{ $order->created_at->toFormattedDateString() }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->customer_email }}</td>
                    <td class="order-status {{ $order->getStatus() }}">{{ $order->getStatus() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {!! $orders->render() !!}
    </section>
@endsection