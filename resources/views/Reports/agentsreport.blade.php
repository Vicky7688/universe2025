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
                <form name="groupLegderForm" id="groupLegderForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="mb-2 col-md-2">
                            <label>Date From</label>
                            <input type="text" name="datefrom" class="onlydate form-control datepicker" id="datefrom"
                                value="{{ Session::get('setcurrentdate') }}">
                        </div>
                        <div class="mb-2 col-md-2">
                            <label>Date To</label>
                            <input type="text" name="dateto" class="onlydate form-control datepicker"
                                value="{{ Session::get('setcurrentdate') }}">
                        </div>
                        <div class="mb-2 col-md-2">
                            <label class="form-label">Agents</label>
                            <select name="agentid" id="agentid" class="form-select">
                                @if(!empty($agents))
                                    <option value="All" selected>All</option>
                                    @foreach ($agents as $row)
                                        <option value="{{ $row->id }}">{{ ucwords($row->user_name).' - '.$row->agent_code }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mb-2 col-md-2">
                            <label class="form-label">Report Type</label>
                            <select name="viewtype" id="viewtype" class="form-select" id="group-d">
                                <option value="detailed">Detailed</option>
                                <option value="compact">Compact</option>
                            </select>
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
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body table-responsive">
                <table id="data-table" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Receipt Date</th>
                            <th>Agent Name</th>
                            <th>Customer</th>
                            <th>Loan Name</th>
                            <th>Loan Advancement</th>
                            <th>Loan Received</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <!-- Rows will be added here by jQuery -->
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
</div>
</div>


<script>
    $(document).ready(function() {

        $(document).on('submit', '#groupLegderForm', function(e) {
            e.preventDefault();
            let formData = $(this).serializeArray();
            $.ajax({
                url: '{{ route('agentsreport') }}',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(res) {
                    var viewtype = $('#viewtype').val();
                    if (viewtype == 'detailed') {
                        let datarow = res.results;

                        var tbody = $('#tbody');
                        tbody.empty();
                        var totalPrincipal = 0;
                        let loanAmount = 0;

                        let rowIndex = 1;

                        let loanAdvance = res.loan_advancements || [];

                        $.each(loanAdvance, function(index, data) {
                            var originalDate = new Date(data.loanDate);
                            var day = originalDate.getDate().toString().padStart(2, '0');
                            var month = (originalDate.getMonth() + 1).toString().padStart(2, '0');
                            var year = originalDate.getFullYear();
                            var formatgrdate = `${day}-${month}-${year}`;

                            let amount = parseFloat(data.loanAmount);

                            let row = `<tr>
                                <td>${rowIndex++}</td>
                                <td>${formatgrdate}</td>
                                <td>${data.agentName}</td>
                                <td>(${data.customer_Id}) ${data.cutomername}</td>
                                <td>${data.loanname}</td>
                                <td>${amount}</td>
                                <td>${0}</td>
                            </tr>`;
                            tbody.append(row);
                            loanAmount += amount;
                        });


                        $.each(datarow, function(index, data) {
                            var originalDate = new Date(data.receiptDate);
                            var day = originalDate.getDate();
                            var month = originalDate.getMonth() + 1;
                            var year = originalDate.getFullYear();
                            day = day < 10 ? '0' + day : day;
                            month = month < 10 ? '0' + month : month;
                            var formatgrdate = day + '-' + month + '-' + year;
                            let formattedPrincipal = parseFloat(data.principal).toFixed(0);

                            totalPrincipal += parseFloat(data.principal);

                            let row = '<tr>' +
                                '<td>' + (rowIndex++) + '</td>' +
                                '<td>' + formatgrdate + '</td>' +
                                '<td>' + data.agentName + '</td>' +
                                '<td>(' + data.customer_Id + ') ' + data.cutomername + '</td>' +
                                '<td>' + data.loanname + '</td>' +
                                '<td>' + 0 + '</td>' +
                                '<td>' + formattedPrincipal + '</td>' +
                                '</tr>';
                            tbody.append(row);
                        });



                        let totalRow = `<tr>
                            <td colspan="5"><strong>Total</strong></td>
                            <td><strong>${loanAmount}</strong></td>
                            <td><strong>${totalPrincipal.toFixed(0)}</strong></td>
                        </tr>`;
                        tbody.append(totalRow);



                    }else {
                        let datarow = res.results;

                        var tbody = $('#tbody');
                        tbody.empty();
                        var totalPrincipal = 0;
                        var loanAmount = 0;

                        let loanAdvance = res.loan_advancements || [];


                        let rowIndex = 1;

                        $.each(loanAdvance, function(index, data) {
                            var originalDate = new Date(data.loanDate);
                            var day = originalDate.getDate().toString().padStart(2, '0');
                            var month = (originalDate.getMonth() + 1).toString().padStart(2, '0');
                            var year = originalDate.getFullYear();
                            var formatgrdate = `${day}-${month}-${year}`;

                            let amount = parseFloat(data.totalLoanAmount);

                            let row = `<tr>
                                <td>${rowIndex++}</td>  <!-- Use rowIndex and increment it -->
                                <td></td>
                                <td>${data.agentName}</td>
                                <td>(${data.customer_Id}) ${data.cutomername}</td>
                                <td>${data.loanname}</td>
                                <td>${amount}</td>
                                <td>${0}</td>
                            </tr>`;
                            tbody.append(row);
                            loanAmount += amount;
                        });

                        $.each(datarow, function(index, data) {
                            let row = `<tr>
                                <td>${rowIndex++}</td>  <!-- Use rowIndex and increment it -->
                                <td></td>
                                <td>${data.agentName}</td>
                                <td>${data.customerIds}</td>
                                <td></td>
                                <td>${0}</td>
                                <td>${parseFloat(data.totalPrincipal).toFixed(0)}</td>
                            </tr>`;

                            tbody.append(row);
                            totalPrincipal += parseFloat(data.totalPrincipal);
                        });

                        let totalRow = `<tr>
                            <td colspan="5"><strong>Total</strong></td>
                            <td><strong>${loanAmount}</strong></td>
                            <td><strong>${totalPrincipal.toFixed(0)}</strong></td>
                        </tr>`;
                        tbody.append(totalRow);
                    }

                }
            });
        });
    });

    $(document).on('click', '#tbody tr', function() {
        // Remove 'selected' class from all rows
        $('#tbody tr').removeClass('selected');

        // Add 'selected' class to the clicked row
        $(this).addClass('selected');
    });
</script>
@include('include.footer')
