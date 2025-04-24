
@include('include.header')
<style>
    .live-number {
    font-size: 24px;
    font-weight: bold;
}
.live-heading {
    font-size: 20px;
    color: #868686;
}
.live-blue {
    color: #108FFF;
}
.live-green {
    color: #05FF8A;
}
.live-Pink {
    color: #FF10EF;
}
.live-greyish {
    color: #05DEFF;
}
.u-f {
    width: 100%;
    max-width: 980px;
    background: #fff;
    box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
    border-radius: 10px;
    padding: 20px;
}
@media (max-width: 1369px) {
    .calendar-dashboard {
        display: none;
    }
}
@media (max-width: 767px) {
    .d-card {
        max-width: 150px;
    }
}
@media (max-width :575px) {
    .d-card {
        display: none;
    }
}
</style>
    <div class="d-flex justify-content-between">
        <div class="u-f">
            <h4>Universe Finance</h4>
            <div class="d-flex justify-content-between mt-4 align-items-center">
                <div class="d-card"><img src="{{ url('public/images/Card.png') }}" alt="Card"></div>
                <div class="line"></div>
                <div class="live-data text-end">
                    <div>
                        <div class="live-number live-blue">
                            {{ DB::table('member_accounts')->count() }}
                        </div>
                        <div class="live-heading">
                            Total Customer
                        </div>
                    </div>
                    <div>
                        <div class="live-number live-green">
                            {{ DB::table('member_loans')->count() }}
                        </div>
                        <div class="live-heading">
                            Total Loans
                        </div>
                    </div>
                    <div>
                        <div class="live-number live-Pink">
                            {{ DB::table('member_loans')->where('status','=','Disbursed')->count() }}
                        </div>
                        <div class="live-heading">
                            Total Disbursed loans
                        </div>
                    </div>
                    <div>
                        <div class="live-number live-greyish">
                            {{ DB::table('member_loans')->where('status','=','Closed')->count() }}
                        </div>
                        <div class="live-heading">
                            Closed Loans
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="calendar-dashboard">
            <img src="{{ url('public/images/Calendar.png') }}" alt="Calendar">
        </div>
    </div>
    <div class="pt-3">
        <div class="row">
            <div class="col-md-6 col-xl-3">
              <div class="card">
                <div class="card-body">
                  <div class="mb-4">
                    <h5 class="card-title mb-0">Customers</h5>
                  </div>
                  <div class="row d-flex align-items-center mb-4">
                    <div class="col-8">
                      <h2 class="d-flex align-items-center mb-0"> {{ DB::table('member_accounts')->count() }} </h2>
                    </div>


                  </div>
                  <div class="progress shadow-sm" style="height: 5px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 57%;"> </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xl-3">
              <div class="card">
                <div class="card-body">
                  <div class="mb-4">
                    <h5 class="card-title mb-0">Total loans</h5>
                  </div>
                  <div class="row d-flex align-items-center mb-4">
                    <div class="col-8">
                      <h2 class="d-flex align-items-center mb-0"> {{ DB::table('member_loans')->count() }} </h2>
                    </div>
                  </div>
                  <div class="progress shadow-sm" style="height: 5px;">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 57%;"> </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xl-3">
              <div class="card">
                <div class="card-body">
                  <div class="mb-4">
                    <h5 class="card-title mb-0">Disbursed loans</h5>
                  </div>
                  <div class="row d-flex align-items-center mb-4">
                    <div class="col-8">
                      <h2 class="d-flex align-items-center mb-0"> {{ DB::table('member_loans')->where('status','=','Disbursed')->count() }} </h2>
                    </div>

                  </div>
                  <div class="progress shadow-sm" style="height: 5px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 57%;"> </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xl-3">
              <div class="card">
                <div class="card-body">
                  <div class="mb-4">
                    <h5 class="card-title mb-0">Closed loans</h5>
                  </div>
                  <div class="row d-flex align-items-center mb-4">
                    <div class="col-8">
                      <h2 class="d-flex align-items-center mb-0"> {{ DB::table('member_loans')->where('status','=','Closed')->count() }} </h2>
                    </div>
                  </div>
                  <div class="progress shadow-sm" style="height: 5px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 57%;"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div>
    <div class="w100" style="display: none;">
        <div>
            <img src="{{ url('public/images/customer.png') }}" alt="customer">
        </div>
    </div>
  </div>
</div>
@include('include.footer')
