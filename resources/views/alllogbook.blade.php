@include('include.header')
<style>
    .selected {
        background-color: #3cbade !important;
        color: white !important;
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
                                        <input type="text" class="form-control form-input-controller datepicker" name="currentdate" id="currentdate" value="{{ now()->format('d-m-Y') }}">
                                    </div>
                                    <div class="mb-2 col-md-4">


                                        <button id="downloadExcel" class="btn btn-primary" style="display:none;margin-top: 25px;">Download Excel</button>
                                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <table class="table align-items-center text-center table-bordered mb-0 data-table">
                            <thead class="tableHeading">
                                <tr>
                                    <th>SrNo</th>
                                    <th>Account name</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody id="dinchak"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function() {
        $('.date1').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
        });

        $(document).on('change', '#currentdate', function() {
            let currentdate = $(this).val();
            ajaxchla(currentdate);
        });

        var currentdate = $('#currentdate').val();
        ajaxchla(currentdate);
    });

    let loanData = []; // Array to store the fetched data

    function ajaxchla(currentdate) {
        $.ajax({
            url: '{{ route("allgetdataofloan") }}',
            type: 'GET',
            data: { id: currentdate },
            success: function(response) {
                $('#dinchak').empty(); // Clear previous content
                let totalCredit = 0;
                let totalDebit = 0;
                let netAmount = 0;
                let srNo = 1; // Initialize a counter for serial numbers

                // Clear loanData array before populating
                loanData = [];

                response.totals.forEach(function(item) {
                    totalCredit += item.total_credit;
                    totalDebit += item.total_debit;
                    netAmount += item.net_balance;

                    const row = `
                        <tr>
                            <td>${srNo++}</td>
                            <td>${item.accountNo}-${item.customer_name}</td>
                            <td>${item.total_credit.toFixed(2)}</td>
                            <td>${item.total_debit.toFixed(2)}</td>
                            <td>${item.net_balance.toFixed(2)}</td>
                        </tr>
                    `;

                    $('#dinchak').append(row);

                    // Store data for download
                    loanData.push([
                        srNo - 1, // SrNo
                        `${item.accountNo}-${item.customer_name}`, // Account name
                        item.total_credit.toFixed(2), // Debit
                        item.total_debit.toFixed(2), // Credit
                        item.net_balance.toFixed(2) // Balance
                    ]);
                });

                const totalsRow = `
                    <tr style="font-weight: bold;">
                        <td></td>
                        <td>Total</td>
                        <td>${totalCredit.toFixed(2)}</td>
                        <td>${totalDebit.toFixed(2)}</td>
                        <td>${netAmount.toFixed(2)}</td>
                    </tr>
                `;
                $('#dinchak').append(totalsRow);
                $('#downloadExcel').css('display','block');
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ', error);
            }
        });
    }

    document.getElementById('downloadExcel').addEventListener('click', function () {
        let csvContent = 'data:text/csv;charset=utf-8,';
        csvContent += 'SrNo,Account name,Debit,Credit,Balance\n';

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

    $(document).on('click', '#dinchak tr', function() {
        // Remove 'selected' class from all rows
        $('#dinchak tr').removeClass('selected');

        // Add 'selected' class to the clicked row
        $(this).addClass('selected');
    });


    // Other functions (getdatarecovery, confirmDelete, submitForm, etc.) go here
</script>
@include('include.footer')
