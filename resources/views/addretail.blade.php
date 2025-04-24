@include('include.header')
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <form action="{{ $formurl }}" method="POST"  >
              @csrf
              <div class="row">
                <div class="mb-2 col-md-3">
                  <label> Group</label>
                  <select id="selectize-optgroup" name="groupname"  placeholder="Select groupname" required>
                    <option value="">Group </option>
                    @foreach ($groups as $stategstcodeslist)
                    <option  @if(!empty($retailsid->groupname)) @if($retailsid->groupname==$stategstcodeslist->id) @selected(true) @endif @endif value="{{ $stategstcodeslist->id }}">{{ $stategstcodeslist->name }}</option>
                    @endforeach
                  </select>
                </div>


                <div class="mb-2 col-md-3">
                    <label> Party Name</label>
                    <input class="form-control"  type="text" name="pname"  required @if(!empty($retailsid->
                    pname)) value="{{ $retailsid->pname }}" @else   @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('pname') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label> Trade Name</label>
                  <input class="form-control"  type="text" name="name"  required @if(!empty($retailsid->
                  name)) value="{{ $retailsid->name }}" @else oninput="createcode(this.value)" value="{{ old('name') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('name') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label> Retailer Code</label>
                  <input class="form-control"  type="text" readonly id="retailercode" name="retailercode" required @if(!empty($retailsid->
                  retailercode)) value="{{ $retailsid->retailercode }}" @else   @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('retailercode') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label>Type Of Trader</label>
                  <select class="form-select"     name="typeoftrader" id="typeoftrader" required>
                    <option @if(!empty($retailsid->typeoftrader)) @if($retailsid->typeoftrader=='retailer') @selected(true) @endif @endif value="retailer">Retailer</option>
                    <option @if(!empty($retailsid->typeoftrader)) @if($retailsid->typeoftrader=='distributer') @selected(true) @endif @endif value="distributer">Distributer</option>
                  </select>
                </div>
                <div class="mb-2 col-md-3">
                  <label for="Item Number">Username</label>
                  <input class="form-control"  type="text" name="ubuser"  id="ubuser"  @if(!empty($retailsid->
                  username)) value="{{ $retailsid->username }}" oninput="checkusername(this.value)" @endif required  autocomplete="off" > <small id="usernameerror" style="color: #ee0f0f;" > </small> </div>
                <div class="mb-2 col-md-3">
                  <label for="Item Number">Email</label>
                  <input class="form-control"  type="email" id="email" name="email" @if(!empty($retailsid->
                  email)) value="{{ $retailsid->email }}"  oninput="checkemail(this.value)"  @endif required   autocomplete="off"> <small id="emailerror" style="color: #ee0f0f;"> </small> </div>
                <div class="mb-2 col-md-3">
                  <label for="Item Number">Password</label>
                  <input class="form-control"  type="password"   id="passwordd" name="password"  required   autocomplete="off">
                  <small id="emailerror" style="color: #ee0f0f;"> </small> </div>
                <div class="mb-2 col-md-3">
                  <label> Contact Person</label>
                  <input class="form-control"  type="text"  name="contactperson" required @if(!empty($retailsid->
                  contactperson)) value="{{ $retailsid->contactperson }}" @else value="{{ old('contactperson') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('contactperson') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label> Contact Number</label>
                  <input class="form-control"  type="text" name="phone" maxlength="10" onKeyPress="return onlyNumberKey(event)"  required @if(!empty($retailsid->
                  phone)) value="{{ $retailsid->phone }}" @else value="{{ old('phone') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('phone') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label> Designation</label>
                  <input class="form-control"  type="text" name="designation" required @if(!empty($retailsid->
                  designation)) value="{{ $retailsid->designation }}" @else value="{{ old('designation') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('designation') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label> address</label>
                  <input class="form-control"  type="text" name="address"   required @if(!empty($retailsid->
                  address)) value="{{ $retailsid->address }}" @else value="{{ old('address') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('address') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label> address 2</label>
                  <input class="form-control"  type="text" name="addresss"   required @if(!empty($retailsid->
                  addresss)) value="{{ $retailsid->addresss }}" @else value="{{ old('addresss') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('addresss') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label> State </label>
                  <select class="form-select"   name="state" required onChange="getgstcode(this.value)">
                    <option value="">Select State</option>


                    @foreach ($stategstcodes as $stategstcodeslist)
                    <option  @if(!empty($retailsid->state)) @if($retailsid->state==$stategstcodeslist->id) @selected(true) @endif @endif   value="{{ $stategstcodeslist->id }}">{{ $stategstcodeslist->state }}</option>
                    @endforeach


                  </select>
                </div>
                <div class="mb-2 col-md-3">
                  <label> City</label>
                  <select class="form-select"   name="city" required id="city-get">
                    <option value="">Select city</option>

              @if(!empty($citycodes))
              @foreach ($citycodes as $citycodeslist)

                    <option  @if(!empty($retailsid->city)) @if($retailsid->city==$citycodeslist->id) @selected(true) @endif @endif   value="{{ $citycodeslist->id }}">{{ $citycodeslist->city_name }}</option>

              @endforeach
              @endif

                  </select>
                </div>
                <div class="mb-2 col-md-3">
                  <label> Area</label>
                  <input class="form-control"  type="text"  name="area" required @if(!empty($retailsid->
                  area)) value="{{ $retailsid->area }}" @else value="{{ old('area') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('area') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label>GSTIN No.</label>
                  <input class="form-control"  type="text" name="gstno"   id="gstno"   required @if(!empty($retailsid->
                  gstno)) value="{{ $retailsid->gstno }}" @else value="{{ old('gstno') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('gstno') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label> Joining date</label>
                  <input class="form-control"  type="date" class="form-control" name="joiningdate" required @if(!empty($retailsid->
                  joiningdate)) value="{{ date('Y-m-d', strtotime($retailsid->joiningdate)); }}" @else value="{{ old('joiningdate') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('joiningdate') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label> Leaving date</label>
                  <input class="form-control"  type="date" name="leavingdate"  class="form-control"   @if(!empty($retailsid->
                  leavingdate)) value="{{ date('Y-m-d', strtotime($retailsid->joiningdate)); }}"  @else value="{{ old('leavingdate') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('leavingdate') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label> Status</label>
                  <select class="form-select"   name="status" required>
                    <option value="">Select Status</option>
                    <option  @if(!empty($retailsid->status)) @if($retailsid->status=='active') @selected(true) @endif   @else @selected(true) @endif value="active">Active</option>
                    <option  @if(!empty($retailsid->status)) @if($retailsid->status=='inactive') @selected(true) @endif  @endif value="inactive">Inactive</option>
                    <option  @if(!empty($retailsid->status)) @if($retailsid->status=='requested') @selected(true) @endif  @endif value="requested">Requested</option>
                    <option  @if(!empty($retailsid->status)) @if($retailsid->status=='rejected') @selected(true) @endif  @endif value="rejected">Rejected</option>
                  </select>
                </div>
              </div>
              <div class="row mt-2">
                <div class="mb-2 col-md-3">
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
                  <th>Group Name</th>
                  <th>Customer Code</th>
                  <th>Customer Name</th>
                  <th>Contact Person</th>
                  <th>Phone</th>
                  {{-- <th>Designation</th> --}}
                  <th>Status</th>
                  <th>Barcode</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>

              @if(!empty($retails))
              @foreach ($retails as $retailslist)
              <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ $retailslist->groups_name}}</td>
                <td>{{ $retailslist->retailercode}}</td>
                <td>{{ $retailslist->name}}</td>
                <td>{{ $retailslist->contactperson}}</td>
                <td>{{ $retailslist->phone}}</td>
                {{-- <td>{{ $retailslist->designation}}</td> --}}
                <td>{{ ucfirst($retailslist->status) }}</td>
                <td> <i class='bx bxs-printer' style="cursor:pointer" onclick="getbarcode('{{ $retailslist->retailercode }}')"></i></a>


                </td>
                 <td>
                    <a href="{{ url('editretail/'.$retailslist->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a> &nbsp;
                    <a onclick="return confirm('Are you Sure?')" href="{{ url('deleteretail/'.$retailslist->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a></td>
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
{{-- <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#bottom-modal">Bottom Modal</button> --}}
<div id="bottom-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-bottom">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="bottomModalLabel">Barcode</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="changebarcode" src="" style="width:100%;padding: 12px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Print</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script>
    function getbarcode(id){

        $.ajax({
                url: "{{ route('getbarcode') }}",
                type: "Post",
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(data) {
                    if (data.barcode_image_name) {
                        $('#changebarcode').attr('src', '{{ url('storage/app/public/barcodes/') }}/' + data.barcode_image_name);
                        $('#bottom-modal').modal('show');
                    }
                }
            });

    }
</script>
@include('include.footer')
