@include('include.header')

<style>
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

</style>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form name="personalLedgerForm" id="personalLedgerForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="mb-2 col-md-2">
                            <label>Date From</label>
                            <input type="text" name="datefrom"  class="onlydate form-control datepicker" id="datefrom"
                                value="{{ Session::get('setcurrentdate') }}">
                        </div>
                        <div class="mb-2 col-md-2">
                            <label>Date To</label>
                            <input type="text" name="dateto" id="dateto" class="onlydate form-control datepicker" value="{{ Session::get('setcurrentdate') }}">
                        </div>
                        <div class="mb-2 col-md-2">
                            <label>Account No</label>
                            <input type="text" onchange="getCustomerAccount('this')" name="account_no" id="account_no" class="onlydate form-control">
                        </div>
                        <div class="mb-2 col-md-2">
                            <label>Loan No</label>
                            <select id="loan_no" name="loan_no" class="form-select">
                                <option value="" selected>Select Acc</option>
                            </select>
                        </div>

                        <div class="mb-2 col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary submit-one">View</button>
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
            <div>
                <p class="mt-1" style="text-align: center;" id="customername"></p>
            </div>
            <div class="card-body table-responsive">
                <table id="data-table" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                           <th>Sr.No</th>
                           <th>Date</th>
                           <th>Particular</th>
                           <th>Debit</th>
                           <th>Credit</th>
                           <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody class="tableBody" id="tableBody">
                    </tbody>
                    <tbody>
                        <tr style="background-color: #3cbade;">
                            <td colspan="3" style="color:#fff !important;"><b>Grand Total</b></td>
                            <td id="debitamount" style="color:#fff !important;"></td>
                            <td id="creditamount" style="color:#fff !important;"></td>
                            <td id="balanceamount" style="color:#fff !important;"></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<script>

</script>
@include('include.footer')

