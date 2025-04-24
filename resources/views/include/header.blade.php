<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="brand" data-topbar-color="light">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<meta charset="utf-8" />
<title>{{ $pagetitle}} | Universe Fianance</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
<meta content="Myra Studio" name="author" />

<!-- App favicon -->
<link rel="shortcut icon" href="{{ url('public/admin') }}/images/favicon.ico">

<!-- Plugins css -->
<link href="{{ url('public/admin') }}/libs/mohithg-switchery/switchery.min.css" rel="stylesheet" type="text/css" />
<link href="{{ url('public/admin') }}/libs/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
<link href="{{ url('public/admin') }}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{ url('public/admin') }}/libs/selectize/css/selectize.bootstrap3.css" rel="stylesheet" type="text/css" />
<link href="{{ url('public/admin') }}/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" type="text/css" />

<link href="{{ url('public/admin') }}/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="{{ url('public/admin') }}/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="{{ url('public/admin') }}/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="{{ url('public/admin') }}/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="{{ url('public/admin') }}/libs/morris.js/morris.css" rel="stylesheet" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.7/inputmask.min.js"></script>


 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">



<!-- App css -->
<link href="{{ url('public/admin') }}/css/custom.css" rel="stylesheet" type="text/css">
<link href="{{ url('public/admin') }}/css/style.min.css" rel="stylesheet" type="text/css">
<link href="{{ url('public/admin') }}/css/icons.min.css" rel="stylesheet" type="text/css">
<script src="{{ url('public/admin') }}/js/config.js"></script>
 <link rel="stylesheet" href="https://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.7/inputmask.min.js"></script>

 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 {{--  4 oct  --}}
 <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 <meta name="csrf-token" content="{{ csrf_token() }}">



</head>
<style>
    #ui-datepicker-div {
	z-index: 1000000 !important;
}

.scroll_1 {
  overflow-y: scroll;
  top: 0;
  bottom: 0;
  max-height: 100%;
}

/* Customize the scrollbar */
.scroll_1::-webkit-scrollbar {
  width: 8px; /* Thin scrollbar */
}

.scroll_1::-webkit-scrollbar-track {
  background: transparent; /* Optional: makes track background transparent */
}

.scroll_1::-webkit-scrollbar-thumb {
  background-color: blue !important; /* Blue scrollbar */
  border-radius: 10px; /* Optional: rounded corners */
  border: 2px solid transparent; /* Optional: adds space around the thumb */
}

.scroll_1::-webkit-scrollbar-thumb:hover {
  background-color: darkblue; /* Darker blue on hover */
}

body{
    font-size: 14px;
    font-weight: 700;
    color: black;
}
.table thead>tr>th{
    font-size: 15px;
    font-weight: 700;
    color: black;
}
h4{
    font-size: 15px;
    font-weight: 700;
    color: black !important;
}

li{
    font-size: 15px;
    font-weight: 700;
    color: black !important;
}

.btn {
    color: black;
    font-weight: 700;
}
.label{
    color: black;
    font-weight: 700;
}


</style>
<body>
<div class="layout-wrapper">
<div class="main-menu"  id="sidebar">
     @include('include.inc.sidebar')
    </div>
<div class="page-content">
@include('include.inc.nav')
<style>
    .tableHeading {
	background-color: #3cbade;
}
    .tableHeading th {
	color: #e9eff1;
}
/* td {
	color: #050505 !important;
} */
</style>
<div class="px-2 content">
    <div class="container-fluid">
      <div class="up-bottom-space20">
        <div class="row">
          <div class="col-lg-6">
            <h4 class="page-title mb-0 cst-text-blue fw-bold">{{ $pagetitle}}</h4>
          </div>
          <div class="col-lg-6">
            <div class="d-none d-lg-block">
              <ol class="breadcrumb m-0 float-end">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" ><a href="{{ $pageto }}">{{ $pagetitle}}</a></li>
              </ol>
            </div>
          </div>
        </div>
      </div>
