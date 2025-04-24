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
                  <input type="text" class="form-control" name="name" required @if(!empty($adminid->name)) value="{{ $adminid->name }}"  @endif >
                </div>
                <div class="mb-2 col-md-4">
                  <label  class="form-label">Phone</label>
                  <input type="text" class="form-control" name="phone" required  @if(!empty($adminid->phone)) value="{{ $adminid->phone }}"  @endif >
                </div>
                <div class="mb-2 col-md-4">
                  <label  class="form-label">Username</label>
                  <input type="text" class="form-control" name="ubuser"  id="ubuser" oninput="checkusername(this.value)" required  autocomplete="off"   @if(!empty($adminid->username)) value="{{ $adminid->username }}"  @endif >
                  <small id="usernameerror" style="color: #ee0f0f;" > </small>
                </div>
                <div class="mb-2 col-md-4">
                  <label  class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" oninput="checkemail(this.value)" required   autocomplete="off"  @if(!empty($adminid->email)) value="{{ $adminid->email }}"  @endif >
                  <small id="emailerror" style="color: #ee0f0f;"> </small>
                </div>
                <div class="mb-2 col-md-4">
                  <label  class="form-label">Password (Fill If Want to Change)</label>
                  <input type="password" class="form-control" name="password"  id="passwordd" required @if(!empty($adminid->password)) value=""  @endif >
                </div>
                <div class="mb-2 col-md-4">
                  <label  class="form-label">Status</label>
                  <select  name="status" required class="form-select" required>
                  <option value="">Select Status</option>
                  <option  @if(!empty($adminid->status)) @if($adminid->status=='active') @selected(true) @endif   @else @selected(true) @endif value="active">Active</option>
                  <option  @if(!empty($adminid->status)) @if($adminid->status=='inactive') @selected(true) @endif  @endif value="inactive">Inactive</option>
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
              @if(!empty($admin_master))
              @foreach ($admin_master as $adminlist)
              <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ $adminlist->name}}</td>
                <td>{{ $adminlist->email}}</td>
                <td>{{ $adminlist->username}}</td>
                <td>{{ $adminlist->phone}}</td>
                <td>{{ ucfirst($adminlist->status) }}</td>
                <td><a href="{{ url('editadmin/'.$adminlist->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a></td>
                {{-- <td><a onclick="return confirm('Are you Sure?')" href="{{ url('deleteadmin/'.$adminlist->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a></td> --}}
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
    @if(!empty($adminid->username))
var thisusername="{{ $adminid->username }}";
    @endif

    $.ajax({
        url: "{{ route('acheckusername') }}",
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
    @if(!empty($adminid->email))
var thisemail="{{ $adminid->email }}";
    @endif


    $.ajax({
        url: "{{ route('acheckemail') }}",
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
