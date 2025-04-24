@include('include.header')
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <form action="{{ $formurl }}" method="POST">
                @csrf
              <div class="row">
                <div class="mb-2 col-md-3">
                  <label  class="form-label">Account name</label>
                  <input type="text" class="form-control"  name="name"  oninput="createcode(this.value)" required @if(!empty($taxsid->name)) value="{{ $taxsid->name }}" @else value="{{ old('name') }}" @endif>
                </div>
                <div class="mb-2 col-md-3">
                  <label  class="form-label">Account name</label>
                  <input type="text" class="form-control" name="code" id="code"  required @if(!empty($taxsid->code)) value="{{ $taxsid->code }}" @else value="{{ old('code') }}" @endif>
                </div>



                <div class="mb-2 col-md-3">
                  <label  class="form-label">Group name</label>
                  <select class="sup-dropdown" name="group_name" required id="get-ledger">

                <option value="">Select Group</option>
                  @foreach ($groups as $groupsllist)
                         <option   @if(!empty($taxsid->group_name))  @if($taxsid->group_name==$groupsllist->id)  @selected(true)  @endif   @endif  value="{{ $groupsllist->id }}">{{ $groupsllist->name }}</option>
                  @endforeach
              </select>
                </div>









                <div class="mb-2 col-md-3">
                  <label  class="form-label">Ledger</label>
                  <select  name="ledger_name"   class="form-select" required id="ledger-get">
                    <option value="">Select Ledger</option>
                  </select>
                </div>






                <div class="mb-2 col-md-3">
                    <label  class="form-label">SGST Percentage</label>
                    <input type="text"  class="form-control" name="sgstpercentage"  onkeypress="return onlyNumberKey(event)"  required @if(!empty($taxsid->sgstpercentage)) value="{{ $taxsid->sgstpercentage }}" @else value="{{ old('sgstpercentage') }}" @endif>
                  </div>

                  <div class="mb-2 col-md-3">
                      <label  class="form-label"> CGST Percentage</label>
                      <input type="text"  class="form-control" name="cgstpercentage"  onkeypress="return onlyNumberKey(event)"  required @if(!empty($taxsid->cgstpercentage)) value="{{ $taxsid->cgstpercentage }}" @else value="{{ old('cgstpercentage') }}" @endif>
                    </div>
                  <div class="mb-2 col-md-3">
                      <label  class="form-label"> IGST Percentage</label>
                      <input type="text"  class="form-control" name="igstpercentage"  onkeypress="return onlyNumberKey(event)"  required @if(!empty($taxsid->igstpercentage)) value="{{ $taxsid->igstpercentage }}" @else value="{{ old('igstpercentage') }}" @endif>
                    </div>

                <div class="mb-2 col-md-3">
                  <label  class="form-label">Status</label>
                  <select  name="status" required class="form-select" required>
                  <option value="">Select Status</option>
                  <option  @if(!empty($unitsid->status)) @if($unitsid->status=='active') @selected(true) @endif   @else @selected(true) @endif value="active">Active</option>
                  <option  @if(!empty($unitsid->status)) @if($unitsid->status=='inactive') @selected(true) @endif  @endif value="inactive">Inactive</option>
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
                  <th>Tax Name</th>
                  <th>Tax Code</th>
                  <th>Groups Name</th>
                  <th>Sgst(%)</th>
                  <th>Cgst(%)</th>
                  <th>Igst(%)</th>
                  <th>Status</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
              </thead>
              code name groups_name sgstpercentage cgstpercentage igstpercentage
              <tbody>
              @if(!empty($taxs))
              @foreach ($taxs as $taxslist)
              <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ $taxslist->name}}</td>
                <td>{{ $taxslist->code}}</td>
                <td>{{ $taxslist->groups_name}}</td>
                <td>{{ $taxslist->sgstpercentage}}</td>
                <td>{{ $taxslist->cgstpercentage}}</td>
                <td>{{ $taxslist->igstpercentage}}</td>
                <td>{{ ucfirst($taxslist->status) }}</td>
                <td><a href="{{ url('edittax/'.$taxslist->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a></td>
                <td><a onclick="return confirm('Are you Sure?')" href="{{ url('deletetax/'.$taxslist->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a></td>
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

<script>

function createcode(id) {

if(id!=""){
    $.ajax({
            url: "{{route('generatecode')}}",
            type: "POST",
            data: {
                id: id,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(data) {
                $('#code').val(data)
           }
        });

    }

  }

</script>
@include('include.footer')
