@include('include.header')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="">
                @csrf
                <div class="row">
                    <div class="mb-2 col-md-3">
                        <label>Comettee Name</label>
                        <input type="text" name="name" class="form-control" id="name" >
                    </div>

                    <div class="mb-2 col-md-3">
                        <label>Duration Type</label>
                        <select name="durationtype" id="durationtype" class="form-control">
                            <option value="">Select</option>
                            {{-- <option value="weekly">Weekly</option> --}}
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                    <div class="mb-2 col-md-3">
                        <label>Duration</label>
                        <input type="text" name="duration" class="form-control" id="datefrom" >
                    </div>

                    <div class="mb-2 col-md-3">
                        <label>Comettee Starts From</label>
                        <input type="text" name="datefrom" class="onlydate form-control datepicker"
                        value="{{ Session::get('setcurrentdate') }}">

                    </div>
                    <div class="mb-2 col-md-3">
                        <label>Total Amount</label>
                        <input type="text" name="totalamount" class="form-control" id="totalamount">
                    </div>
                    <div class="mb-2 col-md-3">
                        <label>Comettee Amount</label>
                        <input type="text" name="commetiamount" class="form-control" id="commetiamount" >
                    </div>

                    <div class="mt-2 mb-2 col-md-12 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary "  > Submit</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route("commeteesubmit") }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    alert('Form submitted successfully!');
                    window.location.href = '{{ url("commetee") }}';
                },
                error: function(xhr, status, error) {
                    var errors = xhr.responseJSON.errors;
                    $('.error').remove();
                    $.each(errors, function(key, value) {
                        var errorMessage = value[0];
                        var inputField = $('[name="' + key + '"]');
                        inputField.after('<div class="error" style="color: red; font-size: 12px;">' + errorMessage + '</div>');
                    });
                }
            });
        });
    });
</script>










<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
            <thead>
              <tr>
                <th>Sr.no</th>
                <th>Name</th>
                <th>Duration</th>
                <th>Commetee From</th>
                <th>Total Amount</th>
                <th>Commetee amount</th>
                <th>Add Member</th>
                {{-- <th>Edit</th> --}}
                <th>Delete</th>
              </tr>
            </thead>
            <tbody>
            @if(!empty($commeteelist))
            @foreach ($commeteelist as $rows)
            <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $rows->name }}</td>
            <td>{{ $rows->duration }} {{ ucfirst($rows->durationtype) }}</td>
            <td>{{ $rows->datefrom }}</td>
            <td>{{ $rows->totalamount }}</td>
            <td>{{ $rows->commetiamount }}</td>
            <td><a href="{{ url('addmemcometee/'.$rows->id) }}"><button class="btn btn-dark " style="color: white;">Add Members</button></a></td>
            {{-- <td><a href="{{ url('editcometee/'.$rows->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a></td> --}}
            <td><a onclick="return confirm('Are you Sure?')" href="{{ url('deleteeditcometee/'.$rows->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a></td>
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




@include('include.footer')
