@include('include.header') 
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <form action="{{ $formurl }}" method="POST">
                @csrf
              <div class="row">
                <div class="mb-2 col-md-4">
                  <label  class="form-label">Name</label>
                  <input type="text" class="form-control" name="name" required @if(!empty($employeid->name)) value="{{ $employeid->name }}"  @endif >
                </div>
                <div class="mb-2 col-md-4">
                  <label  class="form-label">Phone</label>
                  <input type="text" class="form-control" name="phone" required  @if(!empty($employeid->phone)) value="{{ $employeid->phone }}"  @endif >
                </div>
                <div class="mb-2 col-md-4">
                  <label  class="form-label">Username</label>
                  <input type="text" class="form-control" name="ubuser"  id="ubuser" oninput="checkusername(this.value)" required  autocomplete="off"   @if(!empty($employeid->username)) value="{{ $employeid->username }}"  @endif >
                  <small id="usernameerror" style="color: #ee0f0f;" > </small>
                </div>
                <div class="mb-2 col-md-4">
                  <label  class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" oninput="checkemail(this.value)" required   autocomplete="off"  @if(!empty($employeid->email)) value="{{ $employeid->email }}"  @endif >
                  <small id="emailerror" style="color: #ee0f0f;"> </small>
                </div>
                <div class="mb-2 col-md-4">
                  <label  class="form-label">Password (Fill If Want to Change)</label>
                  <input type="password" class="form-control" name="password"  id="passwordd" required @if(!empty($employeid->password)) value=""  @endif >
                </div>
                <div class="mb-2 col-md-4">
                  <label  class="form-label">Status</label>
                  <select  name="status" required class="form-select" required>
                  <option value="">Select Status</option>
                  <option  @if(!empty($employeid->status)) @if($employeid->status=='active') @selected(true) @endif   @else @selected(true) @endif value="active">Active</option>
                  <option  @if(!empty($employeid->status)) @if($employeid->status=='inactive') @selected(true) @endif  @endif value="inactive">Inactive</option>
                  </select>
                </div>
              </div>
              <div class="row mt-2">
                <div class="mb-2 col-md-4">
                  <button type="submit" class="btn btn-primary"  id="submit" >Submit</button>
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
                  <th>Name</th>
                  <th>email</th>
                  <th>username</th>
                  <th>phone</th>
                  <th>Status</th>
                  <th>Edit</th>
                  {{-- <th>Delete</th> --}}
                </tr>
              </thead>
              <tbody>
              @if(!empty($employe_master))
              @foreach ($employe_master as $employelist)
              <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ $employelist->name}}</td>
                <td>{{ $employelist->email}}</td>
                <td>{{ $employelist->username}}</td>
                <td>{{ $employelist->phone}}</td>
                <td>{{ ucfirst($employelist->status) }}</td>
                <td><a href="{{ url('editemploye/'.$employelist->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a></td>
                {{-- <td><a onclick="return confirm('Are you Sure?')" href="{{ url('deleteemploye/'.$employelist->id) }}" ><img src="{{ url('public/employe/images/delete.png') }}" ></a></td> --}}
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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function checkusername(usernamee) {
    var thisusername="";
    @if(!empty($employeid->username))
var thisusername="{{ $employeid->username }}";
    @endif

    $.ajax({
        url: "{{ route('echeckusername') }}",
        type: 'POST',
        data: {
            thisusername: thisusername,
            username: usernamee,
            _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
        success: function(data) {
            if (data.status === true) {
                $('#submit').prop('disabled', true); // Corrected 'disable' to 'disabled'
                $('#usernameerror').text(data.message);
            }else{
                $('#submit').prop('disabled', false); // Corrected 'disable' to 'disabled'
                $('#usernameerror').text('');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
function checkemail(email) {

    var thisemail="";
    @if(!empty($employeid->email))
var thisemail="{{ $employeid->email }}";
    @endif


    $.ajax({
        url: "{{ route('echeckemail') }}",
        type: 'POST',
        data: {
            thisemail: thisemail,
            email: email,
            _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
        success: function(data) {
            if (data.status === true) {
                $('#submit').prop('disabled', true); // Corrected 'disable' to 'disabled'
                 $('#emailerror').text(data.message);
            }else{
                $('#submit').prop('disabled', false); // Corrected 'disable' to 'disabled'
                $('#emailerror').text('');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}


    window.addEventListener('load', function() {
        setTimeout(function() {
        $('#passwordd').val('');
    }, 1000); // 1000 milliseconds = 1 second
})
</script>
@include('include.footer')
