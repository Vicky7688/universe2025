@include('include.header')
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<style>
    .header-title{
	background-color: #0c4a6e;
	padding: 8px;
	color: #fff;
	margin-bottom: 11px;

    }
</style>
    <form action="{{ $formurl }}" method="POST">
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-body">
              <h4 class="header-title">Item Detail</h4>
              @csrf
              <div class="row">
                <div class="mb-2 col-md-3">
                  <label  class="form-label">Brand</label>
                  <select id="selectize-optgroup-brand" name="brand"  placeholder="Select Brand" class="brand-dropdown">
                    <option value="">Select Brand</option>

                    @if (sizeof($brandlist) > 0)
                    @foreach ($brandlist as $brandlistque)

                    <option
                    @if (!empty($itemsid->brand)) @if ($itemsid->brand == $brandlistque->id) @selected(true) @endif
                    @endif
                    value="{{ $brandlistque->id }}">{{ $brandlistque->name }}</option>

                    @endforeach
                    @endif

                  </select>
                </div>
                <div class="mb-2 col-md-3">
                  <label  class="form-label">Category</label>
                  <select id="selectize-optgroup-category" name="category"  placeholder="Select Category" class="category-dropdown">
                    <option value="">Select Category</option>

                    @if (!empty($categorylist))
                    @foreach ($categorylist as $categorylistque)

                    <option
                         @if (!empty($itemsid->category)) @if ($itemsid->category == $categorylistque->id) @selected(true) @endif
                    @endif
                    value="{{ $categorylistque->id }}">{{ $categorylistque->name }}</option>

                        @endforeach
                        @endif


                  </select>
                </div>
                <div class="mb-2 col-md-3">
                  <label  class="form-label">Sub Category</label>
                  <select id="selectize-optgroup-subcategory" name="subcategory"  placeholder="Select subcategory" class="subcategory-dropdown">
                    <option value="">Select SubCategory</option>

                    @if (!empty($subcategorylist))
                    @foreach ($subcategorylist as $subcategorylistque)

                    <option
                    @if (!empty($itemsid->subcategory)) @if ($itemsid->subcategory == $subcategorylistque->id) @selected(true) @endif
                    @endif
                    value="{{ $subcategorylistque->id }}">{{ $subcategorylistque->name }} </option>

                    @endforeach
                    @endif


                  </select>
                </div>
                <div class="mb-2 col-md-3">
                  <label  class="form-label">Sub Child Category</label>
                  <select id="selectize-optgroup-subchildcategory" name="subchildcategory"  placeholder="Select Sub Child Category" class="subchildcategory-dropdown">
                    <option value="">Select Child SubCategory</option>

                    @if (!empty($subchildcategorylist))
                    @foreach ($subchildcategorylist as $subchildcategorylistque)

                    <option
                    @if (!empty($itemsid->subchildcategory)) @if ($itemsid->subchildcategory == $subchildcategorylistque->id) @selected(true) @endif
                    @endif
                    value="{{ $subchildcategorylistque->id }}">{{ $subchildcategorylistque->name }} </option>

                    @endforeach
                    @endif


                  </select>
                </div>
                <div class="mb-2 col-md-3">
                  <label class="form-label">Item Name</label>
                  <input type="text" class="form-control" name="name" required @if(!empty($itemsid->
                  name)) value="{{ $itemsid->name }}" @else value="{{ old('name') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('name') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label class="form-label">Item Print Name</label>
                  <input type="text" class="form-control" name="pname" required @if(!empty($itemsid->
                  pname)) value="{{ $itemsid->pname }}" @else value="{{ old('pname') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('pname') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label class="form-label">Item Code</label>
                  <div class="input-group">
                    <input type="text" class="form-control" name="itemcode" id="itemcode" required @if(!empty($itemsid->
                    itemcode)) value="{{ $itemsid->itemcode }}" @else value="{{ old('itemcode') }}" @endif>
                    <button class="btn btn-dark waves-effect waves-light" type="button" onclick="createcode()"><i class='bx bx-qr-scan'></i></button>
                  </div>
                  <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('itemcode') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label class="form-label">HSN</label>
                  <input type="text" class="form-control" name="hsn" required @if(!empty($itemsid->
                  hsn)) value="{{ $itemsid->hsn }}" @else value="{{ old('hsn') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('hsn') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label class="form-label">Reorder level</label>
                  <input type="text" class="form-control" name="reorderlable" required @if(!empty($itemsid->
                  reorderlable)) value="{{ $itemsid->reorderlable }}" @else value="{{ old('reorderlable') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('reorderlable') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label  class="form-label">Unit</label>
                  <select id="selectize-optgroup-Unit" name="unit"  placeholder="Select unit" class="unit-dropdown" onchange="showSelectedUnit(this)">
                    <option value="">Select Unit</option>

                        @foreach ($units as $unitsque)

                    <option  @if (!empty($itemsid->unit)) @if ($itemsid->unit == $unitsque->id)     @selected(true) @endif  @endif value="{{ $unitsque->id }}">{{ $unitsque->name }} </option>

                        @endforeach


                  </select>
                </div>
                <div class="mb-2 col-md-3">
                  <label class="form-label">Pieces in <span class="uvlax">Box</span></label>
                  <input type="text" class="form-control" name="unitquantity" required @if(!empty($itemsid->
                  unitquantity)) value="{{ $itemsid->unitquantity }}" @else value="{{ old('unitquantity') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('unitquantity') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label class="form-label">Image</label>
                  <input type="file" name="image"  class="form-control"  @if(!empty($itemsid->
                  image)) @else required @endif> </div>
                <div class="mb-2 col-md-3">
                  <label for="inputAddressname" class="form-label">Status</label>
                  <select  name="status" required class="form-select" required>
                  <option value="">Select Status</option>
                  <option  @if(!empty($itemsid->status)) @if($itemsid->status=='active') @selected(true) @endif   @else @selected(true) @endif value="active">Active</option>
                  <option  @if(!empty($itemsid->status)) @if($itemsid->status=='inactive') @selected(true) @endif  @endif value="inactive">Inactive</option>
                  </select>
                </div>
              </div>
              <h4 class="header-title mt-3">Opening Stock Details</h4>
              <div class="row">
                <div class="mb-2 col-md-3">
                  <label class="form-label">Opening Stock(<span class="uvlax">Box</span>)</label>
                  <input type="text" class="form-control" name="openingstock" required @if(!empty($itemsid->
                  openingstock)) value="{{ $itemsid->openingstock }}" @else value="{{ old('openingstock') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('openingstock') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label class="form-label">Opening Stock(Amount in <span class="uvlax">Box</span>)</label>
                  <input type="text" class="form-control" name="openingstock_amount" required @if(!empty($itemsid->
                  openingstock_amount)) value="{{ $itemsid->openingstock_amount }}" @else value="{{ old('openingstock_amount') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('openingstock_amount') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label class="form-label">Opening Stock(Pieces)</label>
                  <input type="text" class="form-control" name="singleopeningstock" required @if(!empty($itemsid->
                  singleopeningstock)) value="{{ $itemsid->singleopeningstock }}" @else value="{{ old('singleopeningstock') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('singleopeningstock') {{$message}} @enderror </small> </div>
                <div class="mb-2 col-md-3">
                  <label class="form-label">Opening Stock(Amount in Pieces)</label>
                  <input type="text" class="form-control" name="op_stock_pc_amount" required @if(!empty($itemsid->
                  op_stock_pc_amount)) value="{{ $itemsid->op_stock_pc_amount }}" @else value="{{ old('op_stock_pc_amount') }}" @endif> <small style="font-size: 70%; color: #ec1b1b; text-transform: capitalize;"> @error('op_stock_pc_amount') {{$message}} @enderror </small> </div>
              </div>
              <h4 class="header-title mt-3">Customer Prices</h4>
              <div class="row" style="padding: 11px;" >
                <table class="order-listt" style="width: 100%">
                  <thead>
                    <tr>
                      <td style="width: 182px;">Select Customers</td>
                      <td>MRP (<span class="uvlax">Box</span>)</td>
                      <td>Sale Rate (<span class="uvlax">Box</span>)</td>
                      <td>Purchase Rate (<span class="uvlax">Box</span>)</td>
                      <td>MRP(single)</td>
                      <td>Sale Rate(single)</td>
                      <td>Purchase Rate(single)</td>
                      <td>Discount</td>
                      <td>Barcode.No</td>
                      <td>B.Image</td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>

                  @if (!empty($itemrates))
                  @php $countercount=count($itemrates) @endphp
                  @foreach ($itemrates as $itemrateslist)
                  <tr>
                    <td><select id="selectize-optgroup-brands" name="customer[]"  placeholder="Select Customers" class="sup-dropdown">

                        @if(!empty($retails))
                        <option value="">Select Customer</option>
                        @foreach ($retails as $retailslist)
                        <option @if(!empty($itemrateslist->customer)) @if($itemrateslist->customer==$retailslist->id) @selected(true) @endif @endif value="{{ $retailslist->id }}">{{ ucfirst($retailslist->name) }}</option>
                        @endforeach
                        @endif

                      </select>
                    </td>
                    <td><input type="text" @if (!empty($itemrateslist->
                      mrp)) value="{{ $itemrateslist->mrp }}" @endif name="mrp[]" class="form-control" /></td>
                    <td><input type="text" @if (!empty($itemrateslist->
                      salerate)) value="{{ $itemrateslist->salerate }}" @endif name="salerate[]" class="form-control" /></td>
                    <td><input type="text" @if (!empty($itemrateslist->
                      purchaserate)) value="{{ $itemrateslist->purchaserate }}" @endif name="purchaserate[]" class="form-control" /></td>
                    <td><input type="text" @if (!empty($itemrateslist->
                      mrpsingle)) value="{{ $itemrateslist->mrpsingle }}" @endif name="mrpsingle[]" class="form-control" /></td>
                    <td><input type="text" @if (!empty($itemrateslist->
                      saleratesingle)) value="{{ $itemrateslist->saleratesingle }}" @endif name="saleratesingle[]" class="form-control" /></td>
                    <td><input type="text" @if (!empty($itemrateslist->
                      purchaseratesingle)) value="{{ $itemrateslist->purchaseratesingle }}" @endif name="purchaseratesingle[]" class="form-control" /></td>
                    <td><input type="text" @if (!empty($itemrateslist->
                      discount)) value="{{ $itemrateslist->discount }}" @endif name="discount[]" class="form-control" /></td>
                    <td><input oninput="getbarcode(this.value,{{ $loop->iteration }})" type="text" @if (!empty($itemrateslist->
                      barcodenumber)) value="{{ $itemrateslist->barcodenumber }}" @endif name="barcodenumber[]" class="form-control" /></td>
                    <td><input type="hidden" name="barcodename[]" id="barcodename{{ $loop->iteration }}" @if (!empty($itemrateslist->
                      barcodename)) value="{{ $itemrateslist->barcodename }}" @else value="{{ old('barcodename') }}" @endif>
                      @if (!empty($itemrateslist->barcodeimage)) <img id="barcode{{ $loop->iteration }}" src="{{ url('') }}/{{ $itemrateslist->barcodeimage }}" style="width: 100%"> @else <img id="barcode{{ $loop->iteration }}" src=""
