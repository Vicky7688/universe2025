@include('include.header')

<style>
.text-danger {
    font-size: 12px;
}

.error {
    font-size: 12px;
    color: red;
}
tbody tr td{
        font-size: 13px;
    }
    .table tr > th {
  font-size: 12px !important;
  font-weight: 600;
  padding: 11px 6px;
}

.btn-primary{
    padding: 3px 15px;
}

.selected {
    background-color: #3cbade !important; /* Dark Background */
    color: white !important; /* White Text */
}
</style>
<section class="content-header">
    <div class="container-fluid my-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="breadcrumbTitle"><i class="ri-folder-chart-line"></i> Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page">Overdue EMI Report</li>
            </ol>
        </nav>
    </div>
</section>
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="javascript:void(0)" id="loaninsttreport" name="loaninsttreport">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div>
                                <label for="name" class="label-text">As on Date</label>
                                <input type="text" name="endDate" id="endDate" class="form-control onlydate datepicker"
                                    value="{{ date('d-m-Y') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div>
                                <label for="installment_type" class="label-text">Loan Type</label>
                                <select name="loancode" id="loancode" class="form-control">
                                    <option value="All" selected>All</option>
                                    @foreach ($loanissueds as $loan)
                                        <option value="{{ $loan->id }}">{{ $loan->loanname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <div class="button2">
                                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-eye"></i> View</button>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <div class="button2">
                                <button type="submit" class="btn btn-primary w-100" id="downloadExcel"><i
                                class="ri-file-excel-2-line"></i> Excel</button>
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
                                        <th>Loan Id</th>
                                        <th>Cust.Name</th>
                                        <th>Loan Date</th>
                                        <th>Loan End Date</th>
                                        <th>Loan Disbursement Amt</th>
                                        <th>Received Amt</th>
                                        <th>Overdue.Amt</th>
                                    </tr>

                                </thead>
                                <tbody class="tableBody" id="tableBody">
                                </tbody>
                                <tbody>
                                    <tr style="background-color:#3cbade; color:white;">
                                        <td colspan="6" style="color:white;">Grand Total</td>
                                        <td colspan="" id="loanAdvancement" style="color:white;">0</td>
                                        <td colspan="" id="receivedAmount" style="color:white;">0</td>
                                        <td colspan="" id="overDueAmount" style="color:white;">0</td>
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

        //_____________Get Overdue Loan Details
        document.getElementById('loaninsttreport').addEventListener('submit', function(event) {
            event.preventDefault();
            let endDate = document.getElementById('endDate').value;
            let loanType = document.getElementById('loancode').value;

            $.ajax({
                url: "{{ route('get-pending-emis') }}",
                type: 'post',
                data: {
                    endDate: endDate,
                    loanType: loanType,
                    _token: "{{ csrf_token() }}"
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        let overDueAmount = res.overdue_loans;
                        let tableBody = document.getElementById('tableBody');
                        tableBody.innerHTML = '';

                        let grandTotalReceivedAmount = 0;
                        let grandTotalLoanAdvancement = 0;
                        let grandTotalOverdueLoan = 0;

                        overDueAmount.forEach((data, index) => {
                            let tableRow = document.createElement('tr');

                            //__________Loan Disbursement Date Format
                            let dates = new Date(data.loanDate);
                            let day = dates.getDate();
                            let month = dates.getMonth() + 1;
                            let year = dates.getFullYear();
                            day = day < 10 ? `0${day}` : day;
                            month = month < 10 ? `0${month}` : month;
                            let formatedDate = `${day}-${month}-${year}`;

                            //__________Loan Due Date Format
                            let dueDate = new Date(data.loan_end_date);
                            let days = dueDate.getDate();
                            let months = dueDate.getMonth() + 1;
                            let years = dueDate.getFullYear();
                            days = days < 10 ? `0${days}` : days;
                            months = months < 10 ? `0${months}` : months;
                            let formatedDueDate = `${days}-${months}-${years}`;

                            //__________Penality Calculation
                            let overDueEmis = parseInt(data.overdue_emi);
                            let penalityRate = 300;
                            let penalityAmount = overDueEmis * penalityRate;

                            tableRow.innerHTML = `
                                        <td>${index + 1}</td>
                                        <td>${data.customer_Id}</td>
                                        <td>${data.loan_id}</td>
                                        <td>${data.customer_name}</td>
                                        <td>${formatedDate}</td>
                                        <td>${formatedDueDate}</td>
                                        <td>${data.loanAmount}</td>
                                        <td>${data.received_amount}</td>
                                        <td>${data.overdue_amount}</td>
                                    `;
                            tableBody.appendChild(tableRow);

                            grandTotalOverdueLoan += parseInt(data.overdue_amount);
                            grandTotalLoanAdvancement += parseInt(data.loanAmount);
                            grandTotalReceivedAmount += parseInt(data.received_amount);
                        });






                        let overDueAmountdiv = document.getElementById('overDueAmount');
                        overDueAmountdiv.textContent = (grandTotalOverdueLoan.toFixed(2));

                        let loanAdvancement = document.getElementById('loanAdvancement');
                        loanAdvancement.textContent = (grandTotalLoanAdvancement.toFixed(2));


                        let receivedAmount = document.getElementById('receivedAmount');
                          receivedAmount.textContent = (grandTotalReceivedAmount.toFixed(2));

                        // Show the download button
                        {{--  $('#downloadExcel').show();  --}}
                    }
                }
            });
        });

        // Download Excel file
        $('#downloadExcel').click(function() {
            let wb = XLSX.utils.book_new();
            let ws_data = [];

            // Add table headers
            ws_data.push([
                '#', 'Branch', 'Center', 'Loan.Id', 'Cust.ID', 'Cust.', 'Husband', 'Cust.No.', 'NomineeNo.', 'Agent', 'Overdue.EMI', 'Overdue.Amt', 'Penality.Amt', 'Future.EMI', 'Disbursed.Date', 'Due.Date', 'Againg.Days', 'Campalet.Adr.'
            ]);

            // Add table rows
            document.querySelectorAll('#tableBody tr').forEach(row => {
                let rowData = Array.from(row.children).map(cell => cell.textContent);
                ws_data.push(rowData);
            });

            let ws = XLSX.utils.aoa_to_sheet(ws_data);
            XLSX.utils.book_append_sheet(wb, ws, 'Overdue Loans');
            XLSX.writeFile(wb, 'Overdue_Loans.xlsx');
        });
    });

    $(document).on('click', '#tableBody tr', function() {
        // Remove 'selected' class from all rows
        $('#tableBody tr').removeClass('selected');

        // Add 'selected' class to the clicked row
        $(this).addClass('selected');
    });

    </script>

