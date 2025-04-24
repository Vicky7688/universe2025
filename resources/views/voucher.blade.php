@include('include.header')

 <style>

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
.selected {
    background-color: #d3d3d3; /* Light gray */
}

#results {
    display: none;
	background-color: #8395a5bd;
	color: white;
	padding: 4px;
}

 </style>
<div class="row">
  <div class="col-xl-12">

    <form id="inputform" autocomplete="off"  method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden"   name="id" id="id" >

        <div class="card">
            <div class="card-body">
        <div class="row">
            <div class="col-sm-2 col-md-3">
                <select id="selectize-optgroup"    placeholder="Search Invoice" onchange="getvoucherdata(this.value)">
    <option value="">Search Voucher </option>
                    @if(!empty($vouchers))
                    @foreach ($vouchers as $getinvoicelist)
                    <option value="{{ $getinvoicelist->id }}">{{ $getinvoicelist->voucherno }}</option>
                    @endforeach
                    @endif
                    </select>
                </div>
        </div>
        </div>
        </div>
    <div class="card">
      <div class="card-body">
          <div class="row">
            <div class="mb-2 col-md-3">
              <label >Voucher Type</label>
              <select name="vouchertype" id="vouchertype" class="form-select">
                <option value="Journal Voucher">Journal Voucher</option>
                <option value="Purchase Voucher">Purchase Voucher</option>
                <option value="Cash Voucher">Cash Voucher</option>
              </select>
            </div>
            <div class="mb-2 col-md-3">
              <label >Voucher Date</label>
              <input type="text" class="onlydate form-control datepicker" name="voucherdate" id="voucherdate" value="{{ date('d-m-Y') }}">
            </div>
            <div class="mb-2 col-md-3">
              <label >Voucher No</label>
              <input type="text"  class="form-control" name="voucherno" id="voucherno" value="{{ $maxVoucherNo+1 }}">
            </div>
            <div class="mb-2 col-md-3">
              <label >Transport</label>
              <input type="text"  class="form-control" name="transport" id="transport">
            </div>
          </div>

      </div>
    </div>




    <div class="card">
      <div class="card-body">

          <div class="row mt-2">



                <table id="myTable" class=" table order-listt">
                    <thead>
                        <tr>
                            <th>Dr/Cr</th>
                            <th>Code</th>
                            <th>Account Description</th>
                            <th>Debit.Amt</th>
                            <th>Credit.Amt</th>
                            <th>Narration</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col-sm-1"><input class="form-control" name="drcr[]"  id="drcr0" type="text"></td>
                            <td class="col-sm-1"><input class="form-control" name="code[]"  id="code0" type="text"></td>
                            <td class="col-sm-3"><input class="form-control" readonly name="description[]" id="description0"  type="text"></td>
                            <td class="col-sm-1"><input class="form-control" name="dramount[]"  id="dramount0" type="text"></td>
                            <td class="col-sm-1"><input class="form-control" name="cramount[]"  id="cramount0" type="text"></td>
                            <td class="col-sm-4"><input class="form-control" name="narration[]" id="narration0"  type="text"></td>
                            <td class="col-sm-1"><a class="deleteRow"></a>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="none">
                        <tr>
                            <td colspan="7" style="text-align: left;">
                                <input type="button" class=" " id="addrow" value="Add Row" />
                            </td>
                        </tr>
                        <tr>
                        </tr>
                    </tfoot>
                </table>


          </div>

      </div>
    </div>




    <div class="card">
      <div class="card-body">

          <div class="row mt-2">




            <div class="mb-2 col-md-12">
              <a href="{{ url('voucher') }}" type="button" class="btn btn-primary ">Add New</a>

              <a type="button" class="btn btn-primary " onclick="getfirst()">First</a>

              <a type="button" class="btn btn-primary " onclick="getprevios()">Previous</a>

              <a type="button" class="btn btn-primary "  onclick="getnext()" >Next</a>

              <a type="button" class="btn btn-primary " onclick="getlast()">Last</a>

              <button type="submit" id="gosub" style="float: right;" class=" btn btn-dark ">Save Voucher</button>

</div>

          </div>
      </div>
    </div>




    </form>
  </div>
</div>
</div>
</div>


