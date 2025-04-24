@include('include.header')


<style>

.suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    max-height: 150px;
    overflow-y: auto;
    background-color: #fff;
    border: 1px solid #ccc;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    z-index: 10;
    display: none;  /* Hide suggestions by default */
}

.suggestions div {
    padding: 8px;
    cursor: pointer;
}

.suggestions div:hover {
    background-color: #f0f0f0;
}


</style>
<form action="{{ $formurl }}" method="POST">
  @csrf
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body"> @csrf
          <div class="row">
            <div class="mb-2 col-md-3">
              <label class="form-label">Select Commetee</label>
              <select id="selectize-optgroup" class="cometiknam" name="cometee" required onChange="getmemberstotal(this.value)"   >
                <option value="">Select </option>
                        @if(sizeof($commetee)>0)
                        @foreach ($commetee as $commeteelist)
                             <option value="{{ $commeteelist->id }}" >{{ $commeteelist->name }}</option>
                        @endforeach
                        @endif
              </select>
            </div>
             <div class="mb-2 col-md-3">
              <label class="form-label">Date</label>
              <input type="text" id="datefor" name="date" class="onlydate form-control date1" value="{{ Session::get('setcurrentdate') }}">
            </div>  
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <table class="table table-striped dt-responsive nowrap w-100">
            <thead>
              <tr>

                {{-- <th>Customer Name</th> --}}
                <th>Comettee Start Date</th>
                <th>Comettee End Date</th>
                <th>Total Recieved Amount</th>
                <th>Withdraw Amount</th>
                <th>Member </th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="tttboody">
            </tbody>
          </table> 
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <h5>Previous Withdrawals</h5>
          <table class="table table-striped dt-responsive nowrap w-100">
            <thead>
              <tr>

                <th>Sr.No</th>
                <th>Widrawl Date</th>
                <th>Member</th>
                <th>Amount</th>  
              </tr>
            </thead>
            <tbody id="tttboodyyyyy">
            </tbody>
          </table> 
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</form>

<script type="text/javascript">
    function ajaxchla() {
        var id = $('.cometiknam').val();
        getmemberstotal(id);
    }

    function getmemberstotal(id) {
        var dat = $('#datefor').val();
        $.ajax({
            url: "{{ route('getmemberstotal') }}",
            type: "POST",
            data: {
                id: id,
                dat: dat,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(data) {
                $('#tttboody').empty();
                $('#tttboodyyyyy').empty();

            var stt = "";
            var butn = ""; 
            var originalDate = new Date(data.comati.datefrom);
            var day = ('0' + originalDate.getDate()).slice(-2);
            var month = ('0' + (originalDate.getMonth() + 1)).slice(-2);
            var year = originalDate.getFullYear();
            var formattedDate = `${day}-${month}-${year}`; 
            var originalDatedateto = new Date(data.comati.dateto);
            var dayo = ('0' + originalDatedateto.getDate()).slice(-2);
            var montho = ('0' + (originalDatedateto.getMonth() + 1)).slice(-2);
            var yearo = originalDatedateto.getFullYear();
            var formattedDateo = `${dayo}-${montho}-${yearo}`; 
            // var selectElement = '<select class="form-control">';  
            // // var selectElement = '<option value="">Select Member</option>';  
            // for (var id in data.members) {
            //     selectElement += '<option value="' + id + '">' + data.members[id] + '</option>';
            // }

            // selectElement += '</select>'; 
            var row = '<tr>' +
            // '<td>' + data.comati.name + '</td>' +
            '<td>' + formattedDate + '</td>' +
            '<td>' + formattedDateo + '</td>' +
            '<td>' + data.commeti_recoveries + '</td>' +
            '<td><input type="text" name="amount" value=""></td>' +
            '<td>' +
                '<div class="input-container">' +
                    '<input type="text" name="member" oninput="getmembersforwidrawl(this.value)" autocomplete="off">' +
                    '<input type="hidden" name="member_id" class="member-id">' +  <!-- Hidden field for member ID -->
                    '<div class="suggestions"></div>' +  <!-- Container for suggestions -->
                '</div>' +
            '</td>' +
            '<td><button type="submit" class="btn btn-dark">Withdraw</button></td>' +
          '</tr>'; 
            $('#tttboody').append(row);










            var loo = data.widrawtable;
                if (loo.length > 0) {
                    loo.forEach(function(member, index) { 
                        var originalDate = new Date(member.paymentdate	);
                        var day = ('0' + originalDate.getDate()).slice(-2);
                        var month = ('0' + (originalDate.getMonth() + 1)).slice(-2);
                        var year = originalDate.getFullYear();
                        var formattedDate = `${day}-${month}-${year}`; 
   
                        var row = '<tr>' +
                        '<td>' + (index + 1) + '</td>' +
                        '<td>' + formattedDate + '</td>' +
                        '<td>' + member.amount + '</td>' + 
                        '<td>' + member.name + '</td>' + 
                        '</tr>';
                        $('#tttboodyyyyy').append(row);
                    });
                } else {
                     
                } 
             }
        });
    }
    
    

    function getmembersforwidrawl(query) { 
    var idd = $('.cometiknam').val();
     
    if (query === "") {
        $('.suggestions').hide();
        return;
    }

    $.ajax({
        url: "{{ route('getmembersforwidrawl') }}",
        type: "GET",
        data: {
            id: query,    
            idd: idd,
        },
        success: function(response) {
            if (response.status === "success" && response.members.length > 0) {
                var suggestionsContainer = $('.suggestions');
                suggestionsContainer.empty(); 
 
                response.members.forEach(function(member) {
                    suggestionsContainer.append('<div class="suggestion-item" data-id="' + member.id + '">' + member.name + '</div>');
                }); 
                suggestionsContainer.show(); 
                $('.suggestion-item').on('click', function() {
                    var selectedMember = $(this).text();
                    var selectedMemberId = $(this).data('id');  
                    $('input[name="member"]').val(selectedMember);  
                    $('input[name="member_id"]').val(selectedMemberId);  
                    suggestionsContainer.hide(); 
                });
            } else {
                $('.suggestions').hide();   
            }
        },
        error: function(xhr, status, error) {
            alert('There was an error with the request: ' + error);
        }
    });
}



    $(document).ready(function() {
        @if (session('success'))
            alert('Success: {{ session("success") }}');
        @elseif (session('error'))
            alert('Error: {{ session("error") }}');
        @endif

        let currentYear = new Date().getFullYear();
        $('.date1').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
        });

        $(document).on('change', '#datefor', function() {
            let currentdate = $(this).val();
            ajaxchla();
        });
 
        $('form').submit(function(e) {
            e.preventDefault();  

            var formData = $(this).serialize(); // Serialize the form data

            $.ajax({
                url: "{{ route('widrawlcometeee') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        alert('Data submitted successfully!');
                        ajaxchla();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('There was an error with the request: ' + error);
                }
            });
        });
    });





    function fdfcheckall() {
        // alert(8656875);
        if ($("#checkall").prop('checked') == true) {
            $('.checkall').prop('checked', true);
        } else {
            $('.checkall').prop('checked', false);
        }
    }
</script>

@include('include.footer')
