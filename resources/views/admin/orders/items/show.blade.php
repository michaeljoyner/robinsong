@extends('admin.base')

@section('content')
    <div class="rs-page-header clearfix">
        <h1 class="pull-left">Order Item Customisations</h1>
        <div class="rs-header-actions pull-right">
            <a href="/admin/orders/show/{{ $item->order->id }}">
                <div class="btn rs-btn btn-light">Back to Order</div>
            </a>
        </div>
        <hr>
    </div>
    <section class="order-item-customisations">
        <h3 class="item-description">{{ $item->description }}</h3>
        @foreach($item->options as $option)
            <div class="customisation-card">
                <h4 class="customisation-card-heading">{{ $option->name }}</h4>
                <p>{{ $option->value }}</p>
            </div>
        @endforeach
        @foreach($item->customisations as $customisation)
            <div class="customisation-card">
                <h4 class="customisation-card-heading">{{ $customisation->name }}</h4>
                <p>{{ $customisation->value }}</p>
            </div>
        @endforeach
    </section>
@endsection

