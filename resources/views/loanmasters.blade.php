@include('include.header')
<div class="row">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <form action="{{ $formurl }}" method="POST">
          @csrf
          <div class="row">

            <div class="mb-2 col-md-2">
              <label  class="form-label">Loan Name</label>
              <input type="text" name="loanname" class="form-control "  @if(!empty($loan_mastersid->
              loanname)) value="{{ $loan_mastersid->loanname }}" @else placeholders="Loan Name"  @endif required> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('loanname') {{$message}} @enderror </small> </div>
            <div class="mb-2 col-md-2">
              <label  class="form-label">Proc.Fee</label>
              <input type="text" name="processingFee" class="form-control onlynumberwithonedot"  @if(!empty($loan_mastersid->processingFee)) value="{{ $loan_mastersid->processingFee }}" @else placeholders="Processing Fee"  @endif required>

            </div>
            <div class="mb-2 col-md-2">
              <label  class="form-label">Interest</label>
              <input type="text" name="interest" class="form-control onlynumberwithonedot"  @if(!empty($loan_mastersid->interest)) value="{{ $loan_mastersid->interest }}" @else placeholders="Interest"  @endif required>
            </div>
             <div class="mb-2 col-md-2">
                <label  class="form-label">Panelty Type</label>
                <select  name="paneltytype"  class="form-select"   >
                      <option  value="">Panelty Type</option>
                      <option  @if (!empty($loan_mastersid->paneltytype)) @if ($loan_mastersid->paneltytype == 'percentage') @selected(true) @endif @endif  value="percentage">Percentage</option>
                      <option  @if (!empty($loan_mastersid->paneltytype)) @if ($loan_mastersid->paneltytype == 'flat') @selected(true) @endif @endif  value="flat">Flat</option>
                </select>
              </div>

            <div class="mb-2 col-md-2">
              <label  class="form-label">Penalty.Int</label>
              <input type="text" name="penaltyInterest" class="form-control onlynumberwithonedot"  @if(!empty($loan_mastersid->
              penaltyInterest)) value="{{ $loan_mastersid->penaltyInterest }}" @else placeholders="Penalty Interest"  @endif required> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('penaltyInterest') {{$message}} @enderror </small>
              </div>


                <div class="mb-2 col-md-2">
                    <label  class="form-label">Installment Type</label>
                    <select  name="insType"  class="form-select" onchange="getall(this.value)" required >
                          <option  value="">Installment Type</option>
                          {{-- <option  @if (!empty($loan_mastersid->insType)) @if ($loan_mastersid->insType == 'Daily') @selected(true) @endif @endif  value="Daily">Daily</option>
                          <option  @if (!empty($loan_mastersid->insType)) @if ($loan_mastersid->insType == 'Weekly') @selected(true) @endif @endif  value="Weekly">Weekly</option> --}}
                          <option  @if (!empty($loan_mastersid->insType)) @if ($loan_mastersid->insType == 'Monthly') @selected(true) @endif @endif  value="Monthly">Monthly</option>
                          {{-- <option  @if (!empty($loan_mastersid->insType)) @if ($loan_mastersid->insType == 'Half Yearly') @selected(true) @endif @endif  value="Half Yearly">Half Yearly</option>
                          <option  @if (!empty($loan_mastersid->insType)) @if ($loan_mastersid->insType == 'Quarterly') @selected(true) @endif @endif  value="Quarterly">Quarterly</option>
                          <option  @if (!empty($loan_mastersid->insType)) @if ($loan_mastersid->insType == 'Yearly') @selected(true) @endif @endif  value="Yearly">Yearly</option> --}}
                    </select>
                  </div>
            <div class="mb-2 col-md-1 d-none">
              <label  class="form-label">Advancement Date</label>
              <select  name="advancementDate"  class="form-select">
                <option  @if (!empty($loan_mastersid->advancementDate)) @if ($loan_mastersid->advancementDate == 'Yes') @selected(true) @endif @endif  value="Yes">Yes</option>
                <option  @if (!empty($loan_mastersid->advancementDate)) @if ($loan_mastersid->advancementDate == 'No') @selected(true) @endif @endif  value="No">No</option>
              </select>
            </div>
            <div class="mb-2 col-md-1 d-none">
              <label  class="form-label"> Recovery Date</label>
              <select  name="recoveryDate"  class="form-select">
                <option  @if (!empty($loan_mastersid->recoveryDate)) @if ($loan_mastersid->recoveryDate == 'Yes') @selected(true) @endif @endif  value="Yes">Yes</option>
                <option  @if (!empty($loan_mastersid->recoveryDate)) @if ($loan_mastersid->recoveryDate == 'No') @selected(true) @endif @endif  value="No">No</option>
              </select>
            </div>
            <div class="mb-2 col-md-2">
                <label  class="form-label">Years</label>
                <input type="text" name="years" id="years" class="form-control onlynumberwithonedot"  @if(!empty($loan_mastersid->
                years)) value="{{ $loan_mastersid->years }}" @else value="0"  @endif required> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('years') {{$message}} @enderror </small> </div>
              <div class="mb-2 col-md-2">
                <label  class="form-label">Months</label>
                <input type="text" name="months" id="months" class="form-control onlynumberwithonedot"  @if(!empty($loan_mastersid->
                months)) value="{{ $loan_mastersid->months }}" @else  value="0"   @endif required>
                </div>
              <div class="mb-2 col-md-2">
                <label  class="form-label">Days</label>
                <input type="text" name="days"  id="days" class="form-control onlynumberwithonedot"  @if(!empty($loan_mastersid->
                days)) value="{{ $loan_mastersid->days }}" @else   value="0"   @endif required> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('days') {{$message}} @enderror </small>
                </div>
            <div class="mb-2 col-md-2">
              <label for="inputAddressname" class="form-label">Status</label>
              <select  name="status" required class="form-select" required>
              <option value="">Select Status</option>
              <option  @if(!empty($loan_type_mastersid->status)) @if($loan_type_mastersid->status=='Active') @selected(true) @endif   @else @selected(true) @endif value="Active">Active</option>
              <option  @if(!empty($loan_type_mastersid->status)) @if($loan_type_mastersid->status=='Closed') @selected(true) @endif  @endif value="Closed">Closed</option>
              </select>
            </div>
          </div>
          <div class="row mt-2">
            <div class="mb-2 col-md-1">
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
              <th>Loan.Name</th>
              <th>Processing.Fee</th>
              <th>Interest</th>
              <th>Penalty.Interest</th>
              {{-- <th>Emi.Date</th> --}}
              <th>Installment.Type</th>
              <th>Status</th>
              <th>Edit</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>


          @if(!empty($loan_masters))
          @foreach ($loan_masters as $brandlist)
          <tr>
            <td>{{ $loop->iteration}}</td>
            <td>{{ $brandlist->loanname }}</td>
            <td>{{ $brandlist->processingFee}}%</td>
            <td>{{ $brandlist->interest}}%</td>
            <td>{{ $brandlist->penaltyInterest}}@if($brandlist->paneltytype=='percentage')%@else Flat @endif</td>
            {{-- <td>{{ $brandlist->emiDate}}</td> --}}
            <td>{{ $brandlist->insType}}</td>
            <td>{{ ucfirst($brandlist->status) }}</td>

            @php
                $checkloanadvancement = DB::table('member_loans')->where('loanType',$brandlist->id)->first();
            @endphp
            @if(!empty($checkloanadvancement))
                <td></td>
            @else
                <td><a href="{{ url('loanmaster/'.$brandlist->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a></td>
            @endif

            @if(!empty($checkloanadvancement))
                <td></td>
            @else
            <td><a onClick="return confirm('Are you Sure?')" href="{{ url('deleteloanmaster/'.$brandlist->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a></td>
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
    function getall(id){

        if(id=='Daily'){
            $('#years').attr('readonly', true);
            $('#years').val(0);
            $('#months').attr('readonly', true);
            $('#months').val(0);
            $('#days').removeAttr('readonly');
            $('#days').focus();
            $('#days').val(0);
        }
        if(id=='Weekly'){
            $('#years').attr('readonly', true);
            $('#years').val(0);
            $('#months').attr('readonly', true);
            $('#months').val(0);
            $('#days').removeAttr('readonly');
            $('#days').focus();
            $('#days').val(0);
        }
        if(id=='Monthly'){
            $('#years').attr('readonly', true);
            $('#years').val(0);
            $('#months').removeAttr('readonly');
            $('#months').focus();
            $('#months').val(0);
            $('#days').removeAttr('readonly');
            $('#days').val(0);
        }
        if(id=='Half Yearly'){

            $('#years').attr('readonly', true);
            $('#years').val(0);
            $('#months').removeAttr('readonly');
            $('#months').focus();
            $('#months').val(0);
            $('#days').removeAttr('readonly');
            $('#days').val(0);
        }
        if(id=='Quarterly'){

            $('#years').attr('readonly', true);
            $('#years').val(0);
            $('#months').removeAttr('readonly');
            $('#months').focus();
            $('#months').val(0);
            $('#days').removeAttr('readonly');
            $('#days').val(0);
        }
        if(id=='Yearly'){
            $('#years').removeAttr('readonly');
            $('#years').focus();
            $('#years').val(0);
            $('#months').removeAttr('readonly');

            $('#months').val(0);
            $('#days').removeAttr('readonly');
            $('#days').val(0);
        }

    }
</script>
@include('include.footer')
