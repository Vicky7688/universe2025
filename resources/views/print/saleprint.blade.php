<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
</head>

<body style="font-family: Arial, sans-serif; width: 120mm; padding: 10px; border: 1px solid #000;margin: 0px auto;text-transform: uppercase;">
    <div>
        <h2 style="text-align: center; margin: 0;">{{ $mastermain->storname }}</h2>
        <p style="text-align: center; margin: 0;">{{ $mastermain->address }}</p>
        <p style="text-align: center; margin: 0;">GSTIN No: {{ $mastermain->gstno }}</p>
        <p style="text-align: center; margin: 0;">FSSAI: {{ $mastermain->fssai }}</p>
        <p style="text-align: center; margin: 0;">Shop Timings: {{ $mastermain->timmings }}</p>
        <p style="text-align: center; margin: 0;">Phone: {{ $mastermain->phone }}</p>
    </div>
    <hr>
    <div>
        <p style="margin: 0;">Party: {{ $retails->retailercode }}</p>
        <p style="margin: 0;">NAME: {{ $retails->name }}</p>
        <p style="margin: 0;">INVOICE NO: {{ $salemaster->invoiceno }}</p>
        <p style="margin: 0;">DATE: {{ $salemaster->created_at->format('d-m-Y') }}</p>
        <p style="margin: 0;">Time: {{ $salemaster->created_at->format('h:i A') }}</p>  
        <p style="margin: 0;">STATE: {{ DB::table('state')->where('id','=',$retails->state)->value('state_name') }}</p>
        <p style="margin: 0;">GSTIN:{{ $retails->gstno }}</p>
    </div>
    <hr>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <th style="border-bottom: 1px solid #000;">Item Name</th>
            <th style="border-bottom: 1px solid #000; text-align: right;">Type</th>
            <th style="border-bottom: 1px solid #000; text-align: right;">Qty</th>
            <th style="border-bottom: 1px solid #000; text-align: right;">MRP</th>
            <th style="border-bottom: 1px solid #000; text-align: right;">Rate</th>
            <th style="border-bottom: 1px solid #000; text-align: right;">Amount</th>
        </tr>
        @php $totals=0; @endphp
        @php $mrptotals=0; @endphp
        @foreach ($item_sale as $itemsale)
        @if($itemsale->baltype=='box')  
        @php $quantityis=$itemsale->quantity; @endphp
           @else  
           @php $quantityis=$itemsale->pquantity; @endphp
         
             @endif 
            @php $totals=$totals+$itemsale->total; @endphp
            @php $mrptotals=$mrptotals+$itemsale->mrp*$quantityis; @endphp
    <tr>
        <td>{{ DB::table('items')->where('itemcode','=',$itemsale->itemcode)->value('pname') }}</td>
        <td style="text-align: right;">{{ $itemsale->baltype }}</td>
        <td style="text-align: right;">  @if($itemsale->baltype=='box')    {{ $itemsale->quantity }}  @else  {{ $itemsale->pquantity }}  @endif  </td>
        <td style="text-align: right;">{{ $itemsale->mrp }}</td>
        <td style="text-align: right;">{{ $itemsale->salerate }}</td>
        <td style="text-align: right;">{{ $itemsale->total }}</td>
    </tr>
        @endforeach 
    </table>
    <hr>
    <div style="text-align: right;">
        <p style="margin: 0;">TOTAL: ₹ {{ $salemaster->grandtotal }}</p>
        <p style="margin: 0;">MRP Total: ₹ {{ $mrptotals }}</p>
        <p style="margin: 0;">Total Saving: ₹ {{ $mrptotals-$salemaster->grandtotal }}</p>
        <p style="margin: 0;">Received: {{ $salemaster->cashrecieved+$salemaster->cardpayment }}</p>
        {{-- <p style="margin: 0;">Refund: ₹50.00</p> --}}
    </div>
    <hr>
    <div>
        <p style="margin: 0;">Terms & Conditions:</p>
        <p style="margin: 0;">Check your money and goods before leaving counter.</p>
        <p style="margin: 0;">Rates are inclusive of GST.</p>
        <p style="margin: 0;">Subject to MOHALI Jurisdiction.</p>
    </div>
     
    <hr>
    <div style="text-align: right;">
        <p style="margin: 0;">Thank You for Shopping with us.</p>
    </div>
</body>

</html>
