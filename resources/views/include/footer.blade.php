
<script type="text/javascript">

function onlyNumberKey(evt) {
        let ASCIICode = (evt.which) ? evt.which : evt.keyCode;
        // Allow numbers, backspace, delete, and decimal point
        if ((ASCIICode >= 48 && ASCIICode <= 57) || ASCIICode === 8 || ASCIICode === 46) {
            // Get the current value of the input
            let currentValue = evt.target.value;
            // Check if the character is a decimal point
            let isDecimalPoint = ASCIICode === 46;
            // Check if the current value already contains a decimal point
            let containsDecimalPoint = currentValue.indexOf('.') !== -1;
            // If a decimal point is already present and the key pressed is another decimal point, prevent input
            if (containsDecimalPoint && isDecimalPoint) {
                return false;
            }
            // Allow the input
            return true;
        } else {
            // Prevent input for other characters
            return false;
        }
    }



    function getDistrict(ele) {
        $.ajax({
            url: "{{route('masterupdate')}}",
            type: "POST",
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            },
            beforeSend: function() {

            },
            data: {
                'actiontype': "getdistrict",
                'stateid': $(ele).val()
            },
            success: function(data) {

                var out = `<option value="">Select District</option>`;
                $.each(data.dist, function(index, value) {
                    out += `<option value="` + value.id + `">` + value.name +
                        `</option>`;
                });
                $('[name="districtId"]').html(out);
            }
        });
    }

    function getTehsil(ele) {
        $.ajax({
            url: "{{route('masterupdate')}}",
            type: "POST",
            dataType: 'json',
            headers: {
                 'X-CSRF-TOKEN': "{{csrf_token()}}"
            },
            beforeSend: function() {

            },
            data: {
                'actiontype': "gettehsil",
                'distId': $(ele).val()
            },
            success: function(data) {

                var out = `<option value="">Select Tehsil</option>`;
                $.each(data.data, function(index, value) {
                    out += `<option value="` + value.id + `">` + value.name +
                        `</option>`;
                });
                $('[name="tehsilId"]').html(out);
            }
        });
    }

    function getPostoffice(ele) {
        $.ajax({
            url: "{{route('masterupdate')}}",
            type: "POST",
            dataType: 'json',
            headers: {
                 'X-CSRF-TOKEN': "{{csrf_token()}}"
            },
            beforeSend: function() {

            },
            data: {
                'actiontype': "getpostoffice",
                'tehsilId': $(ele).val()
            },
            success: function(data) {

                var out = `<option value="">Select Post office</option>`;
                $.each(data.data, function(index, value) {
                    out += `<option value="` + value.id + `">` + value.name +
                        `</option>`;
                });
                $('[name="postOfficeId"]').html(out);
            }
        });
    }

    function getVillage(ele) {
        $.ajax({
            url: "{{ route('masterupdate') }}",
            type: "POST",
            dataType: 'json',
            headers: {
                 'X-CSRF-TOKEN': "{{csrf_token()}}"
            },
            beforeSend: function() {
                // You can add any pre-request actions here
            },
            data: {
                'actiontype': "getvillage",
                'postOfficeId': $(ele).val()
            },
            success: function(data) {
                // Close any alert or loader here if needed
                var out = '<option value="">Select Village</option>';
                $.each(data.data, function(index, value) {
                    out += '<option value="' + value.id + '">' + value.name + '</option>';
                });
                $('[name="villageId"]').html(out);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    </script>
       <!-- Footer Start -->
   <footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div><script>document.write(new Date().getFullYear())</script> © Betabyte</div>
            </div>
            <div class="col-md-6">
                <div class="d-none d-md-flex gap-4 align-item-center justify-content-md-end">
                    <p class="mb-0">Design & Develop by <a href="https://betabyte.in/" target="_blank">Beta Byte Technologies</a> </p>
                </div>
            </div>
        </div>
    </div>
</footer>
</div>
</div>


<script>




    $(document).ready(function() {
    $('.onlynumberwithonedot').on('input', function() {
        var value = $(this).val();
        if (!/^\d*\.?\d{0,6}$/.test(value)) {
            $(this).val(value.slice(0, -1));
        }
    });
});
    </script>

    <script>
    $(document).ready(function() {




    $('.brand-dropdown').on('change', function() {
        var idbrand = $(this).val();
        $.ajax({
            url: "{{ route('itemlist.fetchcategory') }}",
            type: "POST",
            data: {
                idbrand: idbrand,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(result) {
                var $categoryDropdown = $('.category-dropdown');
                if ($categoryDropdown.hasClass('selectized')) {
                    $categoryDropdown[0].selectize.destroy();
                }
                $categoryDropdown.empty();
                $categoryDropdown.append('<option value="">Select category</option>');
                $.each(result.states, function(key, value) {
                    $categoryDropdown.append('<option value="' + value.id + '">' + value.name + '</option>');
                });
                $categoryDropdown.selectize({
                    create: false, // Disable option to create new items
                    sortField: 'text' // Sort options alphabetically
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });



        $('.category-dropdown').on('change', function() {
            var idcat = this.value;
            idbrand = $('.brand-dropdown').val();
            // $("#subcategory-dropdown").html('');
            $.ajax({
                url: "{{route('itemlist.fetchsubcategory')}}",
                type: "POST",
                data: {
                    idbrand: idbrand,
                    idcat: idcat,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',

                success: function(result) {
                var $categoryDropdown = $('.subcategory-dropdown');
                if ($categoryDropdown.hasClass('selectized')) {
                    $categoryDropdown[0].selectize.destroy();
                }
                $categoryDropdown.empty();
                $categoryDropdown.append('<option value="">Select Sub category</option>');
                $.each(result.substates, function(key, value) {
                    $categoryDropdown.append('<option value="' + value.id + '">' + value.name + '</option>');
                });
                $categoryDropdown.selectize({
                    create: false, // Disable option to create new items
                    sortField: 'text' // Sort options alphabetically
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }

            });
        });




        $('.subcategory-dropdown').on('change', function() {
            var idcat = this.value;
            idbrand = $('.brand-dropdown').val();
            idbrandcategory = $('.category-dropdown').val();
            $(".subchildcategory-dropdown").html('');
            $.ajax({
                url: "{{route('itemlist.fetchsubchildcategory')}}",
                type: "POST",
                data: {
                    idbrand: idbrand,
                    idcat: idcat,
                    idbrandcategory: idbrandcategory,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',

                success: function(result) {
                var $categoryDropdown = $('.subchildcategory-dropdown');
                if ($categoryDropdown.hasClass('selectized')) {
                    $categoryDropdown[0].selectize.destroy();
                }
                $categoryDropdown.empty();
                $categoryDropdown.append('<option value="">Select Sub category</option>');
                $.each(result.subchildstates, function(key, value) {
                    $categoryDropdown.append('<option value="' + value.id + '">' + value.name + '</option>');
                });
                $categoryDropdown.selectize({
                    create: false, // Disable option to create new items
                    sortField: 'text' // Sort options alphabetically
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }


            });
        });




        $('#get-ledger').on('change', function() {
            var id = this.value;
            $("#ledger-get").html('');
            $.ajax({
                url: "{{route('taxlist.ledger')}}",
                type: "POST",
                data: {
                    id: id,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#ledger-get').html(
                    '<option value="">Select Ledger</option>');
                    $.each(result.substates, function(key, value) {
                        $("#ledger-get").append('<option value="' + value
                            .id + '">' + value.name + '</option>');
                    });
                }
            });
        });



    });



    function changesession(session) {
            $.ajax({
                url: "{{route('changesession')}}",
                type: "POST",
                data: {
                    session: session,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    window.location.reload(); // Corrected line
                }
            });
        }



    // Apply mask to the date input
    $(document).ready(function(){
        $('.datepicker').inputmask('99-99-9999'); // Apply mask to the input field
    });
    // const dateInputs = document.querySelectorAll('.onlydate');

    // dateInputs.forEach(dateInput => {
    //     dateInput.addEventListener('input', function (e) {
    //         let inputValue = e.target.value;

    //         inputValue = inputValue.replace(/[^\d]/g, '').slice(0, 8);
    //         if (inputValue.length >= 2 && inputValue.charAt(2) !== '-') {
    //             inputValue = `${inputValue.slice(0, 2)}-${inputValue.slice(2)}`;
    //         }
    //         if (inputValue.length >= 5 && inputValue.charAt(5) !== '-') {
    //             inputValue = `${inputValue.slice(0, 5)}-${inputValue.slice(5)}`;
    //         }
    //         e.target.value = inputValue;
    //     });
    // });

    </script>
    <script src="{{ url('public/main/assets')}}/js/jquery-3.6.0.min.js"></script>

    {{-- @if(URL::current()==url('rediscount')) --}}
    <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

    <!-- Javascript -->
    {{-- @php $allitems=DB::table('items')->select('itemcode','name')->get(); @endphp
    @php $allretailer=DB::table('retails')->where('groupname','=','17')->select('retailercode','name')->get(); @endphp
    <script>

    $(function() {
       $( "#autocomplete-4" ).autocomplete({
          source: [
            @foreach($allitems as $allitemsgo)
             { label: "{{ $allitemsgo->name }}({{ $allitemsgo->itemcode }})", value: "{{ $allitemsgo->itemcode }}" },
             @endforeach
          ]
       });
       $( "#autocomplete-3" ).autocomplete({
          source: [
            @foreach($allretailer as $allretailergo)
            { label: "{{ $allretailergo->name }}({{ $allretailergo->retailercode }})", value: "{{ $allretailergo->retailercode }}" },
              @endforeach
          ]
       });
    });
    </script> --}}
    {{-- @endif --}}

 <!-- Vendor js -->
        <!-- App js -->
        <script src="{{ url('public/admin') }}/js/vendor.min.js"></script>
        <script src="{{ url('public/admin') }}/js/app.js"></script>

        <!-- Plugins Js -->
        <script src="{{ url('public/admin') }}/libs/selectize/js/standalone/selectize.min.js"></script>
        <script src="{{ url('public/admin') }}/libs/mohithg-switchery/switchery.min.js"></script>
        <script src="{{ url('public/admin') }}/libs/multiselect/js/jquery.multi-select.js"></script>
        <script src="{{ url('public/admin') }}/libs/jquery.quicksearch/jquery.quicksearch.min.html"></script>
        <script src="{{ url('public/admin') }}/libs/select2/js/select2.min.js"></script>
        <script src="{{ url('public/admin') }}/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
        <script src="{{ url('public/admin') }}/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>

        <!-- Demo js -->


 <script src="{{ url('public/admin') }}/libs/datatables.net/js/jquery.dataTables.min.js"></script>
 <script src="{{ url('public/admin') }}/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
 <script src="{{ url('public/admin') }}/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
 <script src="{{ url('public/admin') }}/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
 <script src="{{ url('public/admin') }}/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
 <script src="{{ url('public/admin') }}/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
 <script src="{{ url('public/admin') }}/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
 <script src="{{ url('public/admin') }}/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
 <script src="{{ url('public/admin') }}/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
 <script src="{{ url('public/admin') }}/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
 <script src="{{ url('public/admin') }}/libs/datatables.net-select/js/dataTables.select.min.js"></script>
 <script src="{{ url('public/admin') }}/libs/pdfmake/build/pdfmake.min.js"></script>
 <script src="{{ url('public/admin') }}/libs/pdfmake/build/vfs_fonts.js"></script>
 <script src="{{ url('public/admin') }}/js/pages/datatables.js"></script>
 <script src="{{ url('public/admin') }}/libs/jquery-knob/jquery.knob.min.js"></script>
 <script src="{{ url('public/admin') }}/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
 <script src="{{ url('public/admin') }}/libs/morris.js/morris.min.js"></script>
 <script src="{{ url('public/admin') }}/libs/raphael/raphael.min.js"></script>
 <script src="{{ url('public/admin') }}/js/pages/dashboard.js"></script>
        <script src="{{ url('public/admin') }}/js/pages/form-advanced.js"></script>

        <script>
            $(document).ready(function() {
    // $('#accountNo').selectize({
    //     // Your selectize options for Brand dropdown

    // });
    $('#selectize-optgroup-brand').selectize({
        // Your selectize options for Brand dropdown

    });

    $('#selectize-optgroup-category').selectize({
        // Your selectize options for Category dropdown

    });
    $('#selectize-optgroup-subcategory').selectize({
        // Your selectize options for Category dropdown

    });
    $('#selectize-optgroup-subchildcategory').selectize({
        // Your selectize options for Category dropdown

    });
    $('#selectize-optgroup-Unit').selectize({
        // Your selectize options for Category dropdown

    });
    $('.sup-dropdown').selectize({
        // Your selectize options for Category dropdown

    });
});
        </script>


        <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
        {!! Toastr::message() !!}



<!-- Include jQuery UI -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<!-- Include Input Mask Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7-beta.19/jquery.inputmask.min.js"></script>

<script>
    $(function() {

//         $(".datepickerr").datepicker({
//     dateFormat: "dd-mm-yy",
//     onSelect: function(dateText, inst) {
//         // Call your function here
//         getInterestOnDate(dateText);
//     }
// });

        $(".datepicker").datepicker({
            dateFormat: "dd-mm-yy",
            onClose: function(dateText, inst) {

                validateAndFormatDate($(this));
            }
        });


        function validateAndFormatDate(input) {

            var datePattern = /^\d{2}-\d{2}-\d{4}$/;
            var dateValue = input.val();

            if (datePattern.test(dateValue)) {
                var parts = dateValue.split("-");
                var day = parseInt(parts[0], 10);
                var month = parseInt(parts[1], 10) - 1;
                var year = parseInt(parts[2], 10);

                var date = new Date(year, month, day);
                if (date && date.getFullYear() === year && date.getMonth() === month && date.getDate() === day) {
                    input.val($.datepicker.formatDate("dd-mm-yy", date));
                } else {
                    alert("Invalid date. Please enter a valid date in dd-mm-yyyy format.");
                    input.val("");
                }
            } else if (dateValue.length > 0) {
                alert("Invalid date format. Please use dd-mm-yyyy.");
                input.val("");
            }

        }
    });
    </script>

<script>
    function setdate(){
var setcurrentdate=$('#setcurrentdate').val();
$.ajax({
            url: '{{ route("setcurrentdate") }}',
            type: 'get',
            data: {
                setcurrentdate:setcurrentdate,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(res) {


            }
        });
        window.location.reload();
    }
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.nav-tabs .nav-link');
    let activeIndex = Array.from(tabs).findIndex(tab => tab.classList.contains('active'));

    document.getElementById('nextTab').addEventListener('click', function () {
        if (activeIndex < tabs.length - 1) {
            tabs[activeIndex + 1].click();
            activeIndex++;
        }
    });
    document.getElementById('nextTabb').addEventListener('click', function () {
        if (activeIndex < tabs.length - 1) {
            tabs[activeIndex + 1].click();
            activeIndex++;
        }
    });

    document.getElementById('prevTab').addEventListener('click', function () {
        if (activeIndex > 0) {
            tabs[activeIndex - 1].click();
            activeIndex--;
        }
    });
    document.getElementById('prevTabb').addEventListener('click', function () {
        if (activeIndex > 0) {
            tabs[activeIndex - 1].click();
            activeIndex--;
        }
    });
});
document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('mySidebar');
    const htmlElement = document.documentElement;

    hamburger.addEventListener('click', () => {
      htmlElement.classList.toggle('sidebar-enable');
      sidebar.classList.toggle('open');
    });
  });


</script>

</body>
</html>