style="width: 100%"> @endif </td>
                    <td><a class="ibtnDel "> <img src="{{ url('public/admin/images/delete.png') }}"
    alt="img"> </a> </td>
                  </tr>
                  @endforeach
                  @else
                  @php $countercount=1 @endphp
                  <tr>
                    <td><select id="selectize-optgroup-brands" name="customer[]"  placeholder="Select Customers" class="sup-dropdown">

        @if(!empty($retails))

                        <option value="">Select Customer</option>

        @foreach ($retails as $retailslist)

                        <option value="{{ $retailslist->id }}">{{ ucfirst($retailslist->name) }}</option>

        @endforeach

        @endif

                      </select>
                    </td>
                    <td><input type="text" name="mrp[]" class="form-control" /></td>
                    <td><input type="text" name="salerate[]" class="form-control" /></td>
                    <td><input type="text" name="purchaserate[]" class="form-control" />
                    </td>
                    <td><input type="text" name="mrpsingle[]" class="form-control" /></td>
                    <td><input type="text" name="saleratesingle[]" class="form-control" />
                    </td>
                    <td><input type="text" name="purchaseratesingle[]"  class="form-control" /></td>
                    <td><input type="text" name="discount[]"  class="form-control" /></td>
                    <td><input type="text" oninput="getbarcode(this.value,0)" name="barcodenumber[]" class="form-control" /></td>
                    <td><input type="hidden" name="barcodename[]" id="barcodename0">
                      <img id="barcode0" src="" style="width: 100%"> </td>
                    <td><a class="ibtnDel "> <img
                                                            src="{{ url('public/admin/images/delete.png') }}"
                                                            alt="img"> </a></td>
                  </tr>
                  @endif
                  </tbody>

                  <tfoot>
                    <tr>
                      <td colspan="8" style="text-align: left;"><button type="button" id="addrow" class="btn btn-success mt-2">Add More</button></td>
                    </tr>
                    <tr> </tr>
                  </tfoot>
                </table>
              </div>
              <h4 class="header-title mt-3">Discounts</h4>
              <div class="dynamic-rows-container">
                @if (!empty($discountsis))
                @php $discountcount=count($discountsis) @endphp
                @foreach ($discountsis as $discountsislist)
                    <div class="row">
                        <div class="mb-2 col-md-3">
                        <label class="form-label">Discount On</label>
                        <select name="discounttype[]" id="" class="form-control">
                            <option value="">Select type</option>
                            <option @if($discountsislist->discounttype=='box') @selected(true) @endif value="box"><span class="uvlax">Box</span></option>
                            <option @if($discountsislist->discounttype=='piece') @selected(true)  @endif value="piece">Pieces</option>
                        </select>
                        </div>
                        <div class="mb-2 col-md-3">
                        <label class="form-label">Quantity From</label>
                        <input type="text" class="form-control" name="qtfrom[]" value="{{ $discountsislist->qtfrom }}" required>
                        </div>
                        <div class="mb-2 col-md-3">
                        <label class="form-label">Quantity To</label>
                        <input type="text" class="form-control" name="qtto[]" value="{{ $discountsislist->qtto }}" required>
                        </div>
                        <div class="mb-2 col-md-1">
                        <label class="form-label">Discount(in%)</label>
                        <input type="text" class="form-control" name="price[]" value="{{ $discountsislist->price }}" required>
                        </div>

                        <div class="mb-2 col-md-1">
                            <label class="form-label">Delete</label>
                            <button class="btn btn-danger delete-row" type="button">Delete</button>
                    </div>
                    </div>
            @endforeach
            @else


            <div class="row">
                <div class="mb-2 col-md-3">
                <label class="form-label">Discount On</label>
                <select name="discounttype[]" id="" class="form-control">
                    <option value="">Select type</option>
                    <option value="box"><span class="uvlax">Box</span></option>
                    <option value="piece">Pieces</option>
                </select>
                </div>
                <div class="mb-2 col-md-3">
                <label class="form-label">Quantity From</label>
                <input type="text" class="form-control" name="qtfrom[]"  required>
                </div>
                <div class="mb-2 col-md-3">
                <label class="form-label">Quantity To</label>
                <input type="text" class="form-control" name="qtto[]"  required>
                </div>
                <div class="mb-2 col-md-1">
                <label class="form-label">Discount(in%)</label>
                <input type="text" class="form-control" name="price[]"   required>
                </div>

                <div class="mb-2 col-md-1">
                    <label class="form-label">Delete</label>
                    <button class="btn btn-danger delete-row" type="button">Delete</button>
            </div>
            </div>

            @endif
              </div>


              <button type="button" id="addRowBtn" class="btn btn-success mt-2">Add More</button>




              <h4 class="header-title mt-3">Goods & Service Tax on SALE</h4>

