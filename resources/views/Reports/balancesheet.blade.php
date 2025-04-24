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
                <form name="balancesheet" id="balancesheet" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="mb-2 col-md-2">
                            <label>Date From</label>
                            <input type="text" name="datefrom" class="onlydate form-control datepicker" id="datefrom"
                                 value="{{ date('d-m-Y', strtotime(Session::get('setcurrentdate'))) }}"
                                 >
                        </div>
                        <div class="mb-2 col-md-2">
                            <label>Date To</label>
                            <input type="text" name="dateto" id="dateto" class="onlydate form-control datepicker"
                                value="{{ date('d-m-Y', strtotime(Session::get('setcurrentdate'))) }}">
                        </div>
                        <div class="mb-2 col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary submit-one">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="tableSection row mb-4">
    <div class="col-lg-12 col-md-12 mb-4">
        <div class="card">
            <div class="card-body px-0 pb-2">
               <div class="row">
                <div class="table-responsive col-sm-6">
                    <!-- <div id="loader" style="display: none;">
                        <div class="dot-loader"></div>
                        <div class="dot-loader dot-loader--2"></div>
                        <div class="dot-loader dot-loader--3"></div>
                    </div> -->
                    <table class="table mb-0">
                        <thead>
                            <tr class="text-center">
                                <th style="font-size: 16px; background-color: #ecdfe2; text-transform: uppercase; color: black;">Liabilty</th>
                            </tr>
                        </thead>
                    </table>
                    <table class="table align-items-center text-center table-bordered mb-0  liability">
                        <thead class="tableHeading">
                            <tr>
                                <tr>
                                    <th>Particular</th>
                                    <th>Amount</th>
                                    <th>Total</th>
                                </tr>
                            </tr>
                        </thead>
                        <tbody class="ltableBody" id="ltableBody">
                        </tbody>

                    </table>
                    <div class="d-flex justify-content-end mt-3">

                    </div>
                </div>



                <div class="table-responsive col-sm-6">
                    <!-- <div id="loader" style="display: none;">
                        <div class="dot-loader"></div>
                        <div class="dot-loader dot-loader--2"></div>
                        <div class="dot-loader dot-loader--3"></div>
                    </div> -->
                    <table class="table mb-0">
                        <thead>
                            <tr class="text-center">
                                <th style="font-size: 16px; background-color: #ecdfe2; text-transform: uppercase; color: black;">Assets</th>
                            </tr>
                        </thead>
                    </table>
                    <table class="table align-items-center text-center table-bordered mb-0  assets">
                        <thead class="tableHeading">
                            <tr>
                                <th>Particular</th>
                                <th>Amount</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody class="tableBody" id="assets">
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



