@include('include.header')
<style>
    #scroll-wrap {
        max-height: 50vh;
        overflow-y: auto;
    }

    .chuchu {
        float: right;
        padding: 3px;
        margin-top: 26px;
        font-size: 17px;
        width: 100%;
        color: #fff;
    }

    .autocomplete-suggestions {
        border: 1px solid #ccc;
        max-height: 150px;
        overflow-y: auto;
        position: absolute;
        background: #fff;
        z-index: 1000;
        width: 30%;
    }

    .autocomplete-suggestion {
        padding: 8px;
        cursor: pointer;
    }

    .autocomplete-suggestion:hover {
        background: #ddd;
    }

    .form-navigation {
        display: flex;
        justify-content: space-between;
    }

    .tab-pane:not(:first-child) .prev-btn {
        display: inline-block;
    }

    .tab-pane:last-child .next-btn {
        display: none;
    }

    .customer_id {
        position: relative;
        padding-bottom: 1px;
        font-size: 12px;
    }

    .customer_id::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 1px;
        /* Thickness of the line */
        background-color: #92989e;
        /* Color of the line */
        font-size: 15px;
        font-weight: 700;

    }

    .form-input-controller {
        padding: 7px 5px !important;
        border-radius: 5px !important;
    }

    .selectize-input.items.required.invalid.not-full.has-options {
        padding: 7px 5px;
        border-radius: 5px;
    }

    .modal-content {
        padding: 27px;
    }

    #editcurrentdate {
        background: #cacaca !important;
    }

    #editpaymentmode {
        background: #cacaca !important;
    }

    #editcustomer_Id {
        background: #cacaca !important;
    }

    .text-danger {
        font-size: 12px;
    }

    .error {
        font-size: 12px;
        color: red;
    }

    tbody tr td {
        font-size: 13px;
    }

    .table tr>th {
        font-size: 12px !important;
        font-weight: 600;
        padding: 11px 6px;
        font-size: 15px;
        font-weight: 700;
    }

    .btn-primary {
        padding: 3px 15px;
    }

    .boldkrde {
        font-weight: bold;
    }

    .fonts {
        font-size: 15px;
        font-weight: 700;
    }

    .form-control.fonts.showhere {
        background-color: #f9f9f9 !important;
        border: 1px solid #ccc !important;
        color: #333 !important;
        font-size: 14px !important;
    }

    {{--  table  --}} .card-body {
        max-height: 50vh;
        /* Set max height for scrolling */
        overflow-y: auto;
        border: 1px solid #ddd;
        /* Optional border */
    }

    .card-body table {
        width: 100%;
        border-collapse: collapse;
    }

    .selected {
        background-color: #3cbade !important;
        /* Dark Background */
        color: white !important;
        /* White Text */
    }