<div class="row">
<div class="mb-2 col-md-3">
<label class="form-label">SGST Code</label>
<input type="text" class="form-control" name="sgstcode" id="sgstcode" required oninput="gettaxr(this.value)"   @if (!empty($itemsid->sgstcode)) value="{{ $itemsid->sgstcode }}" @else value="{{ old('sgstcode') }}" @endif>
<div id="accountList" class="accountList"></div>
</div>
<div class="mb-2 col-md-3">
<label class="form-label">SGST(%)</label>
<input type="text"  class="form-control"  name="salesgstamount" id="sgst" required  @if (!empty($itemsid->salesgstamount)) value="{{ $itemsid->salesgstamount }}" @else value="{{ old('salesgstamount') }}" @endif>
</div>
<div class="mb-2 col-md-3">
<label class="form-label">CGST(%)</label>
<input type="text"  class="form-control" name="salecgstamount" id="cgst" required  @if (!empty($itemsid->salecgstamount)) value="{{ $itemsid->salecgstamount }}" @else value="{{ old('salecgstamount') }}" @endif>
</div>
<div class="mb-2 col-md-3">
<label class="form-label">IGST(%)</label>
<input type="text"  class="form-control" name="saleigstamount" id="igst" required  @if (!empty($itemsid->saleigstamount)) value="{{ $itemsid->saleigstamount }}" @else value="{{ old('saleigstamount') }}" @endif>
</div>

