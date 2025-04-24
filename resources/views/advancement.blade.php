@include('include.header')
<style>
    .sidebar {
        min-height: 100vh;
    }

    .sidebar a {
        display: block;
        padding: 10px;
        color: #333;
        text-decoration: none;
    }

    .sidebar a:hover {
        background-color: #007bff;
        color: white;
    }

    .navbar {
        background-color: #f8f9fa;
        padding: 10px;
    }

    .gradient-btn {
        background: linear-gradient(45deg, #ff4d4d, #4d94ff);
        color: white;
        font-weight: bold;
        border-radius: 50px;
        margin-bottom: 12px;
    }

    .gradient-btn:hover {
        background: linear-gradient(45deg, #ff4d4d, #4d94ff);
        opacity: 0.9;
        color: #fff;
    }

    .mt9 {
        margin-top: 12px
    }

    .form-input-controller {
        padding: 7px 5px;
        border-radius: 5px
    }

    .form-input-controller:focus {
        border: 1px solid #469BE4;
    }

    .loan-controller label {
        color: #666666
    }

    .loan-advance-tab ul.nav.nav-tabs {
        border-bottom: none;
    }

    .loan-tabs a.nav-link.active {
        border-bottom: 3px solid #4EBEFF !important;
        border: none;
    }

    .loan-tabs li {
        font-size: 16px
    }

    .loan-tabs a.nav-link {
        padding: 9px 16px;
    }




    #scroll-wrap {
        max-height: 50vh;
        overflow-y: auto;
    }

    .chuchu {
        float: right;
        padding: 3px;
        margin-top: 26px;
        font-size: 10px;
        width: 100%;
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

    #limit-message {
        color: rgb(118, 19, 19);
    }

    /* Blinking text effect */
    @keyframes blink {
        0% {
            opacity: 1;
        }

        25% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }

        75% {
            opacity: 1;
        }

        100% {
            opacity: 1;
        }
    }

    .warning {
        background-color: white;
        color: rgb(103, 58, 58);
        /* color: #ff0000;  */
        padding: 10px;
        font-weight: bold;
        animation: blink 1.5s infinite;
        /* Blink every 1 second */
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
{{--  4 oct 2024   --}}
<div class="row">
    <main class="col-md-12 main-content">
        <!-- Main Tabs -->
        <div class="">
            <!-- Loan Advancement Content -->
            <form action="">
                <input type="hidden" name="id" id="id">
                <div class="tab-pane loan-advance-tab fade show active" id="loan-advancement">
                    <!-- Sub Tabs -->
                    <ul class="nav   nav-tabs loan-tabs">
                        <li class="nav-item"> <a class="nav-link active" href="#loan-detail" data-toggle="tab">Loan
                                Detail</a> </li>
                        <li class="nav-item"> <a class="nav-link" href="#customer" data-toggle="tab">Customer</a> </li>
                        <li class="nav-item"> <a class="nav-link" href="#guarantor" data-toggle="tab">Guarantor</a>
                        </li>
                    </ul>
                    <!-- Sub Tab Content -->
                    <div class="tab-content">
                        <!-- Loan Detail Form -->
                        <div class="tab-pane fade show active" id="loan-detail">
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <label class="form-label">Loan Date</label>
                                    <input id="transactionDate" type="text" name="loanDate"
                                        class="form-control form-input-controller  datepicker"
                                        value="{{ date('d-m-Y') }}" required="">
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <label class="form-label">Client no.</label>
                                    <input type="text" id="accountNo" name="accountNo"
                                        class="form-control form-input-controller" required=""
                                        oninput="getid(this.value);getdetail(this.value);getloanamount(this)">
                                    <div id="suggestions" class="autocomplete-suggestions"></div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <label class="form-label">Client Name</label>
                                    <input @if (!empty($member_loans->name)) value="{{ $member_loans->name }}" @endif
                                        type="text" id="name" name="name"
                                        class="form-control form-input-controller " required=""
                                        oninput="getcustomerDetails(this)">
                                    <div id="suggestionss" class="autocomplete-suggestions"></div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <label class="form-label">Amount</label>
                                    <input
                                        @if (!empty($member_loans->loanAmount)) value="{{ $member_loans->loanAmount }}" @endif
                                        type="text" oninput="getloanamount('this')" id="amount" name="amount"
                                        class="form-control form-input-controller onlynumberwithonedot   "
                                        required="">
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <label class="form-label">Loan Name</label>
                                    <select name="loan" id="loan" class="form-select form-input-controller"
                                        onchange="getloanname(this.value)">
                                        <option value="">Loan Name</option>

                                        @foreach ($loan_masters as $loan_masterslist)
                                            <option
                                                @if (!empty($member_loans->loanType)) @if ($member_loans->loanType == $loan_masterslist->id) @selected(true) @endif
                                                @endif
                                                value="{{ $loan_masterslist->id }}">{{ $loan_masterslist->loanname }}
                                            </option>
                                        @endforeach


                                    </select>
                                </div>
                                <script>
                                    function getloanname(id) {
                                        $.ajax({
                                            url: "{{ url('getloanname') }}",
                                            type: 'POST',
                                            data: {
                                                id: id,
                                                _token: '{{ csrf_token() }}' // Include CSRF token if needed
                                            },
                                            success: function(response) {
                                                $('#processingFee').val(response.processingFee);
                                                $('#interest').val(response.interest);
                                                $('#paneltytype').val(response.paneltytype);
                                                $('#penaltyInterest').val(response.penaltyInterest);
                                                $('#months').val(response.months);
                                            }
                                        });


                                    }
                                </script>
                                <div class=" col-md-4 col-sm-12">
                                    <label class="form-label mb-1" for="status-org">Payment by </label>
                                    <select name="paymentby" id="paymentby" class="form-select form-input-controller"
                                        onchange="getpayment(this)">
                                        @if (!empty($groups))
                                            <option value=""selected>Select Payment Type</option>

                                            @foreach ($groups as $row)
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
                                    <select name="cashbanktype" id="cashbanktype"
                                        class="form-select form-input-controller">
                                        <option value=""selected>Select Type</option>
                                    </select>
                                </div>




                                <div class="col-md-4">
                                    <label class="form-label">Proc.Fee</label>
                                    <input type="text" name="processingFee" id="processingFee"
                                        class="form-control form-input-controller onlynumberwithonedot" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Interest</label>
                                    <input type="text" name="interest" id="interest"
                                        class="form-control form-input-controller onlynumberwithonedot" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Panelty Type</label>
                                    <select name="paneltytype" id="paneltytype"
                                        class="form-select  form-input-controller">
                                        <option value="">Panelty Type</option>
                                        <option
                                            @if (!empty($loan_mastersid->paneltytype)) @if ($loan_mastersid->paneltytype == 'percentage') @selected(true) @endif
                                            @endif value="percentage">Percentage </option>
                                        <option
                                            @if (!empty($loan_mastersid->paneltytype)) @if ($loan_mastersid->paneltytype == 'flat') @selected(true) @endif
                                            @endif value="flat">Flat</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Penalty.Int</label>
                                    <input type="text" name="penaltyInterest" id="penaltyInterest"
                                        class="form-control form-input-controller onlynumberwithonedot" required>
                                    <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;">
                                        @error('penaltyInterest')
                                            {{ $message }}
                                        @enderror </small>
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Months</label>
                                    <input type="text" name="months" id="months"
                                        class="form-control form-input-controller onlynumberwithonedot" required>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <label class="form-label">Purpose</label>
                                    <select name="purpose" id="purpose" class="form-select  form-input-controller">


                                        @foreach ($purpose_masters as $purpose_masterslist)
                                            <option
                                                @if (!empty($member_loans->purpose)) @if ($member_loans->purpose == $purpose_masterslist->id) @selected(true) @endif
                                                @endif
                                                value="{{ $purpose_masterslist->id }}">{{ $purpose_masterslist->name }}
                                            </option>
                                        @endforeach


                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Emi Date </label>
                                    <input id="emi_date" type="text" name="emi_date"
                                        class="form-control form-input-controller  datepicker">

                                </div>


                                <div class="col-md-4">
                                    <label class="form-label">Cash Back</label>
                                    <input id="caskback" type="text" name="caskback"
                                        class="form-control form-input-controller">
                                </div>



                                <div class="col-md-4 col-sm-12" style="display:none">
                                    <label class="form-label">Loan Limit</label>
                                    <input type="text" oninput="getloanamount('this')" readonly id="loan_limit"
                                        name="loan_limit" class="form-control">
                                </div>
                                <p class="warning">If Emi date is empty the emi date will be the 10Th of next month by
                                    Loan Date </p>

                                <div class="row mt-3">
                                    <div class="d-flex justify-content-between mb-3">
                                        <button class="prevTab btn login-btn  rounded-xl gradient-btn"
                                            style="visibility: hidden">Previous</button>
                                        <button id="nextTab" type="button"
                                            class=" nextTab btn login-btn  rounded-xl gradient-btn"
                                            onclick="next(this)">Next</button>
                                    </div>
                                </div>

                                {{-- <button type="submit" class="btn login-btn  rounded-xl gradient-btn">Next</button> --}}

                            </div>
            </form>
        </div>

        <div class="tab-pane fade" id="customer">
            <div class="row " id="details-container"> </div>
            <div class="row mt-3">
                <div class="d-flex justify-content-between mb-3">
                    <button type="button" id="prevTab"
                        class=" prevTab btn login-btn  rounded-xl gradient-btn">Previous</button>
                    <button type="button" id="nextTabb"
                        class=" nextTab btn login-btn  rounded-xl gradient-btn">Next</button>
                </div>
            </div>


        </div>
        <div class="tab-pane fade" id="guarantor">
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <label class="form-label">Guarantor Name </label>
                    <input @if (!empty($member_loans->guarantorname)) value="{{ $member_loans->guarantorname }}" @endif
                        type="text" id="guarantorname" name="guarantorname"
                        class="form-control form-input-controller  ">
                </div>
                <div class="col-md-4 col-sm-12">
                    <label class="form-label">Contact No </label>
                    <input @if (!empty($member_loans->guarantorno)) value="{{ $member_loans->guarantorno }}" @endif
                        type="text" id="guarantorno" name="guarantorno"
                        class="form-control form-input-controller  onlynumberwithonedot">
                </div>
                <div class="col-md-12 col-sm-12">
                    <label class="form-label">Address </label>
                    <input @if (!empty($member_loans->guarantoraddress)) value="{{ $member_loans->guarantoraddress }}" @endif
                        type="text" id="guarantoraddress" name="guarantoraddress"
                        class="form-control form-input-controller  ">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <label class="form-label">Guarantor Name </label>
                    <input @if (!empty($member_loans->guarantornamee)) value="{{ $member_loans->guarantornamee }}" @endif
                        type="text" id="guarantornamee" name="guarantornamee"
                        class="form-control form-input-controller   ">
                </div>
                <div class="col-md-4 col-sm-12">
                    <label class="form-label">Contact No </label>
                    <input @if (!empty($member_loans->guarantornoo)) value="{{ $member_loans->guarantornoo }}" @endif
                        type="text" id="guarantornoo" name="guarantornoo"
                        class="form-control form-input-controller  onlynumberwithonedot ">
                </div>
                <div class="col-md-12 col-sm-12">
                    <label class="form-label">Address </label>
                    <input @if (!empty($member_loans->guarantoraddresss)) value="{{ $member_loans->guarantoraddresss }}" @endif
                        type="text" id="guarantoraddresss" name="guarantoraddresss"
                        class="form-control form-input-controller  ">
                </div>
            </div>
            <div class="row mt-3">
                <div class="d-flex justify-content-between mb-3">

                    <button id="prevTabb" class=" prevTab btn login-btn  rounded-xl gradient-btn">Previous</button>
                    <button type="submit" class="nextTab btn login-btn  rounded-xl gradient-btn">Submit</button>
                </div>
            </div>
        </div>
</div>
</div>
</form>

<div class="row">

    <h5 class="mt-2">Installment Detail</h5>
    <table class="table table-bordered table-scroll">
        <thead>
            <tr>
                <th>Sr.No</th>
                <th>Loan date</th>
                <th>Loan Name</th>
                <th>Loan Amount</th>
                <th>Recovered</th>
                <th>Rate of Intrest</th>
                <th>Gurrenter 1</th>
                <th>Gurrenter 2</th>
                <th>Agent</th>
                <th>View</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody id="tbody">
        </tbody>
    </table>
</div>
</div>
</main>


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <tbody id="installmentsTableBody">
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
</div>
<script>
    $(document).ready(function() {
        $('form').on('submit', function(event) {
            event.preventDefault();
            var $submitButton = $(this).find('button[type="submit"]');
            $submitButton.prop('disabled', true);

            var formData = $(this).serialize();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: formData,
                success: function(response) {
                    console.log(response.id);
                    $('#accountNo').val(response.id);
                    getdetail(response.id);
                    $('form')[0].reset();
                    alert('Loan Added');
                    $submitButton.prop('disabled', false);
                },
                error: function(xhr) {
                    let response;

                    try {
                        response = JSON.parse(xhr.responseText);
                    } catch (e) {
                        response = {
                            message: 'An unknown error occurred.'
                        };
                    }

                    alert(response.message);
                    console.log(response.message);
                    $submitButton.prop('disabled', false);
                }
            });
        });
    });
