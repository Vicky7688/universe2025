@include('include.header')

<script src="https://www.dukelearntoprogram.com/course1/common/js/image/SimpleImage.js">
</script>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Loan Date</th>
                            <th>Customer</th>
                            <th>Loan.Amount</th>
                            <th>Loan.Name</th>
                            <th>Loan.by</th>
                            <th>Status</th>
                            <th>Installments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($advancements))
                            @foreach ($advancements as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date('d-m-Y',strtotime($row->loanDate)) }}</td>
<td><a href="{{ url('account_opening/' . DB::table('member_accounts')->where('customer_Id','=',$row->accountNo)->value('id')) }}">({{ $row->accountNo }}){{ ucwords($row->name) }}</a></td>
                                    <td>{{ $row->loanAmount }}</td>
                                    <td>{{ ucwords($row->loanname) }}</td>
                                    <td>{{ ucwords($row->agent_name) }}</td>
                                    <td>
                                        @if($row->status=='Disbursed')
                                            <button style="padding: 3px;font-size: 11px;"  type="button" class="btn btn-success btn-bordered waves-effect waves-light">{{ $row->status }}</button>
                                        @else
                                        <button style="padding: 3px;font-size: 11px;"  type="button" class="btn btn-danger btn-bordered waves-effect waves-light">Loan {{ $row->status }}</button>
                                       @endif

                                    </td>
                                    <td><button style="padding: 3px;font-size: 11px;" onclick="getinstallments({{ $row->id }})" type="button" class="btn btn-dark btn-bordered waves-effect waves-light">Installments</button></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Installments</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="scroll-wrap">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Sr.No</th>
                  <th>Emi date</th>
                  <th>Principle</th>
                  <th>Intrest</th>
                  <th>Installment</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="installmentsTableBody">
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          {{--
          <button type="button" class="btn btn-primary">Save changes</button>
          --}} </div>
      </div>
    </div>
  </div>
<script>


function formatDate(dateString) {
    var date = new Date(dateString);
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();
    // Add leading zeros to day and month if needed
    day = day < 10 ? '0' + day : day;
    month = month < 10 ? '0' + month : month;
    return day + '-' + month + '-' + year;
}
function getinstallments(id) {
$.ajax({
    url: "{{url('getemi')}}",
    type: 'POST',
    data: {
        id: id,
        _token: '{{ csrf_token() }}' // Include CSRF token if needed
    },
    success: function(response) {
        // Check if response status is true
        // if (response.status) {
            var installments = response.installments;
            var tableBody = $('#installmentsTableBody');
            tableBody.empty(); // Clear any existing rows
            // Initialize totals for each column
            var totalPrincipal = 0;
            var totalInterest = 0;
            var totalAmount = 0;
var iod=1;
            installments.forEach(function(installment) {
                if (installment.status == 'paid') {
                    var sttatus = 'Paid';
                } else {
                    var sttatus = 'Unpaid';
                }
                var row = '<tr>' +
                    '<td>' + iod++ + '</td>' +
                    '<td>' + formatDate(installment.installmentDate) + '</td>' +
                    '<td>' + installment.principal + '</td>' +
                    '<td>' + installment.interest + '</td>' +
                    '<td>' + installment.totalinstallmentamount + '</td>' +
                    '<td>' + sttatus + '</td>' +
                    '</tr>';
                tableBody.append(row);
                totalPrincipal += parseFloat(installment.principal);
                totalInterest += parseFloat(installment.interest);
                totalAmount += parseFloat(installment.totalinstallmentamount);
            });
            var totalRow = '<tr>' +
                '<td>#</td>' +
                '<td><strong>Total</strong></td>' +
                '<td><strong>' + totalPrincipal.toFixed(2) + '</strong></td>' +
                '<td><strong>' + totalInterest.toFixed(2) + '</strong></td>' +
                '<td><strong>' + totalAmount.toFixed(2) + '</strong></td>' +
                '<td><strong>-</strong></td>' +
                '</tr>';
            tableBody.append(totalRow);
            $('#exampleModal').modal('show');
    },
    error: function(xhr, status, error) {
        console.error('AJAX Error:', status, error);
    }
});
}
</script>


@include('include.footer')