</div>






              <h4 class="header-title mt-3">Goods & Service Tax on PURCHASE</h4>

<div class="row">
<div class="mb-2 col-md-3">
<label class="form-label">SGST Code</label>
<input type="text"  class="form-control" name="pursgstcode" id="pursgstcode" required  oninput="purgettaxr(this.value)"  @if (!empty($itemsid->pursgstcode)) value="{{ $itemsid->pursgstcode }}" @else value="{{ old('pursgstcode') }}" @endif>
<div id="paccountList" class="accountList"></div>
</div>
<div class="mb-2 col-md-3">
<label class="form-label">SGST(%)</label>
<input type="text"  class="form-control"  name="pursgstamount" id="pursgst" required  @if (!empty($itemsid->pursgstamount)) value="{{ $itemsid->pursgstamount }}" @else value="{{ old('pursgstamount') }}" @endif>
</div>
<div class="mb-2 col-md-3">
<label class="form-label">CGST(%)</label>
<input type="text"  class="form-control" name="purcgstamount" id="purcgst" required  @if (!empty($itemsid->purcgstamount)) value="{{ $itemsid->purcgstamount }}" @else value="{{ old('purcgstamount') }}" @endif>
</div>
<div class="mb-2 col-md-3">
<label class="form-label">IGST(%)</label>
<input type="text"  class="form-control" name="purigstamount" id="purigst" required  @if (!empty($itemsid->purigstamount)) value="{{ $itemsid->purigstamount }}" @else value="{{ old('purigstamount') }}" @endif>
</div>

