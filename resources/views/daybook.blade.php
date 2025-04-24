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

.rod {
    background: #dfe4e8; 
}

.rod td {
    text-align: left;
    font-weight: 600 !important;
    padding-left: 19px !important;
}
</style>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                @csrf
                <div class="row">
                    <div class="mb-2 col-md-2">
                        <label>Date From</label>
                        <input type="text" name="datefrom" class="onlydate form-control datepicker" id="datefrom"
                            value="{{ Session::get('setcurrentdate') }}">
                    </div>
                    <div class="mb-2 col-md-2">
                        <label>Date To</label>
                        <input type="text" name="dateto" class="onlydate form-control datepicker" id="dateto"
                            value="{{ Session::get('setcurrentdate') }}">
                    </div>
                    <div class="mb-2 col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary submit-one" onClick="getdata()"><i class="mdi mdi-eye"></i> View</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mt-4">
            <div class="table-responsive">
                <table class="table align-items-center table-bordered mb-0 sale" id="excelTable">
                    <thead class="tableHeading">
                        <tr class="rad">
                            <th colspan="7"  style="text-align:center"><h3>Reciept</h3></th>
                        </tr>
                        <tr class="text-center" id="table-header-row">
                            <th>Sr.no</th>
                            <th>Date</th>
                            <th>ClientId</th>
                            <th>Name</th>
                            <th>Agent</th>
                            <th>Amount</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody class="tableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mt-4">
            <div class="table-responsive">
                <table class="table align-items-center table-bordered mb-0 purchase" id="excelTablee">
                    <thead class="tableHeading">
                        <tr class="rad">
                            <th colspan="7" style="text-align:center"><h3>Payment</h3></th>
                        </tr>
                        <tr class="text-center" id="table-header-row">
                            <th>Sr.no</th>
                            <th>Date</th>
                            <th>ClientId</th>
                            <th>Name</th>
                            <th>Agent</th>
                            <th>Amount</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody class="tableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>




