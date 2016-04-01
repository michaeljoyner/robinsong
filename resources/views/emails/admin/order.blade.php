<h3>New online order from Robin Song</h3>

<p><strong>From: </strong>{{ $data['customer_name'] }}</p>
<p><strong>Email: </strong>{{ $data['customer_email'] }}</p>

<p>You can review the order details on the website.</p>

<p>The customers invoice is as follows:</p>
@include('emails.partials.invoicetable')