</div>










              <div class="row mt-2" >
                <div class="mb-2 col-md-3">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
              <thead>
                <tr>
                  <th>Sr.No</th>
                  <th>Item Name</th>
                  <th>Brand Name</th>
                  <th>Category Name</th>
                  <th>Sub Category Name</th>
                  <th>Sub Child Name</th>
                  <th>Status</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>

              @if(!empty($itemsall))
              @foreach ($itemsall as $itemslist)
              <tr>
                <td>{{ $loop->iteration}}</td>
                <td>({{ $itemslist->itemcode }}) {{ $itemslist->name}}</td>
                <td>{{ $itemslist->brand_name}}</td>
                <td>{{ $itemslist->category_name}}</td>
                <td>{{ $itemslist->subcategory_name}}</td>
                <td>{{ $itemslist->child_name}}</td>
                <td>{{ ucfirst($itemslist->status) }}</td>
                <td><a href="{{ url('edititem/'.$itemslist->id) }}"><img src="{{ url('public/admin/images/edit.png') }}" ></a></td>
                <td><a onclick="return confirm('Are you Sure?')" href="{{ url('deleteitem/'.$itemslist->id) }}" ><img src="{{ url('public/admin/images/delete.png') }}" ></a></td>
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
@include('include.repeat')

@if (!empty($itemsid->unit))
@php $sho=DB::table('units')->where('id','=',$itemsid->unit)->value('name') @endphp
<script>

$('.uvlax').text('{{ $sho }}');
</script>
@else
<script>
    function showSelectedUnit(selectElement) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var unitName = selectedOption.text;
        $('.uvlax').text(unitName);
    }
</script>

@endif
<script>
    // JavaScript or jQuery code
$(document).ready(function() {
var counter = 1;

$('#addRowBtn').click(function() {
var newRow = $('<div class="row mb-3"></div>');

var cols = '';
cols += '<div class="mb-2 col-md-3">';
cols += '<label class="form-label">Discount On</label>';
cols += '<select name="discounttype[]" class="form-control">';
cols += '<option value="">Select type</option>';
cols += '<option value="box"><span class="uvlax">Box</span></option>';
cols += '<option value="piece">Pieces</option>';
cols += '</select>';
cols += '</div>';

cols += '<div class="mb-2 col-md-3">';
cols += '<label class="form-label">Quantity From</label>';
cols += '<input type="text" class="form-control" name="qtfrom[]" required>';
cols += '</div>';

cols += '<div class="mb-2 col-md-3">';
cols += '<label class="form-label">Quantity To</label>';
cols += '<input type="text" class="form-control" name="qtto[]" required>';
cols += '</div>';

cols += '<div class="mb-2 col-md-1">';
cols += '<label class="form-label">Discount(in%)</label>';
cols += '<input type="text" class="form-control" name="price[]" required>';
cols += '</div>';

cols += '<div class="mb-2 col-md-1">';
cols += '<label class="form-label">Delete</label>';
cols += '<button class="btn btn-danger delete-row" type="button">Delete</button>';
cols += '</div>';


newRow.append(cols);
$('.dynamic-rows-container').append(newRow);

counter++;
});

// Function to handle deleting rows
$('.dynamic-rows-container').on('click', '.delete-row', function() {
$(this).closest('.row').remove();
counter--; // Decrease counter accordingly if needed
});
});

  </script>
