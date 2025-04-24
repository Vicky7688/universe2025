@include('include.header')
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <form action="{{ $formurl }}" method="POST">
                @csrf
              <div class="row">
                <div class="mb-2 col-md-4">
                  <label  class="form-label">Brand</label>

                    <select id="selectize-optgroup" name="brand"  placeholder="Select Brand">
                      <option value="">Select Brand</option>
                        @if(sizeof($loadbrands)>0)
                        @foreach ($loadbrands as $loadbrandsquw)
                      <option  @if(!empty($categorysid->brand)) @if($categorysid->brand==$loadbrandsquw->id) @selected(true) @endif  @endif value="{{ $loadbrandsquw->id }}">{{ $loadbrandsquw->name }}</option>
                        @endforeach
                        @endif
                    </select>


                </div>
                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">Category Name</label>
                  <input type="text" name="name" class="form-control" id="inputAddressname" @if(!empty($categorysid->
                  name)) value="{{ $categorysid->name }}" @else placeholder="Category Name"  @endif required> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('name') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-4">
                  <label   class="form-label">Status</label>
                  <select  name="status" required class="form-select" required>
                  <option value="">Select Status</option>
                  <option  @if(!empty($categorysid->status)) @if($categorysid->status=='active') @selected(true) @endif   @else @selected(true) @endif value="active">Active</option>
                  <option  @if(!empty($categorysid->status)) @if($categorysid->status=='inactive') @selected(true) @endif  @endif value="inactive">Inactive</option>
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
                  <th>Brand Name</th>
                  <th>Category Name</th>
                  <th>Status</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>

              @if(!empty($categorys))
              @foreach ($categorys as $categoryslist)
              <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ $categoryslist->brand_name}}</td>
                <td>{{ $categoryslist->name}}</td>
                <td>{{ ucfirst($categoryslist->status) }}</td>
                <td><a href="{{ url('editcategory/'.$categoryslist->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a></td>
                <td><a onclick="return confirm('Are you Sure?')" href="{{ url('deletecategory/'.$categoryslist->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a></td>
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
