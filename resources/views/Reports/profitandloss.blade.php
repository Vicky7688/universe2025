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
                <form name="profitlossForm" id="profitlossForm">
                    <div class="row">
                        <div class="mb-2 col-md-2">
                            <label>Date From</label>
                            <input type="text" name="datefrom" class=" form-control datepicker" id="datefrom"
                                value="{{ Session::get('setcurrentdate') }}">

                        </div>
                        <div class="mb-2 col-md-2">
                            <label>Date To</label>
                            <input type="text" name="dateto" id="dateto " class="onlydate form-control datepicker"
                                value="{{ Session::get('setcurrentdate') }}">
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
                        <table class="table mb-0">
                            <thead>
                                <tr class="text-center">
                                    <th style="font-size: 16px; background-color: #ecdfe2; text-transform: uppercase; color: black;">Expenses</th>
                                </tr>
                            </thead>
                        </table>
                        <table class="table align-items-center text-center table-bordered mb-0 ">
                            <thead class="tableHeading">
                                <tr>
                                <tr>
                                    <th>Particular</th>
                                    <th>Amount</th>
                                </tr>
                                </tr>
                            </thead>
                            <tbody class="exptablebody" id="exptablebody">
                            </tbody>
                            <tbody id="netLossRow">
                                <tr>
                                    <td id="netloss"></td>
                                    <td id="netlossAmount"></td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr style="background-color: #3cbade !important;">
                                    <td id="grandTotalexpense" style="color:white;">Grand Total</td>
                                    <td id="grandTotalexpenseAmount" style="color:white;"></td>
                                </tr>
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
                                    <th style="font-size: 16px; background-color: #ecdfe2; text-transform: uppercase; color: black;">Incomes</th>
                                </tr>
                            </thead>
                        </table>
                        <table class="table align-items-center text-center table-bordered mb-0 ">
                            <thead class="tableHeading">
                                <tr>
                                    <th>Particular</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody class="inctablebody" id="inctablebody">
                            </tbody>
                            <tbody id="netprofitRow">
                                <tr>
                                    <td id="netprofit"></td>
                                    <td id="netprofitAmount"></td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr style="background-color: #3cbade !important;">
                                    <td id="grandTotalIncome" style="color:white;">Grand Total</td>
                                    <td id="grandTotalIncomeAmount" style="color:white;"></td>
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



<script>
    $(document).ready(function() {

        const dateInputs = document.querySelectorAll('.onlydate');
        dateInputs.forEach(dateInput => {
            dateInput.addEventListener('input', function (e) {
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


        $(document).on('submit','#profitlossForm',function(e) {
            e.preventDefault();

                   let startdate = $('#datefrom').val();
            let enddate = $('#dateto').val();

            $.ajax({
                url: "{{ route('profitandloss') }}",
                type: 'post',
                data: {
                    startdate: startdate,
                    enddate: enddate,
                _token: "{{ csrf_token() }}"
            },
                {{--  headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  --}}
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        /******Incomes Table*****/
                        let incomes = res.incomes || [];
                        let inctablebody = $('#inctablebody');
                        inctablebody.empty();

                        /******Expenses Table*****/
                        let expenses = res.expenses || [];
                        let exptablebody = $('#exptablebody');
                        exptablebody.empty();

                        let maxRow = Math.max(incomes.length, expenses.length);
                        let totalIncome = 0;
                        let totalExpenses = 0;

                        for (let i = 0; i < maxRow; i++) {
                            if (i < expenses.length) {
                                let expense = expenses[i];
                                exptablebody.append(`<tr><td>${expense.ledger_name}</td><td>${expense.total_expenses.toFixed(2)}</td></tr>`);
                                totalExpenses += expense.total_expenses;
                            } else {
                                exptablebody.append(`<tr><td>-</td><td>0</td></tr>`);
                            }

                            if (i < incomes.length) {
                                let income = incomes[i];
                                inctablebody.append(`<tr><td>${income.ledger_name}</td><td>${income.total_income.toFixed(2)}</td></tr>`);
                                totalIncome += income.total_income;
                            } else {
                                inctablebody.append(`<tr><td>-</td><td>0</td></tr>`);
                            }
                        }


                            let total_recoverable = 0;
                            let intt_recoverable = res.intt_recoverable ? res.intt_recoverable : 0;
                            total_recoverable += intt_recoverable;
                            if(total_recoverable > 0)
                            {
                                inctablebody.append(`<tr><td>Intt. On Recoverable Loan</td><td>${total_recoverable.toFixed(2)}</td></tr>`);
                            }else{
                                inctablebody.append('');
                            }
                            totalIncome += total_recoverable;

                       let previousyearintt_recoverable = res.previous_intt_recoverable ? res.previous_intt_recoverable : 0;

                        {{--  previousyearintt_recoverable.forEach(data => {  --}}
                            exptablebody.append(`<tr><td>LBS:- Intt. On Recoverable </td><td>${previousyearintt_recoverable.toFixed(2)}</td></tr>`);
                            totalExpenses += previousyearintt_recoverable;
                        {{--  });  --}}


                        let difference = totalIncome - totalExpenses;

                        if (difference > 0) {
                            exptablebody.append(`<tr class="netProfitRow"><td>Net Profit</td><td>${difference.toFixed(2)}</td></tr>`);
                            totalExpenses += difference;
                        } else if (difference < 0) {
                            inctablebody.append(`<tr><td>Net Loss</td><td>${(-difference).toFixed(2)}</td></tr>`);
                            totalIncome += (-difference);
                        }

                        $('#grandTotalIncomeAmount').text(totalIncome.toFixed(2));
                        $('#grandTotalexpenseAmount').text(totalExpenses.toFixed(2));

                        {{--  console.log("Total Income: " + totalIncome.toFixed(2));
                        console.log("Total Expenses: " + totalExpenses.toFixed(2));
                        console.log("Net Profit or Loss: " + difference.toFixed(2));  --}}
                    }
                    else {
                        if (res.status === 'fail') {
                            toastr.error(res.messages);
                        }
                    }

                }
            });
        });
    });
</script>
@include('include.footer')