</script>
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


    function getdetail(thisvalue) {
        $.ajax({
            url: "{{ route('getdetail') }}",
            type: "POST",
            data: {
                accountno: thisvalue,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            beforeSend: function() {},
            success: function(data) {
                const container = document.getElementById('details-container');
                container.innerHTML = '';
                const detail = data.detail;

                for (const [key, value] of Object.entries(detail)) {
                    // Convert key to a readable format
                    const label = key.replace(/_/g, ' ').toUpperCase();
                    const fieldValue = value === null ? '' : value; // Set empty string if value is null

                    // Create a new div element for each field
                    const fieldDiv = document.createElement('div');
                    fieldDiv.classList.add('col-md-4');
                    fieldDiv.innerHTML = `
                        <label class="form-label">${label}</label>
                        <input type="text" class="form-control" value="${fieldValue}" readonly>`;

                    container.appendChild(fieldDiv);
                }

                if (!data.detail.name) {
                    $('#name').val('');
                    $('#tbody').empty();
                    $('#loan_limit').val('');
                } else {
                    $('#name').val(data.detail.name);
                    $('#loan_limit').val(data.detail.loan_limit);
                    $('#tbody').empty();
                    $.each(data.member_loans, function(index, item) {
                        var originalDate = new Date(item.loanDate);
                        var day = originalDate.getDate();
                        var month = originalDate.getMonth() + 1;
                        var year = originalDate.getFullYear();
                        day = day < 10 ? '0' + day : day;
                        month = month < 10 ? '0' + month : month;
                        var formatgrdate = day + '-' + month + '-' + year;

                        if (item.total_recovered >= item.loanAmount) {
                            var colorof = "laallaal";
                        } else {
                            var colorof = "hrahra";
                        }
                        var deleteLink = '';
                        if (item.total_recovered == 0) {
                            @if (Session::get('adminloginid') == 1)
                                deleteLink =
                                    '<a href="javascript:void(0)" onclick="confirmDelete(' +
                                    item.id + ')">' +
                                    '<img src="{{ url('public/admin/images/delete.png') }}">' +
                                    '</a>';
                            @endif
                        }


                        if (item.total_recovered >= item.loanAmount) {
                            var colorof = "laallaal";
                        } else {
                            var colorof = "hrahra";
                        }
                        var editlink = '';

                        if (item.total_recovered == 0) {
                            @if (Session::get('adminloginid') == 1)
                                editlink =
                                    '<a href="javascript:void(0)" onclick="editadvancement(' +
                                    item.id + ')">' +
                                    '<img src="{{ url('public/admin/images/edit.png') }}">' +
                                    '</a>';
                            @endif
                        }


                        $('#tbody').append(
                            '<tr class="' + colorof + '" style="cursor:pointer" id="row-' + item
                            .id + '">' +
                            '<td>1</td>' +
                            '<td>' + formatgrdate + '</td>' +
                            '<td>' + item.loanname + '</td>' +
                            '<td>' + item.loanAmount + '</td>' +
                            '<td>' + item.total_recovered + '</td>' +
                            '<td>' + item.loanInterest + '%</td>' +
                            '<td>' + item.guarantorname + '</td>' +
                            '<td>' + item.guarantornamee + '</td>' +
                            '<td>' + item.agent_name + '</td>' +
                            // '<td>' +
                            // '<button  style="padding: 3px;font-size: 11px;" onclick="getintrest(' +
                            // item.id +
                            // ')" type="button" class="btn btn-info btn-sm">' +
                            // 'Total Recieved' +
                            // '</button>' +
                            // '</td>' +
                            '<td>' +
                            '<button style="padding: 3px;font-size: 11px;" onclick="getinstallments(' +
                            item.id +
                            ')" type="button" class="btn btn-warning btn-sm">' +
                            'Installments' +
                            '</button>' +
                            '</td>' +
                            '<td>' + editlink +
                            '</td>' +
                            '<td>' + deleteLink + '</td>' +
                            '</tr>'
                        );
                    });
                }
            }
        });
    }

    // <td><button class="btn btn-info btn-sm">View</button></td>
    //             <td><button class="btn btn-warning btn-sm">Edit</button></td>
    //             <td><button class="btn btn-danger btn-sm">Delete</button></td>



    // Define the confirmDelete function
    function editadvancement(id) {
        $.ajax({
            url: "{{ url('editadvancement') }}",
            type: 'POST',
            data: {
                id: id,
                _token: '{{ csrf_token() }}' // Include CSRF token if needed
            },
            success: function(response) {
                $('#loan').val(response.member_loans.loanType).change();



                var originalDate = new Date(response.member_loans.loanDate);
                var day = originalDate.getDate();
                var month = originalDate.getMonth() + 1;
                var year = originalDate.getFullYear();
                day = day < 10 ? '0' + day : day;
                month = month < 10 ? '0' + month : month;
                var formatgrdate = day + '-' + month + '-' + year;


                $('#transactionDate').val(formatgrdate);

                var originalDate = new Date(response.member_loans.emiDate);
                var day = originalDate.getDate();
                var month = originalDate.getMonth() + 1;
                var year = originalDate.getFullYear();
                day = day < 10 ? '0' + day : day;
                month = month < 10 ? '0' + month : month;
                var formatgrdate = day + '-' + month + '-' + year;

                $('#emi_date').val(formatgrdate);
                $('#id').val(response.member_loans.id);
                $('#accountNo').val(response.member_loans.accountNo);
                $('#name').val(response.member_loans.name);
                // $('#chequeno').val(response.member_loans.chequeNo);
                $('#guarantorname').val(response.member_loans.guarantorname);
                $('#guarantorno').val(response.member_loans.guarantorno);
                $('#guarantoraddress').val(response.member_loans.guarantoraddress);
                $('#guarantornamee').val(response.member_loans.guarantornamee);
                $('#guarantornoo').val(response.member_loans.guarantornoo);
                $('#guarantoraddresss').val(response.member_loans.guarantoraddresss);
                $('#paymentby').val(response.member_loans.groupCode).change();
                $('#cashbanktype').val(response.member_loans.ledgerCode);
                $('#amount').val(response.member_loans.loanAmount);
                $('#purpose').val(response.member_loans.purpose).change();
                $('#caskback').val(response.member_loans.caskback);


                setTimeout(function() {
                    editadvancementt(id);
                }, 1500);
            }
        });
    }


    function editadvancementt(id) {
        $.ajax({
            url: "{{ url('editadvancement') }}",
            type: 'POST',
            data: {
                id: id,
                _token: '{{ csrf_token() }}' // Include CSRF token if needed
            },
            success: function(response) {
                $('#processingFee').val(response.member_loans.processingFee);
                $('#interest').val(response.member_loans.loanInterest);
                $('#paneltytype').val(response.member_loans.penaltyInteresttype).change();
                $('#penaltyInterest').val(response.member_loans.penaltyInterest);
                $('#months').val(response.member_loans.months);
            }
        });
    }


    // Define the confirmDelete function
    function getintrest(id) {
        $.ajax({
            url: "{{ url('getintrest') }}",
            type: 'POST',
            data: {
                id: id,
                _token: '{{ csrf_token() }}' // Include CSRF token if needed
            },
            success: function(response) {
                var installmentDate = new Date();
                var day = installmentDate.getDate();
                var month = installmentDate.getMonth() + 1;
                var year = installmentDate.getFullYear();
                day = day < 10 ? '0' + day : day;
                month = month < 10 ? '0' + month : month;
                var formattedInstallmentDate = day + '-' + month + '-' + year;



                $('#totalintrest').val(response.totalintrest);
                $('#totalprinciple').val(response.totalprinciple);
                // $('#totalinstallment').val(response.totalinstallment);
                $('#totalpanelty').val(response.totalpanelty);
                $('#totalpaneltywithinstallment').val(response.totalpaneltywithinstallment);
                $('#totalinstallment').val(response.totalinstallment);
                $('#thisid').val(response.thisid);
                // $('#totalinstallment').val(parseFloat(response.totalprinciple) + (parseFloat(response.totalpanelty) + parseFloat(response.totalintrest)));



                $('#currentdate').val(formattedInstallmentDate);

                $('.remainid').css('visibility', 'visible');
                $('#remaining').text(response.remaining);
                $('#remaininginput').val(response.remaining);
                const tbody = $('#dinchak');
                tbody.empty(); // Clear any existing data
                $.each(response.loan_recoveries, function(index, item) {
                    var installmentDate = new Date(item.receiptDate);
                    var day = installmentDate.getDate();
                    var month = installmentDate.getMonth() + 1;
                    var year = installmentDate.getFullYear();
                    day = day < 10 ? '0' + day : day;
                    month = month < 10 ? '0' + month : month;
                    var formattedInstallmentDate = day + '-' + month + '-' + year;
                    const tr = $('<tr id="row-' + item.id + '"></tr>');
                    tr.append(`<td>${formattedInstallmentDate}</td>`);
                    tr.append(`<td>${item.principal}</td>`);
                    tr.append(`<td>${item.interest}</td>`);
                    tr.append(`<td>${item.penalInterest}</td>`);
                    tr.append(`<td>${item.receivedAmount}</td>`);
                    tr.append(`<td>${item.agent_name}</td>`);
                    // tr.append(
                    //     `<td><a href="javascript:void(0)" onclick="editadvancementre(${item.id})"><img src="{{ url('public/admin/images/edit.png') }}"></a></td>`
                    // );
                    tr.append(
                        `<td><a href="javascript:void(0)" onclick="confirmDeletere(${item.id})"><img src="{{ url('public/admin/images/delete.png') }}"></a></td>`
                    );
                    tbody.append(tr);
                });
            }
        });
    }
    // Define the confirmDelete function
    function editadvancementre(id) {
        $.ajax({
            url: "{{ url('editadvancementre') }}",
            type: 'POST',
            data: {
                id: id,
                _token: '{{ csrf_token() }}' // Include CSRF token if needed
            },
            success: function(response) {
                var installmentDate = new Date(response.loan_recoveries.receiptDate);
                var day = installmentDate.getDate();
                var month = installmentDate.getMonth() + 1;
                var year = installmentDate.getFullYear();
                day = day < 10 ? '0' + day : day;
                month = month < 10 ? '0' + month : month;
                var formattedInstallmentDate = day + '-' + month + '-' + year;
                $('#currentdate').val(formattedInstallmentDate);
                $('#recoveryid').val(response.loan_recoveries.id);
                $('#totalprinciple').val(response.loan_recoveries.principal);
                $('#totalintrest').val(response.loan_recoveries.interest);
                $('#totalinstallment').val(response.loan_recoveries.total);
                $('#totalpanelty').val(response.loan_recoveries.penalInterest);
                $('#totalpaneltywithinstallment').val(response.loan_recoveries.receivedAmount);
            }
        });
    }
    // Define the confirmDelete function
    function confirmDeletere(id) {
        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                url: "{{ url('confirmDeletere') }}",
                type: 'POST',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}' // Include CSRF token if needed
                },
                success: function(response) {
                    $('#row-' + id).remove();
                    var customerId = $('#accountNo').val();
                    getdetail(customerId);
                }
            });
        }
    }
    // Define the confirmDelete function
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                url: "{{ url('deleteadvancement') }}",
                type: 'POST',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}' // Include CSRF token if needed
                },
                success: function(response) {
                    $('#row-' + id).remove();
                }
            });
        }
    }

    function formatDate(dateString) {
        var date = new Date(dateString);
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();
        // Add leading zeros to day and month if needed
        day = day < 10 ? '0' + day : day;
        month = month < 10 ? '0' + month : month;
        return day + '-' + month + '-' + year;
    }

    function getinstallments(id) {
        $.ajax({
            url: "{{ url('getemi') }}",
            type: 'POST',
            data: {
                id: id,
                _token: '{{ csrf_token() }}' // Include CSRF token if needed
            },
            success: function(response) {
                // Check if response status is true
                // if (response.status) {
                var installments = response.installments;
                var tableBody = $('#installmentsTableBody');
                tableBody.empty(); // Clear any existing rows
                // Initialize totals for each column
                var totalPrincipal = 0;
                var totalInterest = 0;
                var totalAmount = 0;
                var iod = 1;
                installments.forEach(function(installment) {
                    if (installment.status == 'paid') {
                        var sttatus = 'Paid';
                    } else {
                        var sttatus = 'Unpaid';
                    }
                    var row = '<tr>' +
                        '<td>' + iod++ + '</td>' +
                        '<td>' + formatDate(installment.installmentDate) + '</td>' +
                        '<td>' + installment.principal + '</td>' +
                        '<td>' + installment.interest + '</td>' +
                        '<td>' + installment.totalinstallmentamount + '</td>' +
                        '<td>' + sttatus + '</td>' +
                        '</tr>';
                    tableBody.append(row);
                    totalPrincipal += parseFloat(installment.principal);
                    totalInterest += parseFloat(installment.interest);
                    totalAmount += parseFloat(installment.totalinstallmentamount);
                });
                var totalRow = '<tr>' +
                    '<td>#</td>' +
                    '<td><strong>Total</strong></td>' +
                    '<td><strong>' + totalPrincipal.toFixed(2) + '</strong></td>' +
                    '<td><strong>' + totalInterest.toFixed(2) + '</strong></td>' +
                    '<td><strong>' + totalAmount.toFixed(2) + '</strong></td>' +
                    '<td><strong>-</strong></td>' +
                    '</tr>';
                tableBody.append(totalRow);
                $('#exampleModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    }
</script>
<script>
    function getid(value) {
        if (value.length < 2) {
            document.getElementById('suggestions').innerHTML = '';
            return;
        }

        fetch('{{ route('autocomplete.search') }}?query=' + encodeURIComponent(value))
            .then(response => response.json())
            .then(data => {
                let html = '';
                data.forEach(suggestion => {
                    html += '<div class="autocomplete-suggestion">' + suggestion + '</div>';
                });
                document.getElementById('suggestions').innerHTML = html;
            });
    }

    document.addEventListener('click', function(e) {
        if (!e.target.classList.contains('autocomplete-suggestion')) {
            document.getElementById('suggestions').innerHTML = '';
        }
    });

    document.getElementById('suggestions').addEventListener('click', function(e) {
        if (e.target.classList.contains('autocomplete-suggestion')) {
            document.getElementById('accountNo').value = e.target.textContent;
            document.getElementById('suggestions').innerHTML = '';
        }
    });
</script>
<script>
    let selectedIndex = -1;
    let suggestionsContainer = document.getElementById('suggestions');

    function getid(value) {
        if (value.length < 2) {
            suggestionsContainer.innerHTML = '';
            return;
        }

        fetch('{{ route('autocomplete.search') }}?query=' + encodeURIComponent(value))
            .then(response => response.json())
            .then(data => {
                let html = '';
                data.forEach((suggestion, index) => {
                    html += '<div class="autocomplete-suggestion" onclick="getdetail(\'' + suggestion +
                        '\')">' + suggestion + '</div>';
                });
                suggestionsContainer.innerHTML = html;
                selectedIndex = -1; // Reset selection index
            });
    }

    function handleKeydown(event) {
        let suggestions = document.querySelectorAll('.autocomplete-suggestion');

        if (event.key === 'ArrowDown') {
            event.preventDefault();
            if (suggestions.length > 0) {
                selectedIndex = (selectedIndex + 1) % suggestions.length;
                updateSelection();
            }
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            if (suggestions.length > 0) {
                selectedIndex = (selectedIndex - 1 + suggestions.length) % suggestions.length;
                updateSelection();
            }
        } else if (event.key === 'Enter') {
            event.preventDefault();
            if (selectedIndex > -1 && selectedIndex < suggestions.length) {
                document.getElementById('accountNo').value = suggestions[selectedIndex].textContent;
                suggestionsContainer.innerHTML = '';
                selectedIndex = -1;
            }
        }
    }

    function updateSelection() {
        let suggestions = document.querySelectorAll('.autocomplete-suggestion');
        suggestions.forEach((suggestion, index) => {
            suggestion.classList.toggle('selected', index === selectedIndex);
        });
    }

    document.addEventListener('click', function(e) {
        if (!e.target.classList.contains('autocomplete-suggestion')) {
            suggestionsContainer.innerHTML = '';
        }
    });

    suggestionsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('autocomplete-suggestion')) {
            document.getElementById('accountNo').value = e.target.textContent;
            suggestionsContainer.innerHTML = '';
        }
    });
</script>
<script>
    function getcustomerDetails() {
        let customer_name = $('#name').val();

        $.ajax({
            url: "{{ route('get-customer-Details') }}",
            type: 'post',
            data: {
                customer_name: customer_name,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    let details = res.details;
                    let suggestionss = $('#suggestionss');
                    suggestionss.empty();

                    details.forEach((data) => {
                        suggestionss.append(
                            `<div class="customer_id" data-id="${data.customer_Id}">${data.name} - ${data.customer_Id}</div>`
                        );
                    });
                }
            }
        });
    }


    $(document).on('click', '.customer_id', function(event) {
        event.preventDefault();
        let selected_id = $(this).data('id');

        $.ajax({
            url: "{{ route('get-details') }}",
            type: 'post',
            data: {
                selected_id: selected_id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(res) {
                const container = document.getElementById('details-container');
                container.innerHTML = '';
                const detail = res.details;

                for (const [key, value] of Object.entries(detail)) {
                    const label = key.replace(/_/g, ' ').toUpperCase();
                    const fieldValue = value === null ? '' : value;

                    const fieldDiv = document.createElement('div');
                    fieldDiv.classList.add('col-md-4');
                    fieldDiv.innerHTML =
                        `
                        <label class="form-label">${label}</label>
                        <input type="text" class="form-control  form-input-controller" value="${fieldValue}" readonly>`;

                    container.appendChild(fieldDiv);
                }

                $('#name').val(detail.name);
                $('#accountNo').val(detail.customer_Id);

                let suggestionss = $('#suggestionss');
                suggestionss.empty();

                let tbody = $('#tbody');
                tbody.empty();

                let member_loans = res.member_loans;

                member_loans.forEach((item, index) => {
                    var originalDate = new Date(item.loanDate);
                    var day = originalDate.getDate();
                    var month = originalDate.getMonth() + 1;
                    var year = originalDate.getFullYear();
                    day = day < 10 ? '0' + day : day;
                    month = month < 10 ? '0' + month : month;
                    var formatgrdate = day + '-' + month + '-' + year;

                    if (item.total_recovered >= item.loanAmount) {
                        var colorof = "laallaal";
                    } else {
                        var colorof = "hrahra";
                    }
                    var deleteLink = '';
                    if (item.total_recovered == 0) {
                        deleteLink =
                            '<a href="javascript:void(0)" onclick="confirmDelete(' + item
                            .id + ')">' +
                            '<img src="{{ url('public/admin/images/delete.png') }}">' +
                            '</a>';
                    }


                    if (item.total_recovered >= item.loanAmount) {
                        var colorof = "laallaal";
                    } else {
                        var colorof = "hrahra";
                    }
                    var editlink = '';

                    if (item.total_recovered == 0) {
                        editlink =
                            '<a href="javascript:void(0)" onclick="editadvancement(' + item
                            .id + ')">' +
                            '<img src="{{ url('public/admin/images/edit.png') }}">' +
                            '</a>';
                    }

                    $('#tbody').append(
                        '<tr class="' + colorof + '" style="cursor:pointer" id="row-' +
                        item.id + '">' +
                        '<td>1</td>' +
                        '<td>' + formatgrdate + '</td>' +
                        '<td>' + item.loanname + '</td>' +
                        '<td>' + item.loanAmount + '</td>' +
                        '<td>' + item.total_recovered + '</td>' +
                        '<td>' + item.loanInterest + '%</td>' +
                        '<td>' + item.guarantorname + '</td>' +
                        '<td>' + item.guarantornamee + '</td>' +
                        '<td>' + item.agent_name + '</td>' +
                        '<td>' +
                        '<button  style="padding: 3px;font-size: 11px;" onclick="getintrest(' +
                        item.id +
                        ')" type="button" class="btn btn-warning btn-bordered waves-effect waves-light">' +
                        'Total Recieved' +
                        '</button>' +
                        '</td>' +
                        '<td>' +
                        '<button style="padding: 3px;font-size: 11px;" onclick="getinstallments(' +
                        item.id +
                        ')" type="button" class="btn btn-dark btn-bordered waves-effect waves-light">' +
                        'Installments' +
                        '</button>' +
                        '</td>' +
                        '<td>' + editlink + '</td>' +
                        '<td>' + deleteLink + '</td>' +
                        '</tr>'
                    );
                });
            }
        });
    });
</script>
<script>
    function getloanamount() {
        let customer_id = $('#accountNo').val();
        let loan_amount = $('#amount').val();
        let loan_limit = $('#loan_limit').val();

        $.ajax({
            url: "{{ route('check-loan-limit') }}",
            type: 'post',
            data: {
                customer_id: customer_id,
                loan_amount: loan_amount,
                loan_limit: loan_limit,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    let remaining_limit = res.remaining_limit;
                    let exceed_amount = remaining_limit - loan_limit;

                    if (loan_amount > remaining_limit) {
                        removeLimitMessage();
                        //   alert(`Principle cannot exceed: ${Math.abs(remaining_limit)}`);
                        $('#amount').after(
                            `<div id="limit-message">Principle cannot exceed: ${Math.abs(remaining_limit)}</div>`
                        );
                    } else {
                        removeLimitMessage();
                    }
                }
            }
        });
    }

    function removeLimitMessage() {
        $('#limit-message').remove();
    }
</script>
</div>
</div>

@include('include.footer')
