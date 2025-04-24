@include('include.header')
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <form action="{{ $formurl }}" method="POST">
                @csrf
              <div class="row">


                <div class="mb-2 col-md-3">
                  <label for="inputAddressname" class="form-label">Name</label>
                  <input type="text" name="name" class="form-control" id="inputAddressname" @if(!empty($login_mastersid->name)) value="{{ $login_mastersid->name }}" @else  @endif required>
                  <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('name') {{$message}} @enderror </small>
                </div>


                <div class="mb-2 col-md-3">
                  <label for="inputAddressname" class="form-label">Username</label>
                  <input type="text" name="username" class="form-control" id="inputAddressname" @if(!empty($login_mastersid->username)) value="{{ $login_mastersid->username }}" @else  @endif required>
                  <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('username') {{$message}} @enderror </small>
                </div>


                <div class="mb-2 col-md-3">
                  <label for="inputAddressname" class="form-label">Password</label>
                  <input type="text" name="password" class="form-control" id="inputAddressname" >
                </div>

                <div class="mb-2 col-md-3">
                  <label for="inputAddressname" class="form-label">Status</label>
                  <select  name="status" required class="form-select" required>
                  <option value="">Select Status</option>
                  <option  @if(!empty($login_mastersid->status)) @if($login_mastersid->status=='Active') @selected(true) @endif   @else @selected(true) @endif value="Active">Active</option>
                  <option  @if(!empty($login_mastersid->status)) @if($login_mastersid->status=='Inactive') @selected(true) @endif  @endif value="Inactive">Inactive</option>
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
            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
              <thead>
                <tr>
                  <th>Sr.No</th>
                  <th>Name</th>
                  <th>Username</th>
                  <th>Status</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>
              @if(!empty($login_masters))
              @foreach ($login_masters as $brandlist)
              <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ $brandlist->name}}</td>
                <td>{{ $brandlist->username}}</td>
                <td>{{ ucfirst($brandlist->status) }}</td>
                <td><a href="{{ url('loginmaster/'.$brandlist->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a></td>
                <td><a onclick="return confirm('Are you Sure?')" href="{{ url('deleteloginmaster/'.$brandlist->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a></td>
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
