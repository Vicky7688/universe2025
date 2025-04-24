@include('include.header')

<script src="https://www.dukelearntoprogram.com/course1/common/js/image/SimpleImage.js"></script>
<style>
    canvas {
        width: 100%;
        display: block;
        height: 200px;
        object-fit: contain;
        background: #f6f6f6;
        padding: 20px;
        border-radius: 10px;

    }

    #canv11 img {
        width: 100%;
        display: block;
        height: 200px;
        object-fit: contain;
        background: #f6f6f6;
        padding: 20px;
        border-radius: 10px;

    }

    #canv21 img {
        width: 100%;
        display: block;
        height: 200px;
        object-fit: contain;
        background: #f6f6f6;
        padding: 20px;
        border-radius: 10px;

    }

    #canv31 img {
        width: 100%;
        display: block;
        height: 200px;
        object-fit: contain;
        background: #f6f6f6;
        padding: 20px;
        border-radius: 10px;

    }

    #canv41 img {
        width: 100%;
        display: block;
        height: 200px;
        object-fit: contain;
        background: #f6f6f6;
        padding: 20px;
        border-radius: 10px;

    }
</style>

<div class="row">
    <div class="col-xl-12">

        <div class="card">
            <div class="card-body">

                {{--  <ul class="nav nav-tabs nav-bordered nav-justified">  --}}
                {{--  <li class="nav-item">
                        <a href="#home-b2" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                            <span class="d-inline-block d-sm-none"><i class="mdi mdi-home-variant"></i></span>
                            <span class="d-none d-sm-inline-block">Account Detail</span>
                        </a>
                    </li>  --}}
                {{--  <li class="nav-item">
                        <a href="#profile-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            <span class="d-inline-block d-sm-none"><i class="mdi mdi-account"></i></span>
                            <span class="d-none d-sm-inline-block">Contact Detail</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#messages-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            <span class="d-inline-block d-sm-none"><i class="mdi mdi-email-variant"></i></span>
                            <span class="d-none d-sm-inline-block">Nominee Detail</span>
                        </a>
                    </li>  --}}

                {{--  </ul>  --}}
                <form id="account_opening" action="{{ $formurl }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="tab-content">
                        <div class="tab-pane active" id="home-b2">
                            <div class="row">
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Opening Date </label>
                                    <input type="text" name="openingdate"
                                        class="form-control form-control-sm datepicker" required
                                        @if (!empty($accountopening->openingDate)) value="{{ date('d-m-Y', strtotime($accountopening->openingDate)) }}" @else value="{{ date('d-m-Y') }}" @endif>
                                </div>
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Customer Id <span style="color:#ec1b1b;">*</span></label>
                                    <input type="text" readonly name="customer_Id" id="customer_Id"
                                        class="form-control form-control-sm" required
                                        @if (!empty($accountopening->customer_Id)) value="{{ $accountopening->customer_Id }}" @else value="{{ $last_account }}" @endif>
                                    <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;">
                                        @error('agentcode')
                                            {{ $message }}
                                        @enderror </small>
                                    <div id="checkcustomer_Id"
                                        style="color:white; font-size: 70%;text-transform: capitalize;"></div>
                                </div>

                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Name <span style="color:#ec1b1b;">*</span></label>
                                    <input type="text" name="name" class="form-control form-control-sm" required
                                        @if (!empty($accountopening->name)) value="{{ $accountopening->name }}" @else value="{{ old('name') }}" @endif>
                                </div>

                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Father / Husband <span
                                            style="color:#ec1b1b;">*</span></label>
                                    <input type="text" name="father_husband" required
                                        class="form-control form-control-sm"
                                        @if (!empty($accountopening->father_husband)) value="{{ $accountopening->father_husband }}" @else value="{{ old('father_husband') }}" @endif>
                                </div>

                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select form-select-sm ">
                                        <option
                                            @if (!empty($accountopening->gender)) @if ($accountopening->gender == 'Male') @selected(true) @endif
                                            @endif value="Male">Male</option>
                                        <option
                                            @if (!empty($accountopening->gender)) @if ($accountopening->gender == 'Female') @selected(true) @endif
                                            @endif value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Adhaar No</label>
                                    <input type="text" name="adhaar_no" class="form-control form-control-sm"
                                        @if (!empty($accountopening->adhaar_no)) value="{{ $accountopening->adhaar_no }}" @else value="{{ old('adhaar_no') }}" @endif>
                                </div>

                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Pan Number</label>
                                    <input type="text" name="pan_number" class="form-control form-control-sm"
                                        @if (!empty($accountopening->pan_number)) value="{{ $accountopening->pan_number }}"  @else value="{{ old('pan_number') }}" @endif>
                                </div>
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control form-control-sm"
                                        @if (!empty($accountopening->email)) value="{{ $accountopening->email }}" @else value="{{ old('email') }}" @endif>
                                </div>


                                <div class="mb-2 col-md-3">
                                    <label for="inputAddressname" class="form-label">State</label>
                                    <select name="stateId" class="form-select form-select-sm"
                                        onchange="getDistrict(this)">
                                        <option value="">Select State</option>
                                        @foreach ($state_masters as $state)
                                            <option
                                                @if (!empty($accountopening->stateId)) @if ($accountopening->stateId == $state->id) @selected(true) @endif
                                                @endif
                                                value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-2 col-md-3">
                                    <label for="inputAddressname" class="form-label">District</label>
                                    <select name="districtId" class="form-select form-select-sm"
                                        onchange="getTehsil(this)">
                                        <option value="">Select District</option>
                                        @if (!empty($district))
                                            @foreach ($district as $districts)
                                                <option
                                                    @if (!empty($accountopening->districtId)) @if ($accountopening->districtId == $districts->id) @selected(true) @endif
                                                    @endif
                                                    value="{{ $districts->id }}">{{ $districts->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="mb-2 col-md-3">
                                    <label for="inputAddressname" class="form-label">Tehsil</label>
                                    <select name="tehsilId" class="form-select form-select-sm"
                                        onchange="getPostoffice(this)">
                                        <option value="">Select Tehsil</option>
                                        @if (!empty($tehsil_masters))
                                            @foreach ($tehsil_masters as $tehsil_masterss)
                                                <option
                                                    @if (!empty($accountopening->tehsilId)) @if ($accountopening->tehsilId == $tehsil_masterss->id) @selected(true) @endif
                                                    @endif
                                                    value="{{ $tehsil_masterss->id }}">{{ $tehsil_masterss->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="mb-2 col-md-3">
                                    <label for="inputAddressname" class="form-label">Post Office Name</label>
                                    <select name="postOfficeId" class="form-select form-select-sm"
                                        onchange="getVillage(this)">
                                        <option value="">Select Post Office</option>
                                        @if (!empty($post_office_masters))
                                            @foreach ($post_office_masters as $post)
                                                <option
                                                    @if (!empty($accountopening->id)) @if ($accountopening->postOfficeId == $post->id) @selected(true) @endif
                                                    @endif
                                                    value="{{ $post->id }}">{{ $post->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="mb-2 col-md-3">
                                    <label for="inputAddressname" class="form-label">Village</label>
                                    <select name="accountopening" class="form-select form-select-sm">
                                        <option value="">Select Post Office</option>
                                        @if (!empty($post_office_masters))
                                            @foreach ($post_office_masters as $post)
                                                <option
                                                    @if (!empty($accountopening->id)) @if ($accountopening->postOfficeId == $post->id) @selected(true) @endif
                                                    @endif
                                                    value="{{ $post->id }}">{{ $post->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="mb-2 col-md-9">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="address" class="form-control form-control-sm"
                                        @if (!empty($accountopening->address)) value="{{ $accountopening->address }}" @else value="{{ old('address') }}" @endif>
                                </div>


                                <div class="mb-2 col-md-3">
                                    <label class="form-label">House</label>
                                    <select class="form-select form-select-sm" name="houseType" id="houseType">
                                        <option value="">Select Type</option>
                                        <option
                                            @if (!empty($accountopening->houseType)) @if ($accountopening->houseType == 'rent') @selected(true) @endif
                                            @endif value="rent">Rented</option>
                                        <option
                                            @if (!empty($accountopening->houseType)) @if ($accountopening->houseType == 'permanent') @selected(true) @endif
                                            @endif value="permanent">Parmanent</option>
                                    </select>
                                </div>
                                <div class="mb-2 col-md-9">
                                    <label class="form-label">Landmark / Near By</label>
                                    <input type="text" name="landmark" class="form-control form-control-sm"
                                        @if (!empty($accountopening->landmark)) value="{{ $accountopening->landmark }}" @else value="{{ old('landmark') }}" @endif>
                                </div>
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Mobile Ist No <span
                                            style="color:#ec1b1b;">*</span></label>
                                    <input type="text" name="mobile_first" required
                                        class="form-control form-control-sm"
                                        @if (!empty($accountopening->mobile_first)) value="{{ $accountopening->mobile_first }}"  @else value="{{ old('mobile_first') }}" @endif>
                                </div>
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Mobile 2nd No</label>
                                    <input type="text" name="mobile_second" class="form-control form-control-sm"
                                        @if (!empty($accountopening->mobile_second)) value="{{ $accountopening->mobile_second }}"  @else value="{{ old('mobile_second') }}" @endif>
                                </div>

                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Work Place</label>
                                    <input type="text" name="work_place" class="form-control form-control-sm"
                                        @if (!empty($accountopening->work_place)) value="{{ $accountopening->work_place }}" @else value="{{ old('work_place') }}" @endif>
                                </div>

                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Relation</label>
                                    <select class="form-select form-select-sm" name="relationship" id="relationship">
                                        <option value="">Select Relationship</option>
                                        <option
                                            @if (!empty($accountopening->relationship)) @if ($accountopening->relationship == 'Father') @selected(true) @endif
                                            @endif value="Father">Father</option>
                                        <option
                                            @if (!empty($accountopening->relationship)) @if ($accountopening->relationship == 'Mother') @selected(true) @endif
                                            @endif value="Mother">Mother</option>
                                        <option
                                            @if (!empty($accountopening->relationship)) @if ($accountopening->relationship == 'Brother') @selected(true) @endif
                                            @endif value="Brother">Brother</option>
                                        <option
                                            @if (!empty($accountopening->relationship)) @if ($accountopening->relationship == 'Sister') @selected(true) @endif
                                            @endif value="Sister">Sister</option>
                                        <option
                                            @if (!empty($accountopening->relationship)) @if ($accountopening->relationship == 'Husband') @selected(true) @endif
                                            @endif value="Husband">Husband</option>
                                        <option
                                            @if (!empty($accountopening->relationship)) @if ($accountopening->relationship == 'Wife') @selected(true) @endif
                                            @endif value="Wife">Wife</option>
                                        <option
                                            @if (!empty($accountopening->relationship)) @if ($accountopening->relationship == 'Son') @selected(true) @endif
                                            @endif value="Son">Son</option>
                                        <option
                                            @if (!empty($accountopening->relationship)) @if ($accountopening->relationship == 'Daughter') @selected(true) @endif
                                            @endif value="Daughter">Daughter</option>
                                        <option
                                            @if (!empty($accountopening->relationship)) @if ($accountopening->relationship == 'Uncle') @selected(true) @endif
                                            @endif value="Uncle">Uncle</option>
                                        <option
                                            @if (!empty($accountopening->relationship)) @if ($accountopening->relationship == 'Aunt') @selected(true) @endif
                                            @endif value="Aunt">Aunt</option>
                                        <option
                                            @if (!empty($accountopening->relationship)) @if ($accountopening->relationship == 'Nephew') @selected(true) @endif
                                            @endif value="Nephew">Nephew</option>
                                        <option
                                            @if (!empty($accountopening->relationship)) @if ($accountopening->relationship == 'Niece') @selected(true) @endif
                                            @endif value="Niece">Niece</option>
                                        <option
                                            @if (!empty($accountopening->relationship)) @if ($accountopening->relationship == 'Grandfather') @selected(true) @endif
                                            @endif value="Grandfather">Grandfather</option>
                                        <option
                                            @if (!empty($accountopening->relationship)) @if ($accountopening->relationship == 'Grandmother') @selected(true) @endif
                                            @endif value="Grandmother">Grandmother</option>
                                        <option
                                            @if (!empty($accountopening->relationship)) @if ($accountopening->relationship == 'Cousin') @selected(true) @endif
                                            @endif value="Cousin">Cousin</option>
                                        <option
                                            @if (!empty($accountopening->relationship)) @if ($accountopening->relationship == 'Friend') @selected(true) @endif
                                            @endif value="Friend">Friend</option>
                                        <option
                                            @if (!empty($accountopening->relationship)) @if ($accountopening->relationship == 'Guardian') @selected(true) @endif
                                            @endif value="Guardian">Guardian</option>
                                    </select>
                                </div>
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Relative Mobile No</label>
                                    <input type="text" name="relative_mobile_no"
                                        class="form-control form-control-sm"
                                        @if (!empty($accountopening->relative_mobile_no)) value="{{ $accountopening->relative_mobile_no }}" @else value="{{ old('relative_mobile_no') }}" @endif>
                                </div>
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">First Guarantor Name</label>
                                    <input type="text" name="guarantor_first" class="form-control form-control-sm"
                                        @if (!empty($accountopening->guarantor_first)) value="{{ $accountopening->guarantor_first }}" @else value="{{ old('guarantor_first') }}" @endif>
                                </div>
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">First Guarantor Mobile</label>
                                    <input type="text" name="first_guarantor_mobile"
                                        class="form-control form-control-sm"
                                        @if (!empty($accountopening->first_guarantor_mobile)) value="{{ $accountopening->first_guarantor_mobile }}"  @else value="{{ old('first_guarantor_mobile') }}" @endif>
                                </div>
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Second Guarantor Name</label>
                                    <input type="text" name="guarantor_second"
                                        class="form-control form-control-sm"
                                        @if (!empty($accountopening->guarantor_second)) value="{{ $accountopening->guarantor_second }}"  @else value="{{ old('guarantor_second') }}" @endif>
                                </div>
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Second Guarantor Mobile</label>
                                    <input type="text" name="second_guarantor_mobile"
                                        class="form-control form-control-sm"
                                        @if (!empty($accountopening->second_guarantor_mobile)) value="{{ $accountopening->second_guarantor_mobile }}"  @else value="{{ old('second_guarantor_mobile') }}" @endif>
                                </div>
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Opening bal</label>
                                    <input type="text" name="openingbal" class="form-control form-control-sm"
                                        @if (!empty($accountopening->openingbal)) value="{{ $accountopening->openingbal }}" @else value="{{ old('openingbal') }}" @endif>
                                </div>
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Loan Limit</label>
                                    <input type="text" name="loan_limit" class="form-control form-control-sm"
                                        @if (!empty($accountopening->loan_limit)) value="{{ $accountopening->loan_limit }}" @else value="{{ old('loan_limit') }}" @endif>
                                </div>
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Cibil Score</label>
                                    <input type="text" name="cibilscore" class="form-control form-control-sm"
                                        @if (!empty($accountopening->cibilscore)) value="{{ $accountopening->cibilscore }}" @else value="{{ old('cibilscore') }}" @endif>
                                </div>
                                <div class="mb-2 col-md-3">
                                    <label class="form-label">Work</label>
                                    <input type="text" name="worked" class="form-control form-control-sm"
                                        @if (!empty($accountopening->worked)) value="{{ $accountopening->worked }}" @else value="{{ old('worked') }}" @endif>
                                </div>

                                {{-- <div class="mb-2 col-md-3">
                                    <label class="form-label">Agent</label>
                                    <select name="agents" class="form-select form-select-sm">
                                        @if (!empty($agents))
                                            @foreach ($agents as $row)
                                                <option  @if (!empty($accountopening->agentId))  @if ($accountopening->agentId == $row->agent_code) @selected(true) @endif @endif   value="{{ $row->agent_code }}">{{ $row->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div> --}}

                                <div class="mb-2 col-md-3">
                                    <label for="">Customer Image</label>
                                    <div>
                                        <input type="file" class="form-control"
                                            name="customerImage" accept="image/*" id="finput1"
                                            onchange="upload('canv1','finput1')" @error('customerImage') is-invalid @enderror">
                                        <br>

                                        @error('customerImage')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                        @if (!empty($accountopening->customerInput))
                                            <div style="width:100%" id="canv11">
                                                <img style="width:100%;height: 200px;"
                                                    src="{{ url('storage/app/' . $accountopening->customerInput) }}" alt="">
                                            </div>
                                        @endif

                                        <canvas id="canv1" style="display:none"></canvas>
                                    </div>
                                </div>


                                <div class="mb-2 col-md-3">
                                    <label for="">Id Proof</label>
                                    <div>
                                        {{-- <img id="idProofImage" src="http://placehold.it/180" alt="your image" style="cursor: pointer;" class="avatar-xl"/><br>
                                            <input type='file' name="idProofImage" id="idProofImageInput" onchange="handleidProofImageInput(this)" style="display: none;"/> --}}

                                        <input type="file" class="form-control" multiple="false"
                                            name="idProofImage" accept="image/*" id="finput2"
                                            onchange="upload('canv2','finput2')" @error('idProofImageInput') is-invalid @enderror"><br>

                                            @error('idProofImageInput')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        @if (!empty($accountopening->idProofImageInput))
                                            <div style="width:100%" id= "canv21"> <img
                                                    style="width:100%;height: 200px;"
                                                    src="{{ url('storage/app/' . $accountopening->idProofImageInput) }}"
                                                    alt=""></div>
                                        @endif
                                        <canvas id= "canv2" style="display:none"></canvas>

                                    </div>
                                </div>



                                <div class="mb-2 col-md-3">
                                    <label for="">First Guarantor Image</label>
                                    <div>
                                        {{-- <img id="firstguarantorImage" src="http://placehold.it/180" alt="your image" style="cursor: pointer;" class="avatar-xl"/><br>
                                            <input type='file' name="firstguarantorImage" id="firstguarantorImageInput" onchange="handleFirstGuarantorImageInput(this)" style="display: none;"/> --}}


                                        <input type="file" class="form-control" multiple="false"
                                            name="firstguarantorImage" accept="image/*" id="finput3"
                                            onchange="upload('canv3','finput3')" @error('firstguarantorImageInput') is-invalid @enderror"><br>
                                            @error('firstguarantorImageInput')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        @if (!empty($accountopening->firstguarantorImageInput))
                                            <div style="width:100%" id= "canv31"> <img
                                                    style="width:100%;height: 200px;"
                                                    src="{{ url('storage/app/' . $accountopening->firstguarantorImageInput) }}"
                                                    alt=""></div>
                                        @endif
                                        <canvas id= "canv3" style=" display:none "></canvas>

                                    </div>
                                </div>


                                <div class="mb-2 col-md-3">
                                    <label for="">Second Guarantor Image</label>
                                    <div>
                                        {{-- <img id="secondguarantorImage" src="http://placehold.it/180" alt="your image" style="cursor: pointer;" class="avatar-xl"/><br>
                                            <input type='file' name="secondguarantorImage" id="secondguarantorImageInput" onchange="handleSecondGuarantorImageInput(this)" style="display: none;"/> --}}

                                        <input type="file" class="form-control" multiple="false"
                                            name="secondguarantorImage" accept="image/*" id="finput4"
                                            onchange="upload('canv4','finput4')" @error('secondguarantorImageInput') is-invalid @enderror"><br>
                                             @error('secondguarantorImageInput')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        @if (!empty($accountopening->secondguarantorImageInput))
                                            <div style="width:100%" id= "canv41"> <img
                                                    style="width:100%;height: 200px;"
                                                    src="{{ url('storage/app/' . $accountopening->secondguarantorImageInput) }}"
                                                    alt=""></div>

                                        @endif
                                        <canvas id= "canv4" style="  display:none "></canvas>

                                    </div>
                                </div>



                            </div>
                        </div>

                        <div class="modal-footer me-0">
                            <button id="submitButton"
                                class="btn btn-primary waves-effect waves-light reportSmallBtnCustom ms-2 me-0"
                                type="submit"
                                data-loading-text=" <span class='spinner-border me-1' role='status' aria-hidden='true'></span>
                                Loading...">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function upload(name, namee) {

        $('#' + name + 1).css('display', 'none')
        $('#' + name).css('display', 'block')
        var imgcanvas = document.getElementById(name);
        var fileinput = document.getElementById(namee);
        var image = new SimpleImage(fileinput);

        image.drawTo(imgcanvas);
    }





    //+++++++ check Exiting Customer Id
    $(document).on('input', '#customer_Id', function(e) {
        e.preventDefault();
        let customer_Id = $(this).val();

        $.ajax({
            url: "{{ route('checkCustomerId') }}",
            type: 'post',
            data: {
                customer_Id: customer_Id
            },
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'fail') {
                    let erros = $('#checkcustomer_Id');
                    erros.append(toastr.success(res.messages));
                }
            }
        });
    });





</script>
{{--
<script>
 // Customer Image
    document.getElementById('customer_image').onclick = function() {
        document.getElementById('customerInput').click();
    };

    function customerInputImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('customer_image').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Customer ID Proof Image
    document.getElementById('idProofImage').onclick = function() {
        document.getElementById('idProofImageInput').click();
    };

    function handleidProofImageInput(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('idProofImage').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // First Guarantor Image
    document.getElementById('firstguarantorImage').onclick = function() {
        document.getElementById('firstguarantorImageInput').click();
    };

    function handleFirstGuarantorImageInput(input) { // Changed function name
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('firstguarantorImage').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }



    // Second Guarantor Image
    document.getElementById('secondguarantorImage').onclick = function() {
        document.getElementById('secondguarantorImageInput').click();
    };

    function handleSecondGuarantorImageInput(input) { // Renamed function
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('secondguarantorImage').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }






    $(document).ready(function(){



        $('#account_opening').valdate({
            rules :{
                adhaar_no : {
                    maxlength : 12,
                    digits : true
                },
                email : {
                    email : true
                },
                mobile_first :{
                    minlength : 10,
                    maxlength : 10,
                    digits : true
                },
                mobile_second : {
                    minlength : 10,
                    maxlength : 10,
                    digits : true
                }
                relative_mobile_no : {
                    minlength : 10,
                    maxlength : 10,
                    digits : true
                },
                first_guarantor_mobile{
                    minlength : 10,
                    maxlength : 10,
                    digits : true
                },
                second_guarantor_mobile :{
                    minlength : 10,
                    maxlength : 10,
                    digits : true
                },
                loan_limit : {
                    digits : true
                }
            }

        });



    });
</script> --}}

{{-- <script>
      @if (empty($accountopening->customer_Id))
    function generateCustomerNumber() {
        let customer_Id = $('#customer_Id').val();
        $.ajax({
            url: "{{ route('generate-customer-number') }}",
            type: 'post',
            data: { customer_Id: customer_Id },
            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    let new_account = res.new_account;
                    $('#customer_Id').val(new_account);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error);
                alert("An error occurred while generating the customer number.");
            }
        });
    }

    generateCustomerNumber();
@endif --}}
</script>
@include('include.footer')
