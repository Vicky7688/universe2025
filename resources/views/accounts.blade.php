@include('include.header')

<script src="https://www.dukelearntoprogram.com/course1/common/js/image/SimpleImage.js"></script>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable-buttonsopening" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Ac Opening Date</th>
                            <th>CustomerId</th>
                            <th>Name</th>
                            <th>Father Name</th>
                            <th>First Mob. No</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{--  @if (!empty($accountDetails))
                            @foreach ($accountDetails as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date('d-m-Y',strtotime($row->openingDate)) }}</td>
                                    <td><a href="{{ url('account_opening/' . $row->id) }}">{{ $row->customer_Id }}</a></td>
                                    <td>{{ ucwords($row->name) }}</td>
                                    <td>{{ ucwords($row->father_husband) }}</td>
                                    <td>{{ $row->mobile_first }}</td>  --}}
                        {{-- <td>{{ $row->mobile_second }}</td> --}}
                        {{--  <td>{{ ucwords($row->status) }}</td>
                                    <td><a href="{{ url('account_opening/' . $row->id) }}"><img
                                                src="{{ url('public/admin/images/edit.png') }}"></a></td>
                                    <td><a onclick="return confirm('Are you Sure?')"
                                            href="{{ url('deletebrand/' . $row->id) }}"><img
                                                src="{{ url('public/admin/images/delete.png') }}"></a></td>
                                </tr>
                            @endforeach
                        @endif  --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function upload(name, namee) {

        $('#' + name + 1).css('display', 'none')
        $('#' + name).css('display', 'block')
        var imgcanvas = document.getElementById(name);
        var fileinput = document.getElementById(namee);
        var image = new SimpleImage(fileinput);

        image.drawTo(imgcanvas);
    }
</script>
<script>
    $(document).ready(function() {
        // Check if DataTable is already initialized on the table
        var table = $('#datatable-buttonsopening');

        if ($.fn.DataTable.isDataTable(table)) {
            // If the DataTable exists, clear and destroy it
            table.DataTable().clear().destroy();
        }

        // Initialize the DataTable again
        table.DataTable({
            serverSide: true,
            ajax: {
                url: '{{ route('account.data') }}',
                type: 'GET',
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'openingDate',
                    name: 'openingDate'
                },
                {
                    data: 'customer_Id',
                    name: 'customer_Id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'father_husband',
                    name: 'father_husband'
                },
                {
                    data: 'mobile_first',
                    name: 'mobile_first'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'edit',
                    name: 'edit',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'delete',
                    name: 'delete',
                    orderable: false,
                    searchable: false
                },
            ],
            destroy: true, // Ensures DataTable can be reinitialized
        });
    });
</script>
{{--
<script>
 // Customer Image
    document.getElementById('customer_image').onclick = function() {
        document.getElementById('customerInput').click();
    };

    function customerInputImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('customer_image').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Customer ID Proof Image
    document.getElementById('idProofImage').onclick = function() {
        document.getElementById('idProofImageInput').click();
    };

    function handleidProofImageInput(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('idProofImage').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // First Guarantor Image
    document.getElementById('firstguarantorImage').onclick = function() {
        document.getElementById('firstguarantorImageInput').click();
    };

    function handleFirstGuarantorImageInput(input) { // Changed function name
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('firstguarantorImage').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }



    // Second Guarantor Image
    document.getElementById('secondguarantorImage').onclick = function() {
        document.getElementById('secondguarantorImageInput').click();
    };

    function handleSecondGuarantorImageInput(input) { // Renamed function
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('secondguarantorImage').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }






    $(document).ready(function(){

        //+++++++ check Exiting Customer Id
        $(document).on('input','#customer_Id',function(e){
            e.preventDefault();
            let customer_Id = $(this).val();

            $.ajax({
                url : "{{ route('checkCustomerId') }}",
                type : 'post',
                data : {customer_Id : customer_Id},
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                dataType : 'json',
                success : function(res){
                  if(res.status === 'fail'){
                    let erros = $('#checkcustomer_Id');
                    erros.append(toastr.success(res.messages));
                  }
                }
            });
        });

        $('#account_opening').valdate({
            rules :{
                adhaar_no : {
                    maxlength : 12,
                    digits : true
                },
                email : {
                    email : true
                },
                mobile_first :{
                    minlength : 10,
                    maxlength : 10,
                    digits : true
                },
                mobile_second : {
                    minlength : 10,
                    maxlength : 10,
                    digits : true
                }
                relative_mobile_no : {
                    minlength : 10,
                    maxlength : 10,
                    digits : true
                },
                first_guarantor_mobile{
                    minlength : 10,
                    maxlength : 10,
                    digits : true
                },
                second_guarantor_mobile :{
                    minlength : 10,
                    maxlength : 10,
                    digits : true
                },
                loan_limit : {
                    digits : true
                }
            }

        });



    });
</script> --}}
@include('include.footer')
