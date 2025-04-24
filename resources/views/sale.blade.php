@include('include.header')

<style>

    #ssss:focus {
      background-color: #957413 !important;
      color: #f2ebeb;
    }
    input:focus {
        border: 1px solid #3F51B5 !important;
        outline: none;
    }

    select:focus {
        border: 1px solid #3F51B5 !important;
        outline: none;
    }
    .selected {
        background-color: #d3d3d3 !important;
    }
    /* #myTableproducts th, td {
        padding: 4px;
        text-align: left !important;
    } */
    .modalt {
      position: fixed;
      background: rgba(0, 0, 0, 0.5);
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      z-index: 1002;
      padding: 6% 9%;
      visibility: hidden;
      opacity: 1;
      pointer-events: none;
      transition: all 0.3s;
      &:target {
        visibility: visible;
        opacity: 1;
        pointer-events: auto;
      }
      .modalt-content {
        max-height: 95%;
        padding: 40px;
        background: white;
        border-radius: 5px;
        overflow: auto;
        position: relative;
      }

    }

    .modalt-close {
      color: #aaa;
      line-height: 50px;
      font-size: 80%;
      position: absolute;
      right: 0;
      text-align: center;
      top: 0;
      width: 70px;
      text-decoration: none;
      &:hover {
        color: #363636;
      }
    }




    /* #myTableproductss th, td {
        padding: 4px;
        text-align: left !important;
    } */
    .modall {
      position: fixed;
      background: rgba(0, 0, 0, 0.5);
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      z-index: 1002;
      padding: 6% 23%;
      visibility: hidden;
      opacity: 1;
      pointer-events: none;
      transition: all 0.3s;
      &:target {
        visibility: visible;
        opacity: 1;
        pointer-events: auto;
      }
      .modall-content {
        max-height: 95%;
        padding: 40px;
        background: white;
        border-radius: 5px;
        overflow: auto;
        position: relative;
      }

    }

    .modall-close {
      color: #aaa;
      line-height: 50px;
      font-size: 80%;
      position: absolute;
      right: 0;
      text-align: center;
      top: 0;
      width: 70px;
      text-decoration: none;
      &:hover {
        color: #363636;
      }
    }



    .modalll {
      position: fixed;
      background: rgba(0, 0, 0, 0.5);
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      z-index: 1002;
      padding: 6% 23%;
      visibility: hidden;
      opacity: 1;
      /* pointer-events: none; */
      transition: all 0.3s;
      &:target {
        visibility: visible;
        opacity: 1;
        pointer-events: auto;
      }
      .modalll-content {
        max-height: 95%;
        padding: 40px;
        background: white;
        border-radius: 5px;
        overflow: auto;
        position: relative;
      }

    }

    .modalll-close {
      color: #aaa;
      line-height: 50px;
      font-size: 80%;
      position: absolute;
      right: 0;
      text-align: center;
      top: 0;
      width: 70px;
      text-decoration: none;
      &:hover {
        color: #363636;
      }
    }





.modal-wrapper {
	height: 100%;
	left: 0;
	position: fixed;
	top: 0;
	width: 100%;
	background: #2f37369e;
	box-sizing: border-box;
	cursor: auto;
	opacity: 1;
	overflow-y: auto;
	padding: 50px 0 !important;
	transition: 0.3s;
	z-index: 9999;
	align-items: center;
	display: flex;
	justify-content: center;
	padding: 15px;
}

.modal-container {
	background-color: #f9f9f9;
	border-radius: 5px;
	margin: auto;
	max-width: 700px;
	min-width: 300px;
	position: relative;
	padding: 20px;
	color: #000;
}
    </style>
<div class="row">
  <div class="col-xl-12">
    <div class="card">
        <div class="card-body">
    <div class="row">
        <div class="col-sm-2 col-md-3">
            <select id="selectize-optgroup"    placeholder="Search Invoice" onchange="sgetdatabyinvoice(this.value)">
