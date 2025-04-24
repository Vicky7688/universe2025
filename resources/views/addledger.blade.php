@include('include.header')
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <form action="{{ $formurl }}" method="POST">
                @csrf
              <div class="row">
                <div class="mb-2 col-md-3">
                    <label class="form-label">Group Name</label>
                    <select name="groupname" id="groupname" class="form-select" required>
                        <option value="">Select Group</option>
                        @foreach($groups as $group)
                            <option @if(!empty($ledgerId->groupCode)) @if($ledgerId->groupCode == $group->groupCode ) @selected(true) @endif   @endif value="{{ $group->groupCode }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                  </div>

                <div class="mb-2 col-md-3">
                  <label  class="form-label">Name</label>
                  <input type="text" style="text-transform: capitalize;" class="form-control" name="name" id="name" required @if(!empty($ledgerId->name)) value="{{ $ledgerId->name }}" @else  value="{{ old('name') }}" @endif>
                  <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('name') {{$message}} @enderror </small>
                </div>

                @if(!empty($ledgerId->ledgerCode))

                @else
                    <div class="mb-2 col-md-3">
                        <label class="form-label">Ledger Code</label>
                        <input type="text" style="text-transform: capitalize;" class="form-control" name="ledger_code"  id="ledger_code" required readonly>
                        <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('ledger_code') {{$message}} @enderror </small>
                    </div>
                @endif

                <div class="mb-2 col-md-3">
                    <label class="form-label">Opening Amt.</label>
                    <input type="text" style="text-transform: capitalize;" class="form-control" name="opening_amt"  id="opening_amt" @if(!empty($ledgerId->openingAmount)) value="{{ $ledgerId->openingAmount }}" @else  value="0" @endif required>
                    <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('opening_amt') {{$message}} @enderror </small>
                </div>

                  <div class="mb-2 col-md-3">
                    <label class="form-label">Nature(Dr/Cr)</label>
                    <select  name="nature" id="nature" required class="form-select" required>
                        <option value="">Select Nature</option>
                        <option @if(!empty($ledgerId->openingType)) @if($ledgerId->openingType=='Dr') @selected(true) @endif   @endif value="Dr">Dr</option>
                        <option @if(!empty($ledgerId->openingType)) @if($ledgerId->openingType=='Cr') @selected(true) @endif   @endif value="Cr">Cr</option>
                    </select>
                  </div>

                <div class="mb-2 col-md-3">
                    <label for="inputAddressname" class="form-label">Status</label>
                    <select  name="status" required class="form-select" required>
                    {{--  <option value="">Select Status</option>  --}}
                    <option  @if(!empty($ledgerId->status)) @if($ledgerId->status=='active') @selected(true) @endif   @endif value="active">Active</option>
                    <option  @if(!empty($ledgerId->status)) @if($ledgerId->status=='inactive') @selected(true) @endif  @endif value="inactive">Inactive</option>
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
                  <th>Group Code</th>
                  <th>Name</th>
                  <th>Ledger Code</th>
                  <th>Status</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>
              @if(!empty($ledgers))
              @foreach ($ledgers as $ledger)
              <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ $ledger->groupCode}}</td>
                <td>{{ ucfirst($ledger->name)}}</td>
                <td>{{ $ledger->ledgerCode }}</td>
                <td>{{ ucfirst($ledger->status) }}</td>
                    <td><a href="{{ url('editledger/'.$ledger->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a></td>
                    <td><a onclick="return confirm('Are you Sure?')" href="{{ url('deleteledger/'.$ledger->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a></td>

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
    $(document).ready(function(){
        //++++++++++ Generate Ledger Code Ajax
        $(document).on('input','#name',function(e){
            e.preventDefault();
            let ledgerName = $(this).val();

            $.ajax({
                url : '{{ route("generateLedgerCode") }}',
                type : 'post',
                data : {ledgerName:ledgerName},
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}"
               },
                dataType : 'json',
                success : function(res){
                    if(res.status === 'success'){
                        $('#ledger_code').val(res.ledgerCode);
                    }
                }
            });
        });

        //++++++++ Nature Change
        $(document).on('change','#groupname',function(e){
            e.preventDefault();

            let groupCodeNature = $(this).val();
            $.ajax({
                url : '{{ route('groupnature') }}',
                type : 'post',
                data : {groupCodeNature : groupCodeNature},
                headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                dataType : 'json',
                success : function(res){
                    if(res.status === 'success')
                    {
                        let nature = res.groups;
                        let natureDropdown = $('#nature');
                        natureDropdown.empty();
                        natureDropdown.append('<option value="'+ nature.dr_cr +'">'+ nature.dr_cr + '</option>');
                    }
                }
            });
        });


    });
</script>
@include('include.footer')
