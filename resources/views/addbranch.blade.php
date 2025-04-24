@include('include.header')
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <form id="branchMaster" action="{{$formurl}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="branchMaster" />
                <input type="hidden" name="id" value="new" />

                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-3 col-sm-4 col-6 ">
                            <label for="registrationDate" class="form-label mydatepic">Registration Date</label>
                            <input type="date"  name="registrationDate"
                                id="registrationDate" class="form-control form-control-sm" @if(!empty($branchId->registrationDate))
                                    value="{{ $branchId->registrationDate }}" @else value="{{ now()->format('Y-m-d') }}"@endif>
                        </div>
                          <div class="col-lg-6 col-md-6 col-sm-4 col-6 ">
                            <label for="name" class="form-label">Branch Name</label>
                            <input type="text" name="name" id="name" class="form-control form-control-sm" @if(!empty($branchId->name))
                            value="{{ $branchId->name }}" @else placeholder="Branch Name"

                            @endif />
                        </div>
                         <div class="col-lg-3 col-md-3 col-sm-4 col-6">
                            <label for="registrationNo" class="form-label">Registration No</label>
                            <input type="text" name="registrationNo" id="registrationNo" class="form-control form-control-sm"
                            @if(!empty($branchId->registrationNo))
                                value="{{ $branchId->registrationNo }}" @else placeholder="Reg. No"
                            @endif>
                        </div>

                         <div class="col-lg-3 col-md-3 col-sm-4 col-6">
                            <label for="branch_code" class="form-label">Branch Code</label>
                            <input type="text" name="branch_code" id="branch_code" class="form-control form-control-sm"
                            @if(!empty($branchId->branch_code)) value="{{ $branchId->branch_code }}"  @else placeholder="Branch Code"

                            @endif>
                        </div>

                         <div class="col-lg-3 col-md-3 col-sm-4 col-6">
                            <label for="branch_limit" class="form-label">Branch Limit</label>
                            <input type="text" name="branch_limit" id="branch_limit" value="1" class="form-control form-control-sm"
                            @if(!empty($branchId->branch_limit)) value="{{ $branchId->branch_limit }}" @else  placeholder="Branch Limit" @endif>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-4 col-6">
                            <label for="type" class="form-label">Type</label>
                            <select name="type" id="status-org" class="select21 form-select form-select-sm" data-placeholder="type">
                                <option value="" selected disabled>Select</option>
                                <option  @if(!empty($branchId->type)) @if($branchId->type=='HeadOffice') @selected(true) @endif   @else @selected(true) @endif value="HeadOffice">HeadOffice</option>
                                <option  @if(!empty($branchId->type)) @if($branchId->type=='BranchOffice') @selected(true) @endif  @endif value="BranchOffice">BranchOffice</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-sm-4 col-6">
                            <label for="commissionRD" class="form-label">State</label>
                            <select name="stateId" id="status-org" class="select21 form-select form-select-sm formInputsSelectReport" data-placeholder="State"
                                onchange="getDistrict(this)">
                                <option value="">Select</option>
                                @foreach($states as $state)
                                    <option  @if(!empty($branchId->stateId)) @if($branchId->stateId==$state->id) @selected(true) @endif @endif value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-sm-4 col-6 ">
                            <label for="commissionShare" class="form-label">District</label>
                            <select name="districtId" id="status-org" onchange="getTehsil(this)"  class="select21 form-select form-select-sm formInputsSelectReport" data-placeholder="Active">
                                <option value="">Select</option>
                               @if(!empty($disttt))
                                    @foreach ($disttt as $dist)
                                        <option  @if(!empty($branchId->districtId)) @if($branchId->districtId==$dist->id) @selected(true) @endif @endif value="{{ $dist->id }}">{{ $dist->name }}</option>
                                    @endforeach
                               @endif
                            </select>
                        </div>
                        <div class="col-lg-3 col-sm-4 col-6">
                            <label for="tehsilId" class="form-label">Tehsil</label>
                            <select name="tehsilId" id="status-org" onchange="getPostoffice(this)"
                                class="select21 form-select  form-select-sm formInputsSelectReport" data-placeholder="Active">
                                <option value="">Select</option>
                                @if(!empty($tehsils))
                                    @foreach ($tehsils as $tehsil)
                                        <option  @if(!empty($branchId->tehsilId)) @if($branchId->tehsilId==$tehsil->id) @selected(true) @endif @endif value="{{ $tehsil->id }}">{{ $tehsil->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-3 col-sm-4 col-6 ">
                            <label for="postOfficeId" class="form-label">Post Office</label>
                            <select name="postOfficeId" id="status-org" onchange="getVillage(this)"
                                class="select21 form-select form-select-sm formInputsSelectReport" data-placeholder="Active">
                                <option value="">Select</option>
                                @if (!empty($postOffices))
                                    @foreach ($postOffices as $post)
                                        <option  @if(!empty($branchId->postOfficeId)) @if($branchId->postOfficeId==$post->id) @selected(true) @endif @endif value="{{ $post->id }}">{{ $post->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-3 col-sm-4 col-6">
                            <label for="villageId" class="form-label">Village</label>
                            <select name="villageId" id="status-org" class="select21 form-select  form-select-sm formInputsSelectReport"
                                data-placeholder="Active">
                                <option value="">Select</option>
                                @if(!empty($villages))
                                    @foreach ($villages as $village)
                                        <option  @if(!empty($branchId->villageId)) @if($branchId->villageId==$village->id) @selected(true) @endif @endif value="{{ $village->id }}">{{ $village->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                          <div class="col-lg-3 col-sm-4 col-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" step="NA" name="phone" id="phone" maxlength="10" minlength="10"
                                class="form-control form-control-sm" @if(!empty($branchId->phone)) value="{{ $branchId->phone }}"
                                    @else placeholder="Phone"
                                @endif>
                        </div>
                       <div class="col-lg-3 col-sm-4 col-6">
                            <label for="pincode" class="form-label">Pincode</label>
                            <input type="text" maxlength="6" minlength="6" name="pincode" id="pincode" class="form-control form-control-sm"
                            @if(!empty($branchId->pincode)) value="{{ $branchId->pincode }}" @else
                                    placeholder="Pin code"
                            @endif>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" name="address" id="address" class="form-control form-control-sm"
                            @if(!empty($branchId->address)) value="{{ $branchId->address }}"
                                @else placeholder="Address"
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer me-0">
                    <button id="submitButton" class="btn btn-primary waves-effect waves-light reportSmallBtnCustom ms-2 me-0" type="submit"
                        data-loading-text=" <span class='spinner-border me-1' role='status' aria-hidden='true'></span>
                        Loading...">Submit</button>
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
                  <th>Type</th>
                    <th>Name</th>
                    <th>Reg. No</th>
                    <th>Reg. Date</th>
                    {{-- <th>Address</th> --}}
                    <th>Pincode</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
              </thead>
              <tbody>
              @if(!empty($branch))
              @foreach ($branch as $branchlist)
              <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ $branchlist->type}}</td>
                <td>{{ $branchlist->name}}</td>
                <td>{{ $branchlist->registrationNo }}</td>
                <td>{{ date('d-m-Y',strtotime($branchlist->registrationDate)) }}</td>
                <td>{{ $branchlist->pincode }}</td>
                <td>{{ $branchlist->phone }}</td>
                <td><a href="{{ url('editbranch/'.$branchlist->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a>
                    <a onclick="return confirm('Are you Sure?')" href="{{ url('deletebranch/'.$branchlist->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a></td>
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
