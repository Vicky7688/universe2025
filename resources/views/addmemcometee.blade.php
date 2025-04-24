@include('include.header')


<form action="{{ $formurl }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-11">
                            <input type="hidden" value="{{ $commeteeid->id }}" name="commeteeid">
                            <h2>{{ $commeteeid->name }}</h2>
                            <h5>{{ $commeteeid->duration }} {{ $commeteeid->durationtype }}</h5>
                        </div>
                        <div class="col-1">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Comettee Start Date</th>
                                <th>Comettee End Date</th>
                                <th>Mobile</th>
                                <th>Committee Amount</th>
                                <th><input type="checkbox" id="checkall" onclick="fdfcheckall()"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($member_accounts))
                                @foreach ($member_accounts as $member_accountslist)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $member_accountslist->customer_Id }}</td>
                                        <td>{{ $member_accountslist->name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($commeteeid->datefrom)) }}</td>
                                        <td>{{ date('d-m-Y', strtotime($commeteeid->dateto)) }}</td>
                                        <td>{{ $member_accountslist->mobile_first }}</td>
                                        <td>{{ $commeteeid->commetiamount }}</td>
                                        <td>
                                            <input
                                                @if(DB::table('commetee_members')->where('comm_id', '=', $commeteeid->id)->where('member_id', '=', $member_accountslist->id)->count() > 0)
                                                    @checked(true)
                                                @endif
                                                name="customer[]"
                                                value="{{ $member_accountslist->id }}"
                                                type="checkbox"
                                                class="checkall">
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</form>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    function fdfcheckall() {
        // alert(8656875);
        if ($("#checkall").prop('checked') == true) {
            $('.checkall').prop('checked', true);
        } else {
            $('.checkall').prop('checked', false);
        }
    }
</script>
@include('include.footer')