<option value="">Search Invoice </option>
                @if(!empty($getinvoice))
                @foreach ($getinvoice as $getinvoicelist)
                <option value="{{ $getinvoicelist->id }}">{{ $getinvoicelist->invoiceno }}</option>
                @endforeach
                @endif
                </select>
            </div>
    </div>
    </div>
    </div>
    <form id="inputform" autocomplete="off" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="card">
        <div class="card-body">


                {{-- hidden --}}
          <input type="hidden" id="id" name="id">
          {{-- hidden --}}
          <div class="row mt-2">
            <div class="col-sm-2 col-md">
              <label >Tax Invoice</label>
              <select class="form-select" name="invpicetype" id="invpicetype">
                <option value="">Select Tax Invoice</option>
                <option value="Vat Invoice" selected>Vat Invoice</option>
                <option value="Retail Invoice">Retail Invoice</option>
              </select>
            </div>
            <div class="col-sm-2 col-md">
              <label >Invoice No.</label>
              <input type="text" class="form-control" name="invoiceno" id="invoiceno" value="{{ $newNumbers }}"  readonly >
              {{-- <input class="form-control" type="text" name="grno" value="" id="grno"> --}}
            </div>
            <div class="col-sm-2 col-md">
              <label >Date</label>
               <input type="text"  class="form-control onlydate" name="invoicenodate" id="invoicenodate" value="{{ date('d-m-Y') }}">
            </div>

          </div>

          <div class="row">
            <div class="col-sm-2 col-md-4">
              <label >Mode</label>

              <select name="mode" id="mode" class="form-select" onchange="calculate(0)">
                <option value="cash">Cash</option>
                <option value="credit">Credit</option>
              </select>
            </div>


            <div class="col-sm-2 col-md-4">
              <label >Effect Stock</label>
              <input type="hidden" id="gstnoforref" name="gstnoforref">
              <select name="effectstock" id="effectstock"  class="form-select" >
                <option value="yes">Yes</option>
                <option value="no">No</option>
              </select>
            </div>
            <div class="col-sm-2 col-md-4">
                <label >Vehichle no.</label>
                <input type="text" class="form-control" name="vehichleno" id="vehichleno">
              </div>
              </div>
              <div class="row">
            <div class="col-sm-2 col-md-4">
              <label >Customer</label>
              <input type="text" class="form-control" name="accountcode" id="accountcode" >
            </div>
            <div class="col-sm-2 col-md-4">
              <label >Name</label>
              <input type="text" class="form-control" name="accountname" id="accountname">
            </div>
            <div class="col-sm-2 col-md-4">
              <label >Current Balance</label>
              <input type="text" class="form-control" name="currentbalance" id="currentbalance" readonly>
            </div>

          </div>

        </div>

        <div class="card-body">
          <div class="row">






























            <table id="myTable" class="table align-items-center table-bordered mb-0 order-lists" >
                <thead class="tableHeading">
                  <tr class="text-center" id="table-header-row">
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Sell.item.In</th>
                    <th>Box/Bal</th>
                    <th>Per/Bal</th>
                    <th>Quantity</th>
                    <th>Discount</th>
                    {{-- <th>Discount</th> --}}
                    <th>MRP</th>
                    <th>Rate</th>
                    <th class="none">SGST</th>
                    <th class="none">Amount</th>
                    <th class="none">CGST</th>
                    <th class="none">Amount</th>
                    <th class="none">IGST</th>
                    <th class="none">Amount</th>
                    <th>Amount</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody class="tableBody">
                  <tr>
                      {{-- oninput="sitemgetitems(this.value,0)" --}}
                  <td class="none"><input type="text"  id="getunit0" name="getunit[]">
                  <td><input type="text"  class="form-control" id="itemcode0" name="itemcode[]">
                   </td>
                  <td><input type="text" id="itemname0"    class="form-control"  name="itemname[]">
                   </td>
                  <td><select name="baltype[]"   class="form-select"  id="baltype0" onchange="calculateis(0)" ><option value="box">Box</option><option value="single">Single</option></select></td>
                  <td><input type="text"   class="form-control"  id="balance0" name="balance[]"></td>
                  <td><input type="text"   class="form-control"  id="sbalance0" name="sbalance[]"></td>
                  <td><input type="text"   class="form-control"  id="quantity0"  onKeyPress="return onlyNumberKey(event)"  name="quantity[]" oninput="calculate(0)" ></td>
                  <td><input type="text"   class="form-control"  id="discount0"  onKeyPress="return onlyNumberKey(event)"  name="discount[]" oninput="calculate(0)" ></td>
                  <td class="none"><input type="text" id="discountamt0"  onKeyPress="return onlyNumberKey(event)"  name="discountamt[]" oninput="calculate(0)" ></td>
                  <td><input type="text"   class="form-control" id="mrp0" onKeyPress="return onlyNumberKey(event)"  name="mrp[]"></td>
                  <td><input type="text"   class="form-control" id="salerate0"  onKeyPress="return onlyNumberKey(event)"  name="salerate[]"></td>
                  <td class="none"><input type="text" id="sgst0" readonly onKeyPress="return onlyNumberKey(event)" name="sgst[]" oninput="calculate(0)" ></td>
                  <td class="none"><input type="text" id="sgstamount0"  onKeyPress="return onlyNumberKey(event)"  name="sgstamount[]" readonly ></td>
                  <td class="none"><input type="text" id="cgst0" readonly onKeyPress="return onlyNumberKey(event)" name="cgst[]" oninput="calculate(0)" ></td>
                  <td class="none"><input type="text" id="cgstamount0"  onKeyPress="return onlyNumberKey(event)"  name="cgstamount[]" readonly ></td>
                  <td class="none"><input type="text" id="igst0" readonly onKeyPress="return onlyNumberKey(event)" name="igst[]" oninput="calculate(0)" ></td>
                  <td class="none"><input type="text" id="igstamount0" onKeyPress="return onlyNumberKey(event)" name="igstamount[]"  readonly ></td>
                  <td><input type="text"   class="form-control" id="total0" onKeyPress="return onlyNumberKey(event)" name="total[]"  readonly ></td>
                  <td><input type="button" class="ibtnDel " style="color: #fff; background: rgb(128, 0, 0); border-bottom: rgb(128, 0, 0); border-radius: 7px;"  value="Delete"></td>
                  </tr>
                <tfoot>
                  <tr>
                    <td colspan="20"  ><input type="button" style="margin: 10px;  color: #fff; background: green; border-bottom: green; border-radius: 7px;"  id="addrow" value="Add Row" />
                    </td>
                  </tr>
                  <tr> </tr>
                </tfoot>
                </tbody>

              </table>













        </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-2">
                  <div class="row">
                    <div class="col-md-12">
                    <label >Discount</label>
                    <input type="text"  class="form-control"  onKeyPress="return onlyNumberKey(event)"     name="distotal" id="distotal" value="0.00">
                    </div>
                    <div class="col-md-12">
                    <label >MRP Value </label>
                    <input type="text"  class="form-control"  onKeyPress="return onlyNumberKey(event)" readonly  name="totalmrpvalue" id="totalmrpvalue" value="0.00">
                    </div>
                    <div class="col-md-12">
                    <label >Total Saving</label>
                    <input type="text"  class="form-control"  onKeyPress="return onlyNumberKey(event)" readonly  name="totalsaving" id="totalsaving" value="0.00">
                    </div>
                    <div class="col-md-12">
                    <label >Bill Value </label>
                    <input type="text"  class="form-control"   onKeyPress="return onlyNumberKey(event)" readonly  name="totalsalerate" id="totalsalerate" value="0.00">
                    </div>
                    </div>
                </div>






                <div class="col-md-2">
                  <div class="row">
                    <div class="col-md-12">
                    <label >Payment</label>
                    <input type="text"  class="form-control"  onKeyPress="return onlyNumberKey(event)"     name="payment" id="payment" value="0.00">
                    </div>
                    <div class="col-md-12">
                    <label >Refund </label>
                    <input type="text"  class="form-control"  onKeyPress="return onlyNumberKey(event)"   name="refund" id="refund" value="0.00">
                    </div>
                    </div>
                </div>






                <div class="col-md-2">
                  <div class="row">
                    <div class="col-md-12">
                    <label >Grand Total </label>
                    <input type="text" class="form-control" onKeyPress="return onlyNumberKey(event)"  readonly  name="grandtotal" id="grandtotal" value="0.00" style="color: #e80909;">
                    </div>
                    <div class="col-md-12">
                    <label >Cash Received </label>
                        <input type="text"  onKeyPress="return onlyNumberKey(event)"  class="form-control"  name="cashrecieved" id="cashrecieved" value="0.00" oninput="calcullatecash()">
                    </div>
                    <div class="col-md-12">
                    <label >Card Payment </label>
                        <input type="text"  class="form-control" name="cardpayment" id="cardpayment" value="0.00" oninput="calcullatecashh()">
                    </div>
                    </div>
                    <div class="col-md-12">
                    <label >Credit Payment</label>
                    <input type="text"  onKeyPress="return onlyNumberKey(event)"  class="form-control" name="creditpayment" id="creditpayment" value="0.00">
                    </div>
                </div>










                <div class="col-md-2">
                    <div class="row">
                      <div class="col-md-12" style="visibility: hidden;">
                      <label >Payment</label>
                      <input type="text" class="form-control" value="111"  >
                      </div>
                      <div class="col-md-12"  style="visibility: hidden;">
                      <label >Cash Received </label>
                          <input type="text" class="form-control" value="222"  >
                      </div>
                      <div class="col-md-12">
                      <label >Payment By </label>
                      <select name="paymenttype" id="paymenttype" class="form-select" disabled>
                        <option value="">Select</option>
                        <option value="Paytm">Paytm</option>
                        <option value="Credit Card">Credit Card</option>
                        </select>
                      </div>
                      </div>
                      <div class="col-md-12">

                      </div>
                  </div>




                <div class="col-md-2">
                  <div class="row">
                    <div class="col-md-12">
                    <label >Basic Amount</label>
                    <input type="text"  onKeyPress="return onlyNumberKey(event)"  readonly  class="form-control" name="basicamount" id="basicamount" value="0.00">
                    </div>
                    <div class="col-md-12">
                    <label >SGST Amount </label>
                    <input type="text"  onKeyPress="return onlyNumberKey(event)" readonly  class="form-control" name="bsgstamount" id="bsgstamount" value="0.00" >
                    </div>
                    <div class="col-md-12">
                    <label >CGST Amount</label>
                    <input type="text"  onKeyPress="return onlyNumberKey(event)" readonly  class="form-control" name="csgstamount"  id="csgstamount" value="0.00">
                    </div>
                    </div>
                    <div class="col-md-12">
                    <label >IGST Amount</label>
                    <input type="text"  onKeyPress="return onlyNumberKey(event)" readonly  class="form-control" name="isgstamount"  id="isgstamount" value="0.00">
                    </div>
                </div>

                <div class="col-md-2 mt-1">
                    <div class="row mt-4">
                        <button onclick="changevalue('save')" class="btn btn-primary" type="submit" id="ssss" style="font-size: 12px" name="submit" >Save Bill</button>
                </div>
                    <div class="row mt-4">
                        <button onclick="changevalue('hold')" class="btn btn-primary" type="submit" id="sssss" style="font-size: 12px" name="submit">Hold Bill</button>
                         </div>



                </div>


            </div>

          </div>
        </div>
      </div>

      <input type="hidden"  name="status"  id="status" value="save" >
    </form>
  </div>
</div>
</div>
</div>




<script>
    function changevalue(val){
            $('#status').val(val);
    }
</script>









 <!-- Modal -->
 <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
 aria-labelledby="staticBackdropLabel" aria-hidden="true">
 <div class="modal-dialog">
     <div class="modal-content">
         <div class="modal-header">
             <h5 class="modal-title" id="staticBackdropLabel">Sale Entry Successful!!</h5>
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
              <div class="row  mt-4">
                <div class="col-md-12 d-flex justify-content-center">
            <a target="_blank" href="" id="printbuyer"  class="btn btn-primary"> Print Orignal bill For Buyer </a>
                </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12 d-flex justify-content-center">

            <a target="_blank" href=""   id="printtransporter" class="btn btn-primary"> Print Dublicate bill For Transporter </a>
                </div>
              </div>
         </div>
         <div class="modal-footer">
             <a href="{{ URL::current() }}" type="button" class="btn btn-secondary" >Close</a>
             {{-- <button type="button" class="btn btn-primary">Understood</button> --}}
         </div>
     </div>
 </div>
</div>


<div id="modalt" class="modalt">
    <div class="modalt-content">
      {{-- <a href="#" title="Close" class="modal-close">Close</a> --}}

            <table id="myTableproducts" >
                <thead class="pro">
                <tr>
                    <th>Item Code</th>
                    <th>Item Name 	</th>
                    <th>MRP</th>
                    <th>Sale Rate</th>
                    <th>Purchase Rate</th>
                </tr>
            </thead>
                <tbody id="tt">

            </tbody>
            </table>
      </div>
  </div>



