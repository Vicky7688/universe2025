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
                <div class="mb-2 col-md-3">
                    <label>Date From</label>
                    <input type="text" name="datefrom" class="onlydate form-control datepicker"  id="datefrom" value="{{ Session::get('setcurrentdate') }}" >

                </div>
                <div class="mb-2 col-md-3">
                    <label>Date To</label>
                  <input type="text" name="dateto"  class="onlydate form-control datepicker"   id="dateto" value="{{ Session::get('setcurrentdate') }}">
                </div>

                <div class="mb-2 col-md-3">
                    <label  class="form-label">Group</label>
                      <select class="sup-dropdown" name="group" id="group-d"  placeholder="Select group">
                        <option value="">Select Brand</option>
                          @if(sizeof($groups)>0)
                          @foreach ($groups as $groupslist)
                        <option   value="{{ $groupslist->groupCode }}">{{ $groupslist->name }}</option>
                          @endforeach
                          @endif
                      </select>
                  </div>

                  <div class="mb-2 col-md-3">
                      <label  class="form-label">Ledger</label>
                        <select class="sup-dropdown" name="ledger" id="lgroup-d"  placeholder="Select Ledger">
                          <option value="">Select Ledger</option>
                            @if(sizeof($ledger)>0)
                            @foreach ($ledger as $ledgersli)
                          <option  @if(!empty($categorysid->brand)) @if($categorysid->brand==$ledgersli->id) @selected(true) @endif  @endif value="{{ $ledgersli->ledger_code }}">{{ $ledgersli->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
              </div>
              <div class="row mt-2">
                <div class="mb-2 col-md-4">
                  <button type="submit" class="btn btn-primary">Submit</button>
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
            <table  class="table table-striped dt-responsive nowrap w-100">
              <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Date</th>
                    <th>Head</th>
                    <th>Particular</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Balance</th>
                </tr>
              </thead>
              <tbody id="tbody">

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




    $('#group-d').on('change', function() {
        var id = $(this).val();
        $.ajax({
            url: "{{ route('groupledgers') }}",
            type: "POST",
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(result) {

                var $categoryDropdown = $('#lgroup-d');
                if ($categoryDropdown.hasClass('selectized')) {
                    $categoryDropdown[0].selectize.destroy();
                }
                $categoryDropdown.empty();
                $categoryDropdown.append('<option value="">Select Lesgers</option>');
                $.each(result.substates, function(key, value) {

                    $categoryDropdown.append('<option value="' + value.ledgerCode + '">' + value.name + '</option>');
                });
                $categoryDropdown.selectize({
                    create: false, // Disable option to create new items
                    sortField: 'text' // Sort options alphabetically
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });








      /* View Details*/
  $(document).on('submit', '#groupLegderForm', function(e) {
      e.preventDefault();


      let formData = $(this).serializeArray();

      $.ajax({
          url: '{{ route("ledgerwiseDetails") }}',
          type: 'POST',
          data: formData,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          dataType: 'json',
          success: function(res) {
              if (res.status == 'success') {
                  $('#buttonContainer').empty();
                  let datarow = res.data;
                  let tbody = $('#tbody');
                  tbody.empty();

                  let grandTotalDebit = 0;
                  let grandTotalCredit = 0;
                  let openingBal = 0;
                  let totalDebit = parseFloat(datarow.dr_amount);
                  let totalCredit = parseFloat(datarow.cr_amount);

                  if (!Number.isNaN(totalDebit) && !Number.isNaN(totalCredit)) {
                      openingBal = totalDebit - totalCredit;
                  }
                  let openingBalRow = '<tr class="raddd">' +
                      '<td colspan="6" class="text-center"><strong>Opening Balance</strong></td>' +
                      '<td class="text-center">' + res.openingBalance.toFixed(2) + '</td>' +
                      '</tr>';
                  tbody.append(openingBalRow);

                  let totals = parseFloat(res.openingBalance);
                  let nature = '';

                  // Prepare data for Excel
                  let excelDatap = [];
                  excelDatap.push(["#", "Date", "Head", "Particular", "Debit", "Credit", "Balance"]);

                  $.each(datarow, function(index, data) {
                      var originalDate = new Date(data.transactionDate);
                      var day = originalDate.getDate();
                      var month = originalDate.getMonth() + 1;
                      var year = originalDate.getFullYear();
                      day = day < 10 ? '0' + day : day;
                      month = month < 10 ? '0' + month : month;
                      var formatgrdate = day + '-' + month + '-' + year;

                    //   let debitAmount = parseFloat(data.dr_amount);
                    //   let creditAmount = parseFloat(data.transactionAmount);
                      console.log('phle' + totals);
                      if (data.transactionType === 'Dr') {
                        var debitAmount = parseFloat(data.transactionAmount);
                        var creditAmount = 0;
                          console.log('drme' + totals);
                          totals = parseFloat(totals) + parseFloat(debitAmount);
                          nature = 'Dr';
                          totals = totals;

                          console.log('drmebaad' + totals);
                      } else {
                        var creditAmount = parseFloat(data.transactionAmount);
                        var debitAmount =0;
                          console.log('crme' + totals);
                          totals = parseFloat(totals) - parseFloat(creditAmount);
                          nature = 'Cr';
                          totals = totals;
                          console.log('crmebaad' + totals);
                      }
                      console.log('baadme' + totals);
                      let row = '<tr>' +
                          '<td class="text-center">' + (index + 1) + '</td>' +
                          '<td class="text-center">' + formatgrdate + '</td>' +
                          '<td class="text-center">' + data.formName + '</td>' +
                          '<td class="text-center">' +  data.narration + '</td>' +
                          '<td class="text-center">' + debitAmount.toFixed(2) + '</td>' +
                          '<td class="text-center">' + creditAmount.toFixed(2) + '</td>' +
                          '<td class="text-center">' + Math.abs(totals).toFixed(2) + ' ' + nature + '</td>' +
                          '</tr>';

                      tbody.append(row);

                      grandTotalDebit += parseFloat(debitAmount);
                      grandTotalCredit += parseFloat(creditAmount);

                      excelDatap.push([index + 1, formatgrdate, data.account_name, data.type + ' ' + data.narration, debitAmount.toFixed(2), creditAmount.toFixed(2), totals.toFixed(2) + ' ' + nature]);
                  });



                //   $.each(datarow, function(index, data) {
                //       grandTotalDebit += parseFloat(data.dr_amount);
                //       grandTotalCredit += parseFloat(data.cr_amount);
                //   });

                  let grandTotalRow = '<tr  class="raddddd">' +
                      '<td colspan="4" class="text-center"><strong>Grand Total</strong></td>' +
                      '<td class="text-center">' + grandTotalDebit.toFixed(2) + '</td>' +
                      '<td class="text-center">' + grandTotalCredit.toFixed(2) + '</td>' +
                      '<td class="text-center">' + Math.abs(totals).toFixed(2) + ' ' + nature + '</td>' +
                      '</tr>';
                  tbody.append(grandTotalRow);

                  excelDatap.push(['', '', '', 'Grand Total', grandTotalDebit.toFixed(2), grandTotalCredit.toFixed(2), totals.toFixed(2) + ' ' + nature]);


                  // Create the button and append to container
                  const buttonContainer = document.getElementById('buttonContainer');
                  const downloadButton = document.createElement('button');
                  downloadButton.textContent = 'Download Excel';
                  downloadButton.addEventListener('click', function() {
                      downloadExcel(excelDatap);
                  });
                  buttonContainer.appendChild(downloadButton);


                  const pdfButton = document.createElement('button');
                  pdfButton.textContent = 'Download PDF';
                  pdfButton.addEventListener('click', function() {
                      downloadPDF(excelDatap);
                  });
                  buttonContainer.appendChild(pdfButton);


              }



          }

      });
  });


    });
</script>
@include('include.footer')
