@include('include.header')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form name="fileform" id="fileform">
                    <div class="row">
                        <input type="hidden" name="updateid" id="updateid">
                        <div class="mb-2 col-md-4">
                            <label class="form-label">Customer Id <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="customer_id" id="customer_id" onblur="checkexistscustomer('this')" required>

                        </div>
                        <div class="mb-2 col-md-4">
                            <label class="form-label">Name <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="mb-2 col-md-4">
                            <label class="form-label">File No</label>
                            <input type="text" class="form-control" name="file_no" id="file_no">
                        </div>
                        <div class="mb-2 col-md-4">
                            <label class="form-label">Received By</label>
                            <input type="text" class="form-control" name="receivedby" id="receivedby">
                        </div>
                        <div class="mb-2 col-md-4">
                            <label class="form-label">Date</label>
                            <input type="text" class="onlydate form-control datepicker hasDatepicker" id="currentDate" name="currentDate">
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
                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>File No</th>
                            <th>Name</th>
                            <th>Received By</th>
                            <th>Date</th>
                            <th>Edit</th>
                            {{-- <th>Delete</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($customerDetails))
                            @foreach ($customerDetails as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->file_no }}</td>
                                    <td>{{ $row->customer_id }} - {{ $row->name ?? 'N/A' }}</td>
                                    <td>{{ $row->received_by }}</td>
                                    <td>{{ $row->files_dates }}</td>
                                    <td><a class="editfile" data-id="{{ $row->id }}">
                                        <img
                                                src="{{ url('public/admin/images/edit.png') }}">
                                    </a></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
        {{--  $('#fileform').validate({
            rules: {
                customer_id : {
                    required: true,
                    number: true
                },
                name: {
                    required: true
                }
            },
            messages: {
                customer_id : {
                    required: 'Enter Customer Id',
                    number: 'Enter Only Numeric Value'
                },
                name: {
                    required: 'Enter Customer Name'
                }
            },
            errorElement: 'p',
            errorPlacement: function (error, element) {
                console.log("Validation error for: ", element.attr('name'));
                error.insertAfter(element);
            }
        });  --}}



        $(document).on('submit', '#fileform', function (event) {
            event.preventDefault();

            let formData = new FormData(this);

            let url = $('#updateid').val() ? "{{ route('fileupdate') }}" : "{{ route('fileinsert') }}";

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (res) {
                    if (res.status === 'success') {
                        alert(res.message);
                        $('#fileform')[0].reset();
                        $('#updateid').val('');
                        window.location.href="{{ route('filecustomerindex') }}";
                    } else {
                        alert(res.message);
                    }
                },
                error: function (xhr, status, error) {
                    alert('An error occurred: ' + error);
                    console.error(xhr.responseText);
                }
            });
        });




        $(document).on('click', '.editfile', function (event) {
            event.preventDefault();
            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('editfiles') }}",
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (res) {
                    if (res.status === 'success') {
                        let customerDetails = res.customerDetails;

                        if (customerDetails) {
                            // Populate form fields with retrieved data
                            $('#updateid').val(customerDetails.id ?? '');
                            $('#customer_id').val(customerDetails.customer_id ?? '').prop('readonly', true);
                            $('#name').val(customerDetails.name ?? '').prop('readonly', true);
                            $('#file_no').val(customerDetails.file_no ?? '');
                            $('#receivedby').val(customerDetails.received_by ?? '');
                            $('#currentDate').val(customerDetails.files_dates ?? '');
                        } else {
                            // Handle case where no data is found
                            alert(res.messages || 'No customer details found.');
                        }

                    } else {
                        alert(res.messages);
                    }
                },
                error: function (xhr, status, error) {
                    alert('An error occurred while fetching file details: ' + error);
                    console.error(xhr.responseText);
                }
            });
        });
    });

    function checkexistscustomer(){
        let customerId = $('#customer_id').val();

        $.ajax({
            url: "{{ route('checkalreadymember') }}",
            type: 'POST',
            data: { customerId : customerId},
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (res) {
                if (res.status === 'success') {
                    $('#customer_id').val('');
                    alert(res.messages);
                }
            }
        });

    }
</script>
@include('include.footer')

