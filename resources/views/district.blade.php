@include('include.header')
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <form action="{{ $formurl }}" method="POST">
                @csrf
              <div class="row">

                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">State</label>
                  <select name="stateId" class="form-select form-select-sm" required>
                    <option value="">Select State</option>
                    @foreach ($state_masters as $state)
                    <option  @if(!empty($districtid->stateId)) @if($districtid->stateId==$state->id) @selected(true) @endif @endif value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">District Name</label>
                  <input type="text" name="name" class="form-control form-control-sm" id="inputAddressname" @if(!empty($districtid->name)) value="{{ $districtid->name }}" @else placeholder="district Name"  @endif required>
                  <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('name') {{$message}} @enderror </small>
                </div>
                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">Status</label>
                  <select  name="status"   class="form-select form-select-sm" required>
                  <option value="">Select Status</option>
                  <option  @if(!empty($districtid->status)) @if($districtid->status=='Active') @selected(true) @endif   @else @selected(true) @endif value="active">Active</option>
                  <option  @if(!empty($districtid->status)) @if($districtid->status=='Inactive') @selected(true) @endif  @endif value="inactive">Inactive</option>
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
                  <th>State</th>
                  <th>Name</th>
                  <th>Status</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>
              @if(!empty($district))
              @foreach ($district as $districtlist)
              <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ DB::table('state_masters')->where('id','=',$districtlist->stateId)->value('name'); }}</td>
                <td>{{ $districtlist->name}}</td>
                <td>{{ ucfirst($districtlist->status) }}</td>
                <td><a href="{{ url('editdistrict/'.$districtlist->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a></td>
                <td><a onclick="return confirm('Are you Sure?')" href="{{ url('deletedistrict/'.$districtlist->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a></td>
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
