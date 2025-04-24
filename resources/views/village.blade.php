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
                        <option  @if(!empty($villageId->stateId)) @if($villageId->stateId==$state->id) @selected(true) @endif @endif value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">District</label>
                  <select name="districtId" class="form-select form-select-sm" required onchange="getTehsil(this)"  >
                    <option value="">Select District</option>
                    @if(!empty($district))
                        @foreach ($district as $districts)
                            <option  @if(!empty($villageId->districtId)) @if($villageId->districtId == $districts->id) @selected(true) @endif @endif value="{{ $districts->id }}">{{ $districts->name }}</option>
                        @endforeach
                    @endif
                  </select>
                </div>

                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">Tehsil</label>
                  <select name="tehsilId" class="form-select form-select-sm" required onchange="getPostoffice(this)">
                    <option value="">Select Tehsil</option>
                    @if(!empty($tehsil_masters))
                        @foreach ($tehsil_masters as $tehsil_masterss)
                            <option  @if(!empty($villageId->tehsilId)) @if($villageId->tehsilId==$tehsil_masterss->id) @selected(true) @endif @endif value="{{ $tehsil_masterss->id }}">{{ $tehsil_masterss->name }}</option>
                        @endforeach
                    @endif
                  </select>
                </div>

                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">Post Office Name</label>
                  <select name="postOfficeId" class="form-select form-select-sm" required  >
                    <option value="">Select Post Office</option>
                    @if(!empty($post_office_masters))
                        @foreach ($post_office_masters as $post)
                            <option  @if(!empty($villageId->id)) @if($villageId->postOfficeId == $post->id) @selected(true) @endif @endif value="{{ $post->id }}">{{ $post->name }}</option>
                        @endforeach
                    @endif
                  </select>
                </div>
                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">Village</label>
                  <input type="text" name="village" class="form-control form-control-sm" id="inputAddressname" @if(!empty($villageId->name)) value="{{ $villageId->name }}" @else placeholder="Village"  @endif required>
                  <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('village') {{$message}} @enderror </small>
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

              @if(!empty($villages))
                @foreach ($villages as $village)
                    <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>{{ DB::table('state_masters')->where('id','=',$village->stateId)->value('name'); }}</td>
                        <td>{{ DB::table('district_masters')->where('id','=',$village->districtId)->value('name'); }}</td>
                        <td>{{ DB::table('tehsil_masters')->where('id','=',$village->tehsilId)->value('name'); }}</td>
                        <td>{{ DB::table('post_office_masters')->where('id','=',$village->postOfficeId)->value('name'); }}</td>
                        <td>{{ ucfirst($village->name) }}</td>
                        <td><a href="{{ url('editvillage/'.$village->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a></td>
                        <td><a onclick="return confirm('Are you Sure?')" href="{{ url('deletevillage/'.$village->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a></td>
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