<div id="modall" class="modall">
    <div class="modall-content">
      {{-- <a href="#" title="Close" class="modal-close">Close</a> --}}

            <table id="myTableproductss" >
                <thead class="pros">
                <tr>
                    <th>Code</th>
                    <th>Name 	</th>
                    <th>phone</th>
                    <th>designation</th>
                    <th>address</th>
                </tr>
            </thead>
                <tbody id="ttt">

            </tbody>
            </table>
      </div>
  </div>





  <dialog class="modalid">

    <div class="dialog-header">
      <p>Hold Bills</p>
      <button class="close-modal close-button">&times;</button>
    </div>

    <table class="hosdbisss" style="width: 100%">
        <thead>
            <tr>
                <th>
                    Invoice
                </th>
                <th>
                    Account Name
                </th>
                <th>
                    Account Code
                </th>
                <th>
                    Amount
                </th>
            </tr>
        </thead>
        <tbody id="appen">

        </tbody>
    </table>
  </dialog>








<script>


    function sitemgetitemsname(name, numb) {
        if (name != "") {
            $('#accountListtname' + numb).html('');
            $.ajax({
                url: "{{url('sitemgetitemsname')}}",
                type: "POST",
                data: {
                    name: name,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(index, item) {
                        $('#accountListtname' + numb).append('<ul><li onclick="sgetitemdata(\'' + index + '\',\'' + numb + '\')">' + index + '(' + item + ')</li></ul>');
                    });
                }
            });
         }
     }










 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ///////////////////////

 function calculateis(numb) {
     var baltype = $('#baltype' + numb).val();

      var getunit = $('#getunit' + numb).val();
      var accountcode = $('#accountcode').val();
         if(baltype=='single'){


            $.ajax({
                url: "{{url('sgetitemdataunit')}}",
                type: "POST",
                data: {
                 accountcode: accountcode,
                    name: getunit,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {

                    $('#itemcode' + numb).val(data.gatdata.itemcode);
                    $('#itemname' + numb).val(data.gatdata.name);
                    $('#balance' + numb).val(data.balance);
                    $('#sbalance' + numb).val(data.perbalance);
                    $('#hsn' + numb).val(data.gatdata.hsn);
                    $('#quantity' + numb).val('0');
                    $('#unit' + numb).val(data.gatdata.unit);
                    $('#mrp' + numb).val(data.getit.mrp);
                    $('#salerate' + numb).val(data.getit.salerate);
                    $('#purchaserate' + numb).val(data.getit.purchaserate);
                    $('#discount' + numb).val(data.discount);
                 $('#mrp' + numb).val(data.getit.mrpsingle);
                 $('#salerate' + numb).val(data.getit.saleratesingle);
                 $('#purchaserate' + numb).val(data.getit.purchaseratesingle);

                    var varibalgst = $('#gstnoforref').val();
                    var gstnoown = "{{ $master->gstno }}";
                    var firstTwoChars = gstnoown.substring(0, 2);
                    if (parseFloat(varibalgst) == parseFloat(firstTwoChars)) {
                        $('#sgst' + numb).val(data.gatdata.pursgstamount);
                        $('#cgst' + numb).val(data.gatdata.purcgstamount);
                        $('#igst' + numb).val('0');
                    } else {
                        $('#sgst' + numb).val('0');
                        $('#cgst' + numb).val('0');
                        $('#igst' + numb).val(data.gatdata.purigstamount);
                    }
                    $('#quantity' + numb).focus().select();
                    calculate(numb);
                }
            });






         }else{


             $.ajax({
                url: "{{url('sgetitemdataunit')}}",
                type: "POST",
                data: {
                 accountcode: accountcode,
                    name: getunit,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {

                    $('#itemcode' + numb).val(data.gatdata.itemcode);
                    $('#itemname' + numb).val(data.gatdata.name);
                    $('#balance' + numb).val(data.balance);
                    $('#sbalance' + numb).val(data.perbalance);
                    $('#hsn' + numb).val(data.gatdata.hsn);
                    $('#quantity' + numb).val('0');
                    $('#discount' + numb).val(data.discount);
                    $('#unit' + numb).val(data.gatdata.unit);
                    $('#mrp' + numb).val(data.getit.mrp);
                    $('#salerate' + numb).val(data.getit.salerate);
                    $('#purchaserate' + numb).val(data.getit.purchaserate);
                    var varibalgst = $('#gstnoforref').val();
                    var gstnoown = "{{ $master->gstno }}";
                    var firstTwoChars = gstnoown.substring(0, 2);
                    if (parseFloat(varibalgst) == parseFloat(firstTwoChars)) {
                        $('#sgst' + numb).val(data.gatdata.pursgstamount);
                        $('#cgst' + numb).val(data.gatdata.purcgstamount);
                        $('#igst' + numb).val('0');
                    } else {
                        $('#sgst' + numb).val('0');
                        $('#cgst' + numb).val('0');
                        $('#igst' + numb).val(data.gatdata.purigstamount);
                    }
                    $('#quantity' + numb).focus().select();
                    calculate(numb);
                }
            });













         }
         calculate(numb);
 }


    function calculate(numb) {
        var mrp = $('#mrp' + numb).val();
        var salerate = $('#salerate' + numb).val();
        var quantity = $('#quantity' + numb).val();
        var totalrate = parseFloat(salerate) * parseFloat(quantity);

        var discount = $('#discount' + numb).val();
     var getdiscount = (discount / 100) * totalrate;
     var discounttotalprice = (totalrate - getdiscount);
     $('#discountamt' + numb).val(getdiscount.toFixed(2));
        $('#total' + numb).val(discounttotalprice.toFixed(2));
        var totalsalerate = 0;
        var totalloall = 0;
        var totalmrpvalue = 0;
        var totaligstamount = 0;
        var totalcgstamount = 0;
        var totalsgstamount = 0;
        var totadiscountamtst = 0;
        $('input[name^="salerate[]"]').each(function() {
            totalsalerate += parseFloat(this.value, 10) || 0;
        });
        $('input[name^="total[]"]').each(function() {
            totalloall += parseFloat(this.value, 10) || 0;
        });
        $('input[name^="mrp[]"]').each(function() {
            totalmrpvalue += parseFloat(this.value, 10) || 0;
        });
        var sgst = $('#sgst' + numb).val();
        var cgst = $('#cgst' + numb).val();
        var igst = $('#igst' + numb).val();
        var sgstamount = (sgst / 100) * discounttotalprice;
        $('#sgstamount' + numb).val(sgstamount.toFixed(2));
        var cgstamount = (cgst / 100) * discounttotalprice;
        $('#cgstamount' + numb).val(cgstamount.toFixed(2));
        var igstamount = (igst / 100) * discounttotalprice;
        $('#igstamount' + numb).val(igstamount.toFixed(2));
        $('input[name^="sgstamount[]"]').each(function() {
            totalsgstamount += parseFloat(this.value, 10) || 0;
        });
        $('input[name^="cgstamount[]"]').each(function() {
            totalcgstamount += parseFloat(this.value, 10) || 0;
        });
        $('input[name^="igstamount[]"]').each(function() {
            totaligstamount += parseFloat(this.value, 10) || 0;
        });
        $('input[name^="discountamt[]"]').each(function() {
         totadiscountamtst += parseFloat(this.value, 10) || 0;
     });
     $('#distotal').val(totadiscountamtst.toFixed(2));
        $('#bsgstamount').val(totalsgstamount.toFixed(2));
        $('#csgstamount').val(totalcgstamount.toFixed(2));
        $('#isgstamount').val(totaligstamount.toFixed(2));
        $('#totalmrpvalue').val(totalmrpvalue.toFixed(2));
        $('#totalsalerate').val(totalsalerate.toFixed(2));
        var totalsaving = parseFloat(totalmrpvalue) - parseFloat(totalsalerate);
        $('#totalsaving').val(totalsaving.toFixed(2));
        $('#basicamount').val(totalloall.toFixed(2));
        var grandtotal = parseFloat(totalsgstamount) + parseFloat(totalcgstamount) + parseFloat(totaligstamount) + parseFloat(totalloall);
        $('#payment').val(grandtotal.toFixed(2));
        $('#grandtotal').val(grandtotal.toFixed(2));


       var modetype = $('#mode').val();

       if(modetype=='cash'){
         $('#cashrecieved').val(grandtotal.toFixed(2));

        $('#cardpayment').attr('disabled', false);
        $('#cashrecieved').attr('disabled', false);
        $('#cardpayment').val(0.00);
        $('#creditpayment').val(0.00);
       $('#paymenttype').find('option:selected').prop('selected', false);
       $('#paymenttype').attr('disabled', 'disabled');
       $('#creditpayment').attr('disabled', 'disabled');
       }else{
         $('#cashrecieved').attr('disabled', 'disabled');
        $('#cardpayment').attr('disabled', 'disabled');
       $('#paymenttype').find('option:selected').prop('selected', false);
       $('#paymenttype').attr('disabled', 'disabled');
        $('#cashrecieved').val(0.00);
        $('#cardpayment').val(0.00);
        $('#creditpayment').attr('disabled', false);
       $('#creditpayment').val(grandtotal.toFixed(2));
       }
    }

    function sgetitemdata(name, numb) {
        if (name != "") {

         var accountcode = $('#accountcode').val();
            $.ajax({
                url: "{{url('sgetitemdata')}}",
                type: "POST",
                data: {
                    name: name,
                    accountcode: accountcode,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {
                   $('#getunit' + numb).val(data.getit.id);
                    $('#itemcode' + numb).val(data.gatdata.itemcode);
                    $('#itemname' + numb).val(data.gatdata.name);
                    $('#balance' + numb).val(data.balance);
                    $('#sbalance' + numb).val(data.perbalance);
                    $('#hsn' + numb).val(data.gatdata.hsn);
                    $('#quantity' + numb).val('0');
                    $('#discount' + numb).val(data.discount);
                    $('#unit' + numb).val(data.gatdata.unit);
                    $('#mrp' + numb).val(data.getit.mrp);
                    $('#salerate' + numb).val(data.getit.salerate);
                    $('#purchaserate' + numb).val(data.getit.purchaserate);
                 //    $('#discount' + numb).val('0');
                    var varibalgst = $('#gstnoforref').val();
                    var gstnoown = "{{ $master->gstno }}";
                    var firstTwoChars = gstnoown.substring(0, 2);
                    if (parseFloat(varibalgst) == parseFloat(firstTwoChars)) {
                        $('#sgst' + numb).val(data.gatdata.pursgstamount);
                        $('#cgst' + numb).val(data.gatdata.purcgstamount);
                        $('#igst' + numb).val('0');
                    } else {
                        $('#sgst' + numb).val('0');
                        $('#cgst' + numb).val('0');
                        $('#igst' + numb).val(data.gatdata.purigstamount);
                    }
                    $('#quantity' + numb).focus().select();
                    calculate(numb);
                }
            });
        }
    }
    $(document).ready(function() {
        var counter = 1;
        $("#addrow").on("click", function() {
            var newRow = $("<tr>");
            var cols = "";



            cols += '<td class="none"><input type="text"  id="getunit' + counter + '" name="getunit[]">';
            cols += '<td><input type="text"   class="form-control"   id="itemcode' + counter + '" name="itemcode[]"><div id="accountListt' + counter + '" class="accountList"> </div></td>';
            cols += '<td><input type="text"   class="form-control"  id="itemname' + counter + '"  name="itemname[]"> <div id="accountListtname' + counter + '" class="accountList"> </div></td>';
            cols += '<td><select name="baltype[]"  class="form-select"  id="baltype' + counter + '" onchange="calculateis(' + counter + ')" ><option value="box">Box</option><option value="single">Single</option></select></td>';
            cols += '<td><input type="text"   class="form-control"  id="balance' + counter + '" name="balance[]"></td>';
            cols += '<td><input type="text"   class="form-control"  id="sbalance' + counter + '" name="sbalance[]"></td>';
            cols += '<td><input type="text"   class="form-control"  id="quantity' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="quantity[]" oninput="calculate(' + counter + ')" ></td>';

            cols += '<td><input type="text"   class="form-control"  id="discount' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="discount[]" oninput="calculate(' + counter + ')" ></td>';
            cols += '<td  class="none"><input type="text" id="discountamt' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="discountamt[]" oninput="calculate(' + counter + ')" ></td>';
cols += '<td><input type="text"   class="form-control" id="mrp' + counter + '" onKeyPress="return onlyNumberKey(event)"  name="mrp[]"></td>';
cols += '<td><input type="text"   class="form-control"  id="salerate' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="salerate[]"></td>';
cols += '<td class="none"><input type="text" id="sgst' + counter + '" readonly onKeyPress="return onlyNumberKey(event)" name="sgst[]" oninput="calculate(' + counter + ')" ></td>';
cols += '<td class="none"><input type="text" id="sgstamount' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="sgstamount[]" readonly ></td>';
cols += '<td class="none"><input type="text" id="cgst' + counter + '" readonly onKeyPress="return onlyNumberKey(event)" name="cgst[]" oninput="calculate(' + counter + ')" ></td>';
cols += '<td class="none"><input type="text" id="cgstamount' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="cgstamount[]" readonly ></td>';
cols += '<td class="none"><input type="text" id="igst' + counter + '" readonly onKeyPress="return onlyNumberKey(event)" name="igst[]" oninput="calculate(' + counter + ')" ></td>';
cols += '<td class="none"><input type="text" id="igstamount' + counter + '" onKeyPress="return onlyNumberKey(event)" name="igstamount[]"  readonly ></td>';
cols += '<td><input type="text"   class="form-control"  id="total' + counter + '" onKeyPress="return onlyNumberKey(event)" name="total[]"  readonly ></td>';
cols += '<td><input type="button" class="ibtnDel " style="color: #fff; background: rgb(128, 0, 0); border-bottom: rgb(128, 0, 0); border-radius: 7px;"  value="Delete"></td>';
            newRow.append(cols);
            $("table.order-lists").append(newRow);
            newRow.find('[name="itemcode[]"]').focus().select();
            counter++;
        });
        $("table.order-lists").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();
            calculate(0);

        });
    });

    function calcullatecash() {
        var grandtotal = $('#grandtotal').val();
        var cashrecieved = $('#cashrecieved').val();
        if (grandtotal == cashrecieved) {
            $('#cardpayment').val('0');
            $('#paymenttype').attr('disabled', 'disabled');
            $('#creditpayment').val('0');
        }
        if (grandtotal > cashrecieved) {
            var remain = parseFloat(grandtotal) - parseFloat(cashrecieved);
            $('#cardpayment').val(remain.toFixed(2));
            $('#paymenttype').removeAttr('disabled');
            $('#creditpayment').val(0);

        }
    }

    function calcullatecashh() {
        var grandtotal = $('#grandtotal').val();
        var cashrecieved = $('#cashrecieved').val();
        var cardpayment = $('#cardpayment').val();
        var remain = parseFloat(grandtotal) - parseFloat(cashrecieved);

        if (remain >= cardpayment) {
            var cardremain = parseFloat(remain) - parseFloat(cardpayment);
            $('#creditpayment').val(cardremain.toFixed(2));
        }
    }

    function sgetinviocenumber(invoicenumber) {
        if (invoicenumber != "") {
            $('#accountListshow').html('');
            $.ajax({
                url: "{{url('sgetinviocenumber')}}",
                type: "POST",
                data: {
                    invoicenumber: invoicenumber,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(index, item) {
                        $('#accountListshow').append('<ul><li onclick="sgetdatabyinvoice(\'' + index + '\')">' + item + '</li></ul>');
                    });
                }
            });
        }
    }

    function sgetdatabyinvoice(invoicenumber) {
        if (invoicenumber != "") {
            $('#accountListshow').html('');
            $.ajax({
                url: "{{url('sgetdatabyinvoice')}}",
                type: "POST",
                data: {
                    invoicenumber: invoicenumber,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {
                    $("#myTable tbody tr").remove();
                    $('#search').val(data.getinvoice.invoiceno);


                    var counter = 500;
                    $.each(data.getinvoiceitems, function(index, item) {
                        var newRow = $("<tr>");
                        var cols = "";


 // Assuming 'item' is your object containing the data
 var isSelectedBox = item.baltype === 'box';
 var isSelectedSingle = item.baltype === 'single';


 if(item.baltype=='box'){
 var hjo=item.quantity;
 }

 if(item.baltype=='single'){
 var hjo=item.pquantity;
 }
 cols += '<td class="none"><input type="text" value="' + item.getunit + '"  id="getunit' + counter + '" name="getunit[]">';
 cols += '<td><input value="' + item.itemcode + '"   class="form-control"  type="text" oninput="itemgetitems(this.value,' + counter + ')" id="itemcode' + counter + '"   name="itemcode[]"><div id="accountListt' + counter + '" class="accountList"> </div></td>';
 cols += '<td><input value="' + item.itemname + '"   class="form-control"  type="text" id="itemname' + counter + '"   oninput="itemgetitemsname(this.value,' + counter + ')"  name="itemname[]"> <div id="accountListtname' + counter + '" class="accountList"> </div></td>';
 cols += '<td><select name="baltype[]"   class="form-select" id="baltype' + counter + '" onchange="calculateis(' + counter + ')" ><option value="box"' + (isSelectedBox ? ' selected' : '') + '>Box</option><option value="single"' + (isSelectedSingle ? ' selected' : '') + '>Single</option></select></td>';
 cols += '<td><input   class="form-control"  value="' + item.balance + '" type="text" id="balance' + counter + '"        name="balance[]"></td>';
 cols += '<td><input   class="form-control"  value="' + item.sbalance + '" type="text" id="sbalance' + counter + '" name="sbalance[]"></td>';
 cols += '<td><input   class="form-control"  value="' + hjo + '" type="text" onKeyPress="return onlyNumberKey(event)"  id="quantity' + counter + '"   name="quantity[]" oninput="calculate(' + counter + ')" ></td>';
 cols += '<td><input   class="form-control"  value="' + item.discount + '"  type="text" id="discount' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="discount[]" oninput="calculate(' + counter + ')" ></td>';
 cols += '<td  class="none"><input type="text" id="discountamt' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="discountamt[]" oninput="calculate(' + counter + ')" ></td>';
 cols += '<td><input   class="form-control"  value="' + item.mrp + '" type="text" onKeyPress="return onlyNumberKey(event)"  id="mrp' + counter + '"           name="mrp[]"></td>';
 cols += '<td><input   class="form-control"  value="' + item.salerate + '" type="text" onKeyPress="return onlyNumberKey(event)"  id="salerate' + counter + '"   name="salerate[]"></td>';
 cols += '<td  class="none"><input value="' + item.sgst + '" type="text" onKeyPress="return onlyNumberKey(event)" readonly id="sgst' + counter + '"   name="sgst[]"  oninput="calculate(' + counter + ')"  ></td>';
 cols += '<td  class="none"><input value="' + item.sgstamount + '" type="text" onKeyPress="return onlyNumberKey(event)"  id="sgstamount' + counter + '"  readonly name="sgstamount[]"  ></td>';
 cols += '<td  class="none"><input value="' + item.cgst + '"  type="text" id="cgst' + counter + '"readonly  onKeyPress="return onlyNumberKey(event)"   name="cgst[]"   oninput="calculate(' + counter + ')" ></td>';
 cols += '<td  class="none"><input value="' + item.cgstamount + '"  type="text" id="cgstamount' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="cgstamount[]" readonly ></td>';
 cols += '<td  class="none"><input value="' + item.igst + '" type="text" readonly onKeyPress="return onlyNumberKey(event)"  id="igst' + counter + '"   name="igst[]"  oninput="calculate(' + counter + ')"   ></td>';
 cols += '<td  class="none"><input value="' + item.igstamount + '" type="text" onKeyPress="return onlyNumberKey(event)"  readonlyid="igstamount' + counter + '"   name="igstamount[]"   ></td>';
 cols += '<td><input   class="form-control"  value="' + item.total + '" type="text" onKeyPress="return onlyNumberKey(event)"  id="total' + counter + '"   name="total[]"   ></td>';
 cols += '<td><input  type="button" class="ibtnDel " style="color: #fff; background: rgb(128, 0, 0); border-bottom: rgb(128, 0, 0); border-radius: 7px;"  value="Delete"></td>';
 newRow.append(cols);
 $("table.order-lists").append(newRow);
 calculate(counter);
 counter++;
                    });



                    var originalDate = new Date(data.getinvoice.invoicenodate);
                    var day = originalDate.getDate();
                    var month = originalDate.getMonth() + 1;
                    var year = originalDate.getFullYear();
                    day = day < 10 ? '0' + day : day;
                    month = month < 10 ? '0' + month : month;
                    var formatinvoicedate = day + '-' + month + '-' + year;
                    $('#invpicetype option').each(function() {
                        if ($(this).val() === data.getinvoice.invpicetype) {
                            $(this).prop('selected', true);
                        } else {
                            $(this).prop('selected', false);
                        }
                    });
                    console.debug(data);
                    $('#memo option').each(function() {
                        if ($(this).val() === data.getinvoice.memo) {
                            $(this).prop('selected', true);
                        } else {
                            $(this).prop('selected', false);
                        }
                    });
                    $('#paymenttype option').each(function() {
                        if ($(this).val() === data.getinvoice.paymenttype) {
                            $(this).prop('selected', true);
                        } else {
                            $(this).prop('selected', false);
                        }
                    });
                    $('#effectstock option').each(function() {
                        if ($(this).val() === data.getinvoice.effectstock) {
                            $(this).prop('selected', true);
                        } else {
                            $(this).prop('selected', false);
                        }
                    });

                    $('#id').val(data.getinvoice.id);
                    $('#invoiceno').val(data.getinvoice.invoiceno);
                    $('#invoicenodate').val(formatinvoicedate);
                    $('#accountcode').val(data.getinvoice.accountcode);
                    $('#accountname').val(data.getinvoice.accountname);
                    $('#gstnoforref').val(data.getinvoice.gstnoforref);
                    $('#vehichleno').val(data.getinvoice.vehichleno);
                    $('#totalmrpvalue').val(data.getinvoice.totalmrpvalue);
                    $('#totalsaving').val(data.getinvoice.totalsaving);
                    $('#totalsalerate').val(data.getinvoice.totalsalerate);
                    $('#payment').val(data.getinvoice.payment);
                    $('#refund').val(data.getinvoice.refund);
                    $('#grandtotal').val(data.getinvoice.grandtotal);
                    $('#cashrecieved').val(data.getinvoice.cashrecieved);
                    $('#cardpayment').val(data.getinvoice.cardpayment);
                    $('#creditpayment').val(data.getinvoice.creditpayment);
                    $('#basicamount').val(data.getinvoice.basicamount);
                    $('#bsgstamount').val(data.getinvoice.bsgstamount);
                    $('#csgstamount').val(data.getinvoice.csgstamount);
                    $('#isgstamount').val(data.getinvoice.isgstamount);
                    $('#currentbalance').val(data.getinvoice.currentbalance);
                }
            });
        }
    }
    $(document).ready(function() {
        $('#inputform').on('submit', function(event) {
            // Prevent form submission
            event.preventDefault();

            var status=$('#status').val();

         if(status=='hold'){
             var invoiceno=$('#invoiceno').val();
                 if(invoiceno==''){
                 alert('Fill Invoice Number');
                 return;
                 }else{
             var formData = $(this).serialize();
                $.ajax({
                    url: '{{ route("submitsale") }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        toastr.success('Hold Successfully', 'Hurray...!!!', { "positionClass": "toast-top-center" });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
                // Reset table and form
                $("#myTable tbody tr").remove();
                this.reset();
                $("#type-success").trigger("click");
                setTimeout(function() {
                    location.reload();
                }, 1000);
             }
         }else{



            if (validateForm()) {
                var formData = $(this).serialize();
                $.ajax({
                    url: '{{ route("submitsale") }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        toastr.success('Sale Added Successfully', 'Hurray...!!!', { "positionClass": "toast-top-center" });

                        var invoiceNo = $('#invoiceno').val();
                var baseUrl = "{{ url('generate-pdf') }}";

                // Update the href attributes
                $('#printbuyer').attr('href', baseUrl + '/' + invoiceNo);
                $('#printtransporter').attr('href', baseUrl + '/' + invoiceNo);

                        $('#staticBackdrop').modal('show');

                        $("#myTable tbody tr").remove();
               this.reset();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
                // Reset table and form
                // $("#myTable tbody tr").remove();
                // this.reset();
                // $("#type-success").trigger("click");
                // setTimeout(function() {
                //     location.reload();
                // }, 1000);
            }
         }
        });
    });

 // function getitsubmit(){

 //      $.ajax({
 //                    url: '{{ route("submitsale") }}',
 //                    method: 'POST',
 //                    data: {
 //                     id:'yes',
 //                     _token:"{{ csrf_token() }}"
 //                    },
 //                    success: function(response) {
 //                      if(response.status===true){
 //                         $('#modalll').css('visibility', 'visible');
 //                      }
 //                    },
 //                    error: function(xhr, status, error) {
 //                        console.error(error);
 //                    }
 //                });
 // }
  function validateForm() {
     var isValid = true;
     $('#inputform input[type="text"], #inputform select').each(function() {
         if ($(this).attr('id') === 'id' && $(this).attr('name') === 'id') {
             return true;
         }
         if ($(this).is(':disabled')) {
             return true;
         }
         if ($(this).is('input[type="text"]') && $.trim($(this).val()) === '') {
             $(this).addClass('error');
             isValid = false;
         } else if ($(this).is('select') && $(this).val() === '') {
             $(this).addClass('error');
             isValid = false;
         } else {
             $(this).removeClass('error');
         }
     });
     return isValid;
 }


    function deleteid() {
        var confirmation = confirm("Are you sure you want to delete?");
        if (confirmation) {
            var delid = $('#id').val();
            $.ajax({
                url: "{{url('deletesale')}}",
                type: "POST",
                data: {
                    delid: delid,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {}
            });
            $("#type-error").trigger("click");
            setTimeout(function() {
                location.reload();
            }, 1000);
            $('#inputform')[0].reset();
            $("#myTable tbody tr").remove();
        }
    }

    function getdatat() {
        var barcode = $('#barcode').val();
        $('input[name^="itemcode[]"]').each(function() {
            if ($(this).val() === '') {
                $(this).closest('tr').remove();
            }
        });
        $('input[name^="itemname[]"]').each(function() {
            if ($(this).val() === '') {
                $(this).closest('tr').remove();
            }
        });
        $('input[name^="quantity[]"]').each(function() {
            if ($(this).val() === '') {
                $(this).closest('tr').remove();
            }
        });
        $('input[name^="salerate[]"]').each(function() {
            if ($(this).val() === '') {
                $(this).closest('tr').remove();
            }
        });
        var counter = 1000 + Math.floor(Math.random() * 10000);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td class="none"><input type="text"  id="getunit' + counter + '" name="getunit[]">';
        cols += '<td><input type="text"  id="itemcode' + counter + '" name="itemcode[]"><div id="accountListt' + counter + '" class="accountList"> </div></td>';
        cols += '<td><input type="text" id="itemname' + counter + '"    name="itemname[]"> <div id="accountListtname' + counter + '" class="accountList"> </div></td>';
        cols += '<td><select name="baltype[]" id="baltype' + counter + '" onchange="calculateis(' + counter + ')" ><option value="box">Box</option><option value="single">Single</option></select></td>';
        cols += '<td><input type="text" id="balance' + counter + '" name="balance[]"></td>';
        cols += '<td><input type="text" id="sbalance' + counter + '" name="sbalance[]"></td>';
        cols += '<td><input type="text" id="quantity' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="quantity[]" oninput="calculate(' + counter + ')" ></td>';
        cols += '<td><input type="text" id="discount' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="discount[]" oninput="calculate(' + counter + ')" ></td>';
        cols += '<td  class="none"><input type="text" id="discountamt' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="discountamt[]" oninput="calculate(' + counter + ')" ></td>';
        cols += '<td><input type="text" id="mrp' + counter + '" onKeyPress="return onlyNumberKey(event)"  name="mrp[]"></td>';
        cols += '<td><input type="text" id="salerate' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="salerate[]"></td>';
        cols += '<td class="none"><input type="text" id="sgst' + counter + '" onKeyPress="return onlyNumberKey(event)" readonly name="sgst[]" oninput="calculate(' + counter + ')" ></td>';
        cols += '<td class="none"><input type="text" id="sgstamount' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="sgstamount[]" readonly ></td>';
        cols += '<td class="none"><input type="text" id="cgst' + counter + '" onKeyPress="return onlyNumberKey(event)" readonly name="cgst[]" oninput="calculate(' + counter + ')" ></td>';
        cols += '<td class="none"><input type="text" id="cgstamount' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="cgstamount[]" readonly ></td>';
        cols += '<td class="none"><input type="text" id="igst' + counter + '" onKeyPress="return onlyNumberKey(event)" readonly name="igst[]" oninput="calculate(' + counter + ')" ></td>';
        cols += '<td class="none"><input type="text" id="igstamount' + counter + '" onKeyPress="return onlyNumberKey(event)" name="igstamount[]"  readonly ></td>';
        cols += '<td><input type="text" id="total' + counter + '" onKeyPress="return onlyNumberKey(event)" name="total[]"  readonly ></td>';
        cols += '<td><input type="button" class="ibtnDel " style="color: #fff; background: rgb(128, 0, 0); border-bottom: rgb(128, 0, 0); border-radius: 7px;"  value="Delete"></td>';
        newRow.append(cols);
        $("table.order-lists").append(newRow);
        newRow.find('[name="quantity[]"]').focus().select();
        getbybarcodenumber(barcode, counter);
    }

    function getbybarcodenumber(name, numb) {
        if (name != "") {
            $('#accountListt' + numb).html('');
            $('#accountListtname' + numb).html('');
            $.ajax({
                url: "{{url('getbybarcodenumber')}}",
                type: "POST",
                data: {
                    name: name,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status === true) {
                        $('#itemcode' + numb).val(data.gatdata.itemcode);
                        $('#itemname' + numb).val(data.gatdata.name);
                        $('#balance' + numb).val(data.balance);
                        $('#hsn' + numb).val(data.gatdata.hsn);
                        $('#quantity' + numb).val('0');
                        $('#unit' + numb).val(data.gatdata.unit);
                        $('#mrp' + numb).val(data.gatdata.mrp);
                        $('#salerate' + numb).val(data.gatdata.salerate);
                        $('#purchaserate' + numb).val(data.gatdata.purchaserate);
                        $('#discount' + numb).val('0');
                        var varibalgst = $('#gstnoforref').val();
                        var gstnoown = "{{ $master->gstno }}";
                        var firstTwoChars = gstnoown.substring(0, 2);
                        if (parseFloat(varibalgst) == parseFloat(firstTwoChars)) {
                            $('#sgst' + numb).val(data.gatdata.pursgstamount);
                            $('#cgst' + numb).val(data.gatdata.purcgstamount);
                            $('#igst' + numb).val('0');
                        } else {
                            $('#sgst' + numb).val('0');
                            $('#cgst' + numb).val('0');
                            $('#igst' + numb).val(data.gatdata.purigstamount);
                        }
                        calculate(numb);
                    } else {
                        $('#myTable tr:last').remove();
                    }
                }
            });
        }
        $("#barcode").val('');
    }

    $(document).on('input', 'input[name^="quantity[]"]', function() {
        var inputValue = $(this).val();
        console.log("pichli input:", inputValue);
        var numericValue = Number(inputValue);
        if (!isNaN(numericValue)) {
            $(this).val(numericValue);
        } else {
            console.log("Invalid input:", inputValue);
        }
    });
    $(document).on('click', function() {
        $('.error').removeClass('error');
    });
    setInterval(function() {
        $('.error').removeClass('error');
    }, 3000);
    $(document).ready(function() {
        $('input#barcode').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                getdatat();
                $('input#search').focus().select();
            }
        });
        $('input#search').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                $('#invpicetype').focus().select();
            }
        });
        $('#invpicetype').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                $('input#invoiceno').focus().select();
            }
        });
        $('input#invoiceno').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                $('input#invoicenodate').focus().select();
            }
        });
        $('input#invoicenodate').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                $('#mode').focus().select();
            }
        });
        $('#mode').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                $('#effectstock').focus().select();
            }
        });
        $('#effectstock').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                $('input#accountcode').focus().select();
            }
        });
        $('input#accountcode').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                $('input#accountname').focus().select();
            }
        });
     //    $('input#currentbalance').keypress(function(event) {
     //        if (event.which === 13) {
     //            event.preventDefault();
     //            $('input#accountname').focus().select();
     //        }
     //    });
        $('input#accountname').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                $('input#currentbalance').focus().select();
            }
        });
        $('input#currentbalance').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                $('input#vehichleno').focus().select();
            }
        });
        $('input#vehichleno').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                $('#itemcode0').focus().select();
            }
        });

        $('input#payment').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                $('input#refund').focus().select();
            }
        });
        $('input#refund').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                $('input#cashrecieved').focus().select();
            }
        });
        $('input#cashrecieved').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                $('input#cardpayment').focus().select();
            }
        });
        $('input#cardpayment').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                $('input#creditpayment').focus().select();
            }
        });
        $('input#creditpayment').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
             //    $('#paymenttype').focus().select();

                if ($('#paymenttype').is(':disabled')) {
             $('#ssss').focus().select();
         } else {
             $('#paymenttype').focus().select();
         }


            }
        });
        $('#paymenttype').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                $('#ssss').focus().select();
            }
        });


    });

    $(document).on('keydown', function(event) {
     if (event.key === "Escape") {
         event.preventDefault(); // Prevent the default action, if any
        console.log('visibility');
         $('#modalt').css('visibility', 'hidden');
         $('#tt').empty();
         console.log('visibilityvisibility');
         $('#modall').css('visibility', 'hidden');
         $('#ttt').empty();
     }
 });
    $(document).ready(function() {
        $('#inputform').on('keydown', 'input', function(event) {




            if (event.key === "Enter" && document.activeElement.id === "ssss") {
                event.preventDefault();
                $('#ssss').click();
            }
            if (event.key === "Enter" && document.activeElement.getAttribute("name") === "itemcode[]") {
                event.preventDefault();
                var $elesInput = $(this);
                var itemnameValue = $elesInput.val().trim();

                if (itemnameValue.trim() === '') {
                 $elesInput.closest('tr').remove(); // Remove the closest table row
                    $('input#payment').focus().select();
                    return;
                    console.log('emptyhai');
                } else {
                    console.log('emptynaihai');
                }
            }
            if (event.key === "Enter" && document.activeElement.getAttribute("name") === "quantity[]") {
                event.preventDefault();
                var $quantityInput = $(this);
                var $row = $quantityInput.closest('tr');
                var quantityValue = $quantityInput.val().trim();
                var itemcodeValue = $row.find('input[name="itemcode[]"]').val().trim();
                var itemnameValue = $row.find('input[name="itemname[]"]').val().trim();
                var itembaltype = $row.find('select[name="baltype[]"]').val().trim();



                // Extract the numerical part from the ID
     var quantityInputId = $quantityInput.attr('id');
     var quantityIndexvalll = quantityInputId.match(/\d+$/)[0]; // This will give you the numerical part as a string


                if (itemnameValue.trim() === '') {
                    $('input#payment').focus().select();
                } else {
                    if (quantityValue.trim() === '' || parseInt(quantityValue) < 1) {
                        $(this).addClass('error');
                        console.log("Quantity value is 0. Cannot add row.");


                    } else {

                     if(itembaltype=='box'){
                         var itemnbalance = $row.find('input[name="balance[]"]').val().trim();
                     }else{
                         var itemnbalance = $row.find('input[name="sbalance[]"]').val().trim();
                     }
                             if(parseInt(quantityValue)<=parseInt(itemnbalance)){


                          $('#addrow').click();

                             }else{
                             var rema=(parseInt(quantityValue)-parseInt(itemnbalance));
                             var setti=(parseInt(quantityValue)-parseInt(rema));
                             // alert(setti);
                             $(this).val(setti);

                             $(this).focus().select();

     var htmdddl='<h3>Add Access Items to Purchase Order?</h3><p style="text-align: center; padding: 14px;"><button class="btn btn-danger" type="button"   onclick="addtopurchase(\'' + itemcodeValue + '\',' + rema + ',' + quantityIndexvalll + ')"  style="font-size: 12px;margin: 2px;" >Yes</button><button type="button" class="btn  btn-danger" onclick="closeModasadasdl(' + quantityIndexvalll + ');"  style="font-size: 12px;margin: 2px;" >No</button></p>';
     openModalsss(htmdddl);
 }
                }
                }
            } else {
                if (event.which === 13) {
                    var $target = $(event.target);
                    if ($target.is('table') || $target.closest('table').length > 0) {


                        if (event.key === "Enter" && document.activeElement.getAttribute("name") === "itemcode[]") {
                            event.preventDefault();
                            var itemcodeId = document.activeElement.id;

                            if (document.activeElement.value.trim() !== "") {

                                var itemValue = document.activeElement.value;
                                var itemcodeNumber = itemcodeId.match(/\d+$/);
                                if (itemcodeNumber) {
                                    itemcodeNumber = parseInt(itemcodeNumber[0], 10);
                                    var accountcode=$('#gstnoforref').val();
                                   if(accountcode!== ""){
                                     boardsitemgetitems(itemValue, itemcodeNumber);
                                    document.getElementById(itemcodeId).blur();
                                 }else{
                                     $('#accountcode').addClass('error');
                                     $('#accountcode').focus().select();
                                 }
                                } else {
                                    console.log('No numeric part found in the id');
                                }
                            } else {
                                $('input#payment').focus().select();
                            }
                        }

                     //    event.preventDefault();
                     //    var $this = $(this);
                     //    var $inputs = $('#inputform').find('input');
                     //    var index = $inputs.index($this);
                     //    var nextIndex = index + 1;
                     //    if (nextIndex < $inputs.length) {
                     //        $inputs.eq(nextIndex).focus().select();
                     //    }
                    }
                }
            }
        });
    });

    $(document).ready(function() {
        let selectedRowIndex = -1;

        function selectRow(index) {
            const rows = $('#myTableproducts tr');
            if (index >= 1 && index < rows.length) {
                rows.removeClass('selected');
                $(rows[index]).addClass('selected');
                selectedRowIndex = index;
            }
        }

        $(document).keydown(function(e) {
            const rows = $('#myTableproducts tr');
            if (e.key === 'ArrowDown') {
                if (selectedRowIndex < rows.length - 1) {
                    selectRow(selectedRowIndex + 1);
                }
            } else if (e.key === 'ArrowUp') {
                if (selectedRowIndex > 1) {
                    selectRow(selectedRowIndex - 1);
                }
            }
        });
        selectRow(1);
    });

    function boardsitemgetitems(name, numb) {

        if (name != "") {
            // $('#accountListt' + numb).html('');
            // $('#accountListtname' + numb).html('');
            $.ajax({
                url: "{{url('sitemgetitems')}}",
                type: "POST",
                data: {
                    name: name,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {



                    $('#modalt').css('visibility', 'visible');
                    $('#tt').empty();
                    $.each(data, function(index, item) {



                        var $row = $('<tr id="' + item.rateid + ',' + numb + '">');
                        $row.append('<td>' + item.itemcode + '</td>');
                        $row.append('<td>' + item.name + '</td>');
                        $row.append('<td>' + item.mrp + '</td>');
                        $row.append('<td>' + item.salerate + '</td>');
                        $row.append('<td>' + item.purchaserate + '</td>');
                        $('#myTableproducts').append($row);
                    });


                    let selectedRowIndex = -1;

                    function selectRow(index) {
                        const rows = $('#myTableproducts tr');
                        if (index >= 1 && index < rows.length) {
                            rows.removeClass('selected');
                            $(rows[index]).addClass('selected');
                            selectedRowIndex = index;
                        }
                    }

                    $(document).keydown(function(e) {
                        const rows = $('#myTableproducts tr');
                        if (e.key === 'ArrowDown') {
                            if (selectedRowIndex < rows.length - 1) {
                                selectRow(selectedRowIndex + 1);
                            }
                        } else if (e.key === 'ArrowUp') {
                            if (selectedRowIndex > 1) {
                                selectRow(selectedRowIndex - 1);
                            }
                        }
                    });
                    selectRow(1);
                }
            });
        }
    }
    $('.modal-close').click(function(e) {
        $('#modalt').css('visibility', 'hidden');
    });




    $(document).ready(function() {
        $(document).on('keydown', function(event) {
            if (event.which === 13) {
                var selectedId = $('#myTableproducts .selected').attr('id');

                if (selectedId) {
                    var [name, number] = selectedId.split(',');
                    sgetitemdata(name, number);
                    $('#modalt').css('visibility', 'hidden');
                    $('#myTableproducts #tt').empty();
                } else {
                    console.log('No row selected.');
                }
            }
        });
    });


 function suggest(name) {
     if (name != "") {
         $('#accountList').html('');
         $.ajax({
             url: "{{url('sgetitems')}}",
             type: "POST",
             data: {
                 name: name,
                 _token: '{{csrf_token()}}'
             },
             dataType: 'json',
             success: function(data) {


                 $('#modall').css('visibility', 'visible');
                 $('#ttt').empty();

                 if(data.length==0){
                     var $row = $('<tr >');
                    $row.append('<td colspan=5>No Records Found</td>');
                     $('#myTableproductss').append($row);
                     $('#accountcode').focus().select();
                 }else{
                 $.each(data, function(index, item) {

                     var $row = $('<tr id="' + item.retailercode + '">');
                    $row.append('<td>' + item.retailercode + '</td>');
                     $row.append('<td>' + item.name + '</td>');
                     $row.append('<td>' + item.phone + '</td>');
                     $row.append('<td>' + item.designation + '</td>');
                     $row.append('<td>' + item.address + '</td>');
                     $('#myTableproductss').append($row);
                 });


                 let selectedRowIndex = -1;

 function selectRoww(index) {
     const rows = $('#myTableproductss tr');
     if (index >= 1 && index < rows.length) {
         rows.removeClass('selected');
         $(rows[index]).addClass('selected');
         selectedRowIndex = index;
     }
 }

 $(document).keydown(function(e) {
     const rows = $('#myTableproductss tr');
     if (e.key === 'ArrowDown') {
         if (selectedRowIndex < rows.length - 1) {
             selectRoww(selectedRowIndex + 1);
         }
     } else if (e.key === 'ArrowUp') {
         if (selectedRowIndex > 1) {
             selectRoww(selectedRowIndex - 1);
         }
     }
 });
 selectRoww(1);
             }



             }
         });
     }
 }



 $(document).ready(function() {
     $(document).on('keydown', function(event) {
         if (event.which === 13) {
             var selectedId = $('#myTableproductss .selected').attr('id');

             if (selectedId) {
                 sgetretaildata(selectedId);
                 $('#modall').css('visibility', 'hidden');
                 $('#myTableproductss #ttt').empty();
             } else {
                 console.log('No row selected.');
             }
         }
     });
 });

 function sgetretaildata(name) {
     if (name != "") {
         $('#accountList').html('');
         $.ajax({
             url: "{{url('sgetretaildata')}}",
             type: "POST",
             data: {
                 name: name,
                 _token: '{{csrf_token()}}'
             },
             dataType: 'json',
             success: function(data) {
                 $('#accountcode ').val(name);
                 $('#accountname').val(data.gatdatabyitemcode.name);
                 $('#currentbalance').val(data.curbal);

                 var gsss = data.gatdatabyitemcode.gstno.substring(0, 2);
                 $('#gstnoforref').val(gsss);
                 var inputs = document.querySelectorAll('input[name="itemcode[]"]');
                 inputs.forEach(function(input) {
                     var oninputValue = input.getAttribute('oninput');
                     var number = oninputValue.match(/\d+/)[0];
                     var value = input.value;
                     sgetitemdata(value, number);

                 });
             }
         });
     }
 }


 document.getElementById('accountcode').addEventListener('keypress', function(event) {
     if (event.key === "Enter") {
         event.preventDefault();
         let accountCodeValue = this.value;
         suggest(accountCodeValue);
     }
 });

 $(document).ready(function() {
             $('#invpicetype').focus().select();
         });








 const openModal = document.querySelector(".open-modal");

 const modal = document.querySelector(".modalid");
 const closeModal = document.querySelector("button.close-modal");
 openModal.addEventListener("click", () => {


     $.ajax({
             url: "{{url('getholdsale')}}",
             type: "GET",
             dataType: 'json',
             success: function(data) {




                 if(data.length==0){

                 }else{
                     $('#appen').empty();
                 $.each(data, function(index, item) {

                     var $row = $('<tr>');
                    $row.append('<td>' + item.invoiceno + '</td>');
                    $row.append('<td>' + item.party_name + '</td>');
                     $row.append('<td>' + item.party_code + '</td>');
                     $row.append('<td>' + item.total + '</td>');
                     $('#appen').append($row);
                 });
                 modal.showModal();
             }



             }
         });


 //
 });
 closeModal.addEventListener("click", () => {
   modal.close();
 });






 function openModalsss(html) {
   const modal = `
       <div class="modal-wrapper">
           <div class="modal-container">
              ${html}
           </div>
       </div>`;
   document.querySelector("body").insertAdjacentHTML("beforeend", modal);
   document.querySelector("body").style.overflow = "hidden";
 }
 function closeModasadasdl(vall) {
     calculate(vall);
     $('#quantity'+vall).focus().select();
     if (document.querySelector(".modal-wrapper"))
     document.querySelectorAll(".modal-wrapper").forEach((el) => el.remove());
   document.querySelector("body").style.overflow = "";
 }
 function addtopurchase(code,quantity,vall) {
 $.ajax({
                url: "{{url('addtopurchase')}}",
                type: "POST",
                data: {
                 code: code,
                 quantity: quantity,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {

     closeModasadasdl(vall);


                  }
            });
 }









 // var lastKeypressTime = 0;
 //    var scannerInputTimeout = null;
 //    var scannerInput = '';
 //    var getkro = "";
 //    var oldidoldid = "";
 //    var oldid = "";
 //    document.addEventListener("keypress", function(e) {
 //        oldid = e.target.id;

 //        if (oldid === '') {
 //            var currentTime = new Date().getTime();
 //            var timeSinceLastKeypress = currentTime - lastKeypressTime;
 //            if (timeSinceLastKeypress > 50) {
 //                scannerInput = '';
 //            } else {
 //                document.getElementById("barcode").focus();
 //            }
 //            if (/^\d$/.test(e.key)) {
 //                scannerInput += e.key;
 //            }
 //            lastKeypressTime = currentTime;
 //            clearTimeout(scannerInputTimeout);
 //            scannerInputTimeout = setTimeout(function() {
 //                if (scannerInput.length > 5) {
 //                    e.preventDefault();
 //                    $("#barcode").val(scannerInput);
 //                    var isValid = true;
 //                    $('input[name^="quantity[]"]').each(function() {
 //                        var quantityValue = $(this).val();
 //                        if (parseInt(quantityValue) < 1) {
 //                            $(this).addClass('error');
 //                            $(this).focus();
 //                            isValid = false;
 //                            return false;
 //                        }
 //                    });
 //                    if (isValid) {
 //                        getdatat();
 //                    } else {
 //                        $("#barcode").val('');
 //                    }
 //                }
 //                var inputs = document.querySelectorAll('input[name="itemcode[]"]');
 //                inputs.forEach(function(input) {
 //                    var oninputValue = input.getAttribute('oninput');
 //                    var number = oninputValue.match(/\d+/)[0];
 //                    var value = input.value;
 //                    calculate(number);
 //                });
 //                scannerInput = '';
 //                getkro = '';
 //                oldidoldid = '';
 //                oldid = '';
 //            }, 50);
 //        } else {
 //            if (getkro === '') {
 //                oldidoldid = oldid;
 //                getkro = $('#' + oldid).val();


 //            }
 //            var currentTime = new Date().getTime();
 //            var timeSinceLastKeypress = currentTime - lastKeypressTime;
 //            if (timeSinceLastKeypress > 50) {
 //                scannerInput = '';
 //            } else {
 //                document.getElementById("barcode").focus();
 //            }
 //            if (/^\d$/.test(e.key)) {
 //                scannerInput += e.key;
 //            }
 //            lastKeypressTime = currentTime;
 //            clearTimeout(scannerInputTimeout);
 //            scannerInputTimeout = setTimeout(function() {
 //                if (scannerInput.length > 5) {
 //                    e.preventDefault();
 //                    $("#barcode").val(scannerInput);
 //                    if (oldidoldid !== 'barcode') {

 //                        $('#' + oldidoldid).val(getkro);
 //                    }
 //                    var isValid = true;
 //                    $('input[name^="quantity[]"]').each(function() {
 //                        var quantityValue = $(this).val();
 //                        if (parseInt(quantityValue) < 1) {
 //                            $(this).addClass('error');
 //                            $(this).focus();
 //                            isValid = false;
 //                            return false;
 //                        }
 //                    });
 //                    if (isValid) {
 //                        getdatat();
 //                    } else {
 //                        $("#barcode").val('');
 //                    }
 //                }
 //                var inputs = document.querySelectorAll('input[name="itemcode[]"]');
 //                inputs.forEach(function(input) {
 //                    var oninputValue = input.getAttribute('oninput');
 //                    var number = oninputValue.match(/\d+/)[0];
 //                    var value = input.value;
 //                    calculate(number);
 //                });
 //                scannerInput = '';
 //                getkro = '';
 //                oldidoldid = '';
 //                oldid = '';
 //            }, 50);
 //        }
 //    });


 </script>

  @include('include.footer')
