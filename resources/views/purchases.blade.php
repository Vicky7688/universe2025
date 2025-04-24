@include('include.header')
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            {{-- <form action="{{ $formurl }}" method="POST">
                @csrf
              <div class="row">
                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">Brand Name</label>
                  <input type="text" name="name" class="form-control" id="inputAddressname" @if(!empty($brandsid->name)) value="{{ $brandsid->name }}" @else placeholder="Brand Name"  @endif required>
                  <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('name') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">Status</label>
                  <select  name="status" required class="form-select" required>
                  <option value="">Select Status</option>
                  <option  @if(!empty($brandsid->status)) @if($brandsid->status=='active') @selected(true) @endif   @else @selected(true) @endif value="active">Active</option>
                  <option  @if(!empty($brandsid->status)) @if($brandsid->status=='inactive') @selected(true) @endif  @endif value="inactive">Inactive</option>
                  </select>
                </div>

                <div class="mb-2 col-md-4">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
            </form> --}}
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
                  <th>Date</th>
                  <th>Invoice No</th>
                  <th>Party Name</th>
                  <th>Payment Type</th>
                  <th>Amount</th>
                  {{-- <th>Print </th> --}}
                </tr>
              </thead>
              <tbody>
              @if(!empty($getinvoice))
              @foreach ($getinvoice as $showlist)
              <tr>
                <td>{{ \Carbon\Carbon::parse($showlist->invoicenodate)->format('d-m-Y') }}</td>
                <td>{{ $showlist->invoiceno}}</td>
                <td>{{ $showlist->accountname}}</td>
                <td>{{ $showlist->memo}}</td>
                <td>{{ $showlist->totalamount}}</td>
                {{-- <td><a target="_blank" href="{{ url('generate-pdf') }}/{{ $showlist->invoiceno}}"><i class='bx bxs-printer'></i></a></td> --}}

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