<script>
    function purgettiaxr(id) {
        $('#paccountListt').html('');
        $.ajax({
            url: "{{ route('itemlist.gettaxr') }}",
            type: "get",
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(data) {
                $.each(data, function(index, item) {
                    $('#paccountListt').append('<ul><li onclick="pgetigstrates(\'' + index +
                        '\')">' + item + '</li></ul>');
                });
            }
        });
    }

    function pgetigstrates(id) {
        $('#paccountListt').html('');
        $.ajax({
            url: "{{ route('itemlist.getgstrates') }}",
            type: "get",
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(data) {
                $('#purigstcode').val(data.name);
                $('#purigst').val(data.igstpercentage);
            }
        });
    }

    function pgetgstrates(id) {
        $('#paccountList').html('');
        $.ajax({
            url: "{{ route('itemlist.getgstrates') }}",
            type: "get",
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(data) {
                $('#pursgstcode').val(data.name);
                $('#pursgst').val(data.sgstpercentage);
                $('#purcgst').val(data.cgstpercentage);
                $('#purigst').val(data.igstpercentage);
                //     $.each(data, function(index, item) {
                //     $('#accountList').append('<ul><li onclick="getgstrates(\'' + index + '\')">'+item+'</li></ul>');
                // });
            }
        });
    }

    function purgettaxr(id) {
        $('#paccountList').html('');
        $.ajax({
            url: "{{ route('itemlist.purgettaxr') }}",
            type: "get",
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(data) {
                $.each(data, function(index, item) {
                    $('#paccountList').append('<ul><li onclick="pgetgstrates(\'' + index + '\')">' +
                        item + '</li></ul>');
                });
            }
        });
    }

    function gettaxr(id) {
        $('#accountList').html('');
        $.ajax({
            url: "{{ route('itemlist.gettaxr') }}",
            type: "get",
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(data) {
                $.each(data, function(index, item) {
                    $('#accountList').append('<ul><li onclick="getgstrates(\'' + index + '\')">' +
                        item + '</li></ul>');
                });
            }
        });
    }

    function getgstrates(id) {
        $('#accountList').html('');
        $.ajax({
            url: "{{ route('itemlist.getgstrates') }}",
            type: "get",
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(data) {
                $('#sgstcode').val(data.name);
                $('#sgst').val(data.sgstpercentage);
                $('#cgst').val(data.cgstpercentage);
                $('#igst').val(data.igstpercentage);
                //     $.each(data, function(index, item) {
                //     $('#accountList').append('<ul><li onclick="getgstrates(\'' + index + '\')">'+item+'</li></ul>');

                // });
            }
        });
    }

    function getigstrates(id) {
        $('#accountListt').html('');
        $.ajax({
            url: "{{ route('itemlist.getgstrates') }}",
            type: "get",
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(data) {
                $('#igstcode').val(data.name);
                $('#igst').val(data.igstpercentage);
                //     $.each(data, function(index, item) {
                //     $('#accountList').append('<ul><li onclick="getgstrates(\'' + index + '\')">'+item+'</li></ul>');
                // });
            }
        });
    }

    function gettiaxr(id) {
        $('#accountListt').html('');
        $.ajax({
            url: "{{ route('itemlist.gettaxr') }}",
            type: "get",
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(data) {
                $.each(data, function(index, item) {
                    $('#accountListt').append('<ul><li onclick="getigstrates(\'' + index + '\')">' +
                        item + '</li></ul>');
                });
            }
        });
    }
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var counter = {{ $countercount + 1 }};
        $("#addrow").on("click", function() {
            var newRow = $("<tr>");
            var cols = "";

            cols += '<td>';
cols += '<select id="selectize' + counter + '" name="customer[]" placeholder="Select Customers" class="sup-dropdown">';
cols += '@if(!empty($retails))';
cols += '<option value="">Select Customers</option>';
cols += '@foreach ($retails as $retailslist)';
cols += '<option value="{{ $retailslist->id }}">{{ ucfirst($retailslist->name) }}</option>';
cols += '@endforeach';
cols += '@endif';
cols += '</select>';
cols += '</td>';

            cols += '<td><input type="text" name="mrp[]" class="form-control" /></td>';
            cols += '<td><input type="text" name="salerate[]" class="form-control" /></td>';
            cols += '<td><input type="text" name="purchaserate[]" class="form-control" /></td>';
            cols += '<td><input type="text" name="mrpsingle[]" class="form-control" /></td>';
            cols += '<td><input type="text" name="saleratesingle[]" class="form-control" /></td>';
            cols += '<td><input type="text" name="purchaseratesingle[]" class="form-control" /></td>';
            cols += '<td><input type="text" name="discount[]" class="form-control" /></td>';
            cols += '<td><input type="text" oninput="getbarcode(this.value,' + counter +
                ')" name="barcodenumber[]" class="form-control" /></td>';
            cols += '<td>  <input type="hidden" name="barcodename[]"  id="barcodename' + counter +
                '"     > <img id="barcode' + counter + '" src="" style="width: 100%"></td>';
            cols +=
                '<td><a class="ibtnDel "> <img src="{{ url('public/admin/images/delete.png') }}" alt="img"> </a></td>';
            newRow.append(cols);
            $("table.order-listt").append(newRow);
            $('#selectize'+counter).selectize({ });
            counter++;

        });
        $("table.order-listt").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();
            counter -= 1
        });

    });
