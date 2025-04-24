@include('include.header')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
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

    .tutu td {
        color: #000;
    }

    .selected {
        background-color: #3cbade !important; /* Dark Background */
        color: white !important; /* White Text */
    }

    .table-responsive {
        overflow: auto;
        height: 600px;
    }
</style>
<section class="content-header">
    <div class="container-fluid my-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="breadcrumbTitle"><i class="ri-folder-chart-line"></i>
                        Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page">EMI Report</li>
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
                                <label for="name" class="label-text">From Date</label>
                                <input type="text" name="startdate" id="startdate" class="form-control datepicker"
                                    value="01-01-2017">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div>
                                <label for="name" class="label-text">As on Date</label>
                                <input type="text" name="endDate" id="endDate" class="form-control datepicker"
                                    value="{{ date('d-m-Y', strtotime(Session::get('setcurrentdate'))) }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div>
                                <label for="installment_type" class="label-text">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="false" selected>Unpaid</option>
                                    <option value="paid">Paid</option>
                                    <option value="">All</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div>
                                <label for="installment_type" class="label-text">Report Type</label>
                                <select name="report_type" id="report_type" class="form-control">
                                    <option value="compact" selected>Compact</option>
                                    <option value="detail">Details</option>
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col-md-2">
                            <div>
                                <label for="name" class="label-text">No of Installment</label>
                                <input type="text" name="months" id="months" class="form-control">
                            </div>
                        </div> --}}
                        <div class="col-md-1 d-flex align-items-end">
                            <div class="button2">
                                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-eye"></i>
                                    View</button>
                                {{-- <button id="downloadExcel" type="submit" class="btn btn-primary w-100"><i class="bi bi-eye"></i> Download Excel</button> --}}
                                {{-- <button id="downloadExcel" style="display:none;">Download Excel</button> --}}

                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="button2">
                                <button id="downloadExcel" style="display:none;" type="submit"
                                    class="btn btn-primary w-100"><i class="bi bi-eye"></i> Download Excel</button>
                                {{-- <button id="downloadExcel" style="display:none;">Download Excel</button> --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="tableSection row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="button2 p-2">
                            <label for="">Sreach</label>
                            <input id="search-weeazer" class="form-control">
                        </div>
                    </div>

                </div>
            </div>
                    <div class="col-lg-12 col-md-12">
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
                                        <th>Loan Date</th>
                                        <th>Installment Date</th>
                                        <th>Loan End Date</th>
                                        <th>Account No</th>
                                        <th>Customer name</th>
                                        <th>Loan Name</th>
                                        <th>EMI Amount</th>
                                        <th>Pending Amount</th>
                                        <th>No. of Pending Emi</th>
                                        <th>Emi Status</th>
                                    </tr>
                                </thead>
                                <tbody class="tableBody" id="tableBody">

                                  </tbody>
                                <tbody id="grandtotal">
                                    <tr style="background-color:#3cbade; color:white;">
                                        <td colspan="6" style="color:white;">Grand Total</td>
                                        <td colspan="" id="emiTotal" style="color:white;"></td>
                                        <td colspan="" style="color:white;"></td>
                                        <td colspan="" style="color:white;"></td>
                                        <td colspan="" style="color:white;"></td>
                                        <td colspan="" style="color:white;"></td>
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
        <div class="row mt-0 pt-0">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-2 fonts">
                                <label for="">Total Emi</label>
                                <input type="text" class="fonts" readonly name="total_emi" id="total_emi">
                            </div>
                            <div class="col-sm-2 fonts">
                                <label for="">Total Pending</label>
                                <input type="text" class="fonts" readonly name="total_pendings" id="total_pendings">
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

        function dateFormat(date){
            let dates = new Date(date);
            let days = dates.getDate();
            let month = dates.getMonth() + 1;
            let year = dates.getFullYear();
            days = days < 10 ? `0${days}` : days;
            month = month < 10 ? `0${month}` : month;
            const formattedDate = `${days}-${month}-${year}`;
            return formattedDate;
        }

        document.getElementById('dueEmiReportForm').addEventListener('submit', function(event) {

            event.preventDefault();

            let date = document.getElementById('endDate').value;
            let startdate = document.getElementById('startdate').value;
            let status = document.getElementById('status').value;
            let report_type = document.getElementById('report_type').value;

            fetch("{{ route('get-emi') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        date: date,
                        startdate: startdate,
                        status: status,
                        report_type: report_type
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
                        let emitotals = 0;
                        let pendings = 0;

                        emisDetails.forEach((data) => {
                            {{--  const [year, month, day] = data.installmentDate.split('-');
                            const formattedDate = `${day}-${month}-${year}`;

                            const [years, months, days] = data.loanEndDate.split('-');
                            const formattedDates = `${days}-${months}-${years}`;  --}}

                            if (data.status == 'false') {
                                var sttaat = "Unpaid";
                                var coback = "#e1bdbd";
                            } else {
                                var sttaat = "Paid";
                                var coback = "#d4dfca";
                            }

                            let loan_amount = parseFloat(data.loanAmount) || 0;
                            let interest_rate = parseFloat(data.loanInterest) || 0;
                            let months = parseFloat(data.months) || 0;
                            let interest_amount = ((parseFloat(loan_amount) * parseFloat(interest_rate) / 100) * parseFloat(months));
                            let recovery_amount = parseFloat(data.recoveries) || 0;
                            loan_amount += parseFloat(interest_amount) - parseFloat(recovery_amount);

                            rows.push(`<tr class="tutu" style="background-color: ${coback};" >
                                <td>${index++}</td>
                                <td>${dateFormat(data.loanDate)}</td>
                                <td>${dateFormat(data.installmentDate)}</td>
                                <td>${dateFormat(data.loanEndDate)}</td>
                                <td>${data.customer_Id}</td>
                                <td>${data.name}</td>
                                <td>${data.loanname}</td>
                                <td>${parseFloat(data.principal)+parseFloat(data.interest) ? parseFloat(data.principal)+parseFloat(data.interest) : data.totalinstallmentamount }</td>
                                <td>${loan_amount ? loan_amount :  data.totalinstallmentamount}</td>
                                <td>${data.pending_installments}</td>
                                <td>${sttaat}</td>
                            </tr>`);
                            emiTotal += parseFloat(data.totalinstallmentamount);
                            emitotals += parseFloat(data.principal)+parseFloat(data.interest) ? parseFloat(data.principal)+parseFloat(data.interest) :  parseFloat(data.totalinstallmentamount) ;

                            let pendingAmount = loan_amount ? loan_amount : data.totalinstallmentamount;
                            pendingAmount = parseFloat(pendingAmount) || 0;
                            pendings += pendingAmount;
                        });

                        tableBody.innerHTML = rows.join('');
                        {{--  $('#emiTotal').text(emiTotal);  --}}

                        $('#total_emi').val(emitotals);
                        $('#total_pendings').val(pendings);

                        // Show the download button
                        document.getElementById('downloadExcel').style.display = 'block';

                        // Add event listener to download button
                        document.getElementById('downloadExcel').onclick = function() {
                            downloadExcel(emisDetails);
                        };

                        var $searchBox = $("#search-weeazer");
                        var $tableRows = $("#tableBody tr");

                        $searchBox.on("input", function () {
                          var searchValue = this.value.toLowerCase();

                          if (!searchValue) {
                            $tableRows.show(); // Show all rows if the search box is empty
                            return;
                          }

                          $tableRows.each(function () {
                            var $row = $(this);
                            $row.toggle(
                              $row.text().toLowerCase().indexOf(searchValue) > -1
                            );
                          });
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });



        function downloadExcel(data) {
            // Define headers for the Excel file
            const headers = [
                "Index", "Loan Date", "Installment Date", "Loan End Date", "Customer ID", "Name",
                "Loan Name", "Installment Amount", "Total Pending Amount", "No. of Installments", "Status"
            ];

            // Transform data to match header structure
            const rows = data.map((item, index) => {
                const formattedLoanDate = dateFormat(item.loanDate);
                const formattedInstallmentDate = dateFormat(item.installmentDate);
                const formattedEndDate = dateFormat(item.loanEndDate);

                let loan_amount = parseFloat(item.loanAmount) || 0;
                let interest_rate = parseFloat(item.loanInterest) || 0;
                let months = parseFloat(item.months) || 0;
                let interest_amount = (loan_amount * interest_rate / 100) * months;
                let recovery_amount = parseFloat(item.recoveries) || 0;

                loan_amount = loan_amount + interest_amount - recovery_amount;

                return [
                    index + 1,
                    formattedLoanDate,
                    formattedInstallmentDate,
                    formattedEndDate,
                    item.customer_Id,
                    item.name,
                    item.loanname,
                    (parseFloat(item.principal) + parseFloat(item.interest)) || item.totalinstallmentamount,
                    loan_amount ? loan_amount :  item.totalinstallmentamount,
                    item.pending_installments,
                    item.status === 'false' ? 'Unpaid' : 'Paid'
                ];
            });

            // Create worksheet and add headers and data
            const worksheet = XLSX.utils.aoa_to_sheet([headers, ...rows]);
            const workbook = XLSX.utils.book_new();

            // Append the worksheet
            XLSX.utils.book_append_sheet(workbook, worksheet, "EMI Details");

            // Save as Excel file
            XLSX.writeFile(workbook, 'emi_details.xlsx');
        }
    });

    $(document).on('click', '#tableBody tr', function() {
        // Remove 'selected' class from all rows
        $('#tableBody tr').removeClass('selected');

        // Add 'selected' class to the clicked row
        $(this).addClass('selected');
    });

</script>

