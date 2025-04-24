@include('include.header')
<form action="{{ $formurl }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body"> @csrf
                    <div class="row">
                        <div class="mb-2 col-md-3">
                            <label class="form-label">Select Commetee</label>
                            <select id="selectize-optgroup" class="cometiknam" name="cometee" required
                                onChange="getmembers(this.value)">
                                <option value="">Select </option>
                                @if (sizeof($commetee) > 0)
                                    @foreach ($commetee as $commeteelist)
                                        <option value="{{ $commeteelist->id }}">{{ $commeteelist->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mb-2 col-md-3">
                            <label class="form-label">Date</label>
                            <input type="text" id="datefor" name="date" class="onlydate form-control date1"
                                value="{{ Session::get('setcurrentdate') }}">
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
                                <th>Sr.No</th>
                                <th>Customer </th>
                                <th>Comettee From</th>
                                <th>Comettee Till</th>
                                <th>Payment Date</th>
                                <th>Amount</th>
                                <th><input type="checkbox" id="checkall" onclick="fdfcheckall()">Pay</th>
                                <th>Status</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="mb-2 col-md-4">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
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
        getmembers(id);
    }

    function deleterecoverycometti(id) {

        $.ajax({
            url: "{{ route('deleterecoverycometti') }}",
            type: "POST",
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(data) {
                ajaxchla();
            }
        });
    }

    function getmembers(id) {
        var dat = $('#datefor').val();
        $.ajax({
            url: "{{ route('getcometeemembers') }}",
            type: "POST",
            data: {
                id: id,
                dat: dat,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(data) {
                $('table tbody').empty();
                $('.table tbody').html(data.html);
            }
        });
    }

    $(document).ready(function() {
        @if (session('success'))
            alert('Success: {{ session('success') }}');
        @elseif (session('error'))
            alert('Error: {{ session('error') }}');
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

        // Handle form submission with Ajax
        $('form').submit(function(e) {
            e.preventDefault(); // Prevent the default form submission

            var formData = $(this).serialize(); // Serialize the form data

            $.ajax({
                url: "{{ route('recoverycometeemembers') }}",
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