</script>
<script>
    function truncateString(str, maxLength, end) {
        if (str.length <= maxLength) {
            return str;
        }
        return str.substr(0, maxLength) + end;
    }


    $(document).ready(function() {
        let timeout;

        function fetchData() {
            var barcodenumber = $('#barcodenumber').val();
            $.ajax({
                url: "{{ route('getbarcode') }}",
                type: "Post",
                data: {
                    id: barcodenumber,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(data) {
                    if (data.barcode_image_name) {
                        $('#barcode').attr('src', '{{ url('storage/app/public/barcodes/') }}/' +
                            data.barcode_image_name);
                        $('#barcodename').val('storage/app/public/barcodes/' + data
                            .barcode_image_name);
                        console.log(data.barcode_image_name);
                    } else {}
                }
            });
            console.log('AJAX request triggered');
        }

        function debounceFetchData() {
            clearTimeout(timeout);
            timeout = setTimeout(fetchData, 2000);
        }
        document.getElementById('barcodenumber').addEventListener('keyup', debounceFetchData);
    });

    function getbarcode(number, key) {
        console.log(number);
        console.log(key);

        $.ajax({
            url: "{{ route('getbarcode') }}",
            type: "Post",
            data: {
                id: number,
                key: key,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(data) {
                if (data.barcode_image_name) {
                    $('#barcode' + key).attr('src', '{{ url('storage/app/public/barcodes/') }}/' + data
                        .barcode_image_name);
                    $('#barcodename' + key).val('storage/app/public/barcodes/' + data.barcode_image_name);
                    console.log(data.barcode_image_name);
                } else {}
            }
        });

    }

    function createcode() {
 id=$('[name="name"]').val();
        if (id != "") {
            $.ajax({
                url: "{{ route('generatecode') }}",
                type: "POST",
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(data) {
                    $('#itemcode').val(data)
                }
            });

        }

    }


    // $(document).ready(function() {
    //     var currentPage = 1; // Initialize current page variable
    //     var searchTerm = ''; // Initialize search term variable
    //     // Function to fetch and render data
    //     function fetchDataa(page, search) {
    //         $.ajax({
    //             url: "{{ route('itemlist.getsearchresults') }}",
    //             type: "POST",
    //             data: {
    //                 id: search,
    //                 _token: '{{ csrf_token() }}',
    //                 page: page
    //             },
    //             dataType: 'json',
    //             success: function(data) {
    //                 $('.table tbody').empty(); // Empty the tbody first
    //                 $.each(data.data, function(index, item) {

    //                     var truncatedName = truncateString(item.name, 50, '...');
    //                     var editUrl = "{{ url('edititem') }}/" + item.id;
    //                     var deleteUrl = "{{ url('deleteitem') }}/" + item.id;
    //                     $('.tableBody').append('<tr><td>' + parseInt(index + 1 + (page -
    //                             1) * 10) + '</td><td>' + item.itemcode + '</td><td>' +
    //                         truncatedName + '</td><td>' + item.brand_name +
    //                         '</td><td>' + item.category_name + '</td><td>' + item
    //                         .subcategory_name + '</td><td>' + item.child_name +
    //                         '</td><td><a class="me-3" href="' + editUrl +
    //                         '"> <img src="{{ url('public/main/assets') }}/img/icons/edit.svg" alt="img"> </a>' +
    //                         '<a class="me-3" onclick="return confirm(\'Are you Sure?\')" href="' +
    //                         deleteUrl +
    //                         '"> <img src="{{ url('public/main/assets') }}/img/icons/delete.svg" alt="img"> </a>' +
    //                         '</td></tr>');

    //                 });
    //                 // Update pagination links
    //                 renderPaginationn(data.links);
    //             }
    //         });
    //     }
    //     // Initial fetch with an empty search term
    //     fetchDataa(currentPage, searchTerm);
    //     // Search input event handler
    //     $('#search').on('input', function() {
    //         searchTerm = $(this).val(); // Update search term
    //         fetchDataa(1, searchTerm); // Fetch data for the first page with the search term
    //     });
    //     // Pagination link click event handler
    //     $(document).on('click', '.pagination a', function(event) {
    //         event.preventDefault();
    //         var page = $(this).attr('href').split('page=')[1];
    //         fetchDataa(page, searchTerm); // Fetch data for the clicked page with the search term
    //     });
    //     // Function to render pagination links
    //     function renderPaginationn(links) {
    //         var paginationHtml = '';
    //         $.each(links, function(index, link) {
    //             var activeClass = link.active ? 'active' :
    //             ''; // Add 'active' class for the current page
    //             paginationHtml += '<li class="page-item ' + activeClass +
    //                 '"><a class="page-link" href="' + link.url + '">' + link.label + '</a></li>';
    //         });
    //         $('.pagination').html('<ul class="pagination">' + paginationHtml + '</ul>');
    //     }
    // });

    // $(document).ready(function() {
    //     var currentPage = 1;

    //     function fetchData(page) {
    //         $.ajax({
    //             url: "{{ route('itemlist.table') }}?page=" + page,
    //             type: 'GET',
    //             dataType: 'json',
    //             success: function(data) {
    //                 $('.tableBody').empty();
    //                 $.each(data.data, function(index, item) {
    //                     var truncatedName = truncateString(item.name, 50, '...');
    //                     var editUrl = "{{ url('edititem') }}/" + item.id;
    //                     var deleteUrl = "{{ url('deleteitem') }}/" + item.id;
    //                     $('.tableBody').append('<tr><td>' + parseInt(index + 1 + (page -
    //                             1) * 10) + '</td><td>' + item.itemcode + '</td><td>' +
    //                         truncatedName + '</td><td>' + item.brand_name +
    //                         '</td><td>' + item.category_name + '</td><td>' + item
    //                         .subcategory_name + '</td><td>' + item.child_name +
    //                         '</td><td><a class="me-3" href="' + editUrl +
    //                         '"> <img src="{{ url('public/main/assets') }}/img/icons/edit.svg" alt="img"> </a>' +
    //                         '<a class="me-3" onclick="return confirm(\'Are you Sure?\')" href="' +
    //                         deleteUrl +
    //                         '"> <img src="{{ url('public/main/assets') }}/img/icons/delete.svg" alt="img"> </a>' +
    //                         '</td></tr>');
    //                 });
    //                 currentPage = data.current_page;
    //                 renderPagination(data.links);
    //             }
    //         });
    //     }

    //     function renderPagination(links) {
    //         var paginationHtml = '';
    //         $.each(links, function(index, link) {
    //             var activeClass = link.active ? 'active' :
    //             ''; // Add 'active' class for the current page
    //             paginationHtml += '<li class="page-item ' + activeClass +
    //                 '"><a class="page-link" href="' + link.url + '">' + link.label + '</a></li>';
    //         });
    //         $('.pagination').html('<ul class="pagination">' + paginationHtml + '</ul>');
    //     }
    //     $(document).on('click', '.pagination a', function(event) {
    //         event.preventDefault();
    //         var page = $(this).attr('href').split('page=')[1];
    //         fetchData(page);
    //     });
    //     fetchData(currentPage);
    // });
</script>
@include('include.footer')
