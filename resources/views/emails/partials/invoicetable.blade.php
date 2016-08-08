<table style="border-collapse: collapse; border: 1px solid #bbbbbb; width: 100%;">
    <thead>
    <tr>
        <th style="padding: 10px; border: 1px solid #bbbbbb;">#</th>
        <th style="padding: 10px; border: 1px solid #bbbbbb;">Item</th>
        <th style="padding: 10px; border: 1px solid #bbbbbb;">Package</th>
        <th style="padding: 10px; border: 1px solid #bbbbbb;">Quantity</th>
        <th style="padding: 10px; border: 1px solid #bbbbbb;">Price</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data['items'] as $index => $item)
        <tr>
            <td style="padding: 10px; border: 1px solid #bbbbbb;">{{ $index + 1 }}</td>
            <td style="padding: 10px; border: 1px solid #bbbbbb;">
                {{ $item['description'] }}
                @if($item['has_customisations'])
                    - with customisations
                @endif
            </td>
            <td style="padding: 10px; border: 1px solid #bbbbbb; text-align: right;">{{ $item['package'] }}</td>
            <td style="padding: 10px; border: 1px solid #bbbbbb; text-align: right;">{{ $item['quantity'] }}</td>
            <td style="padding: 10px; border: 1px solid #bbbbbb; text-align: right;">£{{ number_format(($item['price'] /100), 2) }}</td>
        </tr>
    @endforeach
    <tr>
        <td style="padding: 10px; border: 1px solid #bbbbbb; text-align: right;" colspan="3">Shipping fee £{{ $data['shipping_fee'] }}</td>
    </tr>
    <tr>
        <td colspan="3" style="padding: 10px; font-size: 1.5em; font-weight: 700; border: 1px solid #bbbbbb; text-align: right;">Total £{{ number_format($data['amount'] / 100, 2) }}</td>
    </tr>
    </tbody>
</table>