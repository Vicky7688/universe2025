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
                  <select name="stateId" class="form-select form-select-sm" required onchange="getDistrict(this)">
                    <option value="">Select State</option>
                    @foreach ($state_masters as $state)
                    <option  @if(!empty($post_office_mastersid->stateId)) @if($post_office_mastersid->stateId==$state->id) @selected(true) @endif @endif value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">District</label>
                  <select name="districtId" class="form-select form-select-sm" required onchange="getTehsil(this)"  >
                    <option value="">Select District</option>
                    @if(!empty($district))
                    @foreach ($district as $districts)
                    <option  @if(!empty($post_office_mastersid->districtId)) @if($post_office_mastersid->districtId==$districts->id) @selected(true) @endif @endif value="{{ $districts->id }}">{{ $districts->name }}</option>
                    @endforeach
                    @endif
                  </select>
                </div>

                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">Tehsil</label>
                  <select name="tehsilId" class="form-select form-select-sm" required  >
                    <option value="">Select Tehsil</option>
                    @if(!empty($tehsil_masters))
                    @foreach ($tehsil_masters as $tehsil_masterss)
                    <option  @if(!empty($post_office_mastersid->tehsilId)) @if($post_office_mastersid->tehsilId==$districts->id) @selected(true) @endif @endif value="{{ $tehsil_masterss->id }}">{{ $tehsil_masterss->name }}</option>
                    @endforeach
                    @endif
                  </select>
                </div>

                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">postoffice Name</label>
                  <input type="text" name="name" class="form-control form-control-sm" id="inputAddressname" @if(!empty($post_office_mastersid->name)) value="{{ $post_office_mastersid->name }}" @else placeholder="postoffice Name"  @endif required>
                  <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('name') {{$message}} @enderror </small>
                </div>
                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">Pincode</label>
                  <input type="text" name="pincode" class="form-control form-control-sm" id="inputAddressname" @if(!empty($post_office_mastersid->pincode)) value="{{ $post_office_mastersid->pincode }}" @else placeholder="pincode"  @endif required>
                  <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('pincode') {{$message}} @enderror </small>
                </div>
                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">Status</label>
                  <select  name="status"   class="form-select form-select-sm" required>
                  <option value="">Select Status</option>
                  <option  @if(!empty($post_office_mastersid->status)) @if($post_office_mastersid->status=='Active') @selected(true) @endif   @else @selected(true) @endif value="active">Active</option>
                  <option  @if(!empty($post_office_mastersid->status)) @if($post_office_mastersid->status=='Inactive') @selected(true) @endif  @endif value="inactive">Inactive</option>
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
                  <th>District</th>
                  <th>Tehsil</th>
                  <th>Name</th>
                  <th>Status</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>

              @if(!empty($post_office_masters))
              @foreach ($post_office_masters as $postofficelist)
              <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ DB::table('state_masters')->where('id','=',$postofficelist->stateId)->value('name'); }}</td>
                <td>{{ DB::table('district_masters')->where('id','=',$postofficelist->districtId)->value('name'); }}</td>
                <td>{{ DB::table('tehsil_masters')->where('id','=',$postofficelist->tehsilId)->value('name'); }}</td>
                <td>{{ $postofficelist->name}}</td>
                <td>{{ ucfirst($postofficelist->status) }}</td>
                <td><a href="{{ url('editpostoffice/'.$postofficelist->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a></td>
                <td><a onclick="return confirm('Are you Sure?')" href="{{ url('deletepostoffice/'.$postofficelist->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a></td>
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