<script>
    $(document).ready(function() {

        {{--  const dateInputs = document.querySelectorAll('.onlydate');
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
        });  --}}



        $(document).on('submit', '#balancesheet', function(e) {
            e.preventDefault();

            let startDate = $('#datefrom').val();
            let endDate = $('#dateto').val();
            $.ajax({
                url: "{{ route('getbalanceSheet') }}",
                type: 'post',
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    _token: "{{ csrf_token() }}"
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        $('#assets').empty();
                        $('#ltableBody').empty();

                        let grandTotalAssets = 0;
                        let previousGroupNameAssets = {};
                        var groupTotalAssets = 0;
                        var openingBalance = 0;
                        /*****Process Assets*****/

                        $.each(res.assets, function(index, item) {
                            if (item.group_name !== previousGroupNameAssets) {
                                if (previousGroupNameAssets !== null) {
                                    $('#assets').append('<tr><td colspan="2">Total</td><td>' + groupTotalAssets.toFixed(2) +'</td></tr>');
                                }
                                $('#assets').append( '<tr class="rod"><td colspan="3">' + item.group_name + '</td></tr>');
                                previousGroupNameAssets = item.group_name;
                                groupTotalAssets = 0;
                            }

                           openingBalance = item.opening_balance;
                           let debitTotal = item.total_debit;
                           let creditTotal = item.total_credit;
                           let balance = debitTotal - creditTotal;
                            grandTotalAssets +=balance;
                            groupTotalAssets +=balance;
                            if (balance > 0) {
                                $('#assets').append('<tr><td>' + item.ledger_name + '</td><td>' + balance.toFixed(2) +'</td><td></td></tr>');
                            }
                        });

                        let grandTotalLiabilities = 0;
                        let previousGroupNameLiabilities = null;
                        let groupTotalLiabilities = 0;

                        /*****Process Liabilities*****/
                        $.each(res.liabilities, function(index, item) {
                            if (item.group_name !== previousGroupNameLiabilities) {
                                if (previousGroupNameLiabilities !== null) {
                                    $('#ltableBody').append('<tr><td colspan="2">Total</td><td>' + groupTotalLiabilities.toFixed(2) +'</td></tr>');
                                }

                                $('#ltableBody').append('<tr class="rod"><td colspan="3">' + item.group_name + '</td></tr>');
                                previousGroupNameLiabilities = item.group_name;
                                groupTotalLiabilities = 0;
                            }

                            let openingBalance = parseFloat(item.opening_balance) ? parseFloat(item.opening_balance) : 0;
                            let debitTotal = parseFloat(item.total_debit) ? parseFloat(item.total_debit) : 0;
                            let creditTotal = parseFloat(item.total_credit) ? parseFloat(item.total_credit) : 0;
                            let balance = openingBalance + creditTotal - debitTotal;

                            groupTotalLiabilities += balance;
                            grandTotalLiabilities += balance;
                            if (balance > 0) {
                                $('#ltableBody').append('<tr><td>' + item.ledger_name + '</td><td>' + balance.toFixed(2) + '</td><td></td></tr>');
                            }

                        });

                        if (previousGroupNameAssets !== null) {
                            $('#assets').append('<tr><td colspan="2">Total</td><td>' + groupTotalAssets.toFixed(2) + '</td></tr>');
                        }

                        if (previousGroupNameLiabilities !== null) {
                            $('#ltableBody').append('<tr><td colspan="2">Total</td><td>' + groupTotalLiabilities.toFixed(2) + '</td></tr>');
                        }

                        let totalExpenses = 0;
                        let totalIncome = 0;

                        let lastYeartotalExpenses = 0;
                        let lastYeartotalIncome = 0;
                        let lastInttRecoverable = 0;
                        let previousIncomebalance = 0;

                        /*********************************************Previous Year Works*************************************************************/

                        /**Previous Year Interest Recoverable***/
                        let previousyearintt_recoverable = res.previous_intt_recoverable;
                        previousyearintt_recoverable = Math.round(previousyearintt_recoverable);
                        {{--  console.log(previousyearintt_recoverable);  --}}

                        // Since it's a single number, you can directly use it in your calculations
                        lastInttRecoverable += previousyearintt_recoverable;
                        lastYeartotalIncome += lastInttRecoverable;



                        /**Previous Year Expenses***/
                        $.each(res.previousyearexpenses, function(index, exp) {
                            let openingBalance = parseFloat(exp.opening_balance) ? parseFloat(exp.opening_balance) : 0;
                            let debitTotal = parseFloat(exp.total_debit) ? parseFloat(exp.total_debit) : 0;
                            let creditTotal = parseFloat(exp.total_credit) ? parseFloat(exp.total_credit) : 0;
                            let previousExpbalance = openingBalance + debitTotal - creditTotal;
                            lastYeartotalExpenses += previousExpbalance;
                        });

                        /**Previous Year Incomes***/
                        $.each(res.previousyearincomes, function(index, income) {
                            let openingBalance = parseFloat(income.opening_balance) ? parseFloat(income.opening_balance) : 0;
                            let debitTotal = parseFloat(income.total_debit) ? parseFloat(income.total_debit) : 0;
                            let creditTotal = parseFloat(income.total_credit) ? parseFloat(income.total_credit) : 0;
                            previousIncomebalance += openingBalance + creditTotal - debitTotal;
                            lastYeartotalIncome += previousIncomebalance;
                        });



                        /*********************************************Current Year Works*************************************************************/

                        {{--  let previousLedger = {};  --}}

                        currentInttRecoverable = 0;
                        let intt_recoverable = res.intt_recoverable;
                        intt_recoverable = Math.round(intt_recoverable);


                        // Since it's a single number, you can directly use it in your calculations
                        currentInttRecoverable += parseFloat(intt_recoverable) ? parseFloat(intt_recoverable) : 0;
                        grandTotalAssets += currentInttRecoverable;

                        if(currentInttRecoverable > 0){
                            $('#assets').append('<tr><td colspan="2">Intt. On Recoverable On Loan</td><td>' + currentInttRecoverable.toFixed(2) + '</td></tr>');
                        }else{
                            $('#assets').append('');
                        }


                        /**Current Year Expenses***/
                        $.each(res.expenses, function(index, exp) {
                            let openingBalance = parseFloat(exp.opening_balance) ? parseFloat(exp.opening_balance) : 0;
                            let debitTotal = parseFloat(exp.total_debit) ? parseFloat(exp.total_debit) : 0;
                            let creditTotal = parseFloat(exp.total_credit) ? parseFloat(exp.total_credit) : 0;
                            let balance = openingBalance + debitTotal - creditTotal;
                            totalExpenses += balance;
                        });

                        /**Current Year Incomes***/
                        $.each(res.incomes, function(index, income) {
                            let openingBalance = parseFloat(income.opening_balance) ? parseFloat(income.opening_balance) : 0;
                            let debitTotal = parseFloat(income.total_debit) ? parseFloat(income.total_debit) : 0;
                            let creditTotal = parseFloat(income.total_credit) ? parseFloat(income.total_credit) : 0;
                            let balance = openingBalance + creditTotal - debitTotal;
                            totalIncome += balance;
                        });


                        /**Previous Year Profit & Loss***/
                        let Lastdifference = lastYeartotalIncome - lastYeartotalExpenses;
                        {{--  console.log(Lastdifference);  --}}

                        if (Lastdifference > 0) {
                            $('#ltableBody').append('<tr>' + '<td colspan="2">' +'LBS :- Profit FY- ' + res.financialYear + '</td>' +'<td>' + Lastdifference.toFixed(2) + '</td></tr>');
                            grandTotalLiabilities += Lastdifference;
                        } else if (Lastdifference < 0) {
                            $('#assets').append('<tr class="netProfitRow"><td colspan="2">LBS :- Net Loss</td><td>' + (-Lastdifference).toFixed(2) + '</td></tr>');
                            grandTotalAssets += (-Lastdifference);
                        }

                        /**Current Year Profit & Loss***/
                        let difference = totalIncome - totalExpenses + currentInttRecoverable - previousyearintt_recoverable ;

                        if (difference > 0) {
                            $('#ltableBody').append('<tr>' + '<td colspan="2">' + 'Net Profit FY- ' + res.currentfinancialYear + '</td>' +'<td>' + difference.toFixed(2) + '</td></tr>');
                            grandTotalLiabilities += difference;
                        } else if (difference < 0) {
                            $('#assets').append('<tr class="netProfitRow"><td colspan="2">Net Loss Current Year</td><td>' + (-difference).toFixed(2) + '</td></tr>');
                            grandTotalAssets += (-difference);
                        }


                        let assetsRowCount = $('#assets tr').length;
                        let liabilitiesRowCount = $('#ltableBody tr').length;
                        let maxRowCount = Math.max(assetsRowCount, liabilitiesRowCount);

                        for (let i = assetsRowCount; i < maxRowCount; i++) {
                            $('#assets').append('<tr><td colspan="2">&nbsp;</td></tr>');
                        }

                        for (let i = liabilitiesRowCount; i < maxRowCount; i++) {
                            $('#ltableBody').append('<tr><td colspan="2">&nbsp;</td><td></td></tr>');
                        }

                        /**Grand Totals of Balance Sheet***/
                        $('#assets').append('<tr class="raddd" style="background-color: #3cbade; color:white;"><td colspan="2" style="color:white;">Grand Total</td><td style="color:white;">' + grandTotalAssets.toFixed(2) + '</td></tr>');
                        $('#ltableBody').append('<tr class="raddd" style="background-color: #3cbade; color:white;"><td colspan="2" style="color:white;">Grand Total</td><td style="color:white;">' + grandTotalLiabilities.toFixed(2) + '</td></tr>');
                    } else if (res.status === 'fail') {
                        toastr.error(res.messages);
                    }
                }
            });
        });
    });
</script>
@include('include.footer')
