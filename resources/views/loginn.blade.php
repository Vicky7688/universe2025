<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Document</title>
</head>
<style>
    body {
  margin: 0;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background: url("https://i.ibb.co/yFWzhXd/login-3-bg.png");
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  height: 100vh;
  font-family: "Roboto", sans-serif;
}

.form-2-wrapper {
  background: #9d00ff29;
  padding: 50px;
  border-radius: 8px;
}
input.form-control {
  padding: 11px;
  border: none;
  border: 2px solid #405c7cb8;
  border-radius: 30px;
  background-color: transparent;
  font-family: Arial, Helvetica, sans-serif;
}
input.form-control:focus {
  box-shadow: none !important;
  outline: 0px !important;
  background-color: transparent;
}


select.form-control {
  padding: 11px;
  border: none;
  border: 2px solid #405c7cb8;
  border-radius: 30px;
  background-color: transparent;
  font-family: Arial, Helvetica, sans-serif;
}
select.form-control:focus {
  box-shadow: none !important;
  outline: 0px !important;
  background-color: transparent;
}
button.login-btn {
  background: #b400ff;
  color: #fff;
  border: none;
  padding: 10px;
  border-radius: 30px;
}
.register-test a {
  color: #000;
}
.social-login button {
  border-radius: 30px;
}

</style>
<body>
    
    
<div class="container">
  <div class="row">
    <!-- Left Blank Side -->
    <div class="col-lg-6"></div>

    <!-- Right Side Form -->
    <div class="col-lg-6 d-flex align-items-center justify-content-center right-side">
      <div class="form-2-wrapper">
        <div class="logo text-center">
          <img src="{{ url('public/admin/images/logo2.png') }}" alt="hng">
        </div>
        <h2 class="text-center mb-4">Sign Into Your Account</h2>
        <form class="login-form" >    @csrf
          <div class="mb-3 "> 
            <select class="form-control" name="session" id="session" required>
                {{-- @if($records->id==$list->id) @selected(true) @endif --}}
                <option value="">Select Session</option>
                @foreach ($yearly_session as $list) 
                <option  value="{{ $list->id }}">{{ $list->name }}</option> 
              @endforeach
            </select>
          </div>
          <div class="mb-3 "> 
            <select name="type" id="type"  class="form-control" required >
                <option value="">Select Account Type</option>
                <option value="supeadmin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="retailer">Retailer</option>
                <option value="distributer">Distributer</option>
                <option value="user">User</option>
            </select>
          </div>
          <div class="mb-3 ">
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Your Email" required>
          </div>
          <div class="mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Your Password" required>
          </div>
          <div class="mb-3">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="rememberMe">
              <label class="form-check-label" for="rememberMe">Remember me</label>
              <a href="forget-3.html" class="text-decoration-none float-end">Forget Password</a>
            </div>

          </div>
          <button type="submit" class="btn btn-outline-secondary login-btn w-100 mb-3">Login</button>
          {{-- <div class="social-login mb-3 type--A">
            <h5 class="text-center mb-3">Social Login</h5>
            <button class="btn btn-outline-secondary  mb-3"><i class="fa-brands fa-google text-danger"></i> Sign With Google</button>
            <button class="btn btn-outline-secondary mb-3"><i class="fa-brands fa-facebook-f text-primary"></i> Sign With Facebook</button>
          </div> --}}
        </form>

        <!-- Register Link -->
        <p class="text-center register-test mt-3">Don't have an account? <a href="register-3.html" class="text-decoration-none">Register here</a></p>
      </div>
    </div>
  </div>
</div>

</body>
</html>