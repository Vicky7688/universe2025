@include('include.header')
<style>
    .img{
        max-width:180px;
      }
    .input[type=file]{
      padding:10px;
      background:#2d2d2d;
    }
</style>
<div class="row">

    <div class="col-lg-6">
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="{{ url('public/admin/images/boy-app-academy.png') }}" alt="Card image" class="img-fluid" />
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">Loan Advancement</h5>
                        <p class="card-text">Advance Loans, Quick Finance, Rapid Funds, Swift Borrowin. </p>
                        <p class="card-text"><a href="{{ url('advancement') }}"><button type="button" class="btn btn-primary btn-bordered rounded-pill waves-effect waves-light">Proceed</button></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">Loan Recovery</h5>
                        <p class="card-text">Debt Collection, Retrieving Loans, Repayment Process, Recovery Solutions </p>
                        <p class="card-text"><a href="{{ url('advancement') }}"><button type="button" class="btn btn-primary btn-bordered rounded-pill waves-effect waves-light">Proceed</button></a></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <img src="{{ url('public/admin/images/girl-app-academy.png') }}" alt="Card image" class="img-fluid" />
                </div>
            </div>
        </div>
    </div>
</div>

@include('include.footer')
