@include('include.header')
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <form action="{{ $formurl }}" method="POST">
                @csrf
              <div class="row">


                <div class="mb-2 col-md-3">
                  <label  class="form-label">Date From</label>
                  <input type="text" class="form-control date1" name="startdate" id="startdate" @if(!empty($sessionid->startdate)) value="{{ date('d-m-Y', strtotime($sessionid->startdate)) }}" @endif>
                  <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('name') {{$message}} @enderror </small>
                </div>


                <div class="mb-2 col-md-3">
                  <label class="form-label">Date To</label>
                  <input type="text"   class="form-control" readonly name="enddate" id="enddate" @if(!empty($sessionid->enddate)) value="{{ date('d-m-Y', strtotime($sessionid->enddate)) }}" @endif>
                  <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('name') {{$message}} @enderror </small>
                </div>

                <div class="mb-2 col-md-3">
                    <label class="form-label">Session Name</label>
                    <input type="text" name="name" id="session_name" class="form-control"  required @if(!empty($sessionid->name)) value="{{ $sessionid->name }}" @endif>
                    <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"  id="errordiv"> @error('name') {{$message}} @enderror </small>
                  </div>

                <div class="mb-2 col-md-3">
                    <label for="inputAddressname" class="form-label">Audit Perform</label>
                    <select  name="auditperform" required class="form-select" required>
                    {{--  <option value="">Select Status</option>  --}}
                    <option  @if(!empty($sessionid->status)) @if($sessionid->auditPerformed=='No') @selected(true) @endif  @endif value="No">No</option>
                    <option  @if(!empty($sessionid->status)) @if($sessionid->auditPerformed=='Yes') @selected(true) @endif  @endif value="Yes">Yes</option>
                    </select>
                  </div>

                <div class="mb-2 col-md-3">
                    <label for="inputAddressname" class="form-label">Status</label>
                    <select  name="status" required class="form-select" required>
                    <option value="">Select Status</option>
                    <option  @if(!empty($sessionid->status)) @if($sessionid->status=='active') @selected(true) @endif   @else @selected(true) @endif value="active">Active</option>
                    <option  @if(!empty($sessionid->status)) @if($sessionid->status=='inactive') @selected(true) @endif  @endif value="inactive">Inactive</option>
                    </select>
                  </div>

              </div>
              <div class="row mt-2">
                <div class="mb-2 col-md-3">
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
                  <th>Session</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Status</th>
                  <th>Edit</th>
                  {{-- <th>Delete</th> --}}
                </tr>
              </thead>
              <tbody>
              @if(!empty($session))
              @foreach ($session as $sessionlist)
              <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ $sessionlist->name}}</td>
                <td>  {{ date('d-m-Y', strtotime($sessionlist->startdate)) }}</td>
                <td>  {{ date('d-m-Y', strtotime($sessionlist->enddate)) }}</td>
                <td>{{ ucfirst($sessionlist->status) }}</td>
                <td><a href="{{ url('editsession/'.$sessionlist->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a></td>
                {{-- <td><a onclick="return confirm('Are you Sure?')" href="{{ url('deletesession/'.$sessionlist->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a></td> --}}
              </tr>
              @endforeach
              @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
        $(document).ready(function() {
            let currentYear = new Date().getFullYear();
            let startDate = '01-04-' + currentYear;
            let endDate = '31-03-' + (currentYear + 1);

            // Function to update endDate based on startDate year
            function updateEndDate(startDate) {
                let startYear = moment(startDate, 'DD-MM-YYYY').year();
                return '31-03-' + (startYear + 1);
            }

            // Initialize the datepicker
            $('.date1').datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: (currentYear - 10) + ':' + (currentYear + 10),
                minDate: $.datepicker.parseDate('dd-mm-yy', startDate),
                maxDate: $.datepicker.parseDate('dd-mm-yy', endDate),
                showOn: 'focus', // Set to focus to disable the calendar popup
                showOtherMonths: false,
                selectOtherMonths: false,
                beforeShow: function(input, inst) {
                    $(inst.dpDiv).hide(); // Hide the calendar popup
                }
            });

            // Set initial values
            if ($('#startdate').length) {
                $('#startdate').val(startDate);
            }
            if ($('#enddate').length) {
                $('#enddate').val(endDate);
            }

            // Update endDate when startDate is edited
            $(document).on('change', '#startdate', function() {
                let newStartDate = $(this).val();
                let newEndDate = updateEndDate(newStartDate);

                if ($('#enddate').length) {
                    $('#enddate').val(newEndDate);
                }
            });

            $(document).ready(function () {
                const dateInputs = document.querySelectorAll('.date1');
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
            });


            //____________Check Exits Session Name
            $(document).on('input', '#startdate', function(e) {
                e.preventDefault();
                let startDate = $('#startdate').val();
                let endDate = $('#enddate').val();
                let session_name = $('#session_name').val();

                $.ajax({
                    url: "{{ route('sessioname') }}",
                    type: 'post',
                    data: {
                        session_name: session_name,
                        startDate: startDate,
                        endDate: endDate,
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(res) {
                        $('#errordiv').empty(); // Clear previous error messages
                        if (res.status === 'Fail') {
                            $('#errordiv').append(toastr.error(res.messages));
                        } else {
                            $('#session_name').val(res.financialYear);
                        }
                    }
                });
            });


            //____________Check Exits Session Name
            $(document).on('input', '#session_name', function(e) {
                e.preventDefault();

                let session_name = $('#session_name').val();

                $.ajax({
                    url: "{{ route('checkexitsessionname') }}",
                    type: 'post',
                    data: {
                        session_name: session_name,
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(res) {
                        $('#errordiv').empty(); // Clear previous error messages
                        if (res.status === 'Fail') {
                            $('#errordiv').append(toastr.error(res.messages));
                        } else {
                            $('#session_name').val(res.financialYear);
                        }
                    }
                });
            });



        });
    </script>






@include('include.footer')