</style>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ $formurl }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="mb-2 col-md-4">
                            <label class="form-label">Account name </label>

                            <select name="input-datalist" id="input-datalist" oninput="getCustomerId(this.value)"
                                onchange="getCustomerId(this.value)">
                                @if (sizeof($member_accounts) > 0)
                                    @foreach ($member_accounts as $member_accountss)
                                        <option
                                            @if (Session::has('emisess')) @if (Session::get('emisess') == $member_accountss->customer_Id) @selected(true) @endif
                                            @endif
                                            >{{ $member_accountss->name }}({{ $member_accountss->customer_Id }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            {{--  <input type="text" class="form-control fonts showhere"

                                placeholder="Search Account name" list="list-timezone" id="input-datalist"
                                oninput="getCustomerId(this.value)" onchange="getCustomerId(this.value)">
                            <datalist id="list-timezone">
                                @if (sizeof($member_accounts) > 0)
                                    @foreach ($member_accounts as $member_accountss)
                                        <option>{{ $member_accountss->name }}({{ $member_accountss->customer_Id }})
                                        </option>
                                    @endforeach
                                @endif
                            </datalist>  --}}
                        </div>
                        <div class="mb-2 col-md-4">
                            <button id="downloadExcel" class="btn btn-primary"
                                style="display:none;margin-top: 25px; font-weight:600; color:black;">Download
                                Excel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table align-items-center text-center table-bordered mb-0 data-table fonts">
                    <thead class="tableHeading">
                        <tr class="" style="color: black; font-size:25px; font-weight:800;">
                            <th style="color: black; font-size:25px; font-weight:800;">Date</th>
                            <th style="color: black; font-size:25px; font-weight:800;">Loan</th>
                            <th style="color: black; font-size:25px; font-weight:800;">Description</th>
                            <th style="color: black; font-size:25px; font-weight:800;">Agent</th>
                            <th style="color: black; font-size:25px; font-weight:800;">Debit</th>
                            <th style="color: black; font-size:25px; font-weight:800;">Intrest</th>
                            <th style="color: black; font-size:25px; font-weight:800;">Panelty</th>
                            <th style="color: black; font-size:25px; font-weight:800;">Credit</th>
                            <th style="color: black; font-size:25px; font-weight:800;">Remarks</th>
                            <th style="color: black; font-size:25px; font-weight:800;">Balance</th>
                            <th style="color: black; font-size:25px; font-weight:800;">Edit</th>
                            <th style="color: black; font-size:25px; font-weight:800;">Delete</th>
                        </tr>
                    </thead>
                    <tbody id="dinchak"> </tbody>
                </table>
            </div>
            {{--  <div class="card-body" id="totaldiv">

            </div>  --}}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-2 fonts">
                        <label for="">Total Dr.</label>
                        <input type="text" class="fonts" readonly name="total_dr" id="total_dr">
                    </div>
                    <div class="col-sm-2 fonts">
                        <label for="">Total Cr.</label>
                        <input type="text" class="fonts" readonly name="total_cr" id="total_cr">
                    </div>
                    <div class="col-sm-2">
                        <label for="">Penalty</label>
                        <input type="text" class="fonts" readonly name="penality_amount" id="penality_amount">
                    </div>
                    <div class="col-sm-2">
                        <label for="">Balance</label>
                        <input type="text" class="fonts" readonly name="totalbalances" id="totalbalances">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Recovery</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="">
                <form id="editpaymentForm">
                    @csrf
                    <div class="row">
                        <input type="hidden" id="editid" name="id">
                        <div class="col-md-4">
                            <label for="" class="form-label">Date</label>
                            <input type="text" class="form-control form-input-controller date1 hasDatepicker"
                                name="currentdate" id="editcurrentdate">
                        </div>
                        {{--  <div class="col-md-4">
                            <label for="" class="form-label">Payment Mode</label>
                            <input type="text" readonly value="cash"
                                class="form-control form-input-controller date1 hasDatepicker" name="paymentmode"
                                id="editpaymentmode">
                        </div>  --}}
                        <div class="col-md-4">
                            <label for="" class="form-label">Account</label>
                            <input type="text" readonly value="cash"
                                class="form-control form-input-controller date1 hasDatepicker" name="customer_Id"
                                id="editcustomer_Id">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="" class="form-label"> Total Payment </label>
                            <input type="text" id="edittotalpayment" name="totalpayment"
                                class="form-control form-input-controller    onlynumberwithonedot">
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label">Emi Amount</label>
                            <input type="text" id="emiamount" name="emiamount"
                                class="form-control form-input-controller    onlynumberwithonedot">
                        </div>

                        <div class="col-md-4">
                            <label for="" class="form-label">Panelty</label>
                            <input type="text" id="editpanelty" name="panelty"
                                class="form-control form-input-controller    onlynumberwithonedot">
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label">Remarks</label>
                            <input type="text" id="editremarks" name="remarks"
                                class="form-control form-input-controller   ">
                        </div>

                        <div class="col-md-4">
                            <label for="" class="form-label">Payment Mode</label>
                            <select name="paymentmode" id="paymentmode" class="form-select form-input-controller"
                                onchange="getgroupsLedgers(this)">
                                @if (!empty($group_masters))
                                    <option value="">Select Group</option>
                                    @foreach ($group_masters as $row)
                                        <option value="{{ $row->groupCode }}">{{ $row->headName }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="" class="form-label">Ledger</label>
                            <select class="form-select form-input-controller ledgercodesss" id="editledgercodesss"
                                name="editledgercodesss" required>
                                <option value="" selected>Select Group</option>
                            </select>
                        </div>



                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <button type="button" class="chuchu nextTab btn login-btn  rounded-xl gradient-btn"
                                onClick="editsubmitForm()">Update Recovery</button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="exampleModall" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit loan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="">
                <form id="loaneditpaymentForm">
                    @csrf
                    <div class="row">
                        <input type="hidden" id="loaneditid" name="id">
                        <div class="col-md-4">
                            <label for="" class="form-label">Date</label>
                            <input type="text" class="form-control form-input-controller date1 hasDatepicker"
                                name="loanDate" id="editloanloanDate">
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label">Emi Date</label>
                            <input type="text" class="form-control form-input-controller date1 hasDatepicker"
                                name="emi_date" id="editloanemidate">
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label">loan Amount</label>
                            <input type="text" class="form-control form-input-controller date1" name="amount"
                                id="editloanloanAmount">
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label">Customer Id</label>
                            <input type="text" class="form-control form-input-controller date1" name="accountNo"
                                id="editloanaccountNo">
                        </div>

                        <div class="col-md-4">
                            <label for="" class="form-label">Name</label>
                            <input type="text" class="form-control form-input-controller date1 hasDatepicker"
                                name="name" id="editloanloanname">
                        </div>
                        <div class="col-md-4">
                            @php $loanmaster=DB::table('loan_masters')->get(); @endphp
                            <label for="" class="form-label">Loan name</label>
                            <select name="loan" id="editloanloanloanType"
                                class="form-select form-input-controller">
                                <option value="">Select</option>
                                @foreach ($loanmaster as $loanmastername)
                                    <option value="{{ $loanmastername->id }}">{{ $loanmastername->loanname }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            @php $purpose_mastersloanmaster=DB::table('purpose_masters')->get(); @endphp
                            <label for="" class="form-label">Purpose type</label>
                            <select name="purpose" id="editloanloanpurpose"
                                class="form-select form-input-controller">
                                <option value="">Select</option>
                                @foreach ($purpose_mastersloanmaster as $purpose_mastersloanmastername)
                                    <option value="{{ $purpose_mastersloanmastername->id }}">
                                        {{ $purpose_mastersloanmastername->name }} </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-4">
                            <label for="" class="form-label"> Month</label>
                            <input type="text" id="editloanmonths" name="months"
                                class="form-control form-input-controller    onlynumberwithonedot">
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label"> Intrest</label>
                            <input type="text" id="editloanloanloanInterest" name="interest"
                                class="form-control form-input-controller    onlynumberwithonedot">
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label"> Processing Fee</label>
                            <input type="text" id="editloanloanprocessingFee" name="processingFee"
                                class="form-control form-input-controller    onlynumberwithonedot">
                        </div>

                        <div class="col-md-4">
                            <label for="" class="form-label">Penalty Type</label>
                            <select name="paneltytype" id="editloanloanpenaltyInteresttype"
                                class="form-select form-input-controller">
                                <option value="">Panelty Type</option>
                                <option value="percentage">Percentage </option>
                                <option value="flat">Flat</option>
                            </select>
                        </div>


                        <div class="col-md-4">
                            <label for="" class="form-label"> Panelty Fee</label>
                            <input type="text" id="editloanloanpenaltyInterest" name="penaltyInterest"
                                class="form-control form-input-controller onlynumberwithonedot">
                        </div>

                        <div class="col-md-4">
                            <label for="" class="form-label">CashBack</label>
                            <input type="text" id="cashback" name="cashback"
                                class="form-control form-input-controller onlynumberwithonedot">
                        </div>

                        <div class=" col-md-4 col-sm-12">
                            <label class="form-label mb-1" for="status-org">Payment by </label>
                            <select name="paymentby" id="paymentby" class="form-select form-input-controller"
                                onchange="getpayment(this)">
                                @if (!empty($group_masters))
                                    <option value=""selected>Select Payment Type</option>

                                    @foreach ($group_masters as $row)
                                        <option value="{{ $row->groupCode }}">{{ $row->name }}</option>
                                    @endforeach
                                @endif

                                {{--  <option
                                @php
                                    dd($member_loans->loanby);
                                @endphp
                                    @if (!empty($member_loans->loanby)) @if ($member_loans->loanby == 'Cash') @selected(true) @endif
                                    @endif
                                    value="Cash">Cash</option>  --}}
                            </select>
                        </div>

                        <div class=" col-md-4 col-sm-12">
                            <label class="form-label mb-1" for="status-org">Payment by </label>
                            <select name="cashbanktype" id="cashbanktype" class="form-select form-input-controller">
                                <option value=""selected>Select Type</option>
                            </select>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <button type="button" class="chuchu nextTab btn login-btn  rounded-xl gradient-btn"
                                onClick="loaneditsubmitForm()">Update Loan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<script>
    function getpayment(ele) {
        // Hide/show bank fields based on selection (if needed in the future)
        /*
        let name = $(ele).val();
        if (name === 'Cash') {
            $('.bankhaito').hide();
        } else {
            $('.bankhaito').css('display', 'inline-block');
        }
        */

        let group = $(ele).val();

        $.ajax({
            url: "{{ route('getcashbankledgers') }}",
            type: "POST",
            data: {
                group: group
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                $('#cashbanktype').empty(); // Clear existing options

                if (res.status === 'success') {
                    let ledgers = res.ledgers;

                    if (Array.isArray(ledgers) && ledgers.length > 0) {
                        $('#cashbanktype').append(`<option value="">Select Ledger</option>`);

                        ledgers.forEach((data) => {
                            $('#cashbanktype').append(
                                `<option value="${data.ledgerCode}">${data.name}</option>`);
                        });
                    } else {
                        $('#cashbanktype').append(`<option value="">No Record Found</option>`);
                    }
                } else {
                    $('#cashbanktype').append(`<option value="">No Record Found</option>`);
                    alert(res.messages);
                }
            },
            error: function(xhr, status, error) {
                alert("Ajax Error: " + error);
            }
        });
    }

    function getgroupsLedgers(ele) {
        let groupcode = $(ele).val();

        // Determine which section's ledger dropdown to populate
        let ledgerDropdown = $(ele).closest('div').nextAll('.col-md-4').find('select');

        if (groupcode) {
            $.ajax({
                url: "{{ route('getgroupsLedgers') }}",
                type: 'POST',
                data: {
                    groupcode: groupcode
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status === "success") {
                        let ledgers_details = res.ledgers_details;
                        ledgerDropdown.empty();

                        if (Array.isArray(ledgers_details) && ledgers_details.length > 0) {
                            ledgers_details.forEach((data) => {
                                ledgerDropdown.append(
                                    `<option value="${data.ledgerCode}">${data.name}</option>`);
                            });
                        } else {
                            ledgerDropdown.append(`<option value="">Ledger Not Found</option>`);
                        }
                    } else {
                        console.error('Error:', res.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    console.log('Response:', xhr.responseText);
                }
            });
        } else {
            // If no group is selected, reset the ledger dropdown
            ledgerDropdown.empty().append(`<option value="">Select Group</option>`);
        }
    }


    document.addEventListener('DOMContentLoaded', e => {
        $('#input-datalist').autocomplete()
    }, false);

    $(document).ready(function() {
        @if (Session::has('emisess'))
            var cstid = "{{ Session::get('emisess') }}";
            getdataofloan(cstid);
            $.ajax({
                url: '{{ route('getnamebycstmid') }}',
                type: 'GET',
                data: {
                    cstid: cstid
                },
                success: function(response) {
                    $('.showhere').val(response);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ', error);
                }
            });
        @endif
    });


    function loangetdatarecovery(id) {
        // window.open("{{ url('advancement') }}/" + id, "_blank");
        // alert(id);
        $.ajax({
            url: "{{ url('loangetdatarecovery') }}",
            type: 'POST',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                var installmentDate = new Date(response.data.loanDate);
                var day = installmentDate.getDate();
                var month = installmentDate.getMonth() + 1;
                var year = installmentDate.getFullYear();
                day = day < 10 ? '0' + day : day;
                month = month < 10 ? '0' + month : month;
                var formattedInstallmentDate = day + '-' + month + '-' + year;

                var installmentDateemiDate = new Date(response.data.emiDate);
                var day = installmentDateemiDate.getDate();
                var month = installmentDateemiDate.getMonth() + 1;
                var year = installmentDateemiDate.getFullYear();
                day = day < 10 ? '0' + day : day;
                month = month < 10 ? '0' + month : month;
                var installmentDateemiDateemiDate = day + '-' + month + '-' + year;
                // receivedAmount

                $('#loaneditid').val(response.data.id);
                $('#editloanloanname').val(response.data.name);
                $('#editloanloanDate').val(formattedInstallmentDate);
                $('#editloanemidate').val(installmentDateemiDateemiDate);
                $('#editloanaccountNo').val(response.data.accountNo);
                $('#editloanloanAmount').val(response.data.loanAmount);

                $('#paymentby').val(response.data.groupCode).change();
                $('#cashbanktype').val(response.data.ledgerCode);

                $('#cashback').val(response.data.caskback);


                // $('#editloanloanloanType').val(response.data.loanType);
                $('#editloanloanloanType').val(response.data.loanType).change();
                $('#editloanloanpurpose').val(response.data.purpose).change();
                $('#editloanmonths').val(response.data.months);
                $('#editloanloanprocessingFee').val(response.data.processingFee);
                $('#editloanloanloanInterest').val(response.data.loanInterest);
                $('#editloanloanpenaltyInteresttype').val(response.data.penaltyInteresttype).change();
                $('#editloanloanpenaltyInterest').val(response.data.penaltyInterest);
                $('#exampleModall').modal('show');
            }
        });
    }



    function getdatarecovery(id) {
        // alert(id);
        $.ajax({
            url: "{{ url('getdatarecovery') }}",
            type: 'POST',
            data: {
                id: id,
                _token: '{{ csrf_token() }}' // Include CSRF token if needed
            },
            success: function(response) {
                var installmentDate = new Date(response.loan_recovery.receiptDate);
                var day = installmentDate.getDate();
                var month = installmentDate.getMonth() + 1;
                var year = installmentDate.getFullYear();
                day = day < 10 ? '0' + day : day;
                month = month < 10 ? '0' + month : month;
                var formattedInstallmentDate = day + '-' + month + '-' + year;
                // receivedAmount

                let installmentId = response.installmentId;

                $('#editid').val(response.loan_recovery.id);
                $('#edittotalpayment').val(response.loan_recovery.receivedAmount - response.loan_recovery
                    .penalInterest);
                $('#editpanelty').val(response.loan_recovery.penalInterest);
                $('#editremarks').val(response.loan_recovery.remarks);
                $('#emiamount').val(parseFloat(installmentId.principal) + parseFloat(installmentId
                    .interest)).prop('readonly', true);
                $('#paymentmode').val(response.loan_recovery.group_code).change();
                $('#editledgercodesss').val(response.loan_recovery.ledger_code).change();
                $('#editcustomer_Id').val(response.customer_account.customer_Id);
                $('#editcurrentdate').val(formattedInstallmentDate);
                $('#exampleModal').modal('show');
            }
        });

    }
</script>





<script>
    let loanData = []; // Array to store the fetched data

    function updatetable() {
        var data = $('#input-datalist').val();
        getCustomerId(data);
    }

    function getCustomerId(value) {
        const match = value.match(/\((\d+)\)/); // Look for a number inside parentheses
        const customerId = match ? match[1] : null; // Extract the first matching number
        if (customerId) {
            getdataofloan(customerId); // Call the function with the numeric customerId
        }
    }



    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                url: "{{ url('recconfirmDeletere') }}",
                type: 'POST',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}' // Include CSRF token if needed
                },
                success: function(response) {
                    updatetable();
                }
            });
        }
    }

    function getdataofloan(id) {
        $.ajax({
            url: '{{ route('getdataofloan') }}',
            type: 'GET',
            data: {
                id: id
            },
            success: function(response) {
                $('#dinchak').empty();
                let netAmount = 0;
                let totalInterest = 0;
                let totalPenalty = 0;
                let totalCredit = 0;
                let totalDebit = 0;
                let totalnetamount = 0;
                let totalnetamountlat = 0;

                // Clear loanData array before populating
                loanData = [];

                response.forEach(function(item) {



                    var date = new Date(item.date);
                    var day = date.getDate();
                    var month = date.getMonth() + 1;
                    var year = date.getFullYear();
                    day = day < 10 ? '0' + day : day;
                    month = month < 10 ? '0' + month : month;
                    var formattedDate = day + '-' + month + '-' + year;

                    if (item.type === 'credit') {
                        netAmount = parseFloat(item.amount);
                        totalCredit += parseFloat(item.amount);
                        totalnetamount += parseFloat(netAmount) + parseFloat(item.interest);
                        totalnetamountlat += parseFloat(netAmount) + parseFloat(item.interest) +
                            parseFloat(item.penalty);
                        var classss = "class='boldkrde'";


                    } else {
                        totalnetamount = parseFloat(totalnetamount) - parseFloat(item.amount) +
                            parseFloat(item.penalty);
                        totalDebit += parseFloat(item.amount);
                        totalInterest += parseFloat(item.interest) || 0;
                        totalPenalty += parseFloat(item.penalty) || 0;
                        var classss = "";
                    }


                    {{--  console.log();  --}}
                    const row = `
                        <tr ${classss}>
                            <td>${formattedDate}</td>
                            <td style="text-align: left;">${item.loan_id}-${item.loan_name}</td>
                            <td style="text-align: left;">${item.description}</td>
                            <td>${item.agent_name || ''} </td>
                            <td>${item.type === 'credit' ? parseFloat(item.amount).toFixed(2) : '0'}</td>
                            <td>${item.type === 'credit' ? parseFloat(item.interest).toFixed(2) : 0}</td>
                            <td>${item.type === 'credit' ? '' : parseFloat(item.penalty).toFixed(2)}</td>
                            <td>${item.type === 'debit' ? parseFloat(item.amount).toFixed(2) : '0'}</td>
                            <td>${item.remarks ?? ''}</td>
                            <td>${totalnetamount.toFixed(2)}</td>
                            <td>
                                ${item.type === 'credit' ? '' : `<a href="javascript:void(0)" onclick="getdatarecovery(${item.recoveryid})"><img src="{{ url('public/admin/images/edit.png') }}"></a>`}
                                ${item.type === 'debit' ? '' : `<a href="javascript:void(0)" onclick="loangetdatarecovery(${item.id})"><img src="{{ url('public/admin/images/edit.png') }}"></a>`}
                                </td>
                            <td>
                                ${item.type === 'credit' ? '' : `<a href="javascript:void(0)" onclick="confirmDelete(${item.recoveryid})"><img src="{{ url('public/admin/images/delete.png') }}"></a>`}
                                ${item.type === 'debit' ? '' : `<a href="javascript:void(0)" onclick="loanconfirmDelete(${item.id})"><img src="{{ url('public/admin/images/delete.png') }}"></a>`}
                            </td>
                        </tr>
                    `;

                    $('#dinchak').append(row);

                    // Store data for download
                    loanData.push([
                        formattedDate,
                        `${item.loan_id}-${item.loan_name}`,
                        item.description,
                        item.agent_name || '',
                        item.type === 'credit' ? '' : parseFloat(item.interest).toFixed(2),
                        item.type === 'credit' ? '' : parseFloat(item.penalty).toFixed(2),
                        item.type === 'credit' ? parseFloat(item.amount).toFixed(2) : '0',
                        item.type === 'debit' ? parseFloat(item.amount).toFixed(2) : '0',
                        totalnetamount.toFixed(2)
                    ]);



                });

                // <td>${totalCredit.toFixed(2)}</td>
                //         <td>${totalInterest.toFixed(2)}</td>
                //         <td>${totalPenalty.toFixed(2)}</td>
                //         <td>${totalDebit.toFixed(2)}</td>


                $('#totaldiv').show();
                $('#total_dr').val(totalnetamountlat.toFixed(2)).css('text-align', 'right');
                $('#total_cr').val(totalDebit.toFixed(2)).css('text-align', 'right');
                $('#totalbalances').val(totalnetamount.toFixed(2)).css('text-align', 'right');
                $('#penality_amount').val(totalPenalty.toFixed(2)).css('text-align', 'right');




                {{--  const totalsRow = `
                    <tr style="font-weight: bold;">
                       <td>Total</td>
                       <td colspan="2"></td>
                       <td></td>
                       <td>${totalCredit.toFixed(2)}</td>
               <td>${totalInterest.toFixed(2)}</td>
                  <td>${totalPenalty.toFixed(2)}</td>
                     <td>${totalDebit.toFixed(2)}</td>
                        <td>${totalnetamount.toFixed(2)}</td>
                    </tr>
                `;  --}}
                {{--  $('#dinchak').append(totalsRow);  --}}
                $('#downloadExcel').css('display', 'block');

            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ', error);
            }
        });
    }

    $(document).on('click', '#dinchak tr', function() {
        // Remove 'selected' class from all rows
        $('#dinchak tr').removeClass('selected');

        // Add 'selected' class to the clicked row
        $(this).addClass('selected');
    });





    document.getElementById('downloadExcel').addEventListener('click', function() {
        let csvContent = 'data:text/csv;charset=utf-8,';
        csvContent += 'Date,Loan,Description,Agent,Interest,Penalty,Debit,Credit,Balance\n';

        loanData.forEach(function(rowArray) {
            let row = rowArray.join(',');
            csvContent += row + '\n';
        });

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', 'loan_data.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });







    function loanconfirmDelete(id) {
        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                url: "{{ url('deleteadvancement') }}",
                type: 'POST',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}' // Include CSRF token if needed
                },
                success: function(response) {}
            });
            updatetable();
        }

    }


    function loaneditsubmitForm() {
        var formData = $('#loaneditpaymentForm').serialize();
        $.ajax({
            url: "{{ url('loaneditpaymentForm') }}", // Your Laravel route
            type: 'POST',
            data: formData, // Include all form data
            success: function(response) {
                if (response.status) {
                    updatetable();
                    $('#exampleModall').modal('hide');
                    $('#loaneditpaymentForm')[0].reset(); // Reset the form
                }
            },
            error: function(xhr, status, error) {
                try {
                    var jsonResponse = JSON.parse(xhr.responseText);
                    alert(jsonResponse.message);
                } catch (e) {
                    alert('An unexpected error occurred.');
                }
            }
        });
    }



    function editsubmitForm() {
        var formData = $('#editpaymentForm').serialize();
        $.ajax({
            url: "{{ url('editaddrecovery') }}", // Your Laravel route
            type: 'POST',
            data: formData, // Include all form data
            success: function(response) {
                if (response.status) {
                    updatetable();
                    $('#exampleModal').modal('hide');
                    $('#editpaymentForm')[0].reset(); // Reset the form
                }
            },
            error: function(xhr, status, error) {
                try {
                    var jsonResponse = JSON.parse(xhr.responseText);
                    alert(jsonResponse.message);
                } catch (e) {
                    alert('An unexpected error occurred.');
                }
            }
        });
    }
</script>
@include('include.footer')
