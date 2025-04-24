@foreach($cometlist as $cometlistt)

@php
$alreadypaid = DB::table('commeti_recoveries')
->where('cometee', '=', $comati->id)
->where('memberid', '=', $cometlistt->id)
->whereMonth('paymentdate', '=', date('m', strtotime($comatidate))) // Check only the month
->whereYear('paymentdate', '=', date('Y', strtotime($comatidate))) // Check only the year
->first();
@endphp
<tr>  
<td>{{ $loop->iteration }}</td>
<td>({{ $cometlistt->customer_Id }}){{ $cometlistt->name }}</td>
<td>{{ date('d-m-Y', strtotime($comati->datefrom)) }}  </td>
<td> {{ date('d-m-Y', strtotime($comati->dateto)) }}</td> 
<td><input type="text" name="paymentdate[{{ $loop->index }}]" @if($alreadypaid) value="{{ date('d-m-Y', strtotime($alreadypaid->paymentdate)) }}"  @else  value="{{ date('d-m-Y', strtotime($comatidate)) }}" @endif ></td>
<td><input type="text" name="amount[{{ $loop->index }}]" @if($alreadypaid) value="{{ $alreadypaid->amount }}"  @else value="{{ $comati->commetiamount }}" @endif ></td>
<td><input class="checkall" type="checkbox" name="memberid[{{ $loop->index }}]" value="{{ $cometlistt->id }}"></td>
@if($alreadypaid)

<td> <img src="{{ url('public/check.png') }}" alt="check"> </td>
<td>
<a href="javascript:void()" onclick="deleterecoverycometti({{ $alreadypaid->id }})">
<img src="{{ url('public/mark.png') }}" alt="mark">
</a></td>
@else
<td> </td>
<td> </td>
@endif
</tr>
@endforeach