<table class="order-table">
    <thead>
    <tr>
        <td></td>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>{{ $item->qty }}</td>
            <td>{{ $item->name }}</td>
            <td class="price">&pound;{{ $item->subtotal / 100 }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="2">Sub-total</td>
        <td class="price">&pound;{{ $subtotal / 100 }}</td>
    </tr>
    <tr>
        <td colspan="2">Shipping</td>
        <td class="price">&pound;@{{ shipping / 100 }}</td>
    </tr>
    <tr>
        <td colspan="2">Total</td>
        <td class="price">&pound;@{{ (subtotal + (shipping / 100)).toFixed(2) }}</td>
    </tr>
    </tfoot>
</table>