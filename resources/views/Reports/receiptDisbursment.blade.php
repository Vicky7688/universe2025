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
                            <input type="text" name="dateto" class="onlydate form-control datepicker" value="{{ Session::get('setcurrentdate') }}">
                        </div>
                        <div class="mb-2 col-md-2">
                            <label class="form-label">Group</label>
                            <select name="groupledger" class="form-select" id="group-d">
                                <option value="group">Group</option>
                                <option value="ledger">Ledger</option>
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
                <table class="table table-striped dt-responsive nowrap w-100" id="smallfont">
                    <thead>
                        <tr>
                            <th>SR.NO</th>
                            <th>GROUP/LEDGER NAME</th>
                            <th>DEBIT AMOUNT</th>
                            <th>CREDIT AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody class="tbody" id="tbody">
                    </tbody>
                    <tbody>
                        <tr class="raddddd">
                            <td></td>
                            <td><b>Opening Cash</b></td>
                            <td></td>
                            <td id="openingCash"></td>
                        </tr>
                        <tr class="raddddd">
                            <td></td>
                            <td><b>Closing Cash</b></td>
                            <td id="closingCash"></td>
                            <td></td>
                        </tr>
                        <tr class="raddd">
                            <td></td>
                            <td><b>Grand Total</b></td>
                            <td id="drGrandTotal"></td>
                            <td id="crGrandTotal"></td>
                        </tr>
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
            url: '{{ route("receiptDisbursmentgetData") }}',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(res) {
                if (res.status == 'success') {
                    let datarow = res.groups;
                    let tbody = $('#tbody');
                    tbody.empty();

                    let drTotal = 0;
                    let crTotal = 0;
                    $.each(datarow, function(index, data) {
                        let row = '<tr style="padding:10px;">' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + data.name + '</td>' +
                            '<td>' + data.total_debit + '</td>' +
                            '<td>' + data.total_credit + '</td>' +
                            '</tr>';
                        tbody.append(row);
                        drTotal += parseFloat(data.total_debit);
                        crTotal += parseFloat(data.total_credit);
                    });
                    drTotal += parseFloat(res.closingCash);
                    let netDifference = drTotal - crTotal;

                    let openingCash = parseFloat(res.openingCash);
                    let closingCash = parseFloat(res.closingCash);

                    $('#closingCash').html(closingCash.toFixed(2));
                    $('#openingCash').html(openingCash.toFixed(2));

                    let grandTotal = drTotal;
                    if (netDifference > 0) {
                        crTotal += netDifference;
                    } else if (netDifference < 0) {
                        drTotal -= netDifference;
                    }

                    $('#drTotal').html(drTotal.toFixed(2));
                    $('#crTotal').html(crTotal.toFixed(2));

                    $('#drGrandTotal').html(grandTotal.toFixed(2));
                    $('#crGrandTotal').html(grandTotal.toFixed(2));
                } else {
                    if (res.status == 'sucessss') {
                        let datarow = res.ledgers;
                        let tbody = $('#tbody');
                        tbody.empty();

                        let drTotal = 0;
                        let crTotal = 0;
                        $.each(datarow, function(index, data) {
                            let row = '<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + data.name + '</td>' +
                                '<td>' + data.total_debit + '</td>' +
                                '<td>' + data.total_credit + '</td>' +
                                '</tr>';
                            tbody.append(row);
                            drTotal += parseFloat(data.total_debit);
                            crTotal += parseFloat(data.total_credit);
                        });

                        drTotal += parseFloat(res.closingCash);
                        let netDifference = drTotal - crTotal;

                        let openingCash = parseFloat(res.openingCash);
                        let closingCash = parseFloat(res.closingCash);

                        $('#closingCash').html(closingCash.toFixed(2));
                        $('#openingCash').html(openingCash.toFixed(2));

                        let grandTotal = drTotal;
                        if (netDifference > 0) {
                            crTotal += netDifference;
                        } else if (netDifference < 0) {
                            drTotal -= netDifference;
                        }

                        $('#drTotal').html(drTotal.toFixed(2));
                        $('#crTotal').html(crTotal.toFixed(2));

                        $('#drGrandTotal').html(grandTotal.toFixed(2));
                        $('#crGrandTotal').html(grandTotal.toFixed(2));


                    }
                }
            }
        });
    });
});
</script>
@include('include.footer')
