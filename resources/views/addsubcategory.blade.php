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
                      <select id="selectize-optgroup-brand" name="brand"  placeholder="Select Brand" class="brand-dropdown">
                        <option value="">Select Brand</option>
                        @if(sizeof($loadbrands)>0)
                        @foreach ($loadbrands as $loadbrandsquw)
                        <option  @if(!empty($subcategorysid->brand)) @if($subcategorysid->brand==$loadbrandsquw->id) @selected(true) @endif  @endif value="{{ $loadbrandsquw->id }}">{{ $loadbrandsquw->name }}</option>
                        @endforeach
                        @endif
                      </select>
                  </div>

                <div class="mb-2 col-md-4">
                    <label  class="form-label">Category</label>
                      <select id="selectize-optgroup-category" name="category"  placeholder="Select Category" class="category-dropdown">
                        <option value="">Select Category</option>
                        @if(!empty($categorylist))
                @foreach ($categorylist as $categorylistque)
                <option  @if(!empty($subcategorysid->category)) @if($subcategorysid->category==$categorylistque->id) @selected(true) @endif  @endif value="{{ $categorylistque->id }}">{{ $categorylistque->name }}</option>
                @endforeach
                @endif
                      </select>
                  </div>



                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">Brand Name</label>
                  <input type="text" name="name" class="form-control" id="inputAddressname" @if(!empty($subcategorysid->name)) value="{{ $subcategorysid->name }}" @else placeholder="Brand Name"  @endif required>
                  <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('name') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">Status</label>
                  <select  name="status" required class="form-select" required>
                  <option value="">Select Status</option>
                  <option  @if(!empty($subcategorysid->status)) @if($subcategorysid->status=='active') @selected(true) @endif   @else @selected(true) @endif value="active">Active</option>
                  <option  @if(!empty($subcategorysid->status)) @if($subcategorysid->status=='inactive') @selected(true) @endif  @endif value="inactive">Inactive</option>
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
                  <th>Sub Category Name</th>
                  <th>Status</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>
              @if(!empty($subcategorys))
              @foreach ($subcategorys as $subcategoryslist)
              <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ $subcategoryslist->brand_name}}</td>
                <td>{{ $subcategoryslist->category_name}}</td>
                <td>{{ $subcategoryslist->name}}</td>
                <td>{{ ucfirst($subcategoryslist->status) }}</td>
                <td><a href="{{ url('editsubcategory/'.$subcategoryslist->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a></td>
                <td><a onclick="return confirm('Are you Sure?')" href="{{ url('deletesubcategory/'.$subcategoryslist->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a></td>
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
