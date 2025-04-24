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
                                        <input type="text" class="form-control form-input-controller datepicker"
                                            name="currentdate" id="currentdate" value="{{ now()->format('d-m-Y') }}">
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <label class="form-label">Payment Mode</label>
                                        <select class="form-select   form-input-controller" name="paymentmode" required>
                                            <option value="Cash">Cash</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <h5 id="customername"></h5>
                                        <h5 id="loanadvancementamounts"></h5>
                                        <h5 id="creditedamounst"></h5>
                                        <h5 id="loanadvancementDates"></h5>
                                        <h5 id="loantenures"></h5>
                                        <h5 id="interstshow"></h5>
                                    </div>

                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3 col-sm-6">
                                        <label class="form-label">Account name</label>
                                        <input type="text" id="customer_Id" name="customer_Id"
                                            class="form-control form-input-controller " required=""
                                            list="list-timezone" id="input-datalist" oninput="getforclosure(this.value)"
                                            onchange="getforclosure(this.value)">

                                        <datalist id="list-timezone">
                                            @if (sizeof($member_accounts) > 0)
                                                @foreach ($member_accounts as $member_accountss)
                                                    <option value="{{ $member_accountss->customer_Id }}">
                                                        ({{ $member_accountss->customer_Id }})-{{ $member_accountss->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </datalist>
                                    </div>

                                    <div class="col-md-2 col-sm-12">
                                        <label class="form-label">Intt. Type</label>
                                        <select name="interestType" id="interestType" class="form-select form-input-controller" onchange="getInterestType(this)">
                                            <option value=""selected>Select Intt.Type</option>
                                            {{--  <option value="Reduce">Reduce</option>  --}}
                                            <option value="Flat">Flat</option>
                                        </select>
                                    </div>




                                    <div class="col-md-2 col-sm-12">
                                        <label class="form-label">Total Principle</label>
                                        <input type="text" id="totalpayment" name="totalpayment"
                                            class="form-control form-input-controller onlynumberwithonedot"
                                            required="">
                                    </div>



                                    <div class="col-md-2 col-sm-12">
                                        <label class="form-label">Total Interest</label>
                                        <input type="text" id="totalintrest" name="totalintrest"
                                            class="form-control form-input-controller onlynumberwithonedot"
                                            required="">
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <label class="form-label">Penalty</label>
                                        <input type="text" id="panelty" name="panelty"
                                            class="form-control form-input-controller onlynumberwithonedot"
                                            required="">
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <label class="form-label">Balance</label>
                                        <input type="text" id="totalloanpayment" name="totalloanpayment"
                                            class="form-control form-input-controller onlynumberwithonedot" readonly>
                                    </div>

                                    <div class="col-md-12 col-sm-12">
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
                            <input type="text" readonly value="cash"
                                class="form-control form-input-controller date1 hasDatepicker" name="paymentmode"
                                id="editpaymentmode">
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
                            <label for="" class="form-label"> Principle</label>
                            <input type="text" id="editprinciple" name="principle"
                                class="form-control form-input-controller    onlynumberwithonedot">
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label">Intrest</label>
                            <input type="text" id="editintrest" name="intrest"
                                class="form-control form-input-controller    onlynumberwithonedot">
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label">Panelty</label>
                            <input type="text" id="editpanelty" name="panelty"
                                class="form-control form-input-controller    onlynumberwithonedot">
                        </div>
                    </div>




                    <div class="row">
                        <div class="col-md-4">
                            <label for="" class="form-label"> Total Payment </label>
                            <input type="text" id="edittotalpayment" name="totalpayment"
                                class="form-control form-input-controller    onlynumberwithonedot">
                        </div>

                        <div class="col-md-8">
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    function getInterestType(ele){
        let interestType = $(ele).val();
        let customer_Id = $('#customer_Id').val();
        let currentdate = $('#currentdate').val();

        $.ajax({
            url : "{{ route('getinterestType') }}",
            type : 'post',
            data : {
                interestType : interestType,
                customer_Id : customer_Id,
                currentdate:currentdate,
                _token: '{{ csrf_token() }}'
            },
            success : function(res){
                if(res.status === 'success'){
                    let memberLoan = res.memberLoan;
                    let recoveries = res.recoveries ? res.recoveries : [];
                    let differencemonth = res.differencemonth;
                    let loanInterest = 0;
                    let months = 0;
                    let lastbalance = 0;
                    let interestamount = 0;
                    let amountwithinterest = 0;
                    let receivedInterest = 0;

                    loanamount = parseFloat(memberLoan.loanAmount) || 0;
                    loanInterest = parseFloat(memberLoan.loanInterest) || 0;
                    {{--  months = parseFloat(memberLoan.months) || 0;  --}}
                    let netinterest = Math.round(((loanamount * loanInterest)/100)*differencemonth)

                    if(memberLoan || recoveries){
                        if(interestType === 'Flat'){
                            loanamount = parseFloat(memberLoan.loanAmount) || 0;
                            loanInterest = parseFloat(memberLoan.loanInterest) || 0;
                            {{--  months = parseFloat(memberLoan.months) || 0;  --}}
                            let netinterest = Math.round(((loanamount * loanInterest)/100)*differencemonth)
                            interestamount = Math.round((loanamount * loanInterest)/100);
                            let aaaaa = 0;

                            if(Array.isArray(recoveries) && recoveries.length > 0){
                                recoveries.forEach((data) => {
                                    let principal = parseFloat(data.principal) || 0;
                                    let interest_received = parseFloat(data.interest) || 0;
                                    lastbalance += parseFloat(principal) - parseFloat(interest_received);
                                    receivedInterest += principal + interest_received;
                                });

                                aaaaa = loanamount - lastbalance || 0;

                                let closingbalance = aaaaa ? aaaaa : loanamount;
                                amountwithinterest = (((loanamount + netinterest) - receivedInterest) + interestamount);
                                console.log(aaaaa,closingbalance,netinterest,receivedInterest);


                                $('#totalpayment').val(closingbalance);
                                $('#totalintrest').val(interestamount);
                                $('#panelty').val(0);
                                $('#totalloanpayment').val(amountwithinterest);
                            }else{
                                aaaaa = loanamount - lastbalance || 0;

                                let closingbalance = aaaaa ? aaaaa : loanamount;
                                amountwithinterest = (((loanamount + netinterest) - receivedInterest) + interestamount);
                                console.log(aaaaa,closingbalance,netinterest,receivedInterest);


                                $('#totalpayment').val(closingbalance);
                                $('#totalintrest').val(interestamount);
                                $('#panelty').val(0);
                                $('#totalloanpayment').val(amountwithinterest);
                            }
                        }else if(interestType === 'Reduce'){

                            loanamount = parseFloat(memberLoan.loanAmount) || 0;
                            loanInterest = parseFloat(memberLoan.loanInterest) || 0;
                            {{--  months = parseFloat(memberLoan.months) || 0;  --}}
                            {{--  interestamount = ((loanamount * loanInterest)/100);  --}}

                            if(Array.isArray(recoveries) && recoveries.length > 0){
                                recoveries.forEach((data) => {
                                    let principal = parseFloat(data.principal) || 0;
                                    lastbalance += principal;
                                });

                                let closingbalance = loanamount - lastbalance;
                                interestamount = ((closingbalance * loanInterest)/100);
                                amountwithinterest = closingbalance+ interestamount;

                                $('#totalpayment').val(closingbalance);
                                $('#totalintrest').val(interestamount);
                                $('#panelty').val(0);
                                $('#totalloanpayment').val(amountwithinterest);
                            }
                        }else{
                            $('#totalpayment').val(0);
                            $('#totalintrest').val(0);
                            $('#panelty').val(0);
                            $('#totalloanpayment').val(0);
                        }
                    }else{
                        $('#totalpayment').val(0);
                        $('#totalintrest').val(0);
                        $('#panelty').val(0);
                        $('#totalloanpayment').val(0);
                    }

                }else{
                    alret(res.messages);
                }
            }
        });
    }


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
            url: '{{ route('getquickrecoveryfor') }}',
            type: 'GET',
            data: {
                date: currentdate
            }, // Send the date as a parameter
            success: function(response) {
                $('#dinchak').empty();
                response.forEach(function(item) {

                    var installmentDate = new Date(item.receiptDate);
                    var day = installmentDate.getDate();
                    var month = installmentDate.getMonth() + 1;
                    var year = installmentDate.getFullYear();
                    day = day < 10 ? '0' + day : day;
                    month = month < 10 ? '0' + month : month;
                    var formattedInstallmentDate = day + '-' + month + '-' + year;


                    const row = `
                                    <tr>
                                        <td>${formattedInstallmentDate}</td>
                                        <td style="text-align: left;">(${item.customer_Id})-${item.accountName} </td>
                                        <td>${item.loanName}</td>
                                        <td>${item.principal}</td>
                                        <td>${item.interest}</td>
                                        <td>${item.penalInterest}</td>
                                        <td>${item.receivedAmount}</td>
                                        <td>${item.agent_name}</td>
                                        <td><a href="javascript:void(0)" onclick="getdatarecovery(${item.id})"><img src="{{ url('public/admin/images/edit.png') }}"></a></td>
                                        <td><a href="javascript:void(0)" onclick="confirmDelete(${item.id})"><img src="{{ url('public/admin/images/delete.png') }}"></a></td>

                                    </tr>
                        `;
                    // Append the new row to the tbody
                    $('#dinchak').append(row);
                });
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

                $('#editid').val(response.loan_recovery.id);
                $('#edittotalpayment').val(response.loan_recovery.receivedAmount - response.loan_recovery
                    .penalInterest);
                $('#editpanelty').val(response.loan_recovery.penalInterest);
                $('#editremarks').val(response.loan_recovery.remarks);

                $('#editcustomer_Id').val(response.customer_account.customer_Id);
                $('#editcurrentdate').val(formattedInstallmentDate);
                $('#exampleModal').modal('show');
            }
        });

    }





    function getdatarecovery(id) {

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

                $('#editid').val(response.loan_recovery.id);
                $('#editprinciple').val(response.loan_recovery.principal);
                $('#editintrest').val(response.loan_recovery.interest);
                $('#editpanelty').val(response.loan_recovery.penalInterest);
                $('#edittotalpayment').val(response.loan_recovery.total);
                $('#editremarks').val(response.loan_recovery.remarks);

                $('#editcustomer_Id').val(response.customer_account.customer_Id);
                $('#editcurrentdate').val(formattedInstallmentDate);
                $('#exampleModal').modal('show');
            }
        });

    }


    document.addEventListener('DOMContentLoaded', e => {
        $('#input-datalist').autocomplete()
    }, false);



    function getforclosure(id) {
        if(id){
            $.ajax({
                url: '{{ route('getforclosure') }}',
                type: 'GET',
                data: {
                    id: id
                },
                success: function(response) {
                    if(response.status === 'success'){
                        let data = response.message;
                        let loanamount = parseFloat(data.loanamount) || 0;
                        let received = parseFloat(data.amount) || 0;
                        let interest = parseFloat(data.interest) || 0;
                        let principle = parseFloat(data.principle) || 0;
                        let credit = parseFloat(principle) + parseFloat(interest);


                        $('#loanadvancementamounts').text(`Loan Amt :- ${loanamount}`);
                        $('#loanadvancementDates').text(`Loan Date :- ${data.loanDate}`);
                        $('#loantenures').text(`Loan Months :- ${data.month}`);
                        $('#interstshow').text(`Rate Of Intt :- ${data.roi}`);
                        $('#customername').text(`Name :- ${data.name}`);
                        $('#creditedamounst').text(`Credit Amt.:- ${credit}`);


                    }else{

                        $('#loanadvancementamounts').text(`Loan Amt :-`);
                        $('#loanadvancementDates').text(`Loan Date :-`);
                        $('#loantenures').text(`Loan Months :-`);
                        $('#interstshow').text(`Rate Of Intt :- `);
                        $('#customername').text(`Name :-`);
                        $('#creditedamounst').text(`Credit Amt.:- `);

                        $('#totalpayment').val(0);
                        $('#totalintrest').val(0);
                        $('#panelty').val(0);
                        $('#totalloanpayment').val(0);
                    }
                    {{--  $('#totalpayment').val(response.message.amount);
                    $('#totalintrest').val(response.message.interestAmount);
                    $('#panelty').val(0);
                    $('#totalloanpayment').val(parseInt(response.message.interestAmount) + parseInt(response
                        .message.amount));  --}}


                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    if (xhr.status === 404) {
                        var errorMessage = xhr.responseJSON ? xhr.responseJSON.message :
                            'An unexpected error occurred.';
                    }
                }
            });
        }else{
            $('#interestType').val("").trigger('change');

        }
    }





    function calculateTotalLoanPayment() {
        var totalpayment = parseFloat(document.getElementById('totalpayment').value) || 0;
        var totalintrest = parseFloat(document.getElementById('totalintrest').value) || 0;
        var panelty = parseFloat(document.getElementById('panelty').value) || 0;
        var totalloanpayment = totalpayment + totalintrest + panelty;
        document.getElementById('totalloanpayment').value = totalloanpayment.toFixed(2);
    }
    document.getElementById('totalpayment').addEventListener('input', calculateTotalLoanPayment);
    document.getElementById('totalintrest').addEventListener('input', calculateTotalLoanPayment);
    document.getElementById('panelty').addEventListener('input', calculateTotalLoanPayment);
    window.addEventListener('load', calculateTotalLoanPayment);

    function submitForm() {
        var formData = $('#paymentForm').serialize();
        $.ajax({
            url: "{{ url('addfourcloserecovery') }}", // Your Laravel route
            type: 'POST',
            data: formData, // Include all form data
            success: function(response) {
                if (response.status) {
                    updatetable();
                    $('#paymentForm')[0].reset(); // Reset the form
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
            url: "{{ url('editfourcloseraddrecovery') }}", // Your Laravel route
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



    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                url: "{{ url('recconfirmDeleterefourclose') }}",
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
</script>
@include('include.footer')
