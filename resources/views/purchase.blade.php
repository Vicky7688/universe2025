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
    .modal {
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
      .modal-content {
        max-height: 95%;
        padding: 40px;
        background: white;
        border-radius: 5px;
        overflow: auto;
        position: relative;
      }

    }

    .modal-close {
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


    </style>
<div class="row">
  <div class="col-xl-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-2 col-md-3">
                    <select id="selectize-optgroup" name="brand"  placeholder="Search Invoice" onchange="getdatabyinvoice(this.value)">
                        <option value="">Select</option>
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
              <select class="form-select" name="invoicetype" id="invpicetype">
                <option value="">Select Tax Invoice</option>
                <option value="Vat Invoice" selected>Vat Invoice</option>
                <option value="Retail Invoice">Retail Invoice</option>
              </select>
            </div>
            <div class="col-sm-2 col-md">
              <label >G.R.No.</label>
              <input class="form-control" type="text" name="grno" value="" id="grno">
            </div>
            <div class="col-sm-2 col-md">
              <label >GR.Date</label>
              <input class="form-control onlydate" type="text" name="grdate" id="grdate" value="{{ date('d-m-Y') }}">
            </div>
            <div class="col-sm-2 col-md">
              <label >Invoice No.</label>
              <input class="form-control" type="text" name="invoiceno" id="invoiceno">
            </div>
            <div class="col-sm-2 col-md">
              <label for="Date">Date</label>
              <input class="form-control onlydate" type="text" name="invoicedate" id="invoicedate" value="{{ date('d-m-Y') }}">
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-sm-2 col-md-3">
              <label >Customer</label>
              <input type="hidden" id="gstnoforref">
              <input type="text" class="form-control" name="accountcode" id="accountcode" >
            </div>
            <div class="col-sm-2 col-md-3">
              <label >Name</label>
              <input type="text" class="form-control" name="accountname" id="accountname">
            </div>
            <div class="col-sm-2 col-md-3">
              <label >Address</label>
              <input type="text" class="form-control" name="accountaddress" id="accountaddress">
            </div>
            <div class="col-sm-2 col-md-3">
              <label >Address</label>
              <input type="text" class="form-control" name="accountaddresss" id="accountaddresss">
            </div>
          </div>
          <div class="row">
            <div class="col-sm-2 col-md-3">
              <label >Transport Name</label>
              <input type="text" class="form-control"  name="transportname" id="transportname">
            </div>
            <div class="col-sm-2 col-md-3">
              <label >Vehicle No.</label>
              <input type="text" class="form-control" name="transportvehicleno" id="transportvehicleno">
            </div>
            <div class="col-sm-2 col-md-3">
              <label >G.R.No./Dated</label>
              <input type="text" class="form-control onlydate" id="transportgrno" name="transportgrno" value="{{ date('d-m-Y') }}">
            </div>
            <div class="col-sm-2 col-md-3">
              <label >CASH/BILL</label>
              <select name="memo" id="memo" class="form-select">
                <option selected value="Bill Memo">Bill Memo</option>
                <option value="Cash Memo">Cash Memo</option>
              </select>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <table id="myTable" class="table align-items-center table-bordered mb-0 order-lists" >
              <thead class="tableHeading">
                <tr class="text-center" id="table-header-row">
                  <th>I.Code</th>
                  <th>I.Name</th>
                  <th  class="none">Margin</th>
                  <th>Purchase.In</th>
                  <th class="none">Box/Bal</th>
                  <th class="none">Per/Bal</th>
                  <th class="none">HSN </th>
                  <th>Qty</th>
                  <th class="none">Unit</th>
                  <th>MRP</th>
                  <th>SaleRate</th>
                  <th>Rate</th>
                  <th>Discount%</th>
                  <th>Net.Amt</th>
                  <th  class="none" >SGST</th>
                  <th class="none">Sg.Amt</th>
                  <th  class="none" >CGST</th>
                  <th class="none">Cg.Amt</th>
                  <th  class="none" >IGST</th>
                  <th class="none">Ig.Amt</th>
                  <th>Tot.Amt</th>
                  <th></th>
                </tr>
              </thead>
              <tbody class="tableBody">
                <tr> {{-- oninput="itemgetitems(this.value,0)" --}}
                  <td class="none"><input type="text" id="getunit0" name="getunit[]"></td>
                  <td><input type="text"  class="form-control" id="itemcode0" name="itemcode[]"></td>
                  <td><input type="text" id="itemname0"  class="form-control"   name="itemname[]">
                    <div id="accountListtname0" class="accountList"> </div></td>
                  <td class="none"><input type="text" id="margin0" name="margin[]"></td>
                  <td><select name="baltype[]" class="form-select" id="baltype0" onChange="calculateis(0)" >
                      <option value="box">Box</option>
                      <option value="single">Single</option>
                    </select></td>
                  <td class="none"><input type="text" id="balance0" name="balance[]"></td>
                  <td class="none"><input type="text" id="sbalance0" name="sbalance[]"></td>
                  <td class="none"><input type="text" id="hsn0" name="hsn[]"></td>
                  <td><input type="text"  class="form-control" id="quantity0"  onKeyPress="return onlyNumberKey(event)"  name="quantity[]" oninput="calculate(0)" ></td>
                  <td class="none"><input type="text" id="unit0" name="unit[]"></td>
                  <td><input type="text" class="form-control"  id="mrp0" onKeyPress="return onlyNumberKey(event)"  name="mrp[]"></td>
                  <td><input type="text" class="form-control"  id="salerate0"  onKeyPress="return onlyNumberKey(event)"  name="salerate[]"></td>
                  <td><input type="text" class="form-control"  id="purchaserate0"  onKeyPress="return onlyNumberKey(event)"  name="purchaserate[]" oninput="calculate(0)" ></td>
                  <td><input type="text" class="form-control" id="discount0"  onKeyPress="return onlyNumberKey(event)"  name="discount[]" oninput="calculate(0)" ></td>
                  <td  class="none" ><input type="text" id="discountamt0"  onKeyPress="return onlyNumberKey(event)"  name="discountamt[]" oninput="calculate(0)" ></td>
                  <td><input type="text" class="form-control" id="netamount0"  onKeyPress="return onlyNumberKey(event)"  name="netamount[]" oninput="calculate(0)" ></td>
                  <td  class="none" ><input type="text" id="sgst0" onKeyPress="return onlyNumberKey(event)" name="sgst[]" oninput="calculate(0)" ></td>
                  <td class="none"><input type="text" class="form-control" id="sgstamount0"  onKeyPress="return onlyNumberKey(event)"  name="sgstamount[]" readonly ></td>
                  <td  class="none" ><input type="text" id="cgst0" onKeyPress="return onlyNumberKey(event)" name="cgst[]" oninput="calculate(0)" ></td>
                  <td class="none"><input type="text" class="form-control" id="cgstamount0"  onKeyPress="return onlyNumberKey(event)"  name="cgstamount[]" readonly ></td>
                  <td  class="none" ><input type="text" id="igst0" onKeyPress="return onlyNumberKey(event)" name="igst[]" oninput="calculate(0)" ></td>
                  <td class="none"><input type="text" class="form-control" id="igstamount0" onKeyPress="return onlyNumberKey(event)" name="igstamount[]"  readonly ></td>
                  <td><input type="text" class="form-control" id="total0" onKeyPress="return onlyNumberKey(event)" name="total[]"  readonly ></td>
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
            <div class="col-md-10">
              <div class="row">
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-12">
                      <label >Basic Amt.</label>
                      <input type="text" class="form-control" name="basictotalamount" id="basictotalamount" readonly value="0.00">
                    </div>
                    <div class="col-md-12">
                      <label >SGST Amt.</label>
                      <input type="text"   class="form-control" name="sgsttotal"  id="sgsttotal" readonly value="0.00">
                    </div>
                    <div class="col-md-12">
                      <label >Igst Amt.</label>
                      <input type="text"  class="form-control" name="igsttotal"  id="igsttotal" readonly value="0.00">
                    </div>
                    <div class="col-md-12">
                      <label >CGST Amt.</label>
                      <input type="text" class="form-control" name="cgsttotal"  id="cgsttotal" readonly value="0.00">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-12">
                      <label >Uploading/Loading</label>
                      <div class="input-group ">
                        <select class="form-select" name="uploadingloadingname" id="uploadingloadingname">
                          <option value="U0001">Uploading/Loading</option>
                        </select>
                        <input type="text" class="form-control"  id="uploadingloading"  name="uploadingloading" oninput="calculate(0)" value="0.00">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <label >Frieght & Cart</label>
                      <div class="input-group ">
                        <select class="form-select" name="frieghtname" id="frieghtname">
                          <option value="F0001">Frieght & Cart</option>
                        </select>
                        <input type="text" class="form-control" id="frieght"  name="frieght" oninput="calculate(0)"   value="0.00">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <label >Total</label>
                      <input type="text"  class="form-control "  readonly name="totalamount" id="totalamount" value="0.00">
                    </div>
                    <div class="col-md-12">
                      <label >Discount</label>
                      <input type="text"  class="form-control "  readonly name="distotal" readonly id="distotal"  value="0.00">
                    </div>
                    <div class="col-md-12">
                      <label >Grand Total</label>
                      <input type="text"  class="form-control "  readonly  name="grandtotal" readonly id="grandtotal"  value="0.00">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="row mt-4">

                {{--
                <button type="submit"  id="ssss" class="btn" name="save" >Save Bill</button> 
                --}}
                <button onClick="changevalue('save')" class="btn btn-primary" type="submit" id="ssss" style="font-size: 12px" name="submit" >Save Bill</button>
                {{--
                <input name="submit"   class="btn"  id="ssss" type="submit" value="Save Bill"/>
                --}} </div>

              <div class="row mt-4">

                {{--
                <button class="btn" style="font-size: 12px">Hold Bill</button>
                --}}
                <button onClick="changevalue('hold')" class="btn btn-primary" type="submit" id="sssss" style="font-size: 12px" name="submit">Hold Bill</button>
                {{--
                <input name="submit"   class="btn"  type="submit" value="Hold Bill"/>
                --}} </div>
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

<div id="modal" class="modal">
    <div class="modal-content">
      {{-- <a href="#" title="Close" class="modal-close">Close</a> --}}

            <table id="myTableproducts" >
                <thead class="pro">
                <tr>
                    <th>Item Code</th>
                    <th>Item Name 	</th>
                    <th>MRP Rate </th>
                    <th>Sale Rate</th>
                    <th>Purchase Rate</th>
                    <th>Single MRP Rate</th>
                    <th>Single Sale Rate</th>
                    <th>Single Purchase Rate</th>
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

<script>
    function getretaildata(name) {
        if (name != "") {
            $('#accountList').html('');
            $.ajax({
                url: "{{url('getretaildata')}}",
                type: "POST",
                data: {
                    name: name,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {
                    $('#accountcode ').val(name)
                    $('#accountname').val(data.name)
                    $('#accountaddress').val(data.address)
                    $('#accountaddresss').val(data.addresss)
                    var gsss = data.gstno.substring(0, 2);
                    $('#gstnoforref').val(gsss);
                    var inputs = document.querySelectorAll('input[name="itemcode[]"]');
                    inputs.forEach(function(input) {
                        var oninputValue = input.getAttribute('oninput');
                        var number = oninputValue.match(/\d+/)[0];
                        var value = input.value;
                        getitemdata(value, number);
                    });
                }
            });
        }
    }

    function suggest(name) {
        if (name != "") {
            $('#accountList').html('');
            $.ajax({
                url: "{{url('getitems')}}",
                type: "POST",
                data: {
                    name: name,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(index, item) {
                        $('#accountList').append('<ul><li onclick="getretaildata(\'' + index + '\')">' + index + '(' + item + ')</li></ul>');
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
        $(document).on('keydown', function(event) {
            if (event.which === 13) {

                var selectedId = $('#myTableproductss .selected').attr('id');

                if (selectedId) {

                   getretaildata(selectedId);
                    $('#modall').css('visibility', 'hidden');
                    $('#myTableproductss #ttt').empty();
                } else {
                    console.log('No row selected.');
                }
            }
        });
    });


    function suggest(name) {
        if (name != "") {
            // $('#accountList').html('');
            $.ajax({
                url: "{{url('getitems')}}",
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
                        console.log(item);
                        var $row = $('<tr id="' + item.retailercode + '">');
                       $row.append('<td>' + item.retailercode + '</td>');
                        $row.append('<td>' + item.name + '</td>');
                        $row.append('<td>' + item.phone + '</td>');
                        $row.append('<td>' + item.designation + '</td>');
                        $row.append('<td>' + item.address + '</td>');
                        $('#myTableproductss').append($row);
                    });
                    $('input#accountname').focus().select();

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
                   var selectedId = $('#myTableproducts .selected').attr('id');

                   if (selectedId) {
                       var [name, number] = selectedId.split(',');
                       getitemdata(name, number);
                       $('#modal').css('visibility', 'hidden');
                       $('#myTableproducts #tt').empty();
                   } else {
                       console.log('No row selectedqqqq.');
                   }
               }
           });
       });

    function itemgetitems(name, numb) {
        if (name != "") {
            $('#accountListt' + numb).html('');
            $('#accountListtname' + numb).html('');
            $.ajax({
                url: "{{url('itemgetitems')}}",
                type: "POST",
                data: {
                    name: name,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {




                    $('#modal').css('visibility', 'visible');
                       $('#tt').empty();
                       $.each(data, function(index, item) {


                           var $row = $('<tr id="' + item.rateid + ',' + numb + '">');
                           $row.append('<td>' + item.itemcode + '</td>');
                           $row.append('<td>' + item.name + '</td>');
                           $row.append('<td>' + item.mrp + '</td>');
                           $row.append('<td>' + item.salerate + '</td>');
                           $row.append('<td>' + item.purchaserate + '</td>');
                           $row.append('<td>' + item.mrpsingle + '</td>');
                           $row.append('<td>' + item.saleratesingle + '</td>');
                           $row.append('<td>' + item.purchaseratesingle + '</td>');
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

    function itemgetitemsname(name, numb) {
        if (name != "") {
            $('#accountListtname' + numb).html('');
            $.ajax({
                url: "{{url('itemgetitemsname')}}",
                type: "POST",
                data: {
                    name: name,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(index, item) {
                        $('#accountListtname' + numb).append('<ul><li onclick="getitemdata(\'' + index + '\',\'' + numb + '\')">' + index + '(' + item + ')</li></ul>');
                    });
                }
            });
        }
    }

    function calculateis(numb) {
        var baltype = $('#baltype' + numb).val();
         var getunit = $('#getunit' + numb).val();
            if(baltype=='single'){








            $.ajax({
                url: "{{url('getitemdataunit')}}",
                type: "POST",
                data: {
                    name: getunit,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {


                    $('#itemcode' + numb).val(data.gatdata.itemcode);
                    $('#itemname' + numb).val(data.gatdata.name);
                    $('#balance' + numb).val(data.balance);
                    $('#sbalance' + numb).val(data.gatdata.singleopeningstock);
                    $('#hsn' + numb).val(data.gatdata.hsn);
                    $('#quantity' + numb).val('0');
                    $('#unit' + numb).val(data.gatdata.unit);

                    $('#mrp' + numb).val(data.getit.mrpsingle);
                    $('#salerate' + numb).val(data.getit.saleratesingle);
                    $('#purchaserate' + numb).val(data.getit.purchaseratesingle);

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
                    $('#quantity' + numb).focus().select();

                }
            });






            }else{



            $.ajax({
                url: "{{url('getitemdataunit')}}",
                type: "POST",
                data: {
                    name: getunit,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {


                    $('#itemcode' + numb).val(data.gatdata.itemcode);
                    $('#itemname' + numb).val(data.gatdata.name);
                    $('#balance' + numb).val(data.balance);
                    $('#sbalance' + numb).val(data.gatdata.singleopeningstock);
                    $('#hsn' + numb).val(data.gatdata.hsn);
                    $('#quantity' + numb).val('0');
                    $('#unit' + numb).val(data.gatdata.unit);

                    $('#mrp' + numb).val(data.getit.mrp);
                    $('#salerate' + numb).val(data.getit.salerate);
                    $('#purchaserate' + numb).val(data.getit.purchaserate);

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
                    $('#quantity' + numb).focus().select();

                }
            });



            }
            calculate(numb);
    }
    function calculate(numb) {

        // var uploadingloading = $('#uploadingloading' + numb).val();
        // var cart = $('#cart' + numb).val();



        var rate = $('#purchaserate' + numb).val();
        var quantity = $('#quantity' + numb).val();
        var totalrate = parseFloat(rate) * parseFloat(quantity);
        var discount = $('#discount' + numb).val();
        var getdiscount = (discount / 100) * totalrate;
        var discounttotalprice = (totalrate - getdiscount);


        $('#discountamt' + numb).val(getdiscount.toFixed(2));
        $('#netamount' + numb).val(discounttotalprice.toFixed(2));


        var salerate = $('#salerate' + numb).val();
        var sgst = $('#sgst' + numb).val();
        var cgst = $('#cgst' + numb).val();
        var igst = $('#igst' + numb).val();
        var sgstamount = (sgst / 100) * discounttotalprice;
        $('#sgstamount' + numb).val(sgstamount.toFixed(2));
        var cgstamount = (cgst / 100) * discounttotalprice;
        $('#cgstamount' + numb).val(cgstamount.toFixed(2));
        var igstamount = (igst / 100) * discounttotalprice;
        $('#igstamount' + numb).val(igstamount.toFixed(2));
        var totsgst = parseFloat(sgst) + parseFloat(cgst) + parseFloat(igst);
        var totgstamount = (totsgst / 100) * discounttotalprice;
        var totalwithgst = parseFloat(totgstamount) + parseFloat(discounttotalprice);


        var marg = (salerate * 100) / (parseFloat(rate)+parseFloat(totsgst));

        $('#margin' + numb).val(marg.toFixed(2));
        $('#total' + numb).val(totalwithgst.toFixed(2));

        var totalloall = 0;
        var totallsgst = 0;
        var totalligst = 0;
        var totallcgst = 0;
        var totallonet = 0;
        var highestNetAmount = 0;
        var extracharges = 0;
        var totadiscountamtst = 0;
        $('input[name^="netamount[]"]').each(function() {
            totallonet += parseFloat(this.value, 10) || 0;


            var netAmount = parseFloat($(this).val(), 10) || 0;

        // Update highestNetAmount if the current net amount is greater
        if (netAmount > highestNetAmount) {
            highestNetAmount = netAmount;
        }

        });
        $('input[name^="total[]"]').each(function() {
            totalloall += parseFloat(this.value, 10) || 0;
        });

        $('input[name^="sgstamount[]"]').each(function() {
            totallsgst += parseFloat(this.value, 10) || 0;
        });

        $('input[name^="cgstamount[]"]').each(function() {
            totallcgst += parseFloat(this.value, 10) || 0;
        });
        $('input[name^="igstamount[]"]').each(function() {
            totalligst += parseFloat(this.value, 10) || 0;
        });
        $('input[name^="discountamt[]"]').each(function() {
            totadiscountamtst += parseFloat(this.value, 10) || 0;
        });

        $('#sgsttotal').val(totallsgst.toFixed(2));
        $('#cgsttotal').val(totallcgst.toFixed(2));
        $('#igsttotal').val(totalligst.toFixed(2));
        $('#distotal').val(totadiscountamtst.toFixed(2));

        $('#totalamount').val(totalloall.toFixed(2));
        $('#basictotalamount').val(totallonet.toFixed(2));



        var uploadingloading = $('#uploadingloading').val();
        var frieght = $('#frieght').val();
    // if(frieght>0){
    //     var friegtgst=((highestNetAmount/frieght)*totsgst)/100;
    //     console.log('fr');
    //     console.log(friegtgst);
    // }else{
    //     friegtgst=0;
    // }
    // if(uploadingloading>0){
    //     var upiegtgst=((highestNetAmount/uploadingloading)*totsgst)/100;
    //     console.log('up');
    //     console.log(upiegtgst);
    // }else{
    //     upiegtgst=0;
    // }
    //   extracharges=parseFloat(upiegtgst) + parseFloat(friegtgst)

    // if(extracharges>0){
    // if(totalligst>0){
    //     var totalligstt=parseFloat(extracharges)+parseFloat(totalligst);
    // $('#igsttotal').val(totalligstt.toFixed(2));
    // }else{
    //     var extrachargestwo=parseFloat(extracharges)/2;
    //     var totallsgstt=parseFloat(extrachargestwo)+parseFloat(totallsgst);
    //     var totallcgstt=parseFloat(extrachargestwo)+parseFloat(totallcgst);
    // $('#sgsttotal').val(totallsgstt.toFixed(2));
    // $('#cgsttotal').val(totallcgstt.toFixed(2));

    // }
    // }


        var totwithfr = parseFloat(totalloall) + parseFloat(uploadingloading)+ parseFloat(frieght);
        $('#grandtotal').val(totwithfr.toFixed(2));
        // $('#uploadingloading').val(0.00);
        // $('#frieght').val(0.00);



    }

    function getitemdata(name, numb) {
        if (name != "") {
            $('#accountListt' + numb).html('');
            $('#accountListtname' + numb).html('');
            $.ajax({
                url: "{{url('getitemdata')}}",
                type: "POST",
                data: {
                    name: name,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {


                    $('#getunit' + numb).val(data.getit.id);
                    $('#itemcode' + numb).val(data.gatdata.itemcode);
                    $('#itemname' + numb).val(data.gatdata.name);
                    $('#balance' + numb).val(data.balance);
                    $('#sbalance' + numb).val(data.perbalance);
                    // $('#sbalance' + numb).val(data.gatdata.singleopeningstock);
                    $('#hsn' + numb).val(data.gatdata.hsn);
                    $('#quantity' + numb).val('0');
                    $('#unit' + numb).val(data.gatdata.unit);

                    $('#mrp' + numb).val(data.getit.mrp);
                    $('#salerate' + numb).val(data.getit.salerate);
                    $('#purchaserate' + numb).val(data.getit.purchaserate);

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
                    $('#quantity' + numb).focus().select();
                    calculate(numb);
                }
            });
        }
    }




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
                       $('input#uploadingloading').focus().select();
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
                   var itemnameValue = $row.find('input[name="itemname[]"]').val().trim();
                   if (itemnameValue.trim() === '') {
                       $('input#uploadingloading').focus().select();
                   } else {
                       if (quantityValue.trim() === '' || parseInt(quantityValue) < 1) {
                           $(this).addClass('error');
                           console.log("Quantity value is 0. Cannot add row.");
                       } else {
                           $('#addrow').click();
                       }
                   }
               } else {
                   if (event.which === 13) {
                       var $target = $(event.target);
                       if ($target.is('table') || $target.closest('table').length > 0) {


                           if (event.key === "Enter" && document.activeElement.getAttribute("name") === "itemcode[]") {
                               event.preventDefault();
                               var itemcodeId = document.activeElement.id;
                               console.log(itemcodeId);
                               if (document.activeElement.value.trim() !== "") {

                                   var itemValue = document.activeElement.value;
                                   var itemcodeNumber = itemcodeId.match(/\d+$/);
                                   if (itemcodeNumber) {
                                       itemcodeNumber = parseInt(itemcodeNumber[0], 10);

                                       var accountcode=$('#gstnoforref').val();
                                       if(accountcode!== ""){
                                       itemgetitems(itemValue, itemcodeNumber);
                                       document.getElementById(itemcodeId).blur();
                                       }else{
                                        $('#accountcode').addClass('error');
                                        $('#accountcode').focus().select();
                                       }
                                   } else {
                                       console.log('No numeric part found in the id');
                                   }
                               } else {
                                   $('input#uploadingloading').focus().select();
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


    function getinviocenumber(invoicenumber) {
        if (invoicenumber != "") {
            $('#accountListshow').html('');
            $.ajax({
                url: "{{url('getinviocenumber')}}",
                type: "POST",
                data: {
                    invoicenumber: invoicenumber,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {

                    $.each(data, function(index, item) {

                        $('#accountListshow').append('<ul><li onclick="getdatabyinvoice(\'' + index + '\')">' + item + '</li></ul>');
                    });
                }
            });
        }
    }




    function getdatabyinvoice(invoicenumber) {
        if (invoicenumber != "") {
            $('#accountListshow').html('');
            $.ajax({
                url: "{{url('getdatabyinvoice')}}",
                type: "POST",
                data: {
                    invoicenumber: invoicenumber,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {
                    $("table tbody tr").remove();






                    var counter = 500;

                    $.each(data.getinvoiceitems, function(index, item) {




    // Assuming 'item' is your object containing the data
    var isSelectedBox = item.baltype === 'box';
    var isSelectedSingle = item.baltype === 'single';


    if(item.baltype=='box'){
    var hjo=item.quantity;
    }

    if(item.baltype=='single'){
    var hjo=item.pquantity;
    }
    var newRow = $("<tr>");
    var cols = "";
    cols += '<td class="none"><input  value="' + item.getunit + '"  type="text" id="getunit' + counter + '" name="getunit[]"></td>';
    cols += '<td><input  class="form-control"  value="' + item.itemcode + '" type="text"  id="itemcode' + counter + '"   name="itemcode[]"><div id="accountListt' + counter + '" class="accountList"> </div></td>';
    cols += '<td><input  class="form-control"  value="' + item.itemname + '" type="text" id="itemname' + counter + '"    name="itemname[]"> <div id="accountListtname' + counter + '" class="accountList"> </div></td>';
    cols += '<td class="none"><input value="' + item.margin + '" type="text" id="margin' + counter + '"        name="margin[]"></td>';
    cols += '<td><select   class="form-select"  name="baltype[]" id="baltype' + counter + '" onchange="calculateis(' + counter + ')" ><option value="box"' + (isSelectedBox ? ' selected' : '') + '>Box</option><option value="single"' + (isSelectedSingle ? ' selected' : '') + '>Single</option></select></td>';
    cols += '<td class="none"><input value="' + item.balance + '" type="text" id="balance' + counter + '"        name="balance[]"></td>';
    cols += '<td class="none"><input   value="' + item.sbalance + '"  type="text" id="sbalance' + counter + '"        name="sbalance[]"></td>';
    cols += '<td class="none"><input value="' + item.hsn + '" type="text" id="hsn' + counter + '"          name="hsn[]"></td>';
    cols += '<td><input  class="form-control" value="' + hjo + '" type="text" onKeyPress="return onlyNumberKey(event)"  id="quantity' + counter + '"   name="quantity[]" oninput="calculate(' + counter + ')" ></td>';
    cols += '<td class="none"><input value="' + item.unit + '" type="text"   id="unit' + counter + '"           name="unit[]"></td>';
    cols += '<td><input  class="form-control"  value="' + item.mrp + '" type="text" onKeyPress="return onlyNumberKey(event)"  id="mrp' + counter + '"           name="mrp[]"></td>';
    cols += '<td><input  class="form-control"  value="' + item.salerate + '" type="text" onKeyPress="return onlyNumberKey(event)"  id="salerate' + counter + '"   name="salerate[]"></td>';
    cols += '<td><input  class="form-control"  value="' + item.purchaserate + '" type="text" onKeyPress="return onlyNumberKey(event)"  id="purchaserate' + counter + '"   name="purchaserate[]" oninput="calculate(' + counter + ')" ></td>';
    cols += '<td><input  class="form-control"  value="' + item.discount + '"  type="text" id="discount' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="discount[]" oninput="calculate(' + counter + ')" ></td>';
    cols += '<td  class="none" ><input type="text" id="discountamt' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="discountamt[]" oninput="calculate(' + counter + ')" ></td>';
    cols += '<td><input  class="form-control" value="' + item.netamount + '"  type="text" id="netamount' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="netamount[]" oninput="calculate(' + counter + ')" ></td>';
    cols += '<td  class="none" > <input value="' + item.sgst + '" type="text" onKeyPress="return onlyNumberKey(event)"  id="sgst' + counter + '"   name="sgst[]"  oninput="calculate(' + counter + ')"  ></td>';
    cols += '<td class="none"><input  class="form-control"  value="' + item.sgstamount + '" type="text" onKeyPress="return onlyNumberKey(event)"  id="sgstamount' + counter + '"   name="sgstamount[]"  ></td>';
    cols += '<td  class="none" ><input value="' + item.cgst + '"  type="text" id="cgst' + counter + '" onKeyPress="return onlyNumberKey(event)"   name="cgst[]"   oninput="calculate(' + counter + ')" ></td>';
    cols += '<td class="none"><input  class="form-control" value="' + item.cgstamount + '"  type="text" id="cgstamount' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="cgstamount[]" readonly ></td>';
    cols += '<td  class="none" ><input value="' + item.igst + '" type="text" onKeyPress="return onlyNumberKey(event)"  id="igst' + counter + '"   name="igst[]"  oninput="calculate(' + counter + ')"   ></td>';
    cols += '<td class="none"><input  class="form-control" value="' + item.igstamount + '" type="text" onKeyPress="return onlyNumberKey(event)"  id="igstamount' + counter + '"   name="igstamount[]"   ></td>';
    cols += '<td><input  class="form-control" value="' + item.total + '" type="text" onKeyPress="return onlyNumberKey(event)"  id="total' + counter + '"   name="total[]"   ></td>';
    cols += '<td><input    type="button" class="ibtnDel " style="color: #fff; background: rgb(128, 0, 0); border-bottom: rgb(128, 0, 0); border-radius: 7px;"  value="Delete"></td>';
    newRow.append(cols);
    $("table.order-lists").append(newRow);
    calculate(counter);
    counter++;



                    });




                    $('#search').val(data.getinvoice.invoiceno);
                    // Assuming data.grdate is "2024-06-07 00:00:00"
                    var originalDate = new Date(data.getinvoice.grdate);
                    var day = originalDate.getDate();
                    var month = originalDate.getMonth() + 1;
                    var year = originalDate.getFullYear();
                    day = day < 10 ? '0' + day : day;
                    month = month < 10 ? '0' + month : month;
                    var formatgrdate = day + '-' + month + '-' + year;
                    // Assuming data.grdate is "2024-06-07 00:00:00"
                    var originalDate = new Date(data.getinvoice.invoicedate);
                    var day = originalDate.getDate();
                    var month = originalDate.getMonth() + 1;
                    var year = originalDate.getFullYear();
                    day = day < 10 ? '0' + day : day;
                    month = month < 10 ? '0' + month : month;
                    var formatinvoicedate = day + '-' + month + '-' + year;
                    // Assuming data.grdate is "2024-06-07 00:00:00"
                    var originalDate = new Date(data.getinvoice.transportgrno);
                    var day = originalDate.getDate();
                    var month = originalDate.getMonth() + 1;
                    var year = originalDate.getFullYear();
                    day = day < 10 ? '0' + day : day;
                    month = month < 10 ? '0' + month : month;
                    var formatttransportgrno = day + '-' + month + '-' + year;
                    // Assuming data.grdate is "2024-06-07 00:00:00"
                    $('#invpicetype option').each(function() {
                        if ($(this).val() === data.getinvoice.invoicetype) {
                            $(this).prop('selected', true);
                        } else {
                            $(this).prop('selected', false);
                        }
                    });
                    $('#memo option').each(function() {
                        if ($(this).val() === data.getinvoice.memo) {
                            $(this).prop('selected', true);
                        } else {
                            $(this).prop('selected', false);
                        }
                    });

                    $('#id').val(data.getinvoice.id);
                    $('#grno').val(data.getinvoice.grno);
                    $('#grdate').val(formatgrdate);
                    $('#invoiceno').val(data.getinvoice.invoiceno);
                    $('#invoicedate').val(formatinvoicedate);
                    $('#accountcode').val(data.getinvoice.accountcode);
                    $('#gstnoforref').val(data.getinvoice.gstnoforref);
                    $('#accountname').val(data.getinvoice.accountname);
                    $('#accountaddress').val(data.getinvoice.accountaddress);
                    $('#accountaddresss').val(data.getinvoice.accountaddresss);
                    $('#transportname').val(data.getinvoice.transportname);
                    $('#transportvehicleno').val(data.getinvoice.transportvehicleno);
                    $('#transportgrno').val(data.getinvoice.transportgrno);
                    $('#remarks').val(data.getinvoice.remarks);
                    $('#totalamount').val(data.getinvoice.totalamount);
                    $('#uploadingloading').val(data.getinvoice.uploadingloading);
                    $('#frieght').val(data.getinvoice.cart);
                    $('#grandtotal').val(data.getinvoice.grandtotal);
                    $('#basictotalamount').val(data.getinvoice.basictotalamount);
                    $('#sgsttotal').val(data.getinvoice.sgsttotal);
                    $('#igsttotal').val(data.getinvoice.igsttotal);
                    $('#cgsttotal').val(data.getinvoice.cgsttotal);



                }
            });
        }
    }
    $(document).ready(function() {
        var counter = 1;
        $("#addrow").on("click", function() {
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td class="none"><input type="text" id="getunit' + counter + '" name="getunit[]"></td>';
            cols += '<td><input  class="form-control" type="text"  id="itemcode' + counter + '"   name="itemcode[]"><div id="accountListt' + counter + '" class="accountList"> </div></td>';
            cols += '<td><input  class="form-control" type="text" id="itemname' + counter + '"    name="itemname[]"> <div id="accountListtname' + counter + '" class="accountList"> </div></td>';
            cols += '<td class="none"><input type="text" id="margin' + counter + '"        name="margin[]"></td>';
            cols += '<td><select  class="form-select"  name="baltype[]" id="baltype' + counter + '"  onchange="calculateis(' + counter + ')"><option value="box">Box</option><option value="single">Single</option></select></td>';
            cols += '<td class="none"><input type="text" id="balance' + counter + '"  name="balance[]"></td>';
            cols += '<td class="none"><input type="text" id="sbalance' + counter + '"        name="sbalance[]"></td>';
            cols += '<td  class="none"><input type="text" id="hsn' + counter + '"          name="hsn[]"></td>';
            // cols += '<td><input type="text" id="printlabel' + counter + '"   name="printlabel[]"></td>';
            cols += '<td><input  class="form-control" type="text" onKeyPress="return onlyNumberKey(event)"  id="quantity' + counter + '"   name="quantity[]" oninput="calculate(' + counter + ')" ></td>';
            cols += '<td  class="none"><input type="text"   id="unit' + counter + '"    name="unit[]"></td>';
            cols += '<td><input  class="form-control" type="text" onKeyPress="return onlyNumberKey(event)"  id="mrp' + counter + '"           name="mrp[]"></td>';
            cols += '<td><input  class="form-control" type="text" onKeyPress="return onlyNumberKey(event)"  id="salerate' + counter + '"   name="salerate[]"></td>';
            cols += '<td><input  class="form-control" type="text" onKeyPress="return onlyNumberKey(event)"  id="purchaserate' + counter + '"   name="purchaserate[]" oninput="calculate(' + counter + ')" ></td>';
            cols += '<td><input  class="form-control" type="text" id="discount' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="discount[]" oninput="calculate(' + counter + ')" ></td>';

            cols += '<td class="none"><input type="text" id="discountamt' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="discountamt[]" oninput="calculate(' + counter + ')" ></td>';
            cols += '<td><input  class="form-control" type="text" id="netamount' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="netamount[]" oninput="calculate(' + counter + ')" ></td>';
            // cols += '<td><input type="text" onKeyPress="return onlyNumberKey(event)"  id="rate' + counter + '"          name="rate[]"></td>';
            cols += '<td  class="none" ><input type="text" onKeyPress="return onlyNumberKey(event)"  id="sgst' + counter + '"   name="sgst[]"  oninput="calculate(' + counter + ')"  ></td>';
            cols += '<td class="none"><input  class="form-control" type="text" onKeyPress="return onlyNumberKey(event)"  id="sgstamount' + counter + '"   name="sgstamount[]"  ></td>';
            cols += '<td  class="none" ><input type="text" id="cgst' + counter + '" onKeyPress="return onlyNumberKey(event)"   name="cgst[]"   oninput="calculate(' + counter + ')" ></td>';
            cols += '<td class="none"><input  class="form-control" type="text" id="cgstamount' + counter + '"  onKeyPress="return onlyNumberKey(event)"  name="cgstamount[]" readonly ></td>';
            cols += '<td class="none" ><input type="text" onKeyPress="return onlyNumberKey(event)"  id="igst' + counter + '"   name="igst[]"  oninput="calculate(' + counter + ')"   ></td>';
            cols += '<td class="none"><input  class="form-control" type="text" onKeyPress="return onlyNumberKey(event)"  id="igstamount' + counter + '"   name="igstamount[]"   ></td>';
            cols += '<td><input  class="form-control" type="text" onKeyPress="return onlyNumberKey(event)"  id="total' + counter + '"   name="total[]"   ></td>';
            cols += '<td><input    type="button" class="ibtnDel " style="color: #fff; background: rgb(128, 0, 0); border-bottom: rgb(128, 0, 0); border-radius: 7px;"  value="Delete"></td>';
            newRow.append(cols);
            $("table.order-lists").append(newRow);
            newRow.find('[name="itemcode[]"]').focus();
            counter++;
        });
        $("table.order-lists").on("click", ".ibtnDel", function(event) {

            $(this).closest("tr").remove();
            calculate(0);
            // counter -= 1
        });
    });

    function explodeData(data) {
        const split_string = data.split("@@");
        return split_string;
    }








    $(document).ready(function() {
        $('#inputform').on('submit', function(event) {
            event.preventDefault();

            var status=$('#status').val();

            if(status=='hold'){





                var formData = $(this).serialize();
                // Ajax request
                $.ajax({
                    url: '{{ route("submitpurchase") }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });


                $("#myTable tbody tr").remove();
                this.reset();

                $("#type-success").trigger("click");
            setTimeout(function() {
                location.reload();
            }, 1000);




            }else{
            // Prevent form submission


            // Validate form fields
            if (validateForm()) {
                // Serialize form data
                var formData = $(this).serialize();
                // Ajax request
                $.ajax({
                    url: '{{ route("submitpurchase") }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        toastr.success('Purchase Added Successfully', 'Hurray...!!!', { "positionClass": "toast-top-center" });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });

                $("#myTable tbody tr").remove();
                this.reset();

                $("#type-success").trigger("click");
            setTimeout(function() {
                location.reload();
            }, 1000);
            }

        }
        });
    });

    
    function validateForm() {
    var isValid = true;
    // Loop through each input field and select dropdown
    $('#inputform input[type="text"], #inputform select').each(function() {
        // Check if field is hidden, if so, skip validation

        // Check if the input has ID and Name both equal to "id"
        if ($(this).attr('id') === 'id' && $(this).attr('name') === 'id') {
            // Skip validation for this input
            return true;
        }

        // Check if field is empty
        if ($(this).is('input[type="text"]') && $.trim($(this).val()) === '') {
            $(this).addClass('error');
            console.log('Input field with ID "' + $(this).attr('id') + '" is empty.');
            isValid = false;
        } else if ($(this).is('select') && $(this).val() === '') {
            $(this).addClass('error');
            console.log('Select field with ID "' + $(this).attr('id') + '" is not selected.');
            isValid = false;
        } else {
            $(this).removeClass('error');
        }
    });
    return isValid;
}



    $(document).on('click', function() {
        $('.error').removeClass('error');
    });
    function deleteid() {
        var confirmation = confirm("Are you sure you want to delete?");
        if (confirmation) {

            var delid = $('#id').val();
            $.ajax({
                url: "{{url('deletepurchase')}}",
                type: "POST",
                data: {
                    delid: delid,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data) {

                }
            });
            $("#type-error").trigger("click");
            setTimeout(function() {
                location.reload();
            }, 1000); // 2000 milliseconds = 2 seconds
            $('#inputform')[0].reset();
            $("table tbody tr").remove();


        }
    }



    $(document).on('keydown', function(event) {
        if (event.key === "Escape") {
            event.preventDefault(); // Prevent the default action, if any
           console.log('visibility');
            $('#modal').css('visibility', 'hidden');
            $('#tt').empty();
            console.log('visibilityvisibility');
            $('#modall').css('visibility', 'hidden');
            $('#ttt').empty();
        }
    });
    $(document).ready(function() {



        $('#invpicetype').focus().select();

        $('#invpicetype').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();

                $('input#grno').focus().select();
            }
        });
        $('input#grno').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();

                $('input#grdate').focus().select();
            }
        });
        $('input#grdate').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
               var gdate= $('#grdate').val();
                $('#invoicedate').val(gdate);
                $('input#invoiceno').focus().select();
            }
        });

        $('input#invoiceno').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();

                $('input#invoicedate').focus().select();
            }
        });
        $('input#invoicedate').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();

                var invoicedategdate= $('#invoicedate').val();
                $('#grdate').val(invoicedategdate);

                $('input#accountcode').focus().select();
            }
        });
        $('input#accountcode').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                $('input#accountname').focus().select();
            }
        });
            $('input#accountname').keypress(function(event) {
                if (event.which === 13) {
                    event.preventDefault();
                    $('input#accountaddress').focus().select();
                }
            });
            $('input#accountaddress').keypress(function(event) {
                if (event.which === 13) {
                    event.preventDefault();
                    $('input#accountaddresss').focus().select();
                }
            });
            $('input#accountaddresss').keypress(function(event) {
                if (event.which === 13) {
                    event.preventDefault();
                    $('input#transportname').focus().select();
                }
            });
            $('input#transportname').keypress(function(event) {
                if (event.which === 13) {
                    event.preventDefault();
                    $('input#transportvehicleno').focus().select();
                }
            });
            $('input#transportvehicleno').keypress(function(event) {
                if (event.which === 13) {
                    event.preventDefault();
                    $('input#transportgrno').focus().select();
                }
            });
            $('input#transportgrno').keypress(function(event) {
                if (event.which === 13) {
                    event.preventDefault();
                    $('#memo').focus().select();
                }
            });


            $(' #memo').keypress(function(event) {
               if (event.which === 13) {
                   event.preventDefault();
                   $('#itemcode0').focus().select();
                   var $firstInput = $('#myTable tbody input:first');
                   $firstInput.focus().select();
               }
           });

           $('input#uploadingloading').keypress(function(event) {
                if (event.which === 13) {
                    event.preventDefault();
                    $('input#frieght').focus().select();
                }
            });



            $('#frieght').keypress(function(event) {
               if (event.which === 13) {
                   event.preventDefault();
                   $('#ssss').focus().select();
               }
           });

    });


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

    </script>

  @include('include.footer')
