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
                        <option  @if(!empty($subchildcategorysid->brand)) @if($subchildcategorysid->brand==$loadbrandsquw->id) @selected(true) @endif  @endif value="{{ $loadbrandsquw->id }}">{{ $loadbrandsquw->name }}</option>
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
                <option  @if(!empty($subchildcategorysid->category)) @if($subchildcategorysid->category==$categorylistque->id) @selected(true) @endif  @endif value="{{ $categorylistque->id }}">{{ $categorylistque->name }}</option>
                @endforeach
                @endif
                      </select>
                  </div>

                <div class="mb-2 col-md-4">
                    <label  class="form-label">Category</label>



                    <select id="selectize-optgroup-subcategory" name="subcategory"  placeholder="Select subcategory" class="subcategory-dropdown">

                        <option value="">Select SubCategory</option>
                             @if(!empty($subcategorylist))
                                    @foreach ($subcategorylist as $subcategorylistque)
                        <option  @if(!empty($subchildcategorysid->subcategory)) @if($subchildcategorysid->subcategory==$subcategorylistque->id) @selected(true) @endif  @endif value="{{ $subcategorylistque->id }}">{{ $subcategorylistque->name }}</option>
                                    @endforeach
                              @endif
                      </select>


                  </div>



                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">Sub Child Category Name</label>

                  <input type="text" class="form-control" name="name" required @if(!empty($subchildcategorysid->name)) value="{{ $subchildcategorysid->name }}" @else value="{{ old('name') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('name') {{$message}} @enderror </small>
              </div>
                <div class="mb-2 col-md-4">
                  <label for="inputAddressname" class="form-label">Status</label>
                  <select  name="status" required class="form-select" required>
                  <option value="">Select Status</option>
                  <option  @if(!empty($subchildcategorysid->status)) @if($subchildcategorysid->status=='active') @selected(true) @endif   @else @selected(true) @endif value="active">Active</option>
                  <option  @if(!empty($subchildcategorysid->status)) @if($subchildcategorysid->status=='inactive') @selected(true) @endif  @endif value="inactive">Inactive</option>
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
              @if(!empty($subchildcategorys))
              @foreach ($subchildcategorys as $subchildcategoryslist)
              <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ $subchildcategoryslist->brand_name}}</td>
                <td>{{ $subchildcategoryslist->category_name}}</td>
                <td>{{ $subchildcategoryslist->name}}</td>
                <td>{{ ucfirst($subchildcategoryslist->status) }}</td>
                <td><a href="{{ url('editsubchildcategory/'.$subchildcategoryslist->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a></td>
                <td><a onclick="return confirm('Are you Sure?')" href="{{ url('deletesubchildcategory/'.$subchildcategoryslist->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a></td>
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
