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

    .raddd {
        background-color: #95ea5b8f;
    }

    .raddddd:hover {
        background: #000 !important;
    }

    .raddddd td {
        color: #fff !important;
    }

    .raddddd {
        background-color: #3cbade;

    }

    .rod {
        background: #dfe4e8;
    }

    .rod td {
        text-align: left;
        font-weight: 600 !important;
        padding-left: 19px !important;
    }

    .laaal td {
        color: rgb(250, 28, 28);
    }

    .fonts {
        color: black;
        font-weight: 700;
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
                            <label class="form-label">Account name</label>
                            {{-- <input
                                type="text" class="form-control fonts showhere" placeholder="Search Account name"
                                list="list-timezone" id="input-datalist" oninput="getCustomerId(this.value)"
                                onchange="getCustomerId(this.value)"> --}}
                            <select name="" id="input-datalist" oninput="getCustomerId(this.value)"
                                onchange="getCustomerId(this.value)">
                                {{-- <datalist id="list-timezone"> --}}
                                @if (sizeof($member_accounts) > 0)
                                    @foreach ($member_accounts as $member_accountss)
                                        <option>{{ $member_accountss->name }}-({{ $member_accountss->customer_Id }})
                                        </option>
                                    @endforeach
                                @endif
                                {{-- </datalist> --}}
                            </select>
                        </div>
                        <div class="mb-2 col-md-4">
                            <button id="downloadExcel" class="btn btn-primary" type="button"
                                style="display:none;margin-top: 25px; font-weight: 700; color:black;">Download
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
                <table class="table align-items-center text-center table-bordered mb-0 data-table">
                    <thead class="tableHeading">
                        <tr>
                            <th>Loan Date</th>
                            {{--  <th>Loan Name</th>  --}}
                            <th>Emi No</th>
                            {{--  <th>Emi Amount</th>  --}}
                            <th>EMI</th>
                            <th>Credit</th>
                            {{--  <th>Interest Amount</th>  --}}
                            {{--  <th>Amount</th>
                            <th>Adj Amount</th>  --}}
                            {{--  <th>Pending Amount</th>  --}}
                            <th>Balance</th>
                            <th>Due Date</th>
                        </tr>
                    </thead>
                    <tbody id="dinchak">
                    </tbody>
                </table>
                <div style="display: none;" id="netrows">
                    <div class="row">
                        <div class="col-sm-5"></div>
                        <div class="col-sm-2">
                            <label for="">Total Dr.</label>
                            <input type="text" style="text-align:right; font-size:15px; font-weight:700;"
                                name="totaldr" id="totaldr" readonly>
                        </div>
                        <div class="col-sm-2">
                            <label for="">Total Cr.</label>
                            <input type="text" name="totalcr"
                                style="text-align:right; font-size:15px; font-weight:700;" id="totalcr" readonly>
                        </div>
                        <div class="col-sm-2">
                            <label for="">Total Balance</label>
                            <input type="text" name="totalbalance"
                                style="text-align:right; font-size:15px; font-weight:700;" id="totalbalance" readonly>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', e => {
        $('#input-datalist').autocomplete()
    }, false);
</script>

