@include('include.header')
<style>
    .name{
        text-transform: capitalize;
    }
</style>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ $formurl }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Joinning Date</label>
                            <input type="text" name="joiningDate" class="form-control"  @if (!empty($agentId->joiningDate)) value="{{ date('d-m-Y',strtotime($agentId->joiningDate)) }}" @else value="{{ date('d-m-Y') }}" @endif>
                        </div>


                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Type</label>
                            <select name="userType" id="userType" class="form-select">
                                <option value="User">User</option>
                                <option value="Agent">Agent</option>
                            </select>
                            <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('userType'){{ $message }}@enderror </small>
                        </div>
                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">User Name</label>
                            <input type="text" name="user_name" id="user_name" oninput="getExitsUserName('this')" class="form-control name"   @if (!empty($agentId->user_name)) value="{{ $agentId->user_name }}" @else  value="{{ old('user_name') }}" @endif>
                            <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('user_name'){{ $message }}@enderror </small>
                            <div id="usernameDiv" style="color:white; font-size: 70%;text-transform: capitalize;"></div>
                        </div>

                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Password</label>
                            <input type="text" name="password" class="form-control name" value="{{ old('password') }}">
                            <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('password'){{ $message }}@enderror </small>
                        </div>


                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control name"  @if (!empty($agentId->name)) value="{{ $agentId->name }}" @else  value="{{ old('name') }}" @endif>
                            <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('name'){{ $message }}@enderror </small>
                        </div>

                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Code</label>
                            <input type="text" name="agentcode" readonly class="form-control name" id="agentcode"  @if (!empty($agentId->agent_code)) value="{{ $agentId->agent_code }}" @else value="{{ old('agentcode') }}" @endif >
                            <small  style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('agentcode'){{ $message }} @enderror </small>
                            <div id="agentCodematced" style="color:white; font-size: 70%;text-transform: capitalize;"></div>
                        </div>
                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Phone</label>
                            <input type="text" name="agentphoneNo" class="form-control onlynumberwithonedot" @if (!empty($agentId->phone)) value="{{ $agentId->phone }}" @else  value="{{ old('agentphoneNo') }}" @endif >
                        </div>
                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" @if (!empty($agentId->address)) value="{{ $agentId->address }}" @else  value="{{ old('address') }}" @endif >
                        </div>
                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Pan No</label>
                            <input type="text" name="agentpan" class="form-control" @if (!empty($agentId->panNo)) value="{{ $agentId->panNo }}" @else  value="{{ old('agentpan') }}" @endif >
                        </div>
                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Area Of Operation</label>
                            <input type="text" name="areaofOperation" class="form-control" @if (!empty($agentId->area_of_operation)) value="{{ $agentId->area_of_operation }}" @else  value="{{ old('areaofOperation') }}" @endif >
                        </div>
                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Comm. On Saving %</label>
                            <input type="text" name="commsaving" class="form-control" @if (!empty($agentId->commissionSaving)) value="{{ $agentId->commissionSaving }}" @else  value="0" @endif >
                        </div>
                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Comm. On FD %</label>
                            <input type="text" name="commfd" class="form-control" @if (!empty($agentId->commissionFD)) value="{{ $agentId->commissionFD }}" @else  value="0" @endif >
                        </div>
                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Comm. On RD %</label>
                            <input type="text" name="commrd" class="form-control" @if (!empty($agentId->commissionRD)) value="{{ $agentId->commissionRD }}" @else  value="0" @endif >
                        </div>

                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Comm. Loan Daily Collection %</label>
                            <input type="text" name="commloan" class="form-control" @if (!empty($agentId->commissionLoan)) value="{{ $agentId->commissionLoan }}" @else  value="0" @endif >
                        </div>
                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="">Select Status</option>
                                <option @if (!empty($agentId->status)) @if ($agentId->status == 'Active') @selected(true) @endif @else @selected(true) @endif value="Active">Active</option>
                                <option @if (!empty($agentId->status)) @if ($agentId->status == 'Inactive') @selected(true) @endif @endif value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="mt-4 col-md-1">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        @if (!empty($agentId->releavingDate))
                            <div class="mb-2 col-md-3 releavingDateDiv"  id="">
                                <label for="inputAddressname" class="form-label">Releaving Date</label>
                                <input type="text" name="releavingDate" class="form-control" @if (!empty($agentId->releavingDate)) value="{{ date('d-m-Y',strtotime($agentId->releavingDate)) }}" @else  @endif>
                            </div>
                        @else
                            <div class="mb-2 col-md-3 releavingDateDiv" style="display:none;" id="">
                                <label for="inputAddressname" class="form-label">Releaving Date</label>
                                <input type="text"  name="releavingDate" class="form-control" placeholder="DD-MM-YYYY">
                            </div>
                        @endif

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
                            <th>Joinning Date</th>
                            <th>Name</th>
                            <th>Agent Code</th>
                            <th>Phone</th>
                            <th>Pan No</th>
                            <th>Work Area</th>
                            <th>Releaving Date</th>
                            <th>Status</th>
                            <th>Edit</th>
                            {{--  <th>Delete</th>  --}}
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($agents))
                            @foreach ($agents as $agent)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ ucwords($agent->name) }}</td>
                                    <td>{{ date('d-m-Y',strtotime($agent->joiningDate)) }}</td>
                                    <td>{{ $agent->agent_code }}</td>
                                    <td>{{ $agent->phone }}</td>
                                    <td>{{ $agent->panNo }}</td>
                                    <td>{{ ucwords($agent->area_of_operation) }}</td>
                                    <td>@if(!empty($agent->releavingDate)){{ date('d-m-Y', strtotime($agent->releavingDate)) }} @else   @endif </td>
                                    <td>{{ ucwords($agent->status) }}</td>
                                    <td>
                                        @if(Session::get('adminloginid')==1)
                                         <a href="{{ url('editagent/' . $agent->id) }}"><img src="{{ url('public/admin/images/edit.png') }}"></a>
                                        @endif
                                    </td>
                                    {{--  <td><a onclick="return confirm('Are you Sure?')" href="{{ url('deleteagent/' . $agent->id) }}"><img src="{{ url('public/admin/images/delete.png') }}"></a></td>  --}}
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>

    function getExitsUserName(){
        let userName = $('#user_name').val();

        $.ajax({
            url : "{{ route('checkexitsusername') }}",
            type : 'post',
            data : {userName : userName},
            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
            dataType : 'json',
            success : function(res){
                if(res.status === 'Fail'){
                    $('#usernameDiv').append(res.messages);
                }
            }
        });
    }


    $(document).ready(function(){
        //________________ Exits Agent Code Check
        $(document).on('input','#name',function(e){
            e.preventDefault();
            let agent_name = $(this).val();

            $.ajax({
                url : "{{ route('agentCodeCheck') }}",
                type : 'post',
                data : {agent_name : agent_name},
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                dataType : 'json',
                success : function(res){
                    if(res.status === 'success'){
                        let agentCode = res.new_agent_code;
                        $('#agentcode').val(agentCode);
                    }
                }
            });
        });


        $('.releavingDateDiv').show();

        {{--  $('.releavingDateDiv').datepicker({
            dateFormat : 'dd-mm-yy',
            maxDate: new Date()
        });  --}}

        {{--  $('.date1').datepicker({
            dateFormat: 'dd-mm-yy',
            maxDate: new Date()
        }).datepicker("setDate", 'now');  --}}

        $(document).on('change','#status',function(e){
            e.preventDefault();
            let status = $(this).val();

            if(status === 'Inactive')
            {
                $('.releavingDateDiv').show();
            }else{
                {{--  $('#releavingDateDiv').hide();  --}}
            }
        });


    });
</script>
@include('include.footer')
