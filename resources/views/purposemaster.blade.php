@include('include.header')
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <form action="{{ $formurl }}" method="POST">
                @csrf
              <div class="row">
                <div class="mb-2 col-md-8">
                  <label for="inputAddressname" class="form-label">Loan Purpose</label>
                  <input type="text" name="name" class="form-control" id="inputAddressname" @if(!empty($purpose_mastersid->name)) value="{{ $purpose_mastersid->name }}" @else placeholder="Loan Purpose"  @endif required>
                  <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('name') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">Status</label>
                  <select  name="status" required class="form-select" required>
                  <option value="">Select Status</option>
                  <option  @if(!empty($purpose_mastersid->status)) @if($purpose_mastersid->status=='Active') @selected(true) @endif   @else @selected(true) @endif value="Active">Active</option>
                  <option  @if(!empty($purpose_mastersid->status)) @if($purpose_mastersid->status=='Inactive') @selected(true) @endif  @endif value="Inactive">Inactive</option>
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
              @if(!empty($purpose_masters))
              @foreach ($purpose_masters as $brandlist)
              <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ $brandlist->name}}</td>
                <td>{{ ucfirst($brandlist->status) }}</td>
                <td><a href="{{ url('purposemaster/'.$brandlist->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a></td>
                <td><a onclick="return confirm('Are you Sure?')" href="{{ url('deletepurposemaster/'.$brandlist->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a></td>
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