<script>
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


    let loanData = []; // Array to store loan data for download




    function getCustomerId(value) {
        const match = value.match(/\((\d+)\)/); // Look for a number inside parentheses
        const customerId = match ? match[1] : null; // Extract the first matching number
        if (customerId) {
            getdataofloan(customerId); // Call the function with the numeric customerId
        }
    }



    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}-${month}-${year}`;
    }





    function getdataofloan(id) {

        let totaldr = 0;
        let totalcr = 0;
        let totalbalances = 0;
        let interestamounts = 0;
        let dd = 0;
        let cc = 0;
        let ff = 0;
        $.ajax({
            url: '{{ route('emireportloan') }}',
            type: 'GET',
            data: {
                id: id
            },
            success: function(response) {
                $('#dinchak').empty();
                loanData = []; // Clear previous data

                let rowColor = '';
                let totalPrincipal = 0;
                let totalPaid = 0;
                let totalPending = 0;
                let currentLoanId = null;
                let aaaaa = 0;
                let asasasas = 0;
                let ppp = 0;


                let currentLoanTotals = {
                    principal: 0,
                    paid: 0,
                    pending: 0,
                    interest: 0,
                }; // For tracking totals of current loan

                response.forEach(item => {
                    let rowColor = '';
                    const formattedLoanDate = formatDate(item.loan_date);
                    const formattedInstallmentDate = formatDate(item.installment_date);
                    const principal = parseFloat(item.principal);
                    const paid = parseFloat(item.paid);
                    const pending = parseFloat(item.pending);
                    const interest = parseFloat(item.interestpp) || 0;
                    const paidinterest = parseFloat(item.paidinterest) || 0;

                    if (currentLoanId !== item.loanid && currentLoanId !== null) {
                        // Add totals row for previous loan before switching to the new loan
                        const totalsRow = `
                        <tr class="raddd">
                            <td colspan="2" style="text-align: right;"><strong>Total for Loan ${currentLoanId}:</strong></td>
                            <td>${currentLoanTotals.principal.toFixed(2)}</td>
                            <td>${currentLoanTotals.paid.toFixed(2)}</td>
                            <td>${currentLoanTotals.pending.toFixed(2)}</td>
                            <td></td>
                        </tr>
                    `;
                        $('#dinchak').append(totalsRow); // Append the total row

                        // Reset totals for the next loan
                        currentLoanTotals = {
                            principal: 0,
                            paid: 0,
                            pending: 0,
                            interest: 0,
                        };
                    }

                    // Update totals for the current loan
                    currentLoanTotals.principal += principal;
                    currentLoanTotals.paid += paid;
                    currentLoanTotals.pending += pending;
                    {{--  currentLoanTotals.pending += interest;  --}}


                    totalPrincipal += principal;
                    totalPaid += paid;
                    totalPending += pending;
                    interestamounts += interest;


                    aaaaa = principal + interest;
                    asasasas = paid + paidinterest;
                    ppp = aaaaa - asasasas;

                    const currentDate = new Date();

                    // Parse the formattedInstallmentDate into a Date object (assuming it's in a format like 'YYYY-MM-DD')
                    const installmentDate = new Date(formattedInstallmentDate);
                    console.log('installmentDate' + installmentDate, 'currentDate' + currentDate);
                    {{--  if (installmentDate < currentDate) {
                        rowColor = 'laaal';
                    }  --}}

                    if (paid > pending) {
                        rowColor = '';
                    } else {
                        rowColor = 'laaal';
                    }


                    // Append the data row
                    const row = `
                        <tr class="${rowColor}">
                            <td>${formattedLoanDate}</td>
                            {{--  <td style="text-align: left;">(${item.loanid})-${item.loanname}</td>  --}}
                            <td style="text-align: left;">${item.emi_no}</td>
                            {{--  <td style="text-align: center;">${aaaaa}</td>  --}}
                            <td>${aaaaa.toFixed(2)}</td>
                            <td>${asasasas.toFixed(2)}</td>
                            <td>${ppp.toFixed(2)}</td>
                            <td>${formattedInstallmentDate}</td>
                        </tr>
                    `;

                    totaldr += principal;
                    totalcr += paid;
                    totalbalances += pending;
                    ff += paidinterest;

                    $('#dinchak').append(row);

                    // Store data for download
                    loanData.push([
                        formattedLoanDate, // Loan Date
                        {{--  Number(aaaaa).toFixed(2), // Amount  --}}
                        {{--  item.loanid, // EMI No  --}}
                        {{--  item.loanname, // EMI No  --}}
                        item.emi_no, // EMI No
                        Number(principal).toFixed(2), // AmouI No
                        Number(paid).toFixed(2), // Adjusted Amount
                        Number(pending).toFixed(2), // Pending Amount
                        formattedInstallmentDate // Due Date
                    ]);

                    currentLoanId = item.loanid; // Set current loan id
                });

                // After the loop, add the totals row for the last loan
                if (currentLoanId !== null) {
                    const totalsRow = `
                    {{--  <tr class="raddd">
                        <td colspan="3" style="text-align: right;"><strong>Total for Loan ${currentLoanId}:</strong></td>
                        <td>${currentLoanTotals.principal.toFixed(2)}</td>
                        <td>${currentLoanTotals.paid.toFixed(2)}</td>
                        <td>${currentLoanTotals.pending.toFixed(2)}</td>
                        <td></td>
                    </tr>  --}}
                `;
                    $('#dinchak').append(totalsRow);
                }

                $('#netrows').css('display', 'block');

                dd += interestamounts + totaldr;
                cc += totalcr + ff;

                let bbb = dd - cc;

                {{--  let cc = totalcr + interestamounts;  --}}


                $('#totaldr').val(dd.toFixed(2));
                $('#totalcr').val(cc.toFixed(2));
                $('#totalbalance').val(bbb.toFixed(2));

                // Add the grand totals row
                {{--  const grandTotalRow = `
                <tr class="raddd" style="margin-top:5px;">  --}}
                {{--  <td colspan="3" class="border" style="text-align: right;"><strong>Grand Total:</strong></td>  --}}
                {{--  <td class="border"></td>
                    <td class="border"></td>
                    <td class="border"></td>
                     <td class="border"></td>
                      <td class="border"></td>

                    <td class="border">${totalPending.toFixed(2)}</td>
                    <td class="border"></td>
                </tr>
            `;
                $('#dinchak').append(grandTotalRow);  --}}

                // Show the download button
                $('#downloadExcel').css('display', 'block');
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ', error);
            }
        });
    }

    document.getElementById('downloadExcel').addEventListener('click', function() {
        let csvContent = 'data:text/csv;charset=utf-8,';
        csvContent +=
            'Loan Date,Emi No,Amount,Adj Amount,Pending Amount,Due Date\n'; // Header

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




    document.addEventListener('selectstart', (event) => {
        event.preventDefault();
    });

    document.addEventListener('dragstart', (event) => {
        event.preventDefault();
    });

    document.addEventListener('copy', (event) => {
        event.preventDefault();
        alert("Copying is disabled on this page.");
    });

    document.addEventListener('contextmenu', (event) => {
        event.preventDefault();
        alert("Right-click is disabled on this page.");
    });
</script>

@include('include.footer')
