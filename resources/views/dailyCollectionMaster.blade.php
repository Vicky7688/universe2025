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
                            <label for="inputAddressname" class="form-label">Scheme Name</label>
                            <input type="text" name="scheme_name" class="form-control"  @if (!empty($sechmeId->scheme_name)) value="{{ $sechmeId->scheme_name }}" @else value="{{ old('scheme_name') }}" @endif>
                        </div>
                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Scheme Type</label>
                            <select name="scheme_type" id="scheme_type" class="form-select" required>
                                <option value="">Select Status</option>
                                <option @if (!empty($sechmeId->scheme_type)) @if ($sechmeId->scheme_type == 'Daily') @selected(true) @endif @endif value="Daily">Daily</option>
                                <option @if (!empty($sechmeId->scheme_type)) @if ($sechmeId->scheme_type == 'Monthly') @selected(true) @endif @endif value="Monthly">Monthly</option>
                                <option @if (!empty($sechmeId->scheme_type)) @if ($sechmeId->scheme_type == 'Weekly') @selected(true) @endif @endif value="Weekly">Weekly</option>
                                <option @if (!empty($sechmeId->scheme_type)) @if ($sechmeId->scheme_type == 'Yearly') @selected(true) @endif @endif value="Yearly">Yearly</option>
                            </select>
                        </div>
                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Duration</label>
                            <input type="text" name="durration" class="form-control name" id="durration"  @if (!empty($sechmeId->durration)) value="{{ $sechmeId->durration }}" @else value="{{ old('durration') }}" @endif >
                        </div>
                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Interest %</label>
                            <input type="text" name="interest" class="form-control onlynumberwithonedot" @if (!empty($sechmeId->interest)) value="{{ $sechmeId->interest }}" @else  value="{{ old('interest') }}" @endif >
                        </div>
                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Penalty %</label>
                            <input type="text" name="penalty" class="form-control" @if (!empty($sechmeId->penalty)) value="{{ $sechmeId->penalty }}" @else  value="{{ old('penalty') }}" @endif >
                        </div>

                        <div class="mb-2 col-md-3">
                            <label for="inputAddressname" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="">Select Status</option>
                                <option @if (!empty($sechmeId->status)) @if ($sechmeId->status == 'Active') @selected(true) @endif @else @selected(true) @endif value="Active">Active</option>
                                <option @if (!empty($sechmeId->status)) @if ($sechmeId->status == 'Inactive') @selected(true) @endif @endif value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="mt-4 col-md-1">
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
                            <th>Sechme Name</th>
                            <th>Sechme Type</th>
                            <th>Durration</th>
                            <th>Interest Rate</th>
                            <th>Penalty Rate</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($dailyCollection))
                            @foreach ($dailyCollection as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ ucwords($row->scheme_name) }}</td>
                                    <td>{{ $row->scheme_type }}</td>
                                    <td>{{ $row->durration }}</td>
                                    <td>{{ $row->interest }}</td>
                                    <td>{{ $row->penalty }}</td>
                                    <td>{{ ucwords($row->status) }}</td>
                                    <td><a href="{{ url('editdailyCollSechme/' . $row->id) }}"><img src="{{ url('public/admin/images/edit.png') }}"></a></td>
                                    <td><a onclick="return confirm('Are you Sure?')" href="{{ url('deletedailyCollSechme/' . $row->id) }}"><img src="{{ url('public/admin/images/delete.png') }}"></a></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@include('include.footer')