<script>
    //____________Get Loan Account
    function getCustomerAccount() {
        let accountNo = document.getElementById('account_no').value;

        fetch("{{ route('get-customer-loan-account') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ account_no: accountNo })
        }).then(response => response.json())
        .then(res => {
            if(res.status === 'success'){
                let accounts = res.account_numbers;
                let loanAccountDropdown = document.getElementById('loan_no');
                loanAccountDropdown.innerHTML = '';

                if(accounts && accounts.length > 0){
                    accounts.forEach((data) =>{
                        let option = document.createElement('option');
                        option.value = data.id;
                        option.textContent = data.id;
                        loanAccountDropdown.appendChild(option);
                    });
                }else{
                    accounts.forEach((data) =>{
                        let option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Select Loan Ac';
                        loanAccountDropdown.appendChild(option);
                    });
                }
            }
        })
        .catch(error => {
            console.log('Error:', error);
        });
    }


    $(document).ready(function(){

        //______________Get Customer Loan Details
        document.getElementById('personalLedgerForm').addEventListener('submit',function(event){
            event.preventDefault();

            let formData = new FormData(this);

            fetch("{{ route('get-loan-details') }}",{
                method : 'POST',
                headers : {'X-CSRF-TOKEN' : "{{ csrf_token() }}"},
                body : formData
            })
            .then(response => response.json())
            .then(res => {
                if (res.status === 'success') {
                    $('#downloadExcel').show();
                    // $('#downloadPDF').show();

                    let datarow = res.loan_details[""];
                    if (!datarow || datarow.length === 0) {
                        console.error("No data found in accountwiseDetails.");
                        return;
                    }

                    let tableBody = $('#tableBody');
                    tableBody.empty();
                    let counter = 1;

                    function ucfirst(str) {
                        if (!str) return str;
                        return str.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
                    }

                    let customerName = ucfirst(datarow[0].name);
                    let nameElement = $('#customername');
                    nameElement.text("Name: " + customerName);

                    let loanoriginalDate = new Date(datarow[0].loanDate);
                    let days = loanoriginalDate.getDate().toString().padStart(2, '0');
                    let months = (loanoriginalDate.getMonth() + 1).toString().padStart(2, '0');
                    let years = loanoriginalDate.getFullYear();
                    let formatgrdates = days + '-' + months + '-' + years;

                    let loanAmount = parseFloat(datarow[0].loanAmount);

                    let loanDisbursment = '<tr>' +
                        '<td>' + (counter++) + '</td>' +
                        '<td>' + formatgrdates + '</td>' +
                        '<td>' + 'Loan Disbursed' + '</td>' +
                        '<td>' + loanAmount.toFixed(2) + '</td>' +
                        '<td>' + '0.00' + '</td>' +
                        '<td>' + loanAmount.toFixed(2) + '</td>' +
                        '</tr>';
                    tableBody.append(loanDisbursment);

                    let closingBalance = loanAmount;
                    let drTotal = loanAmount;
                    let crTotal = 0;

                    $.each(datarow, function(index, data) {
                        let originalDate = new Date(data.receiptDate);
                        let day = originalDate.getDate().toString().padStart(2, '0');
                        let month = (originalDate.getMonth() + 1).toString().padStart(2, '0');
                        let year = originalDate.getFullYear();
                        let formatgrdate = day + '-' + month + '-' + year;

                        let loan_principal = parseFloat(data.principal) || 0;
                        let loan_interest = parseFloat(data.interest) || 0;
                        let overdue_interest = parseFloat(data.overDueInterest) || 0;
                        let penal_interest = parseFloat(data.penalInterest) || 0;

                        let totalssss = loan_principal + loan_interest + overdue_interest + penal_interest;

                        if (loan_principal > 0) {
                            closingBalance -= totalssss;
                            let principal = '<tr>' +
                                '<td>' + counter++ + '</td>' +
                                '<td>' + formatgrdate + '</td>' +
                                '<td>' + 'Received' + '</td>' +
                                '<td>' + '0.00' + '</td>' +
                                '<td>' + totalssss.toFixed(2) + '</td>' +
                                '<td>' + closingBalance.toFixed(2) + '</td>' +
                                '</tr>';
                            tableBody.append(principal);
                            crTotal += totalssss;
                        }

                        if (loan_interest > 0) {
                            closingBalance += loan_interest;
                            let interest = '<tr>' +
                                '<td>' + counter++ + '</td>' +
                                '<td>' + formatgrdate + '</td>' +
                                '<td>' + 'Interest' + '</td>' +
                                '<td>' + loan_interest.toFixed(2) + '</td>' +
                                '<td>' + '0.00' + '</td>' +
                                '<td>' + closingBalance.toFixed(2) + '</td>' +
                                '</tr>';
                            tableBody.append(interest);
                            drTotal += loan_interest;
                        }

                        if (overdue_interest > 0) {
                            closingBalance += overdue_interest;
                            let overDueIntt = '<tr>' +
                                '<td>' + counter++ + '</td>' +
                                '<td>' + formatgrdate + '</td>' +
                                '<td>' + 'Over Due Interest' + '</td>' +
                                '<td>' + overdue_interest.toFixed(2) + '</td>' +
                                '<td>' + '0.00' + '</td>' +
                                '<td>' + closingBalance.toFixed(2) + '</td>' +
                                '</tr>';
                            tableBody.append(overDueIntt);
                            drTotal += overdue_interest;
                        }

                        if (penal_interest > 0) {
                            closingBalance += penal_interest;
                            let penalIntt = '<tr>' +
                                '<td>' + counter++ + '</td>' +
                                '<td>' + formatgrdate + '</td>' +
                                '<td>' + 'Penal Interest' + '</td>' +
                                '<td>' + penal_interest.toFixed(2) + '</td>' +
                                '<td>' + '0.00' + '</td>' +
                                '<td>' + closingBalance.toFixed(2) + '</td>' +
                                '</tr>';
                            tableBody.append(penalIntt);
                            drTotal += penal_interest;
                        }
                    });

                    $("#debitamount").text(drTotal.toFixed(2));
                    $("#creditamount").text(crTotal.toFixed(2));
                    $("#balanceamount").text((drTotal - crTotal).toFixed(2));

                    // Create Excel file
                    {{--  function downloadExcel() {
                        let wb = XLSX.utils.book_new();
                        let ws = XLSX.utils.aoa_to_sheet(excelData);
                        XLSX.utils.book_append_sheet(wb, ws, "Account Details");
                        XLSX.writeFile(wb, "AccountDetails.xlsx");
                    }  --}}

                    // Add buttons to download files
                    {{--  $('#downloadExcel').click(downloadExcel);  --}}
                    // $('#downloadPDF').click(downloadPDF);
                }
            })
            .catch(error => {
                console.log('Error:', error);
            });
        });



    });
</script>
