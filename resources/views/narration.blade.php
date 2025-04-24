@include('include.header')
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <form action="{{ $formurl }}" method="POST">
                @csrf
              <div class="row">
                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">Narration</label>
                  <input type="text" name="name" class="form-control form-control-sm" id="inputAddressname" @if(!empty($narrationId->name)) value="{{ $narrationId->name }}" @else placeholder="Narration"  @endif required>
                  <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('name') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">Status</label>
                  <select  name="status" required class="form-select form-select-sm" required>
                  <option value="">Select Status</option>
                  <option  @if(!empty($narrationId->status)) @if($narrationId->status=='Active') @selected(true) @endif   @else @selected(true) @endif value="active">Active</option>
                  <option  @if(!empty($narrationId->status)) @if($narrationId->status=='Inactive') @selected(true) @endif  @endif value="inactive">Inactive</option>
                  </select>
                </div>
                <div class="pt-4 col-md-4">
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
              @if(!empty($narrations))
              @foreach ($narrations as $item)
              <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ ucwords($item->name)}}</td>
                <td>{{ ucwords($item->status) }}</td>
                <td><a href="{{ url('edinarration/'.$item->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a></td>
                <td><a onclick="return confirm('Are you Sure?')" href="{{ url('deletenarration/'.$item->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a></td>
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
