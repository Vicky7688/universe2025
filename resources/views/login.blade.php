<!doctype html>
<html lang="en">
<head>
<title>Universe Finance</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{ url('public/login') }}/css/style.css">
</head>
<style>
        .error{
            color:red;
        }
        .login-container {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }
        .login-body {
            background-image: url('public/images/login-bg.png');
            background-size: cover;
            background-position: center;
        }

        .card {
            border: none;
            border-radius: 10px;
        }
        .gradient-btn {
            background: linear-gradient(45deg, #ff4d4d, #4d94ff);
            color: white;
            font-weight: bold;
            border-radius: 50px;
            margin-bottom: 12px;
        }
        .gradient-btn:hover {
            background: linear-gradient(45deg, #ff4d4d, #4d94ff);
            opacity: 0.9;
            color: #fff;
        }
        .form-control.form-controller, .form-select.form-controller {
            border: none;
            border-bottom: 1px solid #BCBCBC;
            border-radius: 0;
            box-shadow: none;
            font-size: 12px;
            color: #787878;
        }
        .form-control.form-controller::placeholder {
            color: red;
        }


        .form-control.form-controller:focus, .form-select.form-controller:focus {
            border-bottom: 1px solid #469BE4;
        }
        .p15 {
            padding: 15px;
        }
        .heading h4 {
            font-size: 32px;
            font-weight: bold;
            margin: 24px 0;
        }

        .img-fluid.logo {
	max-width: 82px;
	height: auto;
}
    </style>
<body>
<section class="login-body d-flex align-items-center justify-content-center vh-100">
  <div class="login-container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-10 col-xl-8">
        <div class="card shadow">
          <div class="card-body">
            <div class="mb-4"> <img src="public/images/logo.png" alt="Universe Finance Logo" class="img-fluid logo"> </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="shadow card p15">
                  <form id="login-form">
                    @csrf
                    <div class="heading">
                      <h4>Login</h4>
                    </div>
                    <div class="mb-4">
                      <label for="session" class="form-label">Select Session</label>
                      <select class="form-select form-controller" name="session" id="session">

                                                    @foreach ($yearly_session as $list)

                        <option  @if($records->id==$list->id) @selected(true) @endif value="{{ $list->id }}">{{ $list->name }}</option>

                                                    @endforeach

                      </select>
                    </div>
                    <div class="mb-4">
                      <label for="username" class="form-label">Username</label>
                      <input type="text" class="form-control form-controller" placeholder="Enter Username" name="username" id="username" required>
                    </div>
                    <div class="mb-4">
                      <label for="password" class="form-label">Password</label>
                      <input type="password" class="form-control form-controller" placeholder="Enter Password" name="password" id="password" required>
                    </div>
                    <div class="mb-4"> <small class="errorssss"> </small> </div>
                    <button type="submit" class="btn login-btn w-100 rounded-xl gradient-btn">Sign In
                        <div style="display: none" class="spinner-border spinner-border-sm" role="status"></div>
                    </button>
                    <div class="mb-4 form-check">
                      <input type="checkbox" class="form-check-input" id="rememberMe">
                      <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                  </form>
                </div>
              </div>
              <div class="col-lg-6  d-none d-lg-block text-end"> <img src="public/images/right side.png" alt="Right side image" class="img-fluid"> </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="{{ url('public/login') }}/js/jquery.min.js"></script>
<script src="{{ url('public/login') }}/js/popper.js"></script>
<script script src="{{ url('public/login') }}/js/bootstrap.min.js"></script>
<script src="{{ url('public/login') }}/js/main.js"></script>
<script>
        $(document).ready(function() {
            // $.ajaxSetup({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // });
            $('#login-form').on('submit', function(e) {
                e.preventDefault();
                $('.spinner-border').css('display', 'inline-block');
                $.ajax({
                    type: "POST",
                    url: "{{ route('login') }}",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('.spinner-border').css('display', 'none');
                        if (response.success) {
                            window.location.href =
                            "{{ url('dashboard') }}"; // Redirect to dashboard on successful login
                        } else {
                            $('.errorssss').html('<p class="error">' + response.message +
                                '</p>');

                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });

                $('input[name="email"], input[name="password"]').on('focus', function() {
                    $('.errorssss').html('');
                });
            });




        });
        </script>
</body>
</html>
