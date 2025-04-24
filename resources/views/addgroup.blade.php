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
                            <input type="text" style="text-transform: capitalize;" class="form-control" name="name"
                                id="name" required
                                @if (!empty($groupsid->name)) value="{{ $groupsid->name }}" @else  value="{{ old('name') }}" @endif>
                            <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('name')
                                    {{ $message }}
                                @enderror </small>
                        </div>

                        @if (!empty($groupsid->groupCode))
                        @else
                            <div class="mb-2 col-md-3">
                                <label class="form-label">Group Code</label>
                                <input type="text" style="text-transform: capitalize;" class="form-control"
                                    name="group_code" id="group_code" required readonly>
                            </div>
                        @endif


                        <div class="mb-2 col-md-3">
                            <label class="form-label">Group Type</label>
                            <select name="type" required class="form-select" required>
                                <option value="">Select Type</option>
                                <option
                                    @if (!empty($groupsid->type)) @if ($groupsid->type == 'Liability') @selected(true) @endif
                                    @endif value="Liability">Liability</option>
                                <option
                                    @if (!empty($groupsid->type)) @if ($groupsid->type == 'Asset') @selected(true) @endif
                                    @endif value="Asset">Asset</option>
                                <option
                                    @if (!empty($groupsid->type)) @if ($groupsid->type == 'Direct Expenses') @selected(true) @endif
                                    @endif value="Direct Expenses">Direct Expenses</option>
                                <option
                                    @if (!empty($groupsid->type)) @if ($groupsid->type == 'Direct Income') @selected(true) @endif
                                    @endif value="Direct Income">Direct Incomes</option>
                                <option
                                    @if (!empty($groupsid->type)) @if ($groupsid->type == 'Indirect Expenses') @selected(true) @endif
                                    @endif value="Indirect Expenses">Inirect Expense</option>
                                <option
                                    @if (!empty($groupsid->type)) @if ($groupsid->type == 'Indirect Income') @selected(true) @endif
                                    @endif value="Indirect Income">Inirect Income</option>
                                <option
                                    @if (!empty($groupsid->type)) @if ($groupsid->type == 'Profit And Loss') @selected(true) @endif
                                    @endif value="Profit And Loss">Profit And Loss</option>
                            </select>
                        </div>

                        <div class="mb-2 col-md-3">
                            <label class="form-label">Nature(Dr/Cr)</label>
                            <select name="nature" required class="form-select" required>
                                <option value="">Select Nature</option>
                                <option
                                    @if (!empty($groupsid->dr_cr)) @if ($groupsid->dr_cr == 'Dr') @selected(true) @endif
                                    @endif value="Dr">Dr</option>
                                <option
                                    @if (!empty($groupsid->dr_cr)) @if ($groupsid->dr_cr == 'Cr') @selected(true) @endif
                                    @endif value="Cr">Cr</option>
                            </select>
                        </div>

                        {{--  <div class="mb-2 col-md-3">
                    <label class="form-label">Show in Journal Voucher</label>
                    <select  name="showJournalVoucher" required class="form-select" required>
                        <option value="">Select Type</option>
                        <option @if (!empty($groupsid->showJournalVoucher)) @if ($groupsid->showJournalVoucher == 'Yes') @selected(true) @endif   @endif value="Yes">Yes</option>
                        <option @if (!empty($groupsid->showJournalVoucher)) @if ($groupsid->showJournalVoucher == 'No') @selected(true) @endif   @endif value="No">No</option>
                    </select>
                  </div>  --}}


                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Status</label>
                            <select name="status" required class="form-select" required>
                                {{--  <option value="">Select Status</option>  --}}
                                <option
                                    @if (!empty($groupsid->status)) @if ($groupsid->status == 'active') @selected(true) @endif
                                    @endif value="active">Active</option>
                                <option
                                    @if (!empty($groupsid->status)) @if ($groupsid->status == 'inactive') @selected(true) @endif
                                    @endif value="inactive">Inactive</option>
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
                            <th>Name</th>
                            <th>Code</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($groupslist))
                            @foreach ($groupslist as $group)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ ucwords($group->name) }}</td>
                                    <td>{{ $group->groupCode }}</td>
                                    <td>{{ ucwords($group->status) }}</td>
                                    @if ($group->can_delete === 'No')
                                        <td></td>
                                        <td></td>
                                    @else
                                        <td><a href="{{ url('editgroups/' . $group->id) }}"><img src="{{ url('public/admin/images/edit.png') }}"></a></td>
                                        <td>
                                            @if (DB::table('ledger_masters')->where('groupCode', '=', $group->groupCode)->first())
                                            @else
                                                <a onclick="return confirm('Are you Sure?')" href="{{ url('deletegroups/' . $group->id) }}"><img src="{{ url('public/admin/images/delete.png') }}"></a>
                                            @endif
                                        </td>
                                    @endif
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
    //++++++++++++ Generate Group Code
    $(document).ready(function() {
        $(document).on('input', '#name', function(e) {
            e.preventDefault();
            let groupName = $(this).val();

            $.ajax({
                url: '{{ route('generateGroupCode') }}',
                type: 'post',
                data: {groupName: groupName},
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        $('#group_code').val(res.groupCode);
                    }
                }
            });
        });
    });
</script>
@include('include.footer')
