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

    /* #editcurrentdate {
 background: #cacaca !important;
} */
    #editpaymentmode {
        background: #cacaca !important;
    }

    #editcustomer_Id {
        background: #cacaca !important;
    }
</style>

<div class="row">
    <div class="col-xl-12">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form id="paymentForm">
                            @csrf <!-- Include CSRF token for Laravel -->
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <label class="form-label">Date</label>
                                        <input type="text" class="form-control form-input-controller   datepicker"
                                            name="currentdate" id="currentdate" value="{{ now()->format('d-m-Y') }}">
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <label class="form-label">Payment Mode</label>
                                        <select class="form-select form-input-controller" name="paymentmode"
                                            id="paymentmode" required onchange="getgroupsLedgers(this)">
                                            @if (!empty($ledger_masters))
                                                <option value="" selected>Select Group</option>
                                                @foreach ($ledger_masters as $row)
                                                    <option value="{{ $row->groupCode }}">{{ $row->headName }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <script>
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
                                    </script>
                                    <div class="col-md-4">
                                        <label for="" class="form-label">Ledger</label>
                                        <select class="form-select form-input-controller ledgercodesss"
                                            id="ledgercodesss" required>
                                            <option value="" selected>Select Group</option>
                                        </select>
                                    </div>



                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4 col-sm-12">
                                        <label class="form-label">Account name</label>
                                        <input type="text" id="customer_Id" name="customer_Id"
                                            class="form-control form-input-controller " required=""
                                            list="list-timezone" id="input-datalist" onblur="getCustomerId(this.value)">
                                        {{--  onchange="getCustomerId(this.value)"  --}}

                                        <datalist id="list-timezone">
                                            @if (sizeof($member_accounts) > 0)
                                                @foreach ($member_accounts as $member_accountss)
                                                    <option value="{{ $member_accountss->customer_Id }}">
                                                        ({{ $member_accountss->customer_Id }})
                                                        -{{ $member_accountss->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </datalist>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <label class="form-label">Total Payment</label>
                                        <input type="text" id="totalpayment" name="totalpayment"
                                            class="form-control form-input-controller    onlynumberwithonedot"
                                            required="" onblur="checksamedatesameamount('this')">
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <label class="form-label">Panelty</label>
                                        <input type="text" id="panelty" name="panelty"
                                            class="form-control form-input-controller    onlynumberwithonedot"
                                            required="">
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <label class="form-label">Remarks</label>
                                        <input type="text" id="remarks" name="remarks"
                                            class="form-control form-input-controller   " required="">
                                    </div>

                                    <div class="col-md-2 col-sm-12">
                                        <button type="button"
                                            class="chuchu nextTab btn login-btn  rounded-xl gradient-btn"
                                            onClick="submitForm()">Pay Recovery</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Recieved Installments</h4>
                                </div>
                                <div class="col-md-6 text-end remainid" style="visibility:hidden">
                                    <input type="hidden" id="remaininginput">
                                    <h4 style="color: #ec0000;">Balance: <span id="remaining">5000</span></h4>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="table-responsive" style="height: 200px;	overflow: scroll;">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Customer</th>
                                                <th>loan</th>
                                                <th>Principle</th>
                                                <th>Intrest</th>
                                                <th>Panelty</th>
                                                <th>Total Recieved</th>
                                                <th>Remarks</th>
                                                <th>Agent</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dinchak">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-2 fonts">
                            <label for="">Total Principal.</label>
                            <input type="text" class="fonts" readonly name="total_dr" id="total_dr" value="0.00">
                        </div>
                        <div class="col-sm-2 fonts">
                            <label for="">Total Interest.</label>
                            <input type="text" class="fonts" readonly name="total_cr" id="total_cr" value="0.00">
                        </div>
                        <div class="col-sm-2">
                            <label for="">Total Penalty</label>
                            <input type="text" class="fonts" readonly name="penality_amount" id="penality_amount" value="0.00">
                        </div>
                        <div class="col-sm-2">
                            <label for="">Total Received</label>
                            <input type="text" class="fonts" readonly name="totalbalances" id="totalbalances" value="0.00">
                        </div>
                    </div>
                </div>
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
                            <input type="text" class="form-control form-input-controller datepicker"
                                name="currentdate" id="editcurrentdate">
                        </div>

                        <div class="col-md-4">
                            <label for="" class="form-label">Payment Mode</label>
                            <select class="form-select form-input-controller" name="paymentmode" id="editpaymentmode"
                                required onchange="getgroupsLedgers(this)">
                                @if (!empty($ledger_masters))
                                    <option value="" selected>Select Group</option>
                                    @foreach ($ledger_masters as $row)
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
                            <label for="" class="form-label">Panelty</label>
                            <input type="text" id="editpanelty" name="panelty"
                                class="form-control form-input-controller    onlynumberwithonedot">
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label">Remarks</label>
                            <input type="text" id="editremarks" name="remarks"
                                class="form-control form-input-controller   ">
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

<div class="modal fade" id="exampleModalsssss" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Installments</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="scroll-wrap">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Emi date</th>
                                <th>Principle</th>
                                <th>Intrest</th>
                                <th>Installment</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="installmentsTableBodys">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>





<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function() {
        let currentYear = new Date().geYear;
        $('.date1').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
        });

        $(document).on('change', '#currentdate', function() {
            let currentdate = $(this).val();
            ajaxchla(currentdate);
        });
    });

    $(document).ready(function() {
        var currentdate = $('#currentdate').val();
        ajaxchla(currentdate);
    });

    function updatetable() {
        var currentdate = $('#currentdate').val();
        ajaxchla(currentdate);
    }

    function ajaxchla(currentdate) {
        $.ajax({
            url: '{{ route('getquickrecovery') }}',
            type: 'GET',
            data: {
                date: currentdate
            }, // Send the date as a parameter
            success: function(response) {
                $('#dinchak').empty();

                let totalReceivedAmount = 0;
                let grandtotalprincipal = 0;
                let grandtotalinterest = 0;
                let grandtotalpenality = 0;
                let totalreceived = 0;

                response.forEach(function(item) {
                    var installmentDate = new Date(item.receiptDate);
                    var day = installmentDate.getDate();
                    var month = installmentDate.getMonth() + 1;
                    var year = installmentDate.getFullYear();
                    day = day < 10 ? '0' + day : day;
                    month = month < 10 ? '0' + month : month;
                    var formattedInstallmentDate = day + '-' + month + '-' + year;

                    totalReceivedAmount += parseFloat(item.receivedAmount);

                    let rtotal = parseFloat(item.receivedAmount) - parseFloat(item.penalInterest);

                    const row = `
                    <tr>
                        <td>${formattedInstallmentDate}</td>
                        <td style="text-align: left;">(${item.customer_Id})-${item.accountName} </td>
                        <td>${item.loanName}</td>
                        <td>${item.principal}</td>
                        <td>${item.interest}</td>
                        <td>${item.penalInterest}</td>
                        <td>${rtotal}</td>
                        <td>${item.remarks}</td>
                        <td>${item.agent_name}</td>
                        <td><a href="javascript:void(0)" onclick="getdatarecovery(${item.id})"><img src="{{ url('public/admin/images/edit.png') }}"></a></td>
                        <td><a href="javascript:void(0)" onclick="confirmDelete(${item.id})"><img src="{{ url('public/admin/images/delete.png') }}"></a></td>
                    </tr>
                `;
                    // Append the new row to the tbody
                    $('#dinchak').append(row);

                    grandtotalprincipal += parseFloat(item.principal) ? parseFloat(item.principal) : 0;
                    grandtotalinterest += parseFloat(item.interest) ? parseFloat(item.interest) : 0;
                    grandtotalpenality += parseFloat(item.penalInterest) ? parseFloat(item.penalInterest) : 0;
                    totalreceived += parseFloat(item.receivedAmount) ? parseFloat(item.receivedAmount) : 0;


                });


                $('#total_dr').val(grandtotalprincipal.toFixed(2));
                $('#total_cr').val(grandtotalinterest.toFixed(2));
                $('#penality_amount').val(grandtotalpenality.toFixed(2));
                $('#totalbalances').val(totalreceived.toFixed(2));




                // After looping through the data, append the total row at the end
                {{--  const totalRow = `
                <tr>
                    <td colspan="6" style="text-align: right;"><strong>Total:</strong></td>
                    <td><strong>${totalReceivedAmount.toFixed(2)}</strong></td>
                    <td colspan="3"></td>
                </tr>
            `;  --}}
                // Append the total row to the tbody
                $('#dinchak').append(totalRow);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ', error);
            }
        });
    }

    function getdatarecovery(id) {
        $.ajax({
            url: "{{ url('getdatarecovery') }}",
            type: 'POST',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
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

                $('#editid').val(response.loan_recovery.id);
                $('#edittotalpayment').val(response.loan_recovery.receivedAmount - response.loan_recovery
                    .penalInterest);
                $('#editpanelty').val(response.loan_recovery.penalInterest);
                $('#editremarks').val(response.loan_recovery.remarks);
                $('#editcustomer_Id').val(response.customer_account.customer_Id);
                $('#editcurrentdate').val(formattedInstallmentDate);
                $('#editpaymentmode').val(response.loan_recovery.group_code).change();
                $('#editledgercodesss').val(response.loan_recovery.ledger_code);
                $('#exampleModal').modal('show');
            }
        });
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

    function submitForm() {
        var formData = $('#paymentForm').serialize();
        $.ajax({
            url: "{{ url('addrecovery') }}", // Your Laravel route
            type: 'POST',
            data: formData, // Include all form data
            success: function(response) {
                if (response.status) {

                    var crdate = $('#currentdate').val();
                    updatetable();
                    $('#paymentForm')[0].reset(); // Reset the form
                    $('#currentdate').val(crdate);
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

    function Dateformat(date) {
        let dates = new Date(date);
        let days = dates.getDate();
        let month = dates.getMonth() + 1;
        let year = dates.getFullYear();

        days = days < 10 ? `0${days}` : days;
        month = month < 10 ? `0${month}` : month;
        let formatedDate = `${days}-${month}-${year}`;
        return formatedDate;
    }

    function getCustomerId(id) {
        $.ajax({
            url: "{{ route('gecustomertloans') }}",
            type: 'post',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    let loanInstallments = res.loanInstallments;

                    $('#installmentsTableBodys').empty();
                    let status = "";

                    if (Array.isArray(loanInstallments) && loanInstallments.length > 0) {
                        loanInstallments.forEach((data, index) => {
                            if (data.status === 'false') {
                                status = 'Unpaid';
                            } else {
                                status = 'Paid';
                            }
                            let row = `<tr>
                                <td>${index+1}</td>
                                <td>${Dateformat(data.installmentDate)}</td>
                                <td>${data.principal}</td>
                                <td>${data.interest}</td>
                                <td>${data.totalinstallmentamount}</td>
                                <td>${status}</td>
                            </tr>`;

                            $('#installmentsTableBodys').append(row);
                            $('#exampleModalsssss').modal('show');
                        });
                    } else {
                        $('#installmentsTableBodys').empty();
                        $('#exampleModalsssss').modal('hide');
                    }
                } else {
                    {{--  alert('no loan');  --}}
                }
            }
        });
    }

    function checksamedatesameamount() {
        // Collect Input Values
        let currentDate = $('#currentdate').val();
        let accountno = $('#customer_Id').val();
        let enteredamount = $('#totalpayment').val();

        // AJAX Call
        $.ajax({
            url: "{{ route('checkduplicateentryaccount') }}",
            type: 'POST',
            data: {
                currentDate: currentDate,
                accountno: accountno,
                enteredamount: enteredamount,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    // Clear the total payment field if a duplicate exists
                    if (res.messages.includes('already exists')) {
                        $('#totalpayment').val('');
                    }
                    {{--  alert(res.messages);  --}}
                } else {
                    // Display failure message
                    {{--  alert(res.messages.join('\n'));  --}}
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while checking for duplicate entries. Please try again.');
                console.error(xhr.responseText);
            }
        });
    }
</script>
@include('include.footer')
