@include('include.header')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="">
                @csrf
                <div class="row">
                    <div class="mb-2 col-md-3">
                        <label>Old Password</label>
                        <input type="password" name="old_password" class="form-control" id="old_password">
                    </div>
                    <div class="mb-2 col-md-3">
                        <label>New Password</label>
                        <input type="password" name="new_password" class="form-control" id="new_password">
                    </div>
                    <div class="mb-2 col-md-3">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" id="confirm_password">
                    </div>

                    <div class="mt-2 mb-2 col-md-12 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault();

            // Get values of the new and confirm password fields
            var newPassword = $('#new_password').val();
            var confirmPassword = $('#confirm_password').val();

            // Check if new password and confirm password are the same
            if (newPassword !== confirmPassword) {
                alert('New password and confirm password must be the same.');
                return;  // Stop form submission
            }

            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route("changepassword") }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    alert('Password changed successfully!');
                    window.location.href = '{{ url("changepassword") }}';
                },
                error: function(xhr, status, error) {
                    var errors = xhr.responseJSON.errors;
                    $('.error').remove();
                    $.each(errors, function(key, value) {
                        var errorMessage = value[0];
                        var inputField = $('[name="' + key + '"]');
                        inputField.after('<div class="error" style="color: red; font-size: 12px;">' + errorMessage + '</div>');
                    });
                }
            });
        });
    });
</script>

@include('include.footer')
