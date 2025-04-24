<div class="wrapper scroll_1" id="sidebar">
    <div class="sidebar" id="sidebar">
        <div class="logo-box">
            <a class='logo-light' href='{{ url('') }}'> <img src="{{ url('public/images/logo.png') }}" alt="logo"
                    height="100" class="logo-lg"> <img src="{{ url('public/admin') }}/images/logo-sm.png"
                    alt="small logo" class="logo-sm" height="28"> </a>
        </div>
        <div data-simplebar>
            <div data-simplebar>
                <ul class="app-menu   ">
                    <li class="menu-item"> <a class='menu-link waves-effect waves-light' href='{{ url('') }}'>
                            <span class="menu-icon"><i class="fas fa-tachometer-alt"></i></span> <span
                                class="menu-text"> Dashboards </span> </a> </li>
                                <li class="menu-item"> <a class='menu-link waves-effect waves-light' href='{{ url('logout') }}'>
                                    <span class="menu-icon"><i class="fas fa-sign-out-alt"></i></span> <span
                                        class="menu-text"> Sign Out </span> </a> </li>

                    <li class="menu-item">
                        <a href="#menuMultilevel" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                            <span class="menu-icon"><i class="fas fa-user-shield"></i></span>
                            <span class="menu-text"> Masters </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="menuMultilevel">
                            <ul class="sub-menu">
                                @if (Session::get('logintype') == 'superadmin')
                                    <li class="menu-item"> <a class='menu-link' href='{{ url('addbranch') }}'> <span
                                                class="menu-text">Head Office</span> </a> </li>
                                    <li class="menu-item"> <a class='menu-link' href='{{ url('loginmaster') }}'> <span
                                                class="menu-text">Create Admin</span> </a> </li>
                                @endif
                                {{-- <li class="menu-item"> <a class='menu-link' href='{{ url('session') }}'> <span class="menu-text">Session Master</span> </a> </li> --}}
                                <li class="menu-item"> <a class='menu-link' href='{{ url('commetee') }}'> <span
                                            class="menu-text">Commetee Master</span> </a> </li>
                                <li class="menu-item">
                                    <a href="#menuMultilevel2" data-bs-toggle="collapse"
                                        class="menu-link waves-effect waves-light">
                                        <span class="menu-text"> Address Module </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse" id="menuMultilevel2">
                                        <ul class="sub-menu">
                                            <li class="menu-item">
                                                <a href="{{ url('state') }}" class="menu-link">
                                                    <span class="menu-text">State</span>
                                                </a>
                                            </li>
                                            <li class="menu-item">
                                                <a href="{{ url('district') }}" class="menu-link">
                                                    <span class="menu-text">District</span>
                                                </a>
                                            </li>
                                            <li class="menu-item">
                                                <a href="{{ url('tehsil') }}" class="menu-link">
                                                    <span class="menu-text">Tehsil</span>
                                                </a>
                                            </li>
                                            <li class="menu-item">
                                                <a href="{{ url('postoffice') }}" class="menu-link">
                                                    <span class="menu-text">Post Office</span>
                                                </a>
                                            </li>

                                            <li class="menu-item">
                                                <a href="{{ url('village') }}" class="menu-link">
                                                    <span class="menu-text">Village</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>




                                <li class="menu-item">
                                    <a href="#menuMultilevel22" data-bs-toggle="collapse"
                                        class="menu-link waves-effect waves-light">
                                        <span class="menu-text"> Accounting Module </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse" id="menuMultilevel22">
                                        <ul class="sub-menu">
                                            <li class="menu-item">
                                                <a href="{{ url('group') }}" class="menu-link">
                                                    <span class="menu-text">Group Master</span>
                                                </a>
                                            </li>
                                            <li class="menu-item">
                                                <a href="{{ url('ledger') }}" class="menu-link">
                                                    <span class="menu-text">Ledger Master</span>
                                                </a>
                                            </li>
                                            <li class="menu-item">
                                                <a href="{{ url('narration') }}" class="menu-link">
                                                    <span class="menu-text">Narration Master</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @if (Session::get('adminloginid') == 1)
                                    <li class="menu-item">
                                        <a href="#menuMultilevel235" data-bs-toggle="collapse"
                                            class="menu-link waves-effect waves-light">
                                            <span class="menu-text"> Agent Master</span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="menuMultilevel235">
                                            <ul class="sub-menu">
                                                <li class="menu-item">
                                                    <a href="{{ url('agent') }}" class="menu-link">
                                                        <span class="menu-text">Agent Master</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif
                                {{-- <li class="menu-item">
                                <a href="#menuMultilevel234" data-bs-toggle="collapse"
                                    class="menu-link waves-effect waves-light">
                                    <span class="menu-text">Collection Master</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="menuMultilevel234">
                                    <ul class="sub-menu">
                                        <li class="menu-item">
                                            <a href="{{ url('dailyCollSechme') }}" class="menu-link">
                                                <span class="menu-text">Create Schemes</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li> --}}






                                <li class="menu-item">
                                    <a href="#menuMultilevel23" data-bs-toggle="collapse"
                                        class="menu-link waves-effect waves-light">
                                        <span class="menu-text"> Loan Module </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse" id="menuMultilevel23">
                                        <ul class="sub-menu">
                                            {{-- <li class="menu-item">
                                            <a href="{{ url('loantype') }}" class="menu-link">
                                                <span class="menu-text">Loan Type Master</span>
                                            </a>
                                        </li> --}}
                                            <li class="menu-item">
                                                <a href="{{ url('loanmaster') }}" class="menu-link">
                                                    <span class="menu-text">Loan Master</span>
                                                </a>
                                            </li>
                                            <li class="menu-item">
                                                <a href="{{ url('purposemaster') }}" class="menu-link">
                                                    <span class="menu-text">Purpose Master</span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </li>
                            </ul>



                        </div>
                    </li>


                    <li class="menu-item">
                        <a href="#menuMultilevelTransactions" data-bs-toggle="collapse"
                            class="menu-link waves-effect waves-light">
                            <span class="menu-icon"><i class="fas fa-file-invoice"></i></span>
                            <span class="menu-text"> Transactions </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="menuMultilevelTransactions">
                            <ul class="sub-menu">
                                <li class="menu-item"> <a class='menu-link' href='{{ url('accounts') }}'> <span
                                            class="menu-text">Account Opening</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('advancement') }}'> <span
                                            class="menu-text">Loan</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('quickrecovery') }}'> <span
                                            class="menu-text">Quick Recovery</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('foreclosure') }}'> <span
                                            class="menu-text">Foreclosure Recovery</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('cometeerecovery') }}'>
                                        <span class="menu-text">Committee Collection</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link'
                                        href='{{ url('widrawcometeerecovery') }}'> <span class="menu-text">Committee
                                            Withdraw</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('disbursment') }}'> <span
                                            class="menu-text">Disbursment List</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('filecustomerindex') }}'>
                                        <span class="menu-text">Customer Files</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('voucher') }}'> <span
                                            class="menu-text">Vouchars</span> </a> </li>
                            </ul>
                        </div>
                    </li>


                    <li class="menu-item">
                        <a href="#menuMultilevelreports" data-bs-toggle="collapse"
                            class="menu-link waves-effect waves-light">
                            <span class="menu-icon"> <i class="fas fa-flag"></i></span>
                            <span class="menu-text"> Reports </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="menuMultilevelreports">
                            <ul class="sub-menu">
                                <li class="menu-item"> <a class='menu-link' href='{{ url('daybook') }}'><span aria-colcount=""class="menu-text">Daybook</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('generalLedgerReport') }}'><span class="menu-text">Genral Ledger</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('receiptDisbursment') }}'><span class="menu-text">Receipt & Disbursment</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('agentsreport') }}'><span class="menu-text">Agents Report</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('Log-Book') }}'><span class="menu-text">Log Book</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('alllogbook') }}'><span class="menu-text">All Over balance</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('emireport') }}'><span class="menu-text">Emi Wise report</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('loanpersonalledgers') }}'><span class="menu-text">Personal Ledger</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('pending-emi-report') }}'><span class="menu-text">Loan OverDue Report</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('due-emi-report') }}'><span class="menu-text">Pending Emi Report</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('emi-report') }}'> <span class="menu-text">Emi Report</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('committeereportIndex') }}'> <span class="menu-text">Committee Report</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('profitlossIndex') }}'><span class="menu-text">Profit & Loss</span> </a> </li>
                                <li class="menu-item"> <a class='menu-link' href='{{ url('balancesheetindex') }}'><span class="menu-text">Balance Sheet</span> </a> </li>
                            </ul>
                        </div>
                    </li>
                    {{-- <li class="menu-item">
                    <a href="#" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                        <span class="menu-icon"><i class='bx bx-transfer-alt'></i></span>
                        <span class="menu-text"> Set Current Date </span>
                        <span class="menu-arrow"></span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                        <span class="menu-icon"><i class='bx bx-transfer-alt'></i></span>
                        <span class="menu-text"> Settings</span>
                        <span class="menu-arrow"></span>
                    </a>
                </li> --}}
                    {{-- <li class="menu-item">
                    <a href="{{ url('logout') }}" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                        <span class="menu-icon"><i class="fas fa-sign-out-alt"></i></span>
                        <span class="menu-text"> Sign Out</span>
                        <span class="menu-arrow"></span>
                    </a>
                </li> --}}

                    <li class="menu-item"> <a class='menu-link waves-effect waves-light'
                            href='{{ url('changepassword') }}'> <span class="menu-icon"><i
                                    class="fas fa-unlock"></i></span> <span class="menu-text"> Change Password </span>
                        </a> </li>


                </ul>
            </div>
        </div>
    </div>
</div>
