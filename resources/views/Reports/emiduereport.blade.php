@include('include.header')

<style>
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
    }

    .btn-primary {
        padding: 3px 15px;
    }

    .selected {
        background-color: #3cbade !important;
        /* Dark Background */
        color: white !important;
        /* White Text */
    }
</style>
<section class="content-header">
    <div class="container-fluid my-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="breadcrumbTitle"><i class="ri-folder-chart-line"></i>
                        Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pending EMI Report</li>
            </ol>
        </nav>
    </div>
</section>
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="javascript:void(0)" id="dueEmiReportForm" name="dueEmiReportForm">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div>
                                <label for="name" class="label-text">As on Date</label>
                                <input type="text" name="endDate" id="endDate" class="form-control datepicker"
                                    value="{{ date('d-m-Y') }}">
                            </div>
                        </div>

                        {{--  <div class="col-md-2">
                            <div>
                                <label for="installment_type" class="label-text">Type</label>
                                <select name="emi_type" id="emi_type" class="form-control">
                                    <option value="Today" selected>Today</option>
                                    <option value="TillDate" selected>Till Date</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div>
                                <label for="installment_type" class="label-text">Report Type</label>
                                <select name="report_type" id="report_type" class="form-control">
                                    <option value="Details" selected>Details</option>
                                    <option value="Compact" selected>Compact</option>
                                </select>
                            </div>
                        </div>  --}}
                        <div class="col-md-2">
                            <div>
                                <label for="name" class="label-text">No of Installment</label>
                                <input type="text" name="months" id="months" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <div class="button2">
                                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-eye"></i>
                                    View</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="tableSection row mb-4">
            <div class="col-lg-12 col-md-12 mb-4">
                <div class="card">
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive">
                            <!-- <div id="loader" style="display: none;">
                                    <div class="dot-loader"></div>
                                    <div class="dot-loader dot-loader--2"></div>
                                    <div class="dot-loader dot-loader--3"></div>
                                </div> -->
                            <table class="table align-items-center text-center table-bordered mb-0 data-table">
                                <thead class="tableHeading">
                                    <tr>
                                        <th>#</th>
                                        <th>Account No</th>
                                        <th>Cust.Name</th>
                                        <th>Principal</th>
                                        <th>Interest</th>
                                        <th>Emi Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="tableBody" id="tableBody">
                                </tbody>
                                <tbody id="grandtotal">
                                    <tr style="background-color:#3cbade; color:white;">
                                        <td colspan="4" style="color:white;">Grand Total</td>
                                        <td colspan="" id="principalTotal" style="color:white;">0</td>
                                        <td colspan="" id="interestTotal" style="color:white;">0</td>
                                        <td colspan="" id="emiTotal" style="color:white;">0</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end mt-3">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
@include('include.footer')

<script>
    $(document).ready(function() {
        $('#downloadExcel').hide();

        $('#endDate').datepicker({
            format: 'dd-mm-yyyy',
            endDate: new Date()
        });

        const dateInputs = document.querySelectorAll('.onlydate');
        dateInputs.forEach(dateInput => {
            dateInput.addEventListener('input', function(e) {
                let inputValue = e.target.value;
                inputValue = inputValue.replace(/[^\d]/g, '').slice(0, 8);
                if (inputValue.length >= 2 && inputValue.charAt(2) !== '-') {
                    inputValue = `${inputValue.slice(0, 2)}-${inputValue.slice(2)}`;
                }
                if (inputValue.length >= 5 && inputValue.charAt(5) !== '-') {
                    inputValue = `${inputValue.slice(0, 5)}-${inputValue.slice(5)}`;
                }
                e.target.value = inputValue;
            });
        });

        document.getElementById('dueEmiReportForm').addEventListener('submit', function(event) {
            event.preventDefault();

            let date = document.getElementById('endDate').value;
            let months = document.getElementById('months').value;
            let monthValue = Number(months);
            {{--  let emi_type = document.getElementById('emi_type').value;  --}}
            {{--  let report_type = document.getElementById('report_type').value;  --}}

            let newmonth = 0;

            //________ Check if the value is a valid number
            if (!isNaN(monthValue)) {
                newmonth += monthValue;
            } else {
                newmonth = '';
            }


            fetch("{{ route('get-pending-emi') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        date: date,
                        {{--  emi_type: emi_type,
                    report_type: report_type,  --}}
                        newmonth: newmonth
                    })
                })
                .then(response => response.json())
                .then(res => {
                    if (res.status === 'success') {
                        let emisDetails = res.emis_details;

                        let index = 1;
                        let rows = [];

                        let principalTd = $('#principalTotal');
                        principalTd.empty();
                        let interestTd = $('#interestTotal');
                        interestTd.empty();
                        let emiTd = $('#emiTotal');
                        emiTd.empty();

                        let principalTotal = 0;
                        let interestTotal = 0;
                        let emiTotal = 0;

                        emisDetails.forEach((data) => {
                            rows.push(`<tr>
                            <td>${index++}</td>
                            <td>${data.accountNo}</td>
                            <td>${data.name}</td>
                            <td>${data.principal}</td>
                            <td>${data.interest}</td>
                            <td>${data.totalinstallmentamount}</td>
                        </tr>`);
                            principalTotal += parseFloat(data.principal);
                            interestTotal += parseFloat(data.interest);
                            emiTotal += parseFloat(data.totalinstallmentamount);
                        });
                        tableBody.innerHTML = rows.join('');

                        ppp.append(principalTotal);
                        iii.append(interestTotal);
                        ss.append(emiTotal);


                        {{--  console.log(principalTotal,interestTotal,emiTotal);  --}}
                        {{--  principalTotal interestTotal emiTotal  --}}
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });

    $(document).on('click', '#tableBody tr', function() {
        // Remove 'selected' class from all rows
        $('#tableBody tr').removeClass('selected');

        // Add 'selected' class to the clicked row
        $(this).addClass('selected');
    });


</script>
