@include('include.header')
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <form action="{{ $formurl }}" method="POST">
                @csrf
              <div class="row">
                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">state Name</label>
                  <input type="text" name="name" class="form-control form-control-sm" id="inputAddressname" @if(!empty($stateid->name)) value="{{ $stateid->name }}" @else placeholder="State Name"  @endif required>
                  <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('name') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">Status</label>
                  <select  name="status" required class="form-select form-select-sm" required>
                  <option value="">Select Status</option>
                  <option  @if(!empty($stateid->status)) @if($stateid->status=='Active') @selected(true) @endif   @else @selected(true) @endif value="active">Active</option>
                  <option  @if(!empty($stateid->status)) @if($stateid->status=='Inactive') @selected(true) @endif  @endif value="inactive">Inactive</option>
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
                  <th>Status</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>
              @if(!empty($state))
              @foreach ($state as $statelist)
              <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ $statelist->name}}</td>
                <td>{{ ucfirst($statelist->status) }}</td>
                <td><a href="{{ url('editstate/'.$statelist->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a></td>
                <td>
@if(DB::table('district_masters')->where('stateId','=',$statelist->id)->first())
@else
                    <a onclick="return confirm('Are you Sure?')" href="{{ url('deletestate/'.$statelist->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a>
@endif
                </td>
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
