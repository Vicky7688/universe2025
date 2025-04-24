@include('include.header')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
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

    .tutu td {
        color: #000;
    }
</style>
<section class="content-header">
    <div class="container-fluid my-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="breadcrumbTitle"><i class="ri-folder-chart-line"></i>
                        Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page">Committee Report</li>
            </ol>
        </nav>
    </div>
</section>
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="javascript:void(0)" id="committeereportform" name="committeereportform">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div>
                                <label for="name" class="label-text">From Date</label>
                                <input type="text" name="startdate" id="startdate" class="form-control datepicker"
                                    value="{{ date('d-m-Y', strtotime(Session::get('setcurrentdate'))) }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div>
                                <label for="name" class="label-text">As on Date</label>
                                <input type="text" name="endDate" id="endDate" class="form-control datepicker"
                                    value="{{ date('d-m-Y', strtotime(Session::get('setcurrentdate'))) }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div>
                                <label for="" class="label-text">Committee Wise</label>
                                <select name="committeetype" id="committeetype" class="form-control" >
                                    <option value="All" selected>All</option>
                                    @if(!empty($allCommittees))
                                        @foreach ($allCommittees as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div>
                                <label for="" class="label-text">Status</label>
                                <select name="installment_status" id="installment_status" class="form-control">
                                    <option value="Unpaid" selected>Unpaid</option>
                                    <option value="Paid">Paid</option>
                                </select>
                            </div>
                        </div>


                        {{-- <div class="col-md-2">
                            <div>
                                <label for="name" class="label-text">No of Installment</label>
                                <input type="text" name="months" id="months" class="form-control">
                            </div>
                        </div> --}}
                        <div class="col-md-1 d-flex align-items-end">
                            <div class="button2">
                                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-eye"></i>
                                    View</button>
                                {{-- <button id="downloadExcel" type="submit" class="btn btn-primary w-100"><i class="bi bi-eye"></i> Download Excel</button> --}}
                                {{-- <button id="downloadExcel" style="display:none;">Download Excel</button> --}}

                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="button2">
                                <button id="downloadExcel" style="display:none;" type="submit"
                                    class="btn btn-primary w-100"><i class="bi bi-eye"></i> Download Excel</button>
                                {{-- <button id="downloadExcel" style="display:none;">Download Excel</button> --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="tableSection row mb-4">
            <div class="col-lg-12 col-md-12 mb-4">
                <div class="card">
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive">
                            <!-- <div id="loader" style="display: none;">
                                    <div class="dot-loader"></div>
                                    <div class="dot-loader dot-loader--2"></div>
                                    <div class="dot-loader dot-loader--3"></div>
                                </div> -->
                            <table class="table align-items-center text-center table-bordered mb-0 data-table">
                                <thead class="tableHeading">
                                    <tr>
                                        <th>#</th>
                                        <th>Account No</th>
                                        <th>Customer name</th>
                                        <th>Committee Name</th>
                                        <th>Committee Amount</th>
                                        <th>No. of Pending Committee</th>
                                        <th>Committee Status</th>
                                    </tr>
                                </thead>
                                <tbody class="tableBody" id="tableBody">
                                </tbody>
                                <tbody id="grandtotal">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#endDate').datepicker({
            format: 'dd-mm-yyyy',
            endDate: new Date()
        });

        $(document).on('submit', '#committeereportform', function(event) {
            event.preventDefault();

            let startDate = $('#startdate').val();
            let endDate = $('#endDate').val();
            let status = $('#installment_status').val();
            let committeetype = $('#committeetype').val();

            $.ajax({
                url: "{{ route('getcommittes') }}",
                type: 'POST',
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    status: status,
                    committeetype: committeetype
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(res) {
                    if (res.status === 'success') {
                        let alldata = res.alldata;
                        let tableBody = $('#tableBody').empty();
                        let grandTotal = 0;

                        if (Array.isArray(alldata) && alldata.length > 0) {
                            alldata.forEach((data, index) => {
                                let row = `<tr>
                                    <td>${index + 1}</td>
                                    <td>${data.customer_Id}</td>
                                    <td>${data.name}</td>
                                    <td>${data.cmname || 'N/A'}</td>
                                    <td>${parseFloat(data.total_paid_amount)}</td>
                                    <td>${data.total_installments}</td>
                                    <td>${data.payment_status}</td>
                                </tr>`;
                                tableBody.append(row);
                                grandTotal += parseFloat(data.total_paid_amount);
                            });

                            // Format grand total as currency
                            $('#grandtotal').html(`<tr><strong>
                                <td colspan="4">Grand Total</td>
                                <td>${grandTotal}</td>
                                <td></td>
                                <td></td></strong>
                            </tr>`);
                        } else {
                            // If no data returned
                            $('#tableBody').append('<tr><td colspan="7" class="text-center">No records found</td></tr>');
                        }
                    } else {
                        // Handle failure response
                        $('#tableBody').append('<tr><td colspan="7" class="text-center">An error occurred while fetching data</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + error);
                    console.error(xhr.responseText);
                }
            });
        });


    });
</script>
@include('include.footer')