<div id="modal" class="modal">
    <div class="modal-content">
      {{-- <a href="#" title="Close" class="modal-close">Close</a> --}}
            <table id="myTableproducts" >
                <thead class="pro">
                <tr>
                    <th>Code</th>
                    <th>Name 	</th>
                    <th>Group Code</th>
                    <th>Group Name 	</th>
                </tr>
            </thead>
                <tbody id="tt">
            </tbody>
            </table>
      </div>
  </div>









<script>


    $(document).ready(function() {
      $('#search').on('input', function() {
          var query = $(this).val();

          $.ajax({
              url: "{{ route('vsearch') }}",
              type: "GET",
              data: {
                  'query': query
              },
              success: function(data) {
                  $('#results').empty();

                  $.each(data, function(key, value) {
                      $('#results').css('display', 'block'); // Make results visible
                      $('#results').append('<li>' + value.voucherno + '</li>'); // Adjust to match your data structure
                  });
              }
          });
      });

      $(document).on('keydown', function(e) {
          var selected = $('#results li.selected');
          if (e.key === 'ArrowDown') {
              if (selected.length && selected.next().length) {
                  selected.removeClass('selected').next().addClass('selected');
              } else {
                  $('#results li').removeClass('selected').first().addClass('selected');
              }
          } else if (e.key === 'ArrowUp') {
              if (selected.length && selected.prev().length) {
                  selected.removeClass('selected').prev().addClass('selected');
              } else {
                  $('#results li').removeClass('selected').last().addClass('selected');
              }
          } else if (e.key === 'Enter') {
              if (selected.length) {
                  $('#search').val(selected.text());
                  $('#results').css('display', 'block');
                  $('#results').css('display', 'none'); // Make results notvisible
                  getvoucherdata(selected.text());
                  $('#results').empty();
              }
          }
      });

      $(document).on('click', '#results li', function() {
          $('#search').val($(this).text());
          getvoucherdata($(this).text());
          $('#results').css('display', 'none'); // Make results notvisible
          $('#results').empty();
      });
  });




      $(document).ready(function() {
          $('#inputform').on('submit', function(event) {
              event.preventDefault();

              $('#gosub').attr('disabled',true);


              var sumdramount = 0;
                  var sumcramount = 0;
                  $('input[name="dramount[]"]').each(function() {
                      sumdramount += parseFloat($(this).val()) || 0;
                  });
                  $('input[name="cramount[]"]').each(function() {
                      sumcramount += parseFloat($(this).val()) || 0;
                  });
                  if (sumdramount == sumcramount) {



              //    if (validateForm()) {
              var formData = $(this).serialize();
              $.ajax({
                  url: '{{ route("submitvoucher") }}',
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
                toastr.success('Voucher Added Successfully', 'Hurray...!!!', { "positionClass": "toast-top-center" });
                  location.reload();
              }, 1000);

            }else{
                alert('Entries Does not match');
            }
          });
      });




  $(document).ready(function() {
      $(document).on('keypress', 'input[name="drcr[]"]', function(event) {
          let charCode = event.which;
          let charStr = String.fromCharCode(charCode);
          if (charStr !== 'c' && charStr !== 'd' && charStr !== 'D' && charStr !== 'D' && charCode !== 13) {
              event.preventDefault();
              return;
          }
          if (event.which === 13) {
              event.preventDefault();
              let value = $(this).val();
              let id = $(this).attr('id');
              let codeval = id.replace('drcr', '');
              console.log("Value:", value, "ID:", id, "codeval:", codeval);
              if (value == 'c') {

                  $(this).val('Credit');
                  $('#code' + codeval).focus().select();
              }
              if (value == 'C') {

                  $(this).val('Credit');
                  $('#code' + codeval).focus().select();
              }



              if (value == 'd') {

                  $(this).val('Debit');
                  $('#code' + codeval).focus().select();
              }
              if (value == 'D') {

                  $(this).val('Debit');
                  $('#code' + codeval).focus().select();
              }





              if (value == 'Debit') {

                  $(this).val('Debit');
                  $('#code' + codeval).focus().select();
              }
              if (value == 'Credit') {

                  $(this).val('Debit');
                  $('#code' + codeval).focus().select();
              }


          }
      });

      $(document).on('keypress', 'input[name="search"]', function(event) {
          if (event.which === 13) {
              event.preventDefault();
          }
      });

      $(document).on('keypress', 'input[name="code[]"]', function(event) {
          if (event.which === 13) {
              event.preventDefault();
              let value = $(this).val();
              let id = $(this).attr('id');
              let codeval = id.replace('code', '');
              console.log("Value:", value, "ID:", id, "codeval:", codeval);

              if (value != "") {
                  $.ajax({
                      url: "{{url('getled')}}",
                      type: "POST",
                      data: {
                          name: value,
                          _token: '{{csrf_token()}}'
                      },
                      dataType: 'json',
                      success: function(data) {
                          // $('#description' + codeval).focus();
                          $('#modal').css('visibility', 'visible');
                          $('#tt').empty();
                          $.each(data, function(index, item) {
                              var $row = $('<tr id="' + item.id + ',' + codeval + '">');
                              $row.append('<td>' + item.lcode + '</td>');
                              $row.append('<td>' + item.lname + '</td>');
                              $row.append('<td>' + item.gcode + '</td>');
                              $row.append('<td>' + item.gname + '</td>');
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

              $(document).on('keydown', function(event) {
                  if (event.which === 13) {
                      var selectedId = $('#myTableproducts .selected').attr('id');
                      if (selectedId) {
                          var [name, number] = selectedId.split(',');
                          getdatadat(name, number);
                          $('#modal').css('visibility', 'hidden');
                          $('#myTableproducts #tt').empty();
                          $('#dramount' + number).blur();
                          $('#cramount' + number).blur();
                          if (number != 0) {
                              var drcr = $('#drcr' + number).val();
                              var sumdramount = 0;
                              var sumcramount = 0;
                              $('input[name="dramount[]"]').each(function() {
                                  sumdramount += parseFloat($(this).val()) || 0;
                              });
                              $('input[name="cramount[]"]').each(function() {
                                  sumcramount += parseFloat($(this).val()) || 0;
                              });

                              if (drcr == 'Credit') {

                                  $('#cramount' + number).val(0);
                                  $('#dramount' + number).val(0);

                                  $('#cramount' + number).focus().select();
                                  $('#cramount' + number).val(parseInt(sumdramount) - parseInt(sumcramount));
                                  setTimeout(function() {
                                      $('#cramount' + number).focus().select();
                                  }, 1000);
                              }
                              if (drcr == 'Debit') {

                                  $('#dramount' + number).val(0);
                                  $('#cramount' + number).val(0);

                                  $('#dramount' + number).focus().select();
                                  $('#dramount' + number).val(parseInt(sumcramount) - parseInt(sumdramount));
                                  setTimeout(function() {
                                      $('#dramount' + number).focus().select();
                                  }, 1000);
                              }

                          } else {


                              if ($('#drcr' + number).val() == 'Credit') {
                                  $('#dramount' + number).val(0);
                                  $('#cramount' + number).val(0);
                                  $('#cramount' + number).focus().select();
                              }
                              if ($('#drcr' + number).val() == 'Debit') {
                                  $('#dramount' + number).val(0);
                                  $('#cramount' + number).val(0);
                                  $('#dramount' + number).focus().select();
                              }
                          }

                      } else {
                          console.log('No row selected.');
                      }
                  }
              });

          }
      });




      $(document).on('keypress', 'input[name="description[]"]', function(event) {
          if (event.which === 13) {
              event.preventDefault();
              let value = $(this).val();
              let id = $(this).attr('id');
              let codeval = id.replace('description', '');
              if (value == '') {
                  $(this).addClass('error');
              } else {
                  $(this).removeClass('error');

                  if ($('#drcr' + codeval).val() == 'Credit') {
                      $('#dramount' + codeval).val(0);
                      $('#cramount' + codeval).val(0);
                      $('#cramount' + codeval).focus().select();
                  }
                  if ($('#drcr' + codeval).val() == 'Debit') {
                      $('#dramount' + codeval).val(0);
                      $('#cramount' + codeval).val(0);
                      $('#dramount' + codeval).focus().select();
                  }

              }
          }
      });

      $(document).on('keypress', 'input[name="dramount[]"]', function(event) {
          if (event.which === 13) {
              event.preventDefault();
              let value = $(this).val();
              let id = $(this).attr('id');
              let codeval = id.replace('dramount', '');
              if (value == '') {
                  $(this).addClass('error');
              } else if (value > 0) {
                  console.log("narration");
                  $('#narration' + codeval).focus().select();
                  $(this).removeClass('error');
              } else {
                  $(this).addClass('error');
              }
          }
      });



      $(document).on('keypress', 'input[name="cramount[]"]', function(event) {
          if (event.which === 13) {
              event.preventDefault();
              let value = $(this).val();
              let id = $(this).attr('id');
              let codeval = id.replace('cramount', '');
              if (value == '') {
                  $(this).addClass('error');
              } else if (value > 0) {
                  console.log("narration");
                  $('#narration' + codeval).focus().select();
                  $(this).removeClass('error');
              } else {
                  $(this).addClass('error');
              }
          }
      });



      $(document).on('keypress', 'input[name="narration[]"]', function(event) {
          if (event.which === 13) {
              event.preventDefault();
              let value = $(this).val();
              let id = $(this).attr('id');
              let codeval = id.replace('narration', '');
              let nextval= parseInt(codeval)+parseInt(1);


              if ($('#narration' + nextval).length) {
                $('#drcr' + nextval).focus().select();
              }else{

              if (codeval != 0) {
                  var sumdramount = 0;
                  var sumcramount = 0;
                  $('input[name="dramount[]"]').each(function() {
                      sumdramount += parseFloat($(this).val()) || 0;
                  });
                  $('input[name="cramount[]"]').each(function() {
                      sumcramount += parseFloat($(this).val()) || 0;
                  });
                  if (sumdramount == sumcramount) {

                    // $('#gosub').css('display','inline-block');
                    $('#gosub').focus();

                  } else {
                      $('#addrow').click();
                  }
              } else {
                  $('#addrow').click();
              }


            }

          }
      });


      function getdatadat(name, number) {
          $.ajax({
              url: "{{url('getdatadat')}}",
              type: "POST",
              data: {
                  name: name,
                  _token: '{{csrf_token()}}'
              },
              dataType: 'json',
              success: function(data) {
                console.log(number);
                  $('#code' + number).val(data.ledgerCode);
                  $('#description' + number).val(data.name);
                //   $('#modall').css('visibility', 'hidden');
                //   $('#modal').css('visibility', 'hidden');

              }
          });

      }


      var counter = 888;

      $("#addrow").on("click", function() {

        var firstDrcrId = $('input[name="drcr[]"]').attr('id');


        let codeval = firstDrcrId.replace('drcr', '');


          var oldval = $('#drcr'+codeval).val();
          if (oldval == 'Credit') {
              var crval = "Debit";
              console.log('DebitDebitDebit' + crval)
          }
          if (oldval == 'Debit') {
              var crval = "Credit";
              console.log('CreditCreditCredit' + crval)
          }


          var newRow = $("<tr>");
          var cols = "";
          cols += '<td class="col-sm-1"><input class="form-control" id="drcr' + counter + '"  name="drcr[]" type="text" value="' + crval + '"></td>';
          cols += '<td class="col-sm-1"><input class="form-control" id="code' + counter + '"  name="code[]" type="text" autofocus></td>';
          cols += '<td class="col-sm-3"><input class="form-control"readonly id="description' + counter + '"  name="description[]" type="text"></td>';
          cols += '<td class="col-sm-1"><input class="form-control" id="dramount' + counter + '"  name="dramount[]" type="text"></td>';
          cols += '<td class="col-sm-1"><input class="form-control" id="cramount' + counter + '"  name="cramount[]" type="text"></td>';
          cols += '<td class="col-sm-4"><input class="form-control" id="narration' + counter + '"  name="narration[]" type="text"></td>';
          cols += '<td><a class="ibtnDel "> <img src="{{ url("public/admin/images/delete.png") }}" alt="img"> </a></td>';
          newRow.append(cols);
          $("table.order-listt").append(newRow);
          $('#code' + counter).focus().select();
          counter++;

      });
      $("table.order-listt").on("click", ".ibtnDel", function(event) {
          $(this).closest("tr").remove();
          counter -= 1
      });
  });

  $(document).on('keydown', function(event) {
      if (event.key === "Escape") {

          $('#modal').css('visibility', 'hidden');
          $('#tt').empty();

          var focusedElement = $(document.activeElement);
          var focusedElementId = focusedElement.attr('id');

          if (focusedElementId && focusedElementId.startsWith('description')) {

              let codeval = focusedElementId.replace('description', '');
              console.log("Focused element ID on Escape:", codeval);
              $('#code' + codeval).focus().select();
          } else {
              console.log("No ID found for the focused element.");
          }

      }
  });



  function getvoucherdata(value) {




if (value != "") {
    $.ajax({
        url: "{{url('getvoucherdata')}}",
        type: "POST",
        data: {
            value: value,
            _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function(data) {
            $('#id').val(data.voucher.id);
            $('#vouchertype option').each(function() {
                if ($(this).val() === data.voucher.vouchertype) {
                    $(this).prop('selected', true);
                } else {
                    $(this).prop('selected', false);
                }
            });

            var originalDate = new Date(data.voucher.voucherdate);
            var day = originalDate.getDate();
            var month = originalDate.getMonth() + 1;
            var year = originalDate.getFullYear();
            day = day < 10 ? '0' + day : day;
            month = month < 10 ? '0' + month : month;
            var formatinvoicedate = day + '-' + month + '-' + year;

            $('#voucherdate').val(formatinvoicedate);
            $('#voucherno').val(data.voucher.voucherno);
            $('#transport').val(data.voucher.transport);




            var counter = 0;
            $("#myTable tbody tr").remove();
            $.each(data.voucherdetail, function(index, item) {
                var newRow = $("<tr>");
                var cols = "";
                      function sanitize(value) {
                      return value == null ? '' : value;
                      }

cols += '<td class="col-sm-1"><input class="form-control"   id="drcr' + counter + '"  name="drcr[]" type="text" value="' + sanitize(item.drcr) + '"></td>';
cols += '<td class="col-sm-1"><input class="form-control"   id="code' + counter + '"  name="code[]" type="text"  value="' + sanitize(item.code) + '"></td>';
cols += '<td class="col-sm-3"><input class="form-control"  readonly id="description' + counter + '"  name="description[]" type="text"  value="' + sanitize(item.description) + '"></td>';
cols += '<td class="col-sm-1"><input class="form-control"   id="dramount' + counter + '"  name="dramount[]" type="text"  value="' + sanitize(item.dramount) + '"></td>';
cols += '<td class="col-sm-1"><input class="form-control"   id="cramount' + counter + '"  name="cramount[]" type="text"  value="' + sanitize(item.cramount) + '"></td>';
cols += '<td class="col-sm-4"><input class="form-control"   id="narration' + counter + '"  name="narration[]" type="text"  value="' + sanitize(item.narration) + '"></td>';
cols += '<td><a class="ibtnDel "> <img src="{{ url("public/admin/images/delete.png") }}" alt="img"> </a></td>';
newRow.append(cols);
                // $("table.order-lists").append(newRow);
                $("table.order-listt").append(newRow);
                counter++;
            });



        }
    });
}

}
function getnext(){
    var value= $('#id').val();
    if(value==''){
        getfirst();
    }else{




if (value != "") {
    $.ajax({
        url: "{{url('nextgetvoucherdata')}}",
        type: "POST",
        data: {
            value: value,
            _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function(data) {
            $('#id').val(data.voucher.id);
            $('#vouchertype option').each(function() {
                if ($(this).val() === data.voucher.vouchertype) {
                    $(this).prop('selected', true);
                } else {
                    $(this).prop('selected', false);
                }
            });

            var originalDate = new Date(data.voucher.voucherdate);
            var day = originalDate.getDate();
            var month = originalDate.getMonth() + 1;
            var year = originalDate.getFullYear();
            day = day < 10 ? '0' + day : day;
            month = month < 10 ? '0' + month : month;
            var formatinvoicedate = day + '-' + month + '-' + year;

            $('#voucherdate').val(formatinvoicedate);
            $('#voucherno').val(data.voucher.voucherno);
            $('#transport').val(data.voucher.transport);




            var counter = 0;
            $("#myTable tbody tr").remove();
            $.each(data.voucherdetail, function(index, item) {
                var newRow = $("<tr>");
                var cols = "";
                      function sanitize(value) {
                      return value == null ? '' : value;
                      }

cols += '<td class="col-sm-1"><input class="form-control"  id="drcr' + counter + '"  name="drcr[]" type="text" value="' + sanitize(item.drcr) + '"></td>';
cols += '<td class="col-sm-1"><input class="form-control"  id="code' + counter + '"  name="code[]" type="text"  value="' + sanitize(item.code) + '"></td>';
cols += '<td class="col-sm-3"><input class="form-control" readonly id="description' + counter + '"  name="description[]" type="text"  value="' + sanitize(item.description) + '"></td>';
cols += '<td class="col-sm-1"><input class="form-control"  id="dramount' + counter + '"  name="dramount[]" type="text"  value="' + sanitize(item.dramount) + '"></td>';
cols += '<td class="col-sm-1"><input class="form-control"  id="cramount' + counter + '"  name="cramount[]" type="text"  value="' + sanitize(item.cramount) + '"></td>';
cols += '<td class="col-sm-4"><input class="form-control"  id="narration' + counter + '"  name="narration[]" type="text"  value="' + sanitize(item.narration) + '"></td>';
cols += '<td><a class="ibtnDel "> <img src="{{ url("public/admin/images/delete.png") }}" alt="img"> </a></td>';

newRow.append(cols);
                // $("table.order-lists").append(newRow);
                $("table.order-listt").append(newRow);
                counter++;
            });



        }
    });
}



    }
}
function getprevios(){
    var value= $('#id').val();
    if(value==''){
        getfirst();
    }else{








if (value != "") {
    $.ajax({
        url: "{{url('previosgetvoucherdata')}}",
        type: "POST",
        data: {
            value: value,
            _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function(data) {
            $('#id').val(data.voucher.id);
            $('#vouchertype option').each(function() {
                if ($(this).val() === data.voucher.vouchertype) {
                    $(this).prop('selected', true);
                } else {
                    $(this).prop('selected', false);
                }
            });

            var originalDate = new Date(data.voucher.voucherdate);
            var day = originalDate.getDate();
            var month = originalDate.getMonth() + 1;
            var year = originalDate.getFullYear();
            day = day < 10 ? '0' + day : day;
            month = month < 10 ? '0' + month : month;
            var formatinvoicedate = day + '-' + month + '-' + year;

            $('#voucherdate').val(formatinvoicedate);
            $('#voucherno').val(data.voucher.voucherno);
            $('#transport').val(data.voucher.transport);




            var counter = 0;
            $("#myTable tbody tr").remove();
            $.each(data.voucherdetail, function(index, item) {
                var newRow = $("<tr>");
                var cols = "";
                      function sanitize(value) {
                      return value == null ? '' : value;
                      }

cols += '<td class="col-sm-1"><input class="form-control"   id="drcr' + counter + '"  name="drcr[]" type="text" value="' + sanitize(item.drcr) + '"></td>';
cols += '<td class="col-sm-1"><input class="form-control"   id="code' + counter + '"  name="code[]" type="text"  value="' + sanitize(item.code) + '"></td>';
cols += '<td class="col-sm-3"><input class="form-control"  readonly id="description' + counter + '"  name="description[]" type="text"  value="' + sanitize(item.description) + '"></td>';
cols += '<td class="col-sm-1"><input class="form-control"   id="dramount' + counter + '"  name="dramount[]" type="text"  value="' + sanitize(item.dramount) + '"></td>';
cols += '<td class="col-sm-1"><input class="form-control"   id="cramount' + counter + '"  name="cramount[]" type="text"  value="' + sanitize(item.cramount) + '"></td>';
cols += '<td class="col-sm-4"><input class="form-control"   id="narration' + counter + '"  name="narration[]" type="text"  value="' + sanitize(item.narration) + '"></td>';
cols += '<td><a class="ibtnDel "> <img src="{{ url("public/admin/images/delete.png") }}" alt="img"> </a></td>';
newRow.append(cols);
                // $("table.order-lists").append(newRow);
                $("table.order-listt").append(newRow);
                counter++;
            });



        }
    });
}



    }
}
function getlast(){


    $.ajax({
        url: "{{url('getlastgetvoucherdata')}}",
        type: "GET",
        // data: {
        //     value: value,
        //     _token: '{{csrf_token()}}'
        // },
        dataType: 'json',
        success: function(data) {
            $('#id').val(data.voucher.id);
            $('#vouchertype option').each(function() {
                if ($(this).val() === data.voucher.vouchertype) {
                    $(this).prop('selected', true);
                } else {
                    $(this).prop('selected', false);
                }
            });

            var originalDate = new Date(data.voucher.voucherdate);
            var day = originalDate.getDate();
            var month = originalDate.getMonth() + 1;
            var year = originalDate.getFullYear();
            day = day < 10 ? '0' + day : day;
            month = month < 10 ? '0' + month : month;
            var formatinvoicedate = day + '-' + month + '-' + year;

            $('#voucherdate').val(formatinvoicedate);
            $('#voucherno').val(data.voucher.voucherno);
            $('#transport').val(data.voucher.transport);




            var counter = 0;
            $("#myTable tbody tr").remove();
            $.each(data.voucherdetail, function(index, item) {
                var newRow = $("<tr>");
                var cols = "";
                      function sanitize(value) {
                      return value == null ? '' : value;
                      }

cols += '<td class="col-sm-1"><input class="form-control"   id="drcr' + counter + '"  name="drcr[]" type="text" value="' + sanitize(item.drcr) + '"></td>';
cols += '<td class="col-sm-1"><input class="form-control"   id="code' + counter + '"  name="code[]" type="text"  value="' + sanitize(item.code) + '"></td>';
cols += '<td class="col-sm-3"><input class="form-control"  readonly id="description' + counter + '"  name="description[]" type="text"  value="' + sanitize(item.description) + '"></td>';
cols += '<td class="col-sm-1"><input class="form-control"   id="dramount' + counter + '"  name="dramount[]" type="text"  value="' + sanitize(item.dramount) + '"></td>';
cols += '<td class="col-sm-1"><input class="form-control"   id="cramount' + counter + '"  name="cramount[]" type="text"  value="' + sanitize(item.cramount) + '"></td>';
cols += '<td class="col-sm-4"><input class="form-control"   id="narration' + counter + '"  name="narration[]" type="text"  value="' + sanitize(item.narration) + '"></td>';
cols += '<td><a class="ibtnDel "> <img src="{{ url("public/admin/images/delete.png") }}" alt="img"> </a></td>';
newRow.append(cols);
                // $("table.order-lists").append(newRow);
                $("table.order-listt").append(newRow);
                counter++;
            });



        }
    });

}
function getfirst(){

    $.ajax({
        url: "{{url('getfirstgetvoucherdata')}}",
        type: "GET",
        // data: {
        //     value: value,
        //     _token: '{{csrf_token()}}'
        // },
        dataType: 'json',
        success: function(data) {
            $('#id').val(data.voucher.id);
            $('#vouchertype option').each(function() {
                if ($(this).val() === data.voucher.vouchertype) {
                    $(this).prop('selected', true);
                } else {
                    $(this).prop('selected', false);
                }
            });

            var originalDate = new Date(data.voucher.voucherdate);
            var day = originalDate.getDate();
            var month = originalDate.getMonth() + 1;
            var year = originalDate.getFullYear();
            day = day < 10 ? '0' + day : day;
            month = month < 10 ? '0' + month : month;
            var formatinvoicedate = day + '-' + month + '-' + year;

            $('#voucherdate').val(formatinvoicedate);
            $('#voucherno').val(data.voucher.voucherno);
            $('#transport').val(data.voucher.transport);




            var counter = 0;
            $("#myTable tbody tr").remove();
            $.each(data.voucherdetail, function(index, item) {
                var newRow = $("<tr>");
                var cols = "";
                      function sanitize(value) {
                      return value == null ? '' : value;
                      }

cols += '<td class="col-sm-1"><input class="form-control"   id="drcr' + counter + '"  name="drcr[]" type="text" value="' + sanitize(item.drcr) + '"></td>';
cols += '<td class="col-sm-1"><input class="form-control"   id="code' + counter + '"  name="code[]" type="text"  value="' + sanitize(item.code) + '"></td>';
cols += '<td class="col-sm-3"><input class="form-control"  readonly id="description' + counter + '"  name="description[]" type="text"  value="' + sanitize(item.description) + '"></td>';
cols += '<td class="col-sm-1"><input class="form-control"   id="dramount' + counter + '"  name="dramount[]" type="text"  value="' + sanitize(item.dramount) + '"></td>';
cols += '<td class="col-sm-1"><input class="form-control"   id="cramount' + counter + '"  name="cramount[]" type="text"  value="' + sanitize(item.cramount) + '"></td>';
cols += '<td class="col-sm-4"><input class="form-control"   id="narration' + counter + '"  name="narration[]" type="text"  value="' + sanitize(item.narration) + '"></td>';
cols += '<td><a class="ibtnDel "> <img src="{{ url("public/admin/images/delete.png") }}" alt="img"> </a></td>';
newRow.append(cols);
                // $("table.order-lists").append(newRow);
                $("table.order-listt").append(newRow);
                counter++;
            });



        }
    });


}

</script>
@include('include.footer')
