<h3>Thank you for your purchase from Robin Song!</h3>
<p>Thank you {{ $data['customer_name'] }}. Here is your invoice.</p>
<hr>
<h4>Invoice #{{ $data['order_number'] }}</h4>
<p><strong>To: </strong>{{ $data['customer_name'] }}</p>
<p><strong>Date: </strong>{{ \Carbon\Carbon::now()->toFormattedDateString() }}</p>
@include('emails.partials.invoicetable')