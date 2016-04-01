@extends('admin.base')

@section('content')
    <div class="rs-page-header clearfix">
        <h1 class="pull-left">@if($status) {{ ucfirst($status) }} @endif  Orders</h1>
        <div class="rs-header-actions pull-right">
            <span class="filter-label">Filter: </span>
            <a href="/admin/orders/fulfilled">
                <div class="btn rs-btn btn-orange">
                    Fulfilled
                </div>
            </a>
            <a href="/admin/orders/cancelled">
                <div class="btn rs-btn btn-red">
                    Cancelled
                </div>
            </a>
            <a href="/admin/orders/ongoing">
                <div class="btn rs-btn btn-light">
                    Ongoing
                </div>
            </a>
            <a href="/admin/orders/archived">
                <div class="btn rs-btn btn-clear-danger">
                    Archived
                </div>
            </a>
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