<script>
    function getdata() {
        var dateto = $('#dateto').val();
        var datefrom = $('#datefrom').val();

        $.ajax({
            url: "{{ route('daybookdata') }}",
            type: "POST",
            data: {
                dateto: dateto,
                datefrom: datefrom,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(data) {
                console.log("Received data:", data); // Debugging line

                // Clear existing rows
                $('.sale tbody').empty();
                $('.purchase tbody').empty();

                // Sale Table
                var previousLedgerName = null;
                var groupTotal = 0;
                var grandTotal = 0;
                var indexWithinGroup = 0;
                var processedIds = []; // Array to store processed IDs

                if (data.openingBalancepurchase) {
                    $('.sale tbody').append('<tr class="raddd"><td colspan="6">Opening Balance</td><td>' + data.openingBalancepurchase + '</td></tr>');
                    {{--  console.log(data.openingBalancepurchase);  --}}
                }

                $.each(data.sale, function(index, item) {
                    if (processedIds.includes(item.id)) {
                        return; // Skip if item.id is already processed
                    }
                    processedIds.push(item.id);

                    // Format Date
                    var originalDate = new Date(item.transactionDate);
                    var day = ('0' + originalDate.getDate()).slice(-2);
                    var month = ('0' + (originalDate.getMonth() + 1)).slice(-2);
                    var year = originalDate.getFullYear();
                    var formattedDate = `${day}-${month}-${year}`;

                    // Grouping and Totals
                    if (item.ledgers_name !== previousLedgerName) {
                        if (previousLedgerName !== null) {
                            $('.sale tbody').append('<tr><td colspan="6">Total</td><td>' + groupTotal.toFixed(2) + '</td></tr>');
                        }
                        $('.sale tbody').append('<tr class="rod"><td colspan="7">' + item.ledgers_name + '</td></tr>');
                        previousLedgerName = item.ledgers_name;
                        groupTotal = 0;
                        indexWithinGroup = 0;
                    }

                    groupTotal += parseFloat(item.transactionAmount);
                    grandTotal += parseFloat(item.transactionAmount);

                    $('.sale tbody').append('<tr><td>' + (++indexWithinGroup) + '</td><td>' + formattedDate + '</td><td>' + item.accountNo + '</td><td>' + item.formName + '</td><td>' + item.agent_name + '</td><td>' + item.transactionAmount + '</td></tr>');
                });

                if (previousLedgerName !== null) {
                    $('.sale tbody').append('<tr><td colspan="6">Total</td><td>' + groupTotal.toFixed(2) + '</td></tr>');
                }

                var rightot = (parseFloat(data.openingBalancepurchase) + grandTotal).toFixed(2);
                $('.sale tbody').append('<tr class="raddd"><td colspan="6">Grand Total</td><td>' + rightot + '</td></tr>');

                // Purchase Table
                previousLedgerName = null;
                groupTotal = 0;
                grandTotal = 0;
                indexWithinGroup = 0;
                processedIds = []; // Reset array

                $.each(data.purchase, function(index, item) {
                    if (processedIds.includes(item.id)) {
                        return; // Skip if item.id is already processed
                    }
                    processedIds.push(item.id);

                    // Format Date
                    var originalDate = new Date(item.transactionDate);
                    var day = ('0' + originalDate.getDate()).slice(-2);
                    var month = ('0' + (originalDate.getMonth() + 1)).slice(-2);
                    var year = originalDate.getFullYear();
                    var formattedDate = `${day}-${month}-${year}`;

                    // Grouping and Totals
                    if (item.ledgers_name !== previousLedgerName) {
                        if (previousLedgerName !== null) {
                            $('.purchase tbody').append('<tr><td colspan="6">Total</td><td>' + groupTotal.toFixed(2) + '</td></tr>');
                        }
                        $('.purchase tbody').append('<tr class="rod"><td colspan="7">' + item.ledgers_name + '</td></tr>');
                        previousLedgerName = item.ledgers_name;
                        groupTotal = 0;
                        indexWithinGroup = 0;
                    }

                    groupTotal += parseFloat(item.transactionAmount);
                    grandTotal += parseFloat(item.transactionAmount);
                    rightot = (parseFloat(rightot) - parseFloat(item.transactionAmount)).toFixed(2);
                    $('.purchase tbody').append('<tr><td>' + (++indexWithinGroup) + '</td><td>' + formattedDate + '</td><td>' + item.accountNo + '</td><td>' + item.formName + '</td><td>' + item.agent_name + '</td><td>' + item.transactionAmount + '</td></tr>');
                });

                if (previousLedgerName !== null) {
                    $('.purchase tbody').append('<tr><td colspan="6">Total</td><td>' + groupTotal.toFixed(2) + '</td></tr>');
                }

                $('.purchase tbody').append('<tr class="raddddd"><td colspan="6">Cash-in-hand</td><td>' + rightot + '</td></tr>');

                var rightots = (parseFloat(rightot) + grandTotal).toFixed(2);
                $('.purchase tbody').append('<tr class="raddd"><td colspan="6">Grand Total</td><td>' + rightots + '</td></tr>');

                // Call gogo() after updating tables
                gogo();
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error); // Debugging line
            }
        });
    }

    function gogo() {
        const saleTable = document.querySelector('#excelTable tbody');
        const purchaseTable = document.querySelector('#excelTablee tbody');

        if (!saleTable || !purchaseTable) {
            console.error("Table elements not found."); // Debugging line
            return;
        }

        const saleRows = saleTable.querySelectorAll('tr');
        const purchaseRows = purchaseTable.querySelectorAll('tr');

        const saleRowCount = saleRows.length;
        const purchaseRowCount = purchaseRows.length;

        const insertEmptyRows = (table, rowCount, targetCount, beforeSelector) => {
            const diff = targetCount - rowCount;
            for (let i = 0; i < diff; i++) {
                const emptyRow = document.createElement('tr');
                const emptyTd = document.createElement('td');
                emptyTd.setAttribute('colspan', '7'); // Adjust colspan to match the number of columns in your table
                emptyTd.innerHTML = '&nbsp;';
                emptyRow.appendChild(emptyTd);
                const beforeElement = table.querySelector(beforeSelector);
                if (beforeElement) {
                    table.insertBefore(emptyRow, beforeElement);
                } else {
                    table.appendChild(emptyRow); // Append to the end if the selector is not found
                }
            }
        };

        if (saleRowCount > purchaseRowCount) {
            insertEmptyRows(purchaseTable, purchaseRowCount, saleRowCount, 'tr:last-child');
        } else if (purchaseRowCount > saleRowCount) {
            insertEmptyRows(saleTable, saleRowCount, purchaseRowCount, 'tr:last-child');
        }
    }
    </script>

@include('include.footer')
