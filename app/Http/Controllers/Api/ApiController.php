<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\loan_masters;
use App\Models\purpose_masters;
use App\Models\member_accounts;
use App\Models\AgentMaster;
use App\Models\member_loans;
use App\Models\loan_installments;
use App\Models\general_ledgers;
use App\Models\ledger_masters;
use App\Models\loan_recoveries;
use App\Models\ledgers;
use App\Models\post_office_masters;
use App\Models\village_masters;
use App\Models\state_masters;
use App\Models\tehsil_masters;
use App\Models\district_masters;
use App\Models\AccountOpening;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use DB;
use Hash;
use Log;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\DB as FacadesDB;

class ApiController extends Controller
{
    public function userlogin(Request $request){

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'session_id' => 'required|exists:session_masters,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }
        $user = DB::table('agent_masters')->where('user_name', $request->username)->first();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found']);
        } else {
            if (Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Successful.',
                    'agent_id' => $user->id,
                    'session_id' => $request->session_id,
                    'device_name' => $request->device_name,
                    'miui_version' =>  $request->miui_version,
                    'ip_address' =>  $request->ip_address,
                ]);
            } else {
                return response()->json(['status' => false, 'message' => 'Invalid Password']);
            }
        }
    }





    


















































































    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        $user = DB::table('member_accounts')->where('customer_Id', '=', $username)->first();
        if ($user) {

            if (Hash::check($password, $user->password)) {

                return response()->json([
                    'status' => true,
                    'message' => 'Login SuccessFull',
                    'cid' => $username,
                    'password' => $user->password_status,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Password',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Username',
            ]);
        }
    }





    public function changeuserpassword(Request $request)
    {
        $cid = $request->cid;
        $oldpassword = $request->oldpassword;
        $newpassword = $request->newpassword;
        $confirmpassword = $request->confirmpassword;




        $user = DB::table('member_accounts')->where('customer_Id', '=', $cid)->first();
        if ($user) {

            if (Hash::check($oldpassword, $user->password)) {

                if ($newpassword == $confirmpassword) {

                    $update = member_accounts::find($user->id);
                    $update->password = Hash::make($newpassword);
                    $update->password_status = 'changed';
                    $update->save();

                    $updateee = member_accounts::find($user->id);
                    return response()->json([
                        'status' => true,
                        'message' => 'Password Changed SuccessFull',
                        'cid' => $cid,
                        'password' => $updateee->password_status,
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Confirm Password does not match',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Wrong old Password',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Cid',
            ]);
        }
    }



    // public function changeuserpassword(Request $request)
    // {
    //     // Validate the incoming request
    //     $validated = $request->validate([
    //         'oldpassword' => 'required|string',
    //         'newpassword' => 'required|string|min:8',
    //         'confirmpassword' => 'required|string|min:8',
    //     ]);

    //     if ($request->newpassword !== $request->confirmpassword) {
    //         return response()->json(['status' => false, 'message' => 'New password and confirm password must match.'], 400);
    //     }

    //     $user = member_accounts::where('id', '=', $request->cid)->first();

    //     if (!$user || !Hash::check($request->oldpassword, $user->password)) {
    //         return response()->json(['status' => false, 'message' => 'Old password is incorrect.'], 400);
    //     }

    //     $user->password = Hash::make($request->newpassword);
    //     $user->save();

    //     return response()->json(['status' => true, 'message' => 'Password changed successfully!'], 200);
    // }


    public function changePassword(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'oldpassword' => 'required|string',
            'newpassword' => 'required|string|min:8',
            'confirmpassword' => 'required|string|min:8',
        ]);

        if ($request->newpassword !== $request->confirmpassword) {
            return response()->json(['status' => false, 'message' => 'New password and confirm password must match.'], 400);
        }

        $user = AgentMaster::where('id', '=', $request->agent_id)->first();

        if (!$user || !Hash::check($request->oldpassword, $user->password)) {
            return response()->json(['status' => false, 'message' => 'Old password is incorrect.'], 400);
        }

        $user->password = Hash::make($request->newpassword);
        $user->save();

        return response()->json(['status' => true, 'message' => 'Password changed successfully!'], 200);
    }





    public function banner()
    {
        $image = url('public/images/banner.png');
        $banner = array();
        for ($x = 0; $x <= 10; $x++) {
            $banner[] = $image;
        }
        return response()->json([
            'status' => true,
            'banner' => $banner,
        ]);
    }
    public function getlogin()
    {
        $session = DB::table('yearly_session')->select('id', 'startdate', 'enddate')->first();
        if ($session) {
            $sessionDate = date('Y', strtotime($session->startdate)) . "-" . date('Y', strtotime($session->enddate));
        } else {
            $sessionDate = null;
        }
        return response()->json([
            'status' => true,
            'message' => 'Welcome to Universe Finance',
            'session' => [
                'id' => $session->id ?? null,
                'date' => $sessionDate
            ]
        ]);
    }








    public function searchgetallCustomer(Request $request)
    {

        $page = $request->input('page', 1);
        $size = $request->input('size', 15);

        $searchTerm = $request->input('search', '');
        $query = member_accounts::select('id', 'name', 'customer_Id', 'father_husband', 'gender', 'mobile_first', 'openingDate', 'status');

        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('customer_id', 'like', '%' . $searchTerm . '%');
            });
        }

        // Paginate the results
        $searchallCustomer = $query->get();


        return response()->json([
            'status' => true,
            'searchallCustomer' => $searchallCustomer,
        ]);
    }


    public function getallCustomer(Request $request)
    {
        // Get the current page and number of items per page from the request, with default values
        $page = $request->input('page', 1);
        $size = $request->input('size', 15);
        $customers = DB::table('member_accounts')->select('id', 'name', 'customer_Id', 'father_husband', 'gender', 'mobile_first', 'openingDate', 'status')
            ->orderby('name', 'ASC')->paginate($size, ['*'], 'page', $page);
        // Return response as JSON
        return response()->json([
            'status' => true,
            'currentPage' => $customers->currentPage(),
            'totalPages' => $customers->lastPage(),
            'totalCustomers' => $customers->total(),
            'allCustomers' => $customers->items(),
        ]);
    }
    // loan recovery
    public function getdatacustomer(Request $request)
    {
        $datacustomer = DB::table('member_accounts')->where('customer_id', $request->customerid)->first();
        if ($datacustomer) {
            return response()->json([
                'status' => true,
                'datacustomer' => $datacustomer,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Not Found',
            ]);
        }
    }
    public function allloans(Request $request)
    {
        $allloans = DB::table('loan_masters')->select('id', 'loanname')->get();
        return response()->json(['status' => true, 'allloans' => $allloans]);
    }
    public function allpurpose(Request $request)
    {
        $allpurpose = DB::table('purpose_masters')->select('id', 'name')->get();
        return response()->json(['status' => true, 'allpurpose' => $allpurpose]);
    }


    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'session_id' => 'required|exists:session_masters,id'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }
        $user = DB::table('agent_masters')->where('user_name', $request->username)->first();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found']);
        } else {
            if (Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Successful.',
                    'agent_id' => $user->id,
                    'session_id' => $request->session_id,
                    'device_name' => $request->device_name,
                    'miui_version' =>  $request->miui_version,
                    'ip_address' =>  $request->ip_address,
                ]);
            } else {
                return response()->json(['status' => false, 'message' => 'Invalid Password']);
            }
        }
    }







    public function disbursedloan(Request $request)
    {
        $cid = $request->cid;
        $page = $request->input('page', 1);
        $size = $request->input('size', 15);

        // Start building the query
        $query = DB::table('member_loans')
            ->join('member_accounts', 'member_loans.accountNo', '=', 'member_accounts.customer_id')
            ->join('loan_masters', 'member_loans.loanType', '=', 'loan_masters.id')
            ->join('agent_masters', 'member_loans.agentId', '=', 'agent_masters.id')
            ->leftJoin(
                DB::raw('(SELECT loanId, SUM(principal) AS total_recovered FROM loan_recoveries GROUP BY loanId) AS loan_recovery_summary'),
                'member_loans.id',
                '=',
                'loan_recovery_summary.loanId'
            )
            ->select(
                'member_loans.id',
                'member_loans.loanDate',
                'member_loans.loanAmount',
                'member_loans.accountNo',
                'member_accounts.name as customer_name',
                'member_accounts.mobile_first as primary_phone',
                'member_loans.status',
                'loan_masters.loanname',
                'agent_masters.name as agentname',
                DB::raw('IFNULL(loan_recovery_summary.total_recovered, 0) as total_recovered'),
                DB::raw('IF(IFNULL(loan_recovery_summary.total_recovered, 0) > 0, "no", "yes") as changable')
            )
            ->orderBy('member_accounts.name', 'ASC');

        // Apply the where condition only if $cid is not empty
        if (!empty($cid)) {
            $query->where('member_accounts.customer_Id', '=', $cid);
        }

        // Execute the query and paginate
        $disbursedloan = $query->paginate($size, ['*'], 'page', $page);

        // Return response based on whether loans are found
        if (!$disbursedloan->isEmpty()) {
            return response()->json([
                'status' => true,
                'currentPage' => $disbursedloan->currentPage(),
                'totalPages' => $disbursedloan->lastPage(),
                'totalLoans' => $disbursedloan->total(),
                'disbursedloan' => $disbursedloan->items(),
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No Loan Found'
            ]);
        }
    }


    public function searchdisbursedloan(Request $request)
    {
        $cid = $request->cid;
        $page = $request->input('page', 1);
        $size = $request->input('size', 15);
        $searchTerm = $request->input('search', ''); // Retrieve search term
        // Start building the query
        $query = DB::table('member_loans')
            ->join('member_accounts', 'member_loans.accountNo', '=', 'member_accounts.customer_id')
            ->join('loan_masters', 'member_loans.loanType', '=', 'loan_masters.id')
            ->join('agent_masters', 'member_loans.agentId', '=', 'agent_masters.id')
            ->leftJoin(
                DB::raw('(SELECT loanId, SUM(principal) AS total_recovered FROM loan_recoveries GROUP BY loanId) AS loan_recovery_summary'),
                'member_loans.id',
                '=',
                'loan_recovery_summary.loanId'
            )
            ->select(
                'member_loans.id',
                'member_loans.loanDate',
                'member_loans.loanAmount',
                'member_loans.accountNo',
                'member_accounts.name as customer_name',
                'member_accounts.mobile_first as primary_phone',
                'member_loans.status',
                'loan_masters.loanname',
                'agent_masters.name as agentname',
                DB::raw('IFNULL(loan_recovery_summary.total_recovered, 0) as total_recovered'),
                DB::raw('IF(IFNULL(loan_recovery_summary.total_recovered, 0) > 0, "no", "yes") as changable')
            )
            ->orderBy('member_loans.id', 'DESC');

        // Apply the where condition only if $cid is not empty
        if (!empty($cid)) {
            $query->where('member_accounts.customer_Id', '=', $cid);
        }
        // Apply the search filter if the search term is provided
        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('member_accounts.customer_Id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('member_accounts.name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('member_accounts.mobile_first', 'like', '%' . $searchTerm . '%');
            });
        }
        // Execute the query and paginate
        $disbursedloan = $query->paginate($size, ['*'], 'page', $page);

        // Return response based on whether loans are found
        if (!$disbursedloan->isEmpty()) {
            return response()->json([
                'status' => true,
                'currentPage' => $disbursedloan->currentPage(),
                'totalPages' => $disbursedloan->lastPage(),
                'totalLoans' => $disbursedloan->total(),
                'disbursedloan' => $disbursedloan->items(),
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No Loan Found'
            ]);
        }
    }












    public function getinstallments(Request $request)
    {
        $installments = DB::table('loan_installments')->where('LoanId', '=', $request->loanid)->get();
        $installments = DB::table('loan_installments as li')
            ->join('member_loans as la', 'li.LoanId', '=', 'la.id')
            ->join('loan_masters as lm', 'la.loanType', '=', 'lm.id')
            ->where('li.LoanId', $request->loanid)
            ->select('li.id', 'li.LoanId as loan_id', 'li.installmentDate', 'li.principal', 'li.interest', 'li.totalinstallmentamount', 'li.status', 'lm.loanname as loanName')->get();
        if (!$installments->isEmpty()) {
            return response()->json([
                'status' => !$installments->isEmpty(),
                'installments' => $installments,
            ]);
        } else {
            return response()->json([
                'status' => !$installments->isEmpty(),
                'message' => $installments->isEmpty() ? 'No Loan Found' : null // Include a message if data is empty
            ]);
        }
    }
    public function loanadvancement(Request $request)
    {
        Log::info($request->all());
        DB::beginTransaction();
        try {
            $loanmass = loan_masters::find($request->loan);
            $account_no = $request->accountNo;

            $runningloan = member_loans::where('accountNo', '=', $account_no)->where('status', '=', 'Disbursed')->first();
            if ($runningloan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Previos Loan is Not Closed Yet'
                ], 400);
            }
            $checkaccount_no = DB::table('member_accounts')->where('customer_Id', $account_no)->first();
            $checkdate = $checkaccount_no->openingDate;
            $loan_date = date("Y-m-d", strtotime($request->loanDate));
            // $emidate = date('Y-m-d',strtotime($request->emiDate));
            //__________Check if the loan date is greater than the account opening date
            if ($loan_date >= $checkdate) {
                $newdate = $loan_date;

                if (!empty($request->emiDate)) {
                    $emidate = date('Y-m-d', strtotime($request->emiDate));
                } else {
                    $emidate = Carbon::parse($newdate)->addMonth()->day(10)->format('Y-m-d');
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Loan date must be after the account opening date'
                ], 400);
            }
            $advancement_amount = 0;
            $loan_amount = $request->amount;
            $loan_limit = $checkaccount_no->loan_limit;
            $creditAmount = DB::table('member_loans')
                ->where('accountNo', $request->accountNo) // Replace with the actual customer_id
                ->sum('loanAmount');
            $debitAmount = DB::table('loan_recoveries')
                ->join('member_loans', 'loan_recoveries.loanId', '=', 'member_loans.id')
                ->where('member_loans.accountNo', $request->accountNo) // Replace with the actual customer_id
                ->sum('loan_recoveries.principal');
            $remainingcredit = $creditAmount - $debitAmount;
            $bchahua = $loan_limit - $remainingcredit;
            if ($bchahua >= $loan_amount) {
                $advancement_amount = $loan_amount;
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Loan Amount Exceed From Loan Limit , You have ' . $bchahua . ' in your Limit'
                ], 400);
            }
            //____________Processing Fee Calculation
            $loan_amounts = $request->amount;
            $processing_fee = $request->processingFee;
            $processing_fee_amount = (($loan_amounts * $processing_fee) / 100);
            $meminsert = new member_loans();
            $meminsert->loanDate = $newdate;
            $meminsert->processingFee = $request->processingFee;
            $meminsert->loanInterest = $request->interest;
            $meminsert->installmentType = $loanmass->insType;
            $meminsert->recoveryDate = 'yes';
            $meminsert->advancementDate = 'yes';
            $meminsert->penaltyInteresttype = $request->paneltytype;
            $meminsert->penaltyInterest = $request->penaltyInterest;
            $meminsert->years = 0;
            $meminsert->months = $request->months;
            $meminsert->days = 0;
            $meminsert->emiDate = $emidate;
            $meminsert->accountNo = $request->accountNo;
            $meminsert->loanType = $request->loan;
            $meminsert->purpose = $request->purpose;
            $meminsert->loanby = $request->paymentby;
            $meminsert->loanAmount = $advancement_amount;
            $meminsert->chequeNo = $request->chequeNo;
            $meminsert->ledgerBankAccountId = $request->bankname;
            $meminsert->name = $checkaccount_no->name;
            $meminsert->guarantorname = $request->guarantorname;
            $meminsert->guarantorno = $request->guarantorno;
            $meminsert->guarantoraddress = $request->guarantoraddress;
            $meminsert->guarantornamee = $request->guarantornamee;
            $meminsert->guarantornoo = $request->guarantornoo;
            $meminsert->guarantoraddresss = $request->guarantoraddresss;
            $meminsert->agentId = $request->agentid;
            $meminsert->updatedBy = $request->agentid;
            $meminsert->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
            $meminsert->sessionId     = $request->sessionid;
            $meminsert->save();
            $loandetail = loan_masters::find($request->loan);
            // if ($request->insType == 'Monthly') {
            $loanamount = $request->amount;
            $rateofinterest = $request->interest;
            $tanure = $request->months;
            $monthly_roi = $rateofinterest * $tanure;
            // $month_interst_amount = ((($loanamount * $monthly_roi) / 100) / $tanure);
            // $principal = ($loanamount / $tanure);
            // $emi_amount = $month_interst_amount + $principal;
            $monthlyPrincipaltotal = 0;
            $monthlyInstallmenttottal = 0;
            $monthlyround = 0;
            $monthlyInterest = ((($loanamount * $monthly_roi) / 100) / $tanure);
            $totalintrest = $monthlyInterest * $tanure;
            $principlleadd = $loanamount + $totalintrest;
            $monthlyInstallment = $principlleadd / $tanure;
            $monthlyPrincipal = $monthlyInstallment - $monthlyInterest;
            // dd($monthlyInterest,$totalintrest,$principlleadd,$monthlyInstallment,$monthlyPrincipal);
            $startDate = Carbon::parse($emidate); // Parse the starting date
            for ($i = 0; $i < $tanure; $i++) {
                $loanInstallment = new loan_installments();
                $loanInstallment->LoanId = $meminsert->id;
                // Adjusting the installment date
                if ($i == 0) {
                    $loanInstallment->installmentDate = $startDate->toDateString();
                } else {
                    $nextDate = $startDate->copy()->addMonth($i);
                    if ($nextDate->month == 2) {
                        if ($nextDate->day > 28) {
                            $nextDate->day = $nextDate->isLeapYear() ? 29 : 28;
                        }
                    }

                    $loanInstallment->installmentDate = $nextDate->toDateString();
                }
                // Last installment adjustments
                if ($i == $tanure - 1) {
                    $monthlp = $loanamount - $monthlyPrincipaltotal;
                    $monthlyInt = $loanamount - $monthlyround;
                    $loanInstallment->principal = $monthlp;
                    $loanInstallment->interest = round($monthlyInterest);
                    $loanInstallment->totalinstallmentamount = round($monthlyInterest) + $monthlp;
                } else {
                    // Regular installment calculations
                    $monthlyPrincipaltotal += round($monthlyPrincipal);
                    $monthlyInstallmenttottal += round($monthlyInstallment);
                    $monthlyround += round($monthlyInterest);
                    $loanInstallment->principal = round($monthlyPrincipal);
                    $loanInstallment->interest = round($monthlyInterest);
                    $loanInstallment->totalinstallmentamount = round($monthlyPrincipal) + round($monthlyInterest);
                }
                // Common attributes
                $loanInstallment->paid_date = null;
                $loanInstallment->status = 'false';
                $loanInstallment->re_amount = 0;
                $loanInstallment->agentId = $request->agentid;
                $loanInstallment->updatedBy = $request->agentid;
                $loanInstallment->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
                $loanInstallment->sessionId     = $request->sessionid;
                // Save the installment and handle errors
                if (!$loanInstallment->save()) {
                    // Handle error
                }
            }
            $ledhai = ledger_masters::where('loan', '=', $loanmass->id)->first();
            $ledgerme = new general_ledgers();
            $ledgerme->LoanId = $meminsert->id;
            $ledgerme->accountNo = $request->accountNo;
            $ledgerme->groupCode = $ledhai->groupCode;
            $ledgerme->ledgerCode = $ledhai->ledgerCode;
            $ledgerme->formName = $request->name;
            $ledgerme->transactionDate = $newdate;;
            $ledgerme->transactionType = 'Dr';
            $ledgerme->transactionAmount = $advancement_amount;
            $ledgerme->narration = $loandetail->loanname;
            $ledgerme->branchId = Null;
            $ledgerme->agentId = $request->agentid;
            $ledgerme->updatedBy = $request->agentid;
            $ledgerme->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
            $ledgerme->sessionId     = $request->sessionid;
            $ledgerme->save();
            $ledgerme = new general_ledgers();
            $ledgerme->LoanId = $meminsert->id;
            $ledgerme->accountNo = $request->accountNo;
            $ledgerme->groupCode = 'C002';
            $ledgerme->ledgerCode = 'CAS001';
            $ledgerme->formName = $request->name;
            $ledgerme->transactionDate = $newdate;;
            $ledgerme->transactionType = 'Cr';
            $ledgerme->transactionAmount = $advancement_amount;
            $ledgerme->narration = $loandetail->loanname;
            $ledgerme->branchId = Null;
            $ledgerme->agentId = $request->agentid;
            $ledgerme->updatedBy = $request->agentid;
            $ledgerme->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
            $ledgerme->sessionId     = $request->sessionid;
            $ledgerme->save();
            //__________If Processing Fees
            if ($processing_fee > 0) {
                $ledhai = ledger_masters::where('loan', '=', $loanmass->id)->first();
                $ledgerme = new general_ledgers();
                $ledgerme->LoanId = $meminsert->id;
                $ledgerme->accountNo = $request->accountNo;
                $ledgerme->groupCode = 'IINC01';
                $ledgerme->ledgerCode = 'PRC001';
                $ledgerme->formName = $request->name;
                $ledgerme->transactionDate = $newdate;;
                $ledgerme->transactionType = 'Cr';
                $ledgerme->transactionAmount = $processing_fee;
                $ledgerme->narration = $loandetail->loanname;
                $ledgerme->branchId = Null;
                $ledgerme->agentId = $request->agentid;
                $ledgerme->updatedBy = $request->agentid;
                $ledgerme->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
                $ledgerme->sessionId     = $request->sessionid;
                $ledgerme->save();
                $ledgerme = new general_ledgers();
                $ledgerme->LoanId = $meminsert->id;
                $ledgerme->accountNo = $request->accountNo;
                $ledgerme->groupCode = 'C002';
                $ledgerme->ledgerCode = 'CAS001';
                $ledgerme->formName = $request->name;
                $ledgerme->transactionDate = $newdate;;
                $ledgerme->transactionType = 'Dr';
                $ledgerme->transactionAmount = $processing_fee;
                $ledgerme->narration = $loandetail->loanname;
                $ledgerme->branchId = Null;
                $ledgerme->agentId = $request->agentid;
                $ledgerme->updatedBy = $request->agentid;
                $ledgerme->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
                $ledgerme->sessionId     = $request->sessionid;
                $ledgerme->save();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Transaction failed: ' . $e->getMessage()
            ]);
        }
        return response()->json([
            'status' => true,
            'loanid' => $meminsert->id,
            'message' => 'Loan Disbursed'
        ]);
    }
    public function geteditloan(Request $request)
    {
        $editloanDetails = DB::table('member_loans')
            ->join('member_accounts', 'member_accounts.customer_Id', '=', 'member_loans.accountNo')
            ->where('member_loans.id', '=', $request->loanid)
            ->select(
                'member_loans.id',
                'member_loans.accountNo',
                'member_loans.loanDate',
                'member_loans.purpose',
                'member_loans.loanType',
                'member_loans.loanAmount',
                'member_loans.emiDate',
                'member_loans.guarantorname',
                'member_loans.guarantornamee',
                'member_loans.guarantorno',
                'member_loans.guarantornoo',
                'member_loans.guarantoraddress',
                'member_loans.guarantoraddresss',
                'member_loans.loanBy',
                'member_loans.processingFee',
                'member_loans.status',
                'member_loans.penaltyInteresttype',
                'member_loans.penaltyInterest',
                'member_loans.loanInterest',
                'member_accounts.name as customer_name',
            )
            ->first();
        if ($editloanDetails) {
            return response()->json([
                'status' => true,
                'editloanDetails' => $editloanDetails,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Loan Not Found',
            ]);
        }
    }
    // public function updateloanadvancement(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $id = $request->id;
    //         $loanmass = loan_masters::find($request->loan);
    //         $account_no = $request->accountNo;
    //         $checkaccount_no = DB::table('member_accounts')->where('customer_Id', $account_no)->first();
    //         $checkdate = $checkaccount_no->openingDate;
    //         $loan_date = date("Y-m-d", strtotime($request->loanDate));
    //         if ($loan_date >= $checkdate) {
    //             $newdate = $loan_date;
    //         } else {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Loan date must be after the account opening date'
    //             ], 400);
    //         }
    //         $loanAmountofid = member_loans::find($id)->loanAmount;
    //         $advancement_amount = 0;
    //         $loan_amount = $request->amount;
    //         $loan_limit = $checkaccount_no->loan_limit;
    //         $creditAmount = DB::table('member_loans')
    //             ->where('accountNo', $request->accountNo)
    //             ->sum('loanAmount');
    //         $debitAmount = DB::table('loan_recoveries')
    //             ->join('member_loans', 'loan_recoveries.loanId', '=', 'member_loans.id')
    //             ->where('member_loans.accountNo', $request->accountNo)
    //             ->sum('loan_recoveries.principal');
    //         $remainingcredit = ($creditAmount - $debitAmount) - $loanAmountofid;
    //         $bchahua = $loan_limit - $remainingcredit;
    //         if ($bchahua >= $loan_amount) {
    //             $advancement_amount = $loan_amount;
    //         } else {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Loan Amount Exceed From Loan Limit , You have ' . $bchahua . 'in your Limit'
    //             ], 400);
    //         }
    //         //____________Processing Fee Calculation
    //         $loan_amounts = $request->amount;
    //         $processing_fee = $request->processingFee;
    //         $processing_fee_amount = (($loan_amounts * $processing_fee) / 100);
    //         $meminsert = member_loans::find($id);
    //         $meminsert->loanDate = $newdate;
    //         $meminsert->processingFee = $request->processingFee;
    //         $meminsert->loanInterest = $request->interest;
    //         $meminsert->installmentType = $loanmass->insType;
    //         $meminsert->recoveryDate = 'yes';
    //         $meminsert->advancementDate = 'yes';
    //         $meminsert->penaltyInteresttype = $request->paneltytype;
    //         $meminsert->penaltyInterest = $request->penaltyInterest;
    //         $meminsert->years = 0;
    //         $meminsert->months = $request->months;
    //         $meminsert->days = 0;
    //         // $meminsert->emiDate = "";
    //         $meminsert->accountNo = $request->accountNo;
    //         $meminsert->loanType = $request->loan;
    //         $meminsert->purpose = $request->purpose;
    //         $meminsert->loanby = $request->paymentby;
    //         $meminsert->loanAmount = $advancement_amount;
    //         $meminsert->chequeNo = $request->chequeNo;
    //         $meminsert->ledgerBankAccountId = $request->bankname;
    //         $meminsert->name = $request->name;
    //         $meminsert->guarantorname = $request->guarantorname;
    //         $meminsert->guarantorno = $request->guarantorno;
    //         $meminsert->guarantoraddress = $request->guarantoraddress;
    //         $meminsert->guarantornamee = $request->guarantornamee;
    //         $meminsert->guarantornoo = $request->guarantornoo;
    //         $meminsert->guarantoraddresss = $request->guarantoraddresss;
    //         $meminsert->agentId = $request->agentid;
    //         $meminsert->updatedBy = $request->agentid;
    //         $meminsert->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
    //         $meminsert->sessionId     = $request->sessionid;
    //         $meminsert->save();
    //         $loandetail = loan_masters::find($request->loan);
    //         // if ($request->insType == 'Monthly') {
    //         loan_installments::where('LoanId', '=', $id)->delete();
    //         $loanamount = $request->amount;
    //         $rateofinterest = $request->interest;
    //         $tanure = $request->months;
    //         $monthlyPrincipaltotal = 0;
    //         $monthlyInstallmenttottal = 0;
    //         $monthlyround = 0;
    //         // dd($monthlyInterest,$totalintrest,$principlleadd,$monthlyInstallment,$monthlyPrincipal);
    //         $monthly_roi = $rateofinterest * $tanure;
    //         $monthlyInterest = ((($loanamount * $monthly_roi) / 100) / $tanure);
    //         $totalintrest = $monthlyInterest * $tanure;
    //         $principlleadd = $loanamount + $totalintrest;
    //         $monthlyInstallment = $principlleadd / $tanure;
    //         $monthlyPrincipal = $monthlyInstallment - $monthlyInterest;
    //         $startDate = Carbon::parse($newdate);
    //         $startDate->addMonth(); // Move to the next month
    //         for ($i = 0; $i < $tanure; $i++) {
    //             $loanInstallment = new loan_installments();
    //             $loanInstallment->LoanId = $meminsert->id;
    //             $loanInstallment->installmentDate = $startDate->toDateString();
    //             if ($i == $tanure - 1) {
    //                 // Adjust the last installment to include the remaining principal
    //                 $monthlp = $loanamount - $monthlyPrincipaltotal;
    //                 $monthlnstal = $loanamount - $monthlyInstallmenttottal;
    //                 $monthlyInt = $loanamount - $monthlyround;
    //                 $loanInstallment->principal = $monthlp;
    //                 $loanInstallment->interest = round($monthlyInterest);
    //                 $loanInstallment->totalinstallmentamount = round($monthlyInterest) + $monthlp;
    //             } else {
    //                 $monthlyPrincipaltotal = $monthlyPrincipaltotal + round($monthlyPrincipal);
    //                 $monthlyInstallmenttottal = $monthlyInstallmenttottal + round($monthlyInstallment);
    //                 $monthlyround = $monthlyround + round($monthlyInterest);
    //                 $loanInstallment->principal = round($monthlyPrincipal);
    //                 $loanInstallment->interest = round($monthlyInterest);
    //                 $loanInstallment->totalinstallmentamount = round($monthlyPrincipal) + round($monthlyInterest);
    //             }
    //             $loanInstallment->paid_date = null;
    //             $loanInstallment->status = 'false';
    //             $loanInstallment->re_amount = 0;
    //             $loanInstallment->agentId = $request->agentid;
    //             $loanInstallment->updatedBy = $request->agentid;
    //             $loanInstallment->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
    //             $loanInstallment->sessionId     = $request->sessionid;
    //             $loanInstallment->save();
    //             $startDate->addMonth(); // Move to the next month
    //         }
    //         // } else {
    //         //     return back();
    //         // }
    //         general_ledgers::where('LoanId', '=', $id)->delete();
    //         $ledhai = ledger_masters::where('loan', '=', $loanmass->id)->first();
    //         $ledgerme = new general_ledgers();
    //         $ledgerme->LoanId = $meminsert->id;
    //         $ledgerme->accountNo = $request->accountNo;
    //         $ledgerme->groupCode = $ledhai->groupCode;
    //         $ledgerme->ledgerCode = $ledhai->ledgerCode;
    //         $ledgerme->formName = $request->name;
    //         $ledgerme->transactionDate = $newdate;
    //         $ledgerme->transactionType = 'Dr';
    //         $ledgerme->transactionAmount = $advancement_amount;
    //         $ledgerme->narration = $loandetail->loanname;
    //         $ledgerme->branchId = Null;
    //         $ledgerme->agentId = $request->agentid;
    //         $ledgerme->updatedBy = $request->agentid;
    //         $ledgerme->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
    //         $ledgerme->sessionId     = $request->sessionid;
    //         $ledgerme->save();
    //         $ledgerme = new general_ledgers();
    //         $ledgerme->LoanId = $meminsert->id;
    //         $ledgerme->accountNo = $request->accountNo;
    //         $ledgerme->groupCode = 'C002';
    //         $ledgerme->ledgerCode = 'CAS001';
    //         $ledgerme->formName = $request->name;
    //         $ledgerme->transactionDate = $newdate;
    //         $ledgerme->transactionType = 'Cr';
    //         $ledgerme->transactionAmount = $advancement_amount;
    //         $ledgerme->narration = $loandetail->loanname;
    //         $ledgerme->branchId = Null;
    //         $ledgerme->agentId = $request->agentid;
    //         $ledgerme->updatedBy = $request->agentid;
    //         $ledgerme->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
    //         $ledgerme->sessionId     = $request->sessionid;
    //         $ledgerme->save();
    //         // general_ledgers::where('LoanId', '=', $id)->delete();
    //         // $ledhai = ledger_masters::where('loan', '=', $loanmass->id)->first();
    //         //__________If Processing Fees
    //         if ($processing_fee_amount > 0) {
    //             $ledgerme = new general_ledgers();
    //             $ledgerme->LoanId = $meminsert->id;
    //             $ledgerme->accountNo = $request->accountNo;
    //             $ledgerme->groupCode = 'IINC01';
    //             $ledgerme->ledgerCode = 'PRC001';
    //             $ledgerme->formName = $request->name;
    //             $ledgerme->transactionDate = $newdate;
    //             $ledgerme->transactionType = 'Cr';
    //             $ledgerme->transactionAmount = $processing_fee_amount;
    //             $ledgerme->narration = $loandetail->loanname;
    //             $ledgerme->branchId = Null;
    //             $ledgerme->agentId = $request->agentid;
    //             $ledgerme->updatedBy = $request->agentid;
    //             $ledgerme->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
    //             $ledgerme->sessionId     = $request->sessionid;
    //             $ledgerme->save();
    //             $ledgerme = new general_ledgers();
    //             $ledgerme->LoanId = $meminsert->id;
    //             $ledgerme->accountNo = $request->accountNo;
    //             $ledgerme->groupCode = 'C002';
    //             $ledgerme->ledgerCode = 'CAS001';
    //             $ledgerme->formName = $request->name;
    //             $ledgerme->transactionDate = $newdate;
    //             $ledgerme->transactionType = 'Dr';
    //             $ledgerme->transactionAmount = $processing_fee_amount;
    //             $ledgerme->narration = $loandetail->loanname;
    //             $ledgerme->branchId = Null;
    //             $ledgerme->agentId = $request->agentid;
    //             $ledgerme->updatedBy = $request->agentid;
    //             $ledgerme->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
    //             $ledgerme->sessionId     = $request->sessionid;
    //             $ledgerme->save();
    //         }
    //         DB::commit();
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Transaction failed: ' . $e->getMessage()
    //         ]);
    //     }
    //     return response()->json([
    //         'status' => true,
    //         'loanid' => $meminsert->id,
    //         'message' => 'Loan Disbursed updated'
    //     ]);
    // }
    public function getrecovery(Request $request)
    {
        $loanid = $request->loanid;
        $datis = $request->date;
        if (!empty($datis)) {
            $cdatee = date("Y-m-d", strtotime($datis));
            $cdate = Carbon::createFromFormat('Y-m-d', $cdatee);
            // $cdate = $cdatePlusOneDay->format('Y-m-d');
        } else {
            $startDate = Carbon::today();
            $cdate = $startDate->toDateString();
        }
        $member_loans = member_loans::find($loanid);
        $penaltyInteresttype = $member_loans->penaltyInteresttype;
        $penaltyInterest = $member_loans->penaltyInterest;
        $paneltywithinstallment = 0;
        $totalinstallment = 0;
        $totalpanelty = 0;
        $totalpaneltywithinstallment = 0;
        $totalintrest  = 0;
        $totalprinciple = 0;
        // Get all installments for the loan up to the given date and not paid
        $allinst = loan_installments::where('LoanId', '=', $loanid)
            ->where('installmentDate', '<=', $cdate)
            ->where('status', '!=', 'paid')
            ->orderBy('installmentDate', 'asc')
            ->get();
        if (sizeof($allinst) > 0) {
            $lastInstallmentDate = $allinst->last()->installmentDate;
            foreach ($allinst as $allinstlist) {
                $panelty = 0;
                $installmentDate = $allinstlist->installmentDate;
                if ($penaltyInteresttype == 'percentage') {
                } else {
                    $installment = $allinstlist->totalinstallmentamount;
                    $panelty = $penaltyInterest;
                    $paneltywithinstallment = $installment + $panelty;
                    $totalintrest = $totalintrest + $allinstlist->interest;
                    $totalprinciple = $totalprinciple + $allinstlist->principal;
                }
                $totalinstallment = $totalinstallment + $installment;
                $totalpanelty = $totalpanelty + $panelty;
                $totalpaneltywithinstallment = $totalpaneltywithinstallment + $paneltywithinstallment;
            }
            $allinsSAast = loan_installments::where('LoanId', '=', $loanid)->orderBy('installmentDate', 'DESC')->first();
            if ($cdate > $allinsSAast->installmentDate) {
                $monthsDiff = Carbon::parse($lastInstallmentDate)->diffInMonths(Carbon::parse($cdate));
                for ($i = 1; $i < $monthsDiff; $i++) {
                    $totalpanelty =   $totalpanelty + $panelty;
                    $totalpaneltywithinstallment = $totalpaneltywithinstallment + $panelty;
                }
            }
        } else {
            $allinst = loan_installments::where('LoanId', '=', $loanid)
                ->where('status', '!=', 'paid')
                ->orderBy('installmentDate', 'asc')
                ->first();
            if ($allinst) {
                $installmentDate = date('Y-m-d', strtotime($allinst->installmentDate));
                $totalinstallment = $allinst->principal;
                $totalintrest = 0;
                $totalprinciple = $allinst->principal;
                $totalpanelty = 0;
                $totalpaneltywithinstallment = $allinst->principal;
            } else {
                $installmentDate = 0;
                $totalinstallment = 0;
                $totalintrest = 0;
                $totalprinciple = 0;
                $totalpanelty = 0;
                $totalpaneltywithinstallment = 0;
                $installmentDate = date('Y-m-d');
            }
        }
        $loaninst = loan_installments::where('LoanId', '=', $loanid)->sum('principal');
        $loanrecoveries = loan_recoveries::where('loanId', '=', $loanid)->sum('principal');
        $remaining = round($loaninst - $loanrecoveries);
        return response()->json([
            'status' => true,
            'installmentDate' => $installmentDate,
            'totalintrest' => round($totalintrest, 2),
            'totalprinciple' => round($totalprinciple, 2),
            'totalinstallment' => round($totalinstallment, 2),
            'totalpanelty' => round($totalpanelty, 2),
            'totalpaneltywithinstallment' => round($totalpaneltywithinstallment, 2),
            'loanid' => $loanid,
            'remaining' => $remaining,
        ]);
    }
    public function takerecovery(Request $request)
    {
        $currrdate = $request->currentdate;
        $totalprinciple = $request->totalprinciple;
        $id = $request->loanid;
        $totalintrest = $request->totalintrest;
        $totalpanelty = $request->totalpanelty;
        $totalpaneltywithinstallment = $request->totalpaneltywithinstallment;
        $cdatee = date("Y-m-d", strtotime($currrdate));
        $totalPayment = floatval($totalprinciple);
        $paidInstallmentIds = [];
        DB::beginTransaction();
        try {
            $checkloanid = DB::table('member_loans')->where('id', $id)->first();
            $checkdate = $checkloanid->loanDate;
            //__________Check if the loan date is greater than the account opening date
            if ($cdatee >= $checkdate) {
                $newdate = $cdatee;
            } else {
                return response()->json(['status' => false, 'message' => 'Loan date must be after the account opening date']);
            }
            // Proceed with saving loan recovery data
            $loanRecovery = new loan_recoveries();
            $loanRecovery->receiptDate = $newdate; // $newdate is guaranteed to be defined here
            $loanRecovery->loanId = $id;
            $loanRecovery->principal = $totalprinciple;
            $loanRecovery->principalround = round($totalprinciple);
            $loanRecovery->interest = $totalintrest;
            $loanRecovery->penalInterest = $totalpanelty;
            $loanRecovery->total = $request->totalinstallment;
            $loanRecovery->receivedAmount = $totalpaneltywithinstallment;
            $loanRecovery->status = 'True';
            $loanRecovery->agentId = $request->agentid;
            $loanRecovery->updatedBy = $request->agentid;
            $loanRecovery->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
            $loanRecovery->sessionId     = $request->sessionid;
            $loanRecovery->save();
            $loanRecoveryidis = $loanRecovery->id;
            // dd($request->thisid);
            DB::transaction(function () use ($id, $cdatee, $loanRecoveryidis, &$totalPayment, &$paidInstallmentIds) {
                $installments = loan_installments::where('LoanId', $id)
                    ->where('status', '!=', 'paid')
                    ->orderBy('installmentDate', 'asc')
                    ->get();
                foreach ($installments as $installment) {
                    $installmentAmount = floatval($installment->principal);
                    $paid_date = date("Y-m-d", strtotime($cdatee));
                    if ($totalPayment >= $installmentAmount) {
                        $installment->update([
                            'paid_date' => $paid_date,
                            'status' => 'paid',
                            're_amount' => $installmentAmount,
                            're_amountround' => round($installmentAmount),
                            'loanRecoveryidis' => $loanRecoveryidis,
                        ]);
                        $paidInstallmentIds[] = $installment->id;
                        $totalPayment -= $installmentAmount;
                    } else {
                    }
                }
                $loanRecoveryww = loan_recoveries::find($loanRecoveryidis);
                $loanRecoveryww->instaId = implode(",", $paidInstallmentIds);
                $loanRecoveryww->save();
            });
            $member_loans = member_loans::find($id);
            $ledhai = ledger_masters::where('loan', '=', $member_loans->loanType)->first();
            $formname = $member_loans->name;
            $accountNo = $member_loans->accountNo;
            $ledgerme = new general_ledgers();
            $ledgerme->LoanId = $id;
            $ledgerme->accountNo = $accountNo;
            $ledgerme->groupCode = 'C002';
            $ledgerme->ledgerCode = 'CAS001';
            $ledgerme->formName = $formname;
            $ledgerme->transactionDate = $newdate;
            $ledgerme->transactionType = 'Dr';
            $ledgerme->transactionAmount = $totalpaneltywithinstallment;
            $ledgerme->narration = $ledhai->name;
            $ledgerme->branchId = Null;
            $ledgerme->refid = 'recovery';
            $ledgerme->loanRecoveryidis = $loanRecoveryidis;
            $ledgerme->agentId = $request->agentid;
            $ledgerme->updatedBy = $request->agentid;
            $ledgerme->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
            $ledgerme->sessionId     = $request->sessionid;
            $ledgerme->save();
            $ledgerme = new general_ledgers();
            $ledgerme->LoanId = $id;
            $ledgerme->accountNo = $accountNo;
            $ledgerme->groupCode = $ledhai->groupCode;
            $ledgerme->ledgerCode = $ledhai->ledgerCode;
            $ledgerme->formName = $formname;
            $ledgerme->transactionDate = $newdate;
            $ledgerme->transactionType = 'Cr';
            $ledgerme->transactionAmount = $totalprinciple;
            $ledgerme->narration = 'Principle recieved';
            $ledgerme->branchId = Null;
            $ledgerme->refid = 'recovery';
            $ledgerme->loanRecoveryidis = $loanRecoveryidis;
            $ledgerme->agentId = $request->agentid;
            $ledgerme->updatedBy = $request->agentid;
            $ledgerme->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
            $ledgerme->sessionId     = $request->sessionid;
            $ledgerme->save();
            $loan_intt_ledger = ledger_masters::where('loan_intt_id', '=', $member_loans->loanType)->first();
            if ($totalintrest > 0) {
                $ledgerme = new general_ledgers();
                $ledgerme->LoanId = $id;
                $ledgerme->accountNo = $accountNo;
                $ledgerme->groupCode = $loan_intt_ledger->groupCode;
                $ledgerme->ledgerCode = $loan_intt_ledger->ledgerCode;
                $ledgerme->formName = $formname;
                $ledgerme->transactionDate = $newdate;
                $ledgerme->transactionType = 'Cr';
                $ledgerme->transactionAmount = $totalintrest;
                $ledgerme->narration = 'Intrest Recived On Loan';
                $ledgerme->branchId = Null;
                $ledgerme->refid = 'recovery';
                $ledgerme->loanRecoveryidis = $loanRecoveryidis;
                $ledgerme->agentId = $request->agentid;
                $ledgerme->updatedBy = $request->agentid;
                $ledgerme->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
                $ledgerme->sessionId     = $request->sessionid;
                $ledgerme->save();
            }
            if ($request->totalpanelty > 0) {
                $ledgerme = new general_ledgers();
                $ledgerme->LoanId = $id;
                $ledgerme->accountNo = $accountNo;
                $ledgerme->groupCode = 'IINC01';
                $ledgerme->ledgerCode = 'PAN001';
                $ledgerme->formName = $formname;
                $ledgerme->transactionDate = $newdate;
                $ledgerme->transactionType = 'Cr';
                $ledgerme->transactionAmount = $totalpanelty;
                $ledgerme->narration = 'Penalty Recived On Loan';
                $ledgerme->branchId = Null;
                $ledgerme->refid = 'recovery';
                $ledgerme->loanRecoveryidis = $loanRecoveryidis;
                $ledgerme->agentId = $request->agentid;
                $ledgerme->updatedBy = $request->agentid;
                $ledgerme->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
                $ledgerme->sessionId     = $request->sessionid;
                $ledgerme->save();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
        $loanamountofthisid = DB::table('member_loans')->where('id', '=', $id)->value('loanAmount');
        $recoveredfthisid = DB::table('loan_recoveries')->where('loanId', '=', $id)->sum('principal');
        if ($recoveredfthisid >= $loanamountofthisid) {
            $closeloan = member_loans::find($id);
            $closeloan->status = 'Closed';
            $closeloan->save();
        }
        return response()->json([
            'status' => true,
            'loanid' => $id,
            'message' => 'Recovery recorded successfully.'
        ]);
    }
    public function getpaidrecovery(Request $request)
    {
        $loanid = $request->loanid;
        $getallrecovery = DB::table('loan_recoveries')
            ->join('agent_masters', 'loan_recoveries.agentId', '=', 'agent_masters.id')
            ->select('loan_recoveries.id', 'loan_recoveries.loanId', 'loan_recoveries.receiptDate', 'loan_recoveries.principal', 'loan_recoveries.interest', 'loan_recoveries.penalInterest', 'loan_recoveries.total', 'agent_masters.name as agentname', 'agent_masters.id as agentid')
            ->where('loan_recoveries.loanId', $loanid)
            ->get();
        if (sizeof($getallrecovery) > 0) {
            return response()->json([
                'status' => true,
                'getallrecovery' => $getallrecovery,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No Recovery Found',
            ]);
        }
    }
    public function deleteloan(Request $request)
    {
        member_loans::where('id', '=', $request->loanid)->delete();
        general_ledgers::where('LoanId', '=', $request->loanid)->delete();
        loan_installments::where('LoanId', '=', $request->loanid)->delete();
        return response()->json([
            'status' => true,
            'message' => "Loan Deleted",
        ]);
    }
    public function deleterecovery(Request $request)
    {
        $reci = loan_recoveries::find($request->recoveryid);
        $closeloanopen = member_loans::find($reci->loanId);
        $closeloanopen->status = 'Disbursed';
        $closeloanopen->save();
        loan_recoveries::find($request->recoveryid)->delete();
        $krupdate = loan_installments::where('loanRecoveryidis', '=', $request->recoveryid)->get();
        if (sizeof($krupdate) > 0) {
            foreach ($krupdate as $krupdatelist) {
                $krupdateddd = loan_installments::find($krupdatelist->id);
                $krupdateddd->status = 'false';
                $krupdateddd->paid_date = Null;
                $krupdateddd->loanRecoveryidis = Null;
                $krupdateddd->save();
            }
        }
        general_ledgers::where('loanRecoveryidis', '=', $request->recoveryid)->delete();
        return response()->json([
            'status' => true,
            'message' => 'Recovery Deleted.'
        ]);
    }
    public function account_opening(Request $request)
    {
        Log::info($request->all());
        $maxCustomerId = DB::table('member_accounts')->max('customer_Id');
        $newCustomerId = $maxCustomerId + 1;
        if ($request->file('customerImage')) {
            $ncustomerImagecustomerImage = rand() . 'customerImageall.' . $request->file('customerImage')->getClientOriginalExtension();
            $rouncustomerImagecustomerImage = $request->file('customerImage')->storeAs('public/featcustomerImages', $ncustomerImagecustomerImage);
        } else {
            $rouncustomerImagecustomerImage = "";
        }
        //+++++++++++++ Customer Id Proof
        if ($request->file('idProofImage')) {
            $nidProofImageidProofImage = rand() . 'idProofImageall.' . $request->file('idProofImage')->getClientOriginalExtension();
            $rounidProofImageidProofImage = $request->file('idProofImage')->storeAs('public/featidProofImages', $nidProofImageidProofImage);
        } else {
            $rounidProofImageidProofImage = "";
        }
        //+++++++++++++ First Guarantor Image
        if ($request->file('firstguarantorImage')) {
            $nfirstguarantorImagefirstguarantorImage = rand() . 'firstguarantorImageall.' . $request->file('firstguarantorImage')->getClientOriginalExtension();
            $rounfirstguarantorImagefirstguarantorImage = $request->file('firstguarantorImage')->storeAs('public/featfirstguarantorImages', $nfirstguarantorImagefirstguarantorImage);
        } else {
            $rounfirstguarantorImagefirstguarantorImage = "";
        }
        //+++++++++++++ Second Guarantor Image
        if ($request->file('secondguarantorImage')) {
            $nsecondguarantorImagesecondguarantorImage = rand() . 'secondguarantorImageall.' . $request->file('secondguarantorImage')->getClientOriginalExtension();
            $rounsecondguarantorImagesecondguarantorImage = $request->file('secondguarantorImage')->storeAs('public/featsecondguarantorImages', $nsecondguarantorImagesecondguarantorImage);
        } else {
            $rounsecondguarantorImagesecondguarantorImage = "";
        }
        //++++++++++ Customer Insert
        $customer = new AccountOpening();
        $customer->openingDate = date('Y-m-d', strtotime($request->openingdate));
        $customer->customer_Id = $newCustomerId;
        $customer->name = $request->name;
        $customer->father_husband = $request->father_husband;
        $customer->gender = $request->gender;
        $customer->adhaar_no     = $request->adhaar_no;
        $customer->pan_number = $request->pan_number;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->stateId = $request->stateId;
        $customer->password = Hash::make('1234567890');
        $customer->districtId = $request->districtId;
        $customer->tehsilId = $request->tehsilId;
        $customer->postOfficeId = $request->postOfficeId;
        $customer->villageId = $request->villageId;
        $customer->houseType = $request->houseType;
        $customer->landmark = $request->landmark;
        $customer->mobile_first = $request->mobile_first;
        $customer->mobile_second = $request->mobile_second;
        $customer->work_place = $request->work_place;
        $customer->relationship = $request->relationship;
        $customer->relative_mobile_no = $request->relative_mobile_no;
        $customer->guarantor_first = $request->guarantor_first;
        $customer->first_guarantor_mobile = $request->first_guarantor_mobile;
        $customer->guarantor_second = $request->guarantor_second;
        $customer->second_guarantor_mobile = $request->second_guarantor_mobile;
        $customer->loan_limit = $request->loan_limit;
        $customer->openingbal = $request->openingbal;
        $customer->cibilscore = $request->cibilscore;
        $customer->customerInput = $rouncustomerImagecustomerImage;
        $customer->idProofImageInput = $rounidProofImageidProofImage;
        $customer->firstguarantorImageInput = $rounfirstguarantorImagefirstguarantorImage;
        $customer->secondguarantorImageInput = $rounsecondguarantorImagesecondguarantorImage;
        $customer->agentId = $request->agentid;
        $customer->updatedBy = $request->agentid;
        $customer->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
        $customer->sessionId     = $request->session_id;
        $customer->is_delete = 'No';
        // dd($request->all());
        $customer->save();
        return response()->json([
            'status' => true,
            'customer' => $customer->id,
            'message' => 'Customer Added',
        ]);
    }
    public function state(Request $request)
    {
        $state = DB::table('state_masters')->select('id', 'name')->get();
        return response()->json(['status' => true, 'state' => $state]);
    }
    public function district(Request $request)
    {
        $district = DB::table('district_masters')->where('stateId', '=', $request->stateId)->select('id', 'stateId', 'name')->get();
        return response()->json(['status' => true, 'district' => $district]);
    }
    public function tehsil(Request $request)
    {
        $tehsil = DB::table('tehsil_masters')->where('stateId', '=', $request->stateId)->where('districtId', '=', $request->districtId)->select('id', 'stateId', 'districtId', 'name')->get();
        return response()->json(['status' => true, 'tehsil' => $tehsil]);
    }
    public function postoffice(Request $request)
    {
        $postoffice = DB::table('post_office_masters')->where('stateId', '=', $request->stateId)->where('districtId', '=', $request->districtId)->where('tehsilId', '=', $request->tehsilId)->select('id', 'stateId', 'districtId', 'tehsilId', 'name')->get();
        return response()->json(['status' => true, 'postoffice' => $postoffice]);
    }
    public function village(Request $request)
    {
        $village = DB::table('village_masters')->where('stateId', '=', $request->stateId)->where('districtId', '=', $request->districtId)->where('tehsilId', '=', $request->tehsilId)->where('postOfficeId', '=', $request->postOfficeId)->select('id', 'stateId', 'districtId', 'tehsilId', 'postOfficeId', 'name')->get();
        return response()->json(['status' => true, 'village' => $village]);
    }
    public function getcustomer(Request $request)
    {
        $getcustomerr = DB::table('member_accounts')->where('customer_Id', $request->customer_id)->first();
        if (!$getcustomerr) {
            return response()->json(['status' => false, 'message' => 'Customer not found'], 404);
        }
        $parameters = [
            'customerInput',
            'idProofImageInput',
            'firstguarantorImageInput',
            'secondguarantorImageInput',
        ];
        foreach ($parameters as $param) {
            if (!empty($getcustomerr->$param)) {
                $getcustomerr->$param = url('storage/app/' . $getcustomerr->$param);
            }
        }
        return response()->json(['status' => true, 'getcustomerr' => $getcustomerr]);
    }
    public function searchgetcustomer(Request $request)
    {
        $search = $request->search;
        $getcustomerr = DB::table('member_accounts')
            ->where('customer_Id', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->select('customer_Id', 'name')
            ->get();
        if (!$getcustomerr) {
            return response()->json(['status' => false, 'message' => 'Customer not found'], 404);
        }
        $parameters = [
            'customerInput',
            'idProofImageInput',
            'firstguarantorImageInput',
            'secondguarantorImageInput',
        ];
        foreach ($parameters as $param) {
            if (!empty($getcustomerr->$param)) {
                $getcustomerr->$param = url('storage/app/' . $getcustomerr->$param);
            }
        }
        return response()->json(['status' => true, 'getcustomerr' => $getcustomerr]);
    }



    public function payrecovery(Request $request)
    {
        $paneltyis = $request->panelty ?? 0;
        $cdatee = date("Y-m-d", strtotime($request->currentdate));
        $totalPayment = floatval($request->totalpayment);
        $customer_Id = $request->customer_Id;
        $thisid = DB::table('member_loans')
            ->where('accountNo', '=', $customer_Id)
            ->where('status', '=', 'Disbursed')
            ->first();
        if (!$thisid) {
            return response()->json(['status' => false, 'message' => 'NO Loan Found'], 400);
        }
        $id = $thisid->id;
        $checkdate = $thisid->loanDate;
        if ($cdatee < $checkdate) {
            return response()->json(['status' => false, 'message' => 'Loan date must be after the account opening date'], 400);
        }
        $firstis = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->first();
        $sumprincipal = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->sum('principal');
        $suminterest = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->sum('interest');
        $naihonachiye = $sumprincipal + $suminterest;
        if ($naihonachiye < $totalPayment) {
            return response()->json(['status' => false, 'message' => 'Amount is greater than Pending Amount'], 400);
        }
        $getinstallmentsbydate = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->orderby('id', 'ASC')->get();
        if ($getinstallmentsbydate->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'No installments found'], 400);
        }
        $pendingpayment = $totalPayment;
        $totalintest = 0;
        $totalprinciple = 0;
        foreach ($getinstallmentsbydate as $list) {
            if ($pendingpayment >= $list->principal + $list->interest) {
                $totalintest += $list->interest;
                $totalprinciple += $list->principal;
                $pendingpayment -= ($list->principal + $list->interest);
            } else {
                if ($pendingpayment > 0) {
                    if ($pendingpayment > $list->interest) {
                        $totalintest += $list->interest;
                        $bchahuaprinciple = $pendingpayment - $list->interest;
                        $pendingpayment -= $list->interest;
                        $totalprinciple += $bchahuaprinciple;
                        $pendingpayment -= ($bchahuaprinciple + $list->interest);
                    }
                }
            }
        }
        $principleis = $totalPayment - $totalintest;
        $totalpaneltywithinstallmentis = $principleis + $totalintest + $paneltyis;
        $loanRecovery = new loan_recoveries();
        $loanRecovery->receiptDate = $cdatee;
        $loanRecovery->loanId = $id;
        $loanRecovery->principal = $principleis;
        $loanRecovery->interest = $totalintest;
        $loanRecovery->penalInterest = $paneltyis;
        $loanRecovery->total = $totalpaneltywithinstallmentis;
        $loanRecovery->receivedAmount = $totalpaneltywithinstallmentis;
        $loanRecovery->status = 'True';
        $loanRecovery->agentId = $request->agentid;
        $loanRecovery->updatedBy = $request->agentid;
        $loanRecovery->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
        $loanRecovery->sessionId     = $request->sessionid;
        $loanRecovery->save();
        $loanRecoveryidis = $loanRecovery->id;
        $member_loans = member_loans::find($id);
        $ledhai = ledger_masters::where('loan', '=', $member_loans->loanType)->first();
        $formname = $member_loans->name;
        $accountNo = $member_loans->accountNo;
        // Create ledger entries
        $this->createLedgerEntry($id, $accountNo, 'C002', 'CAS001', $formname, $cdatee, 'Dr', $totalpaneltywithinstallmentis, $ledhai->name, $loanRecoveryidis, $request);
        $this->createLedgerEntry($id, $accountNo, $ledhai->groupCode, $ledhai->ledgerCode, $formname, $cdatee, 'Cr', $principleis, 'Principle received', $loanRecoveryidis, $request);
        if ($totalintest > 0) {
            $loan_intt_ledger = ledger_masters::where('loan_intt_id', '=', $member_loans->loanType)->first();
            $this->createLedgerEntry($id, $accountNo, $loan_intt_ledger->groupCode, $loan_intt_ledger->ledgerCode, $formname, $cdatee, 'Cr', $totalintest, 'Interest Received On Loan', $loanRecoveryidis, $request);
        }
        if ($paneltyis > 0) {
            $this->createLedgerEntry($id, $accountNo, 'IINC01', 'PAN001', $formname, $cdatee, 'Cr', $paneltyis, 'Penalty Received On Loan', $loanRecoveryidis, $request);
        }
        // Update installments
        $totalPayment = DB::table('loan_recoveries')->where('loanId', $id)->sum('principal');
        DB::table('loan_installments')->where('LoanId', $id)->update(['status' => 'false']);
        $getinstallmentsbydatse = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->orderby('id', 'ASC')->get();
        foreach ($getinstallmentsbydatse as $inst) {
            $installmentAmount = $inst->principal;
            if ($totalPayment >= $installmentAmount) {
                DB::table('loan_installments')->where('id', $inst->id)->update([
                    'paid_date' => $cdatee,
                    'status' => 'paid',
                    're_amount' => $request->totalpaneltywithinstallment,
                    'loanRecoveryidis' => $loanRecoveryidis,
                ]);
                $totalPayment -= $installmentAmount;
            }
        }
        return response()->json(['status' => true, 'message' => 'Success'], 200);
    }




    // Helper function to create ledger entries
    public function createLedgerEntry($loanId, $accountNo, $groupCode, $ledgerCode, $formName, $transactionDate, $transactionType, $transactionAmount, $narration, $loanRecoveryidis, $request)
    {
        $ledger = new general_ledgers();
        $ledger->LoanId = $loanId;
        $ledger->accountNo = $accountNo;
        $ledger->groupCode = $groupCode;
        $ledger->ledgerCode = $ledgerCode;
        $ledger->formName = $formName;
        $ledger->transactionDate = $transactionDate;
        $ledger->transactionType = $transactionType;
        $ledger->transactionAmount = $transactionAmount;
        $ledger->narration = $narration;
        $ledger->branchId = null;
        $ledger->refid = 'recovery';
        $ledger->loanRecoveryidis = $loanRecoveryidis;
        $ledger->agentId = $request->agentid;
        $ledger->updatedBy = $request->agentid;
        $ledger->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
        $ledger->sessionId     = $request->sessionid;
        $ledger->save();
    }
    public function getquickrecovery(Request $request)
    {
        // Check if the date is provided, otherwise use the current date
        $receiptDate = empty($request->date) ? date("Y-m-d") : date("Y-m-d", strtotime($request->date));
        $data = DB::table('loan_recoveries')
            ->join('agent_masters', 'loan_recoveries.agentId', '=', 'agent_masters.id')
            ->join('member_loans', 'loan_recoveries.loanId', '=', 'member_loans.id')
            ->join('loan_masters', 'member_loans.loanType', '=', 'loan_masters.id')
            ->join('member_accounts', 'member_loans.accountNo', '=', 'member_accounts.customer_Id')
            ->select('loan_recoveries.*', 'agent_masters.name as agent_name', 'loan_masters.loanname as loanName', 'member_accounts.customer_Id', 'member_accounts.name as accountName')
            ->where('loan_recoveries.receiptDate', '=', $receiptDate)
            ->orderby('loan_recoveries.updated_at', 'DESC')
            ->get();
        return response()->json(['status' => true, 'getquickrecovery' => $data]);
    }
    public function getdatarecovery(Request $request)
    {
        // Find the loan recovery record by ID
        $loan_recovery = loan_recoveries::find($request->id);
        if (!$loan_recovery) {
            return response()->json(['status' => false, 'message' => 'Loan recovery not found.'], 404);
        }
        // Get the associated member loan
        $member_loan = DB::table('member_loans')->where('id', $loan_recovery->loanId)->first();
        if (!$member_loan) {
            return response()->json(['status' => false, 'message' => 'Member loan not found.'], 404);
        }
        // Get the customer account details
        $customer_account = DB::table('member_accounts')->where('customer_Id', $member_loan->accountNo)->first();
        // Prepare the response data with only the needed values
        $data = [
            'status' => true,
            'loan_recovery' => [
                'id' => $loan_recovery->id,
                'receiptDate' => $loan_recovery->receiptDate,
                'receivedAmount' => $loan_recovery->receivedAmount,
                'penalInterest' => $loan_recovery->penalInterest,
                'remarks' => $loan_recovery->remarks,
                'customer_Id' => $customer_account->customer_Id,
            ],
        ];
        return response()->json($data, 200);
    }
    public function recconfirmDeletere(Request $request)
    {
        $reci = loan_recoveries::find($request->id);
        $closeloanopen = member_loans::find($reci->loanId);
        $closeloanopen->status = 'Disbursed';
        $closeloanopen->save();
        loan_recoveries::find($request->id)->delete();
        general_ledgers::where('loanRecoveryidis', '=', $request->id)->delete();
        DB::table('loan_installments')->where('LoanId', $reci->loanId)->update(['status' => 'false']);
        $totalPayment = DB::table('loan_recoveries')->where('loanId', $reci->loanId)->sum('principal');
        $getinstallmentsbydatse = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $reci->loanId)->orderby('id', 'ASC')->get();
        foreach ($getinstallmentsbydatse as $inst) {
            $installmentAmount = $inst->principal;
            if ($totalPayment >= $installmentAmount) {
                DB::table('loan_installments')->where('id', $inst->id)->update([
                    'status' => 'paid',
                ]);
                $totalPayment -= $installmentAmount;
            }
        }
        $member_loanscheck = DB::table('member_loans')
            ->join('loan_masters', 'loan_masters.id', '=', 'member_loans.loanType')
            ->join('agent_masters', 'agent_masters.id', '=', 'member_loans.agentId')
            ->where('member_loans.id', '=', $reci->loanId)
            ->select(
                'member_loans.*',
                'loan_masters.loanname',
                'agent_masters.name as agent_name',
                DB::raw('COALESCE((SELECT SUM(principal) FROM loan_recoveries WHERE loan_recoveries.loanId = member_loans.id), 0) as total_recovered')
            )
            ->orderBy('member_loans.loanDate', 'DESC') // Order by loanDate in ascending order
            ->get();
        foreach ($member_loanscheck as $member_loanscheckedit) {
            if ($member_loanscheckedit->loanAmount <= $member_loanscheckedit->total_recovered) {
                $mamam = member_loans::find($member_loanscheckedit->id);
                $mamam->status = 'Closed';
                $mamam->save();
            } else {
                $mamam = member_loans::find($member_loanscheckedit->id);
                $mamam->status = 'Disbursed';
                $mamam->save();
            }
        }
        return response()->json(['status' => true, 'message' => 'Success']);
    }
    public function editaddrecovery(Request $request)
    {
        DB::beginTransaction();
        try {
            $reci = loan_recoveries::find($request->id);
            $closeloanopen = member_loans::find($reci->loanId);
            $closeloanopen->status = 'Disbursed';
            $closeloanopen->save();
            loan_recoveries::find($request->id)->delete();
            general_ledgers::where('loanRecoveryidis', '=', $request->id)->delete();
            DB::table('loan_installments')->where('LoanId', $reci->loanId)->update(['status' => 'false']);
            $totalPayment = DB::table('loan_recoveries')->where('loanId', $reci->loanId)->sum('principal');
            $getinstallmentsbydatse = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $reci->loanId)->orderby('id', 'ASC')->get();
            foreach ($getinstallmentsbydatse as $inst) {
                $installmentAmount = $inst->principal;
                if ($totalPayment >= $installmentAmount) {
                    DB::table('loan_installments')->where('id', $inst->id)->update([
                        'status' => 'paid',
                    ]);
                    $totalPayment -= $installmentAmount;
                }
            }
            $paneltyis = $request->panelty ?? 0;
            $cdatee = date("Y-m-d", strtotime($request->currentdate));
            $totalPayment = floatval($request->totalpayment);
            $customer_Id = $request->customer_Id;
            $thisid = DB::table('member_loans')
                ->where('accountNo', '=', $customer_Id)
                ->where('status', '=', 'Disbursed')
                ->first();
            if (!$thisid) {
                return response()->json(['status' => false, 'message' => 'NO Loan Found'], 400);
            }
            $id = $thisid->id;
            $checkdate = $thisid->loanDate;
            if ($cdatee < $checkdate) {
                return response()->json(['status' => false, 'message' => 'Loan date must be after the account opening date'], 400);
            }
            $firstis = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->first();
            $sumprincipal = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->sum('principal');
            $suminterest = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->sum('interest');
            $naihonachiye = $sumprincipal + $suminterest;
            if ($naihonachiye < $totalPayment) {
                return response()->json(['status' => false, 'message' => 'Amount is greater than Pending Amount'], 400);
            }
            $getinstallmentsbydate = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->orderby('id', 'ASC')->get();
            if ($getinstallmentsbydate->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'No installments found'], 400);
            }
            $pendingpayment = $totalPayment;
            $totalintest = 0;
            $totalprinciple = 0;
            foreach ($getinstallmentsbydate as $list) {
                if ($pendingpayment >= $list->principal + $list->interest) {
                    $totalintest += $list->interest;
                    $totalprinciple += $list->principal;
                    $pendingpayment -= ($list->principal + $list->interest);
                } else {
                    if ($pendingpayment > 0) {
                        if ($pendingpayment > $list->interest) {
                            $totalintest += $list->interest;
                            $bchahuaprinciple = $pendingpayment - $list->interest;
                            $pendingpayment -= $list->interest;
                            $totalprinciple += $bchahuaprinciple;
                            $pendingpayment -= ($bchahuaprinciple + $list->interest);
                        }
                    }
                }
            }
            $principleis = $totalPayment - $totalintest;
            $totalpaneltywithinstallmentis = $principleis + $totalintest + $paneltyis;
            $loanRecovery = new loan_recoveries();
            $loanRecovery->receiptDate = $cdatee;
            $loanRecovery->loanId = $id;
            $loanRecovery->principal = $principleis;
            $loanRecovery->interest = $totalintest;
            $loanRecovery->penalInterest = $paneltyis;
            $loanRecovery->total = $totalpaneltywithinstallmentis;
            $loanRecovery->receivedAmount = $totalpaneltywithinstallmentis;
            $loanRecovery->status = 'True';
            $loanRecovery->remarks = $request->remarks;
            $loanRecovery->agentId = $request->agentid;
            $loanRecovery->updatedBy = $request->agentid;
            $loanRecovery->updatedbytype = DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type');
            $loanRecovery->sessionId     = $request->sessionid;
            $loanRecovery->save();
            $loanRecoveryidis = $loanRecovery->id;
            $member_loans = member_loans::find($id);
            $ledhai = ledger_masters::where('loan', '=', $member_loans->loanType)->first();
            $formname = $member_loans->name;
            $accountNo = $member_loans->accountNo;
            // Create ledger entries
            $this->createLedgerEntry($id, $accountNo, 'C002', 'CAS001', $formname, $cdatee, 'Dr', $totalpaneltywithinstallmentis, $ledhai->name, $loanRecoveryidis, $request);
            $this->createLedgerEntry($id, $accountNo, $ledhai->groupCode, $ledhai->ledgerCode, $formname, $cdatee, 'Cr', $principleis, 'Principle received', $loanRecoveryidis, $request);
            if ($totalintest > 0) {
                $loan_intt_ledger = ledger_masters::where('loan_intt_id', '=', $member_loans->loanType)->first();
                $this->createLedgerEntry($id, $accountNo, $loan_intt_ledger->groupCode, $loan_intt_ledger->ledgerCode, $formname, $cdatee, 'Cr', $totalintest, 'Interest Received On Loan', $loanRecoveryidis, $request);
            }
            if ($paneltyis > 0) {
                $this->createLedgerEntry($id, $accountNo, 'IINC01', 'PAN001', $formname, $cdatee, 'Cr', $paneltyis, 'Penalty Received On Loan', $loanRecoveryidis, $request);
            }
            // Update installments
            $totalPayment = DB::table('loan_recoveries')->where('loanId', $id)->sum('principal');
            DB::table('loan_installments')->where('LoanId', $id)->update(['status' => 'false']);
            $getinstallmentsbydatse = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->orderby('id', 'ASC')->get();
            foreach ($getinstallmentsbydatse as $inst) {
                $installmentAmount = $inst->principal;
                if ($totalPayment >= $installmentAmount) {
                    DB::table('loan_installments')->where('id', $inst->id)->update([
                        'paid_date' => $cdatee,
                        'status' => 'paid',
                        'loanRecoveryidis' => $loanRecoveryidis,
                    ]);
                    $totalPayment -= $installmentAmount;
                }
            }
            $member_loanscheck = DB::table('member_loans')
                ->join('loan_masters', 'loan_masters.id', '=', 'member_loans.loanType')
                ->join('agent_masters', 'agent_masters.id', '=', 'member_loans.agentId')
                ->where('member_loans.accountNo', '=', $accountNo)
                ->select(
                    'member_loans.*',
                    'loan_masters.loanname',
                    'agent_masters.name as agent_name',
                    DB::raw('COALESCE((SELECT SUM(principal) FROM loan_recoveries WHERE loan_recoveries.loanId = member_loans.id), 0) as total_recovered')
                )
                ->orderBy('member_loans.loanDate', 'DESC') // Order by loanDate in ascending order
                ->get();
            foreach ($member_loanscheck as $member_loanscheckedit) {
                if ($member_loanscheckedit->loanAmount <= $member_loanscheckedit->total_recovered) {
                    $mamam = member_loans::find($member_loanscheckedit->id);
                    $mamam->status = 'Closed';
                    $mamam->save();
                } else {
                    $mamam = member_loans::find($member_loanscheckedit->id);
                    $mamam->status = 'Disbursed';
                    $mamam->save();
                }
            }
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Success'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Transaction Failed'
                // ,  'errors' => $e->getMessage()
            ]);
        }
    }


    public function todaycollection(Request $request)
    {
        // Format the date from the request
        $dateTo = date('Y-m-d', strtotime($request->date));

        // Query to get the collection data
        $results = DB::table('loan_recoveries')
            ->join('agent_masters as agents', 'loan_recoveries.agentId', '=', 'agents.id')
            ->join('member_loans as memberloans', 'loan_recoveries.loanId', '=', 'memberloans.id')
            ->join('loan_masters as loanmasters', 'memberloans.loanType', '=', 'loanmasters.id')
            ->join('member_accounts as memberaccounts', 'memberloans.accountNo', '=', 'memberaccounts.customer_Id')
            ->select(
                'memberaccounts.customer_Id',
                'memberaccounts.name as cutomername',
                'loan_recoveries.receiptDate',
                'loan_recoveries.agentId',
                'loan_recoveries.principal',
                'agents.name as agentName',
                'loanmasters.loanname'
            )
            ->where('loan_recoveries.receiptDate', '=', $dateTo)
            ->get();

        // Calculate the total principal
        $totalPrincipal = $results->sum('principal');

        // Check if there are results
        if (sizeof($results) > 0) {
            return response()->json([
                'status' => true,
                'todaycollection' => $results,
                'total' => $totalPrincipal, // Returning the total principal
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No data',
            ]);
        }
    }





    public function verifyPayment(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        if (!$request->has('razorpay_payment_id') || empty($request->razorpay_payment_id)) {
            return response()->json(['status' => false, 'message' => 'Invalid or missing Razorpay payment ID'], 400);
        }


        try {
            $payment = $api->payment->fetch($request->razorpay_payment_id);
            $response = $payment->capture(['amount' => $payment['amount']]);

            if ($response->status !== 'captured') {
                throw new \Exception('Payment capture failed.');
            }
            DB::beginTransaction();

            $paneltyis = 0;
            $cdatee = date("Y-m-d", strtotime($request->installmentdate));
            $totalPayment = floatval($request->totalamount);
            $customer_Id = $request->customer_Id;
            $thisid = DB::table('member_loans')
                ->where('accountNo', '=', $customer_Id)
                ->where('status', '=', 'Disbursed')
                ->first();
            if (!$thisid) {
                return response()->json(['status' => false, 'message' => 'NO Loan Found'], 400);
            }
            $id = $thisid->id;
            $checkdate = $thisid->loanDate;
            if ($cdatee < $checkdate) {
                return response()->json(['status' => false, 'message' => 'Loan date must be after the account opening date'], 400);
            }
            $firstis = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->first();
            $sumprincipal = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->sum('principal');
            $suminterest = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->sum('interest');
            $naihonachiye = $sumprincipal + $suminterest;
            if ($naihonachiye < $totalPayment) {
                return response()->json(['status' => false, 'message' => 'Amount is greater than Pending Amount'], 400);
            }
            $getinstallmentsbydate = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->orderby('id', 'ASC')->get();
            if ($getinstallmentsbydate->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'No installments found'], 400);
            }
            $pendingpayment = $totalPayment;
            $totalintest = 0;
            $totalprinciple = 0;
            foreach ($getinstallmentsbydate as $list) {
                if ($pendingpayment >= $list->principal + $list->interest) {
                    $totalintest += $list->interest;
                    $totalprinciple += $list->principal;
                    $pendingpayment -= ($list->principal + $list->interest);
                } else {
                    if ($pendingpayment > 0) {
                        if ($pendingpayment > $list->interest) {
                            $totalintest += $list->interest;
                            $bchahuaprinciple = $pendingpayment - $list->interest;
                            $pendingpayment -= $list->interest;
                            $totalprinciple += $bchahuaprinciple;
                            $pendingpayment -= ($bchahuaprinciple + $list->interest);
                        }
                    }
                }
            }
            $principleis = $totalPayment - $totalintest;
            $totalpaneltywithinstallmentis = $principleis + $totalintest + $paneltyis;
            $loanRecovery = new loan_recoveries();
            $loanRecovery->receiptDate = $cdatee;
            $loanRecovery->loanId = $id;
            $loanRecovery->principal = $principleis;
            $loanRecovery->interest = $totalintest;
            $loanRecovery->penalInterest = $paneltyis;
            $loanRecovery->total = $totalpaneltywithinstallmentis;
            $loanRecovery->receivedAmount = $totalpaneltywithinstallmentis;
            $loanRecovery->status = 'True';
            $loanRecovery->receivedBy = 'Online';
            $loanRecovery->agentId = 0;
            $loanRecovery->updatedBy = 0;
            $loanRecovery->updatedbytype = 0;
            $loanRecovery->sessionId     = 1;
            $loanRecovery->save();
            $loanRecoveryidis = $loanRecovery->id;
            $member_loans = member_loans::find($id);
            $ledhai = ledger_masters::where('loan', '=', $member_loans->loanType)->first();
            $formname = $member_loans->name;
            $accountNo = $member_loans->accountNo;

            $this->createLedgerEntry($id, $accountNo, 'C002', 'CAS001', $formname, $cdatee, 'Dr', $totalpaneltywithinstallmentis, $ledhai->name, $loanRecoveryidis, $request);
            $this->createLedgerEntry($id, $accountNo, $ledhai->groupCode, $ledhai->ledgerCode, $formname, $cdatee, 'Cr', $principleis, 'Principle received', $loanRecoveryidis, $request);
            if ($totalintest > 0) {
                $loan_intt_ledger = ledger_masters::where('loan_intt_id', '=', $member_loans->loanType)->first();
                $this->createLedgerEntry($id, $accountNo, $loan_intt_ledger->groupCode, $loan_intt_ledger->ledgerCode, $formname, $cdatee, 'Cr', $totalintest, 'Interest Received On Loan', $loanRecoveryidis, $request);
            }
            if ($paneltyis > 0) {
                $this->createLedgerEntry($id, $accountNo, 'IINC01', 'PAN001', $formname, $cdatee, 'Cr', $paneltyis, 'Penalty Received On Loan', $loanRecoveryidis, $request);
            }

            $totalPayment = DB::table('loan_recoveries')->where('loanId', $id)->sum('principal');
            DB::table('loan_installments')->where('LoanId', $id)->update(['status' => 'false']);
            $getinstallmentsbydatse = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->orderby('id', 'ASC')->get();
            foreach ($getinstallmentsbydatse as $inst) {
                $installmentAmount = $inst->principal;
                if ($totalPayment >= $installmentAmount) {
                    DB::table('loan_installments')->where('id', $inst->id)->update([
                        'paid_date' => $cdatee,
                        'status' => 'paid',
                        're_amount' => $request->totalpaneltywithinstallment,
                        'loanRecoveryidis' => $loanRecoveryidis,
                    ]);
                    $totalPayment -= $installmentAmount;
                }
            }


            DB::commit();
            return response()->json(['status' => true, 'message' => 'Payment verification successful'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment verification failed: ', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Payment verification failed: ' . $e->getMessage()], 500);
        }
    }





    // public function verifyPayment(Request $request)
    // {
    //     $paneltyis = $request->panelty ?? 0;
    //     $cdatee = date("Y-m-d", strtotime($request->currentdate));
    //     $totalPayment = floatval($request->totalpayment);
    //     $customer_Id = $request->customer_Id;
    //     $thisid = DB::table('member_loans')
    //         ->where('accountNo', '=', $customer_Id)
    //         ->where('status', '=', 'Disbursed')
    //         ->first();
    //     if (!$thisid) {
    //         return response()->json(['status' => false, 'message' => 'NO Loan Found'], 400);
    //     }
    //     $id = $thisid->id;
    //     $checkdate = $thisid->loanDate;
    //     if ($cdatee < $checkdate) {
    //         return response()->json(['status' => false, 'message' => 'Loan date must be after the account opening date'], 400);
    //     }
    //     $firstis = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->first();
    //     $sumprincipal = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->sum('principal');
    //     $suminterest = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->sum('interest');
    //     $naihonachiye = $sumprincipal + $suminterest;
    //     if ($naihonachiye < $totalPayment) {
    //         return response()->json(['status' => false, 'message' => 'Amount is greater than Pending Amount'], 400);
    //     }
    //     $getinstallmentsbydate = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->orderby('id', 'ASC')->get();
    //     if ($getinstallmentsbydate->isEmpty()) {
    //         return response()->json(['status' => false, 'message' => 'No installments found'], 400);
    //     }
    //     $pendingpayment = $totalPayment;
    //     $totalintest = 0;
    //     $totalprinciple = 0;
    //     foreach ($getinstallmentsbydate as $list) {
    //         if ($pendingpayment >= $list->principal + $list->interest) {
    //             $totalintest += $list->interest;
    //             $totalprinciple += $list->principal;
    //             $pendingpayment -= ($list->principal + $list->interest);
    //         } else {
    //             if ($pendingpayment >0) {
    //             if ($pendingpayment > $list->interest) {
    //                 $totalintest += $list->interest;
    //                 $bchahuaprinciple=$pendingpayment-$list->interest;
    //                 $pendingpayment -= $list->interest;
    //                 $totalprinciple += $bchahuaprinciple;
    //                 $pendingpayment -= ($bchahuaprinciple + $list->interest);
    //             }
    //         }
    //         }
    //     }
    //     $principleis = $totalPayment - $totalintest;
    //     $totalpaneltywithinstallmentis = $principleis + $totalintest + $paneltyis;
    //     $loanRecovery = new loan_recoveries();
    //     $loanRecovery->receiptDate = $cdatee;
    //     $loanRecovery->loanId = $id;
    //     $loanRecovery->principal = $principleis;
    //     $loanRecovery->interest = $totalintest;
    //     $loanRecovery->penalInterest = $paneltyis;
    //     $loanRecovery->total = $totalpaneltywithinstallmentis;
    //     $loanRecovery->receivedAmount = $totalpaneltywithinstallmentis;
    //     $loanRecovery->status = 'True';
    //     $loanRecovery->agentId = $request->agentid;
    //     $loanRecovery->updatedBy = $request->agentid;
    //     $loanRecovery->updatedbytype = DB::table('agent_masters')->where('id','=',$request->agentid)->value('user_type');
    //     $loanRecovery->sessionId     = $request->sessionid;
    //     $loanRecovery->save();
    //     $loanRecoveryidis = $loanRecovery->id;
    //     $member_loans = member_loans::find($id);
    //     $ledhai = ledger_masters::where('loan', '=', $member_loans->loanType)->first();
    //     $formname = $member_loans->name;
    //     $accountNo = $member_loans->accountNo;
    //     // Create ledger entries
    //     $this->createLedgerEntry($id, $accountNo, 'C002', 'CAS001', $formname, $cdatee, 'Dr', $totalpaneltywithinstallmentis, $ledhai->name, $loanRecoveryidis,$request);
    //     $this->createLedgerEntry($id, $accountNo, $ledhai->groupCode, $ledhai->ledgerCode, $formname, $cdatee, 'Cr', $principleis, 'Principle received', $loanRecoveryidis,$request);
    //     if ($totalintest > 0) {
    //         $loan_intt_ledger = ledger_masters::where('loan_intt_id', '=', $member_loans->loanType)->first();
    //         $this->createLedgerEntry($id, $accountNo, $loan_intt_ledger->groupCode, $loan_intt_ledger->ledgerCode, $formname, $cdatee, 'Cr', $totalintest, 'Interest Received On Loan', $loanRecoveryidis,$request);
    //     }
    //     if ($paneltyis > 0) {
    //         $this->createLedgerEntry($id, $accountNo, 'IINC01', 'PAN001', $formname, $cdatee, 'Cr', $paneltyis, 'Penalty Received On Loan', $loanRecoveryidis,$request);
    //     }
    //     // Update installments
    //     $totalPayment = DB::table('loan_recoveries')->where('loanId', $id)->sum('principal');
    //     DB::table('loan_installments')->where('LoanId', $id)->update(['status' => 'false']);
    //     $getinstallmentsbydatse = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->orderby('id', 'ASC')->get();
    //     foreach ($getinstallmentsbydatse as $inst) {
    //         $installmentAmount = $inst->principal;
    //         if ($totalPayment >= $installmentAmount) {
    //             DB::table('loan_installments')->where('id', $inst->id)->update([
    //                 'paid_date' => $cdatee,
    //                 'status' => 'paid',
    //                 're_amount' => $request->totalpaneltywithinstallment,
    //                 'loanRecoveryidis' => $loanRecoveryidis,
    //             ]);
    //             $totalPayment -= $installmentAmount;
    //         }
    //     }
    //     return response()->json(['status' => true, 'message' => 'Success'], 200);
    // }


    public function editcustomer(Request $post)
    {
        $id = $post->customer_Id;

        $existsId = FacadesDB::table('member_accounts')->where('customer_Id', $id)->first();
        if ($existsId) {
            $existsId->customerInput = url('storage/app/' . $existsId->customerInput);
            $existsId->idProofImageInput = url('storage/app/' . $existsId->idProofImageInput);
            $existsId->firstguarantorImageInput = url('storage/app/' . $existsId->firstguarantorImageInput);
            $existsId->secondguarantorImageInput = url('storage/app/' . $existsId->secondguarantorImageInput);

            return response()->json(['status' => 'success', 'customerDetails' => $existsId]);
        } else {

            return response()->json(['status' => false, 'message' => 'Record Not Found']);
        }
    }


    public function updatecustomer(Request $request)
    {

        $id = $request->customer_Id;
        $existsId = FacadesDB::table('member_accounts')->where('customer_Id', $id)->first();


        Log::info($request->all());


        if ($request->file('customerInput')) {
            $ncustomerImagecustomerImage = rand() . 'customerImageall.' . $request->file('customerInput')->getClientOriginalExtension();
            $rouncustomerImagecustomerImage = $request->file('customerInput')->storeAs('public/featcustomerImages', $ncustomerImagecustomerImage);
        } else {
            $rouncustomerImagecustomerImage = $existsId->customerInput;
        }
        //+++++++++++++ Customer Id Proof
        if ($request->file('idProofImageInput')) {
            $nidProofImageidProofImage = rand() . 'idProofImageall.' . $request->file('idProofImageInput')->getClientOriginalExtension();
            $rounidProofImageidProofImage = $request->file('idProofImageInput')->storeAs('public/featidProofImages', $nidProofImageidProofImage);
        } else {
            $rounidProofImageidProofImage =  $existsId->idProofImageInput;
        }
        //+++++++++++++ First Guarantor Image
        if ($request->file('firstguarantorImageInput')) {
            $nfirstguarantorImagefirstguarantorImage = rand() . 'firstguarantorImageall.' . $request->file('firstguarantorImageInput')->getClientOriginalExtension();
            $rounfirstguarantorImagefirstguarantorImage = $request->file('firstguarantorImageInput')->storeAs('public/featfirstguarantorImages', $nfirstguarantorImagefirstguarantorImage);
        } else {
            $rounfirstguarantorImagefirstguarantorImage = $existsId->firstguarantorImageInput;
        }
        //+++++++++++++ Second Guarantor Image
        if ($request->file('secondguarantorImageInput')) {
            $nsecondguarantorImagesecondguarantorImage = rand() . 'secondguarantorImageall.' . $request->file('secondguarantorImageInput')->getClientOriginalExtension();
            $rounsecondguarantorImagesecondguarantorImage = $request->file('secondguarantorImageInput')->storeAs('public/featsecondguarantorImages', $nsecondguarantorImagesecondguarantorImage);
        } else {
            $rounsecondguarantorImagesecondguarantorImage = $existsId->secondguarantorImageInput;
        }
        //++++++++++ Customer Update
        FacadesDB::table('member_accounts')->where('customer_Id', $id)->update([
            // 'openingDate' => date('Y-m-d', strtotime($request->openingdate)),
            'name' => $request->name ?? $existsId->name,
            'father_husband' => $request->father_husband ?? $existsId->father_husband,
            'gender' => $request->gender ?? $existsId->gender,
            'adhaar_no'  => $request->adhaar_no ?? $existsId->adhaar_no,
            'pan_number' => $request->pan_number ?? $existsId->pan_number,
            'email' => $request->email ?? $existsId->email,
            'address' => $request->address ?? $existsId->address,
            'customerInput' => $rouncustomerImagecustomerImage,
            'idProofImageInput' => $rounidProofImageidProofImage,
            'firstguarantorImageInput' => $rounfirstguarantorImagefirstguarantorImage,
            'secondguarantorImageInput' => $rounsecondguarantorImagesecondguarantorImage,
            'openingbal' => $request->openingbal ?? $existsId->openingbal,



            // 'stateId' => $request->stateId,
            // 'districtId' => $request->districtId,
            // 'tehsilId' => $request->tehsilId,
            // 'postOfficeId' => $request->postOfficeId,
            // 'villageId' => $request->villageId,
            // 'houseType' => $request->houseType,
            // 'landmark' => $request->landmark,
            // 'mobile_first' => $request->mobile_first,
            // 'mobile_second' => $request->mobile_second,
            // 'work_place' => $request->work_place,
            // 'relationship' => $request->relationship,
            // 'relative_mobile_no' => $request->relative_mobile_no,
            // 'guarantor_first' => $request->guarantor_first,
            // 'first_guarantor_mobile' => $request->first_guarantor_mobile,
            // 'guarantor_second' => $request->guarantor_second,
            // 'second_guarantor_mobile' => $request->second_guarantor_mobile,
            // 'loan_limit' => $request->loan_limit,
            // 'cibilscore' => $request->cibilscore,
            // 'agentId' => $request->agentid,
            // 'updatedBy' => $request->agentid,
            'updatedbytype' => DB::table('agent_masters')->where('id', '=', $request->agentid)->value('user_type'),
            'sessionId' => $request->session_id ?? $existsId->sessionId,
            // 'is_delete' => 'No',
        ]);


        return response()->json([
            'status' => true,
            'customer' => $existsId->id,
            'message' => 'Customer Updated',
        ]);
    }


    public function editloandisbursement(Request $post)
    {
        $loanId = $post->loanId;

        $check_recoveries = DB::table('loan_recoveries')->where('loanId', $loanId)->first();
        if (!empty($check_recoveries)) {
            return response()->json(['status' => false, 'messages' => 'this Loan Has Received Loan Recovery You Access this Account']);
        } else {
            $member_loans = DB::table('member_loans')->where('id', $loanId)->where('status', '=', 'Disbursed')->first();
            if (!empty($member_loans)) {
                return response()->json(['status' => true, 'message' => 'success', 'loanDetails' => $member_loans]);
            } else {
                return response()->json(['status' => false, 'messages' => 'Record Not Found']);
            }
        }
    }

    public function updateloanadvancement(Request $post)
    {

        $loanId = $post->loanId;

        $check_recoveries = DB::table('loan_recoveries')->where('loanId', $loanId)->first();

        if (!empty($check_recoveries)) {
            return response()->json(['status' => false, 'messages' => 'this Loan Has Received Loan Recovery You Access this Account']);
        }

        $member_loans = DB::table('member_loans')->where('id', $loanId)->where('status', '=', 'Disbursed')->first();

        if (!empty($member_loans)) {
            $loanmass = loan_masters::find($post->loanType);
            $account_no = $post->accountNo;


            $checkaccount_no = DB::table('member_accounts')->where('customer_Id', $account_no)->first();


            $checkdate = $checkaccount_no->openingDate;
            $loan_date = date("Y-m-d", strtotime($post->loanDate));


            if ($loan_date >= $checkdate) {
                $newdate = $loan_date;
                // $emidate = Carbon::parse($newdate)->addMonth()->day(10)->format('Y-m-d');

                if (!empty($post->emiDate)) {
                    $emidate = date('Y-m-d', strtotime($post->emiDate));
                } else {
                    $emidate = Carbon::parse($newdate)->addMonth()->day(10)->format('Y-m-d');
                }
            } else {
                return response()->json(['status' => false, 'message' => 'Loan date must be after the account opening date'], 400);
            }

            $loanAmountofid = member_loans::find($loanId)->loanAmount;

            $advancement_amount = 0;
            $loan_amount = $post->loanAmount;
            $loan_limit = $checkaccount_no->loan_limit;


            $creditAmount = DB::table('member_loans')
                ->where('accountNo', $post->accountNo) // Replace with the actual customer_id
                ->sum('loanAmount');
            $debitAmount = DB::table('loan_recoveries')
                ->join('member_loans', 'loan_recoveries.loanId', '=', 'member_loans.id')
                ->where('member_loans.accountNo', $post->accountNo) // Replace with the actual customer_id
                ->sum('loan_recoveries.principal');


            $remainingcredit = ($creditAmount - $debitAmount) - $loanAmountofid;

            $bchahua = $loan_limit - $remainingcredit;

            if ($bchahua >= $loan_amount) {
                $advancement_amount = $loan_amount;
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Loan Amount Exceed From Loan Limit , You have ' . $bchahua . 'in your Limit'
                ], 400);
            }

            //____________Processing Fee Calculation
            $loan_amounts = $post->loanAmount;
            $processing_fee = $post->processingFee;
            $processing_fee_amount = (($loan_amounts * $processing_fee) / 100);


            DB::beginTransaction();

            try {

                // $ids = DB::table('member_loans')->where('id',$loanId)->update([
                //     'loanDate' => $newdate,
                //     'loanType' => $post->loanType,
                //     'purpose' => $post->purpose,
                //     'loanAmount' => $advancement_amount,
                //     'processingFee' => $post->processingFee,
                //     'emiDate' => $emidate,
                //     'loanInterest' => $post->loanInterest,
                //     'penaltyInteresttype' => $post->penalityType,
                //     'months' => $post->months,
                //     'guarantorname' => $post->guarantorname,
                //     'guarantorno' => $post->guarantorno,
                //     'name' => $post->name,
                //     'penaltyInterest' => $post->penaltyInterest,
                //     'accountNo' => $post->accountNo,
                //     'loanby' => $post->loanBy,
                //     'installmentType' => $loanmass->insType,
                //     'recoveryDate' => 'yes',
                //     'advancementDate' => 'yes',
                //     'guarantoraddress' => $post->guarantoraddress,
                //     'guarantoraddresss' => $post->guarantoraddresss,
                //     'years' => 0,
                //     'days' => 0,
                //     'guarantornamee' => $post->guarantornamee,
                //     'guarantornoo' => $post->guarantornoo,
                //     'agentId' => Session::get('adminloginid'),
                //     'updatedBy' => Session::get('adminloginid'),
                //     'updatedbytype' => Session::get('user_type'),
                //     'sessionId'     => Session::get('sessionof'),
                // ]);

                $meminsert = member_loans::find($loanId);
                $meminsert->loanDate = $newdate;
                $meminsert->loanType = $post->loanType;
                $meminsert->purpose = $post->purpose;
                $meminsert->loanAmount = $advancement_amount;
                $meminsert->processingFee = $post->processingFee;
                $meminsert->emiDate = $emidate;
                $meminsert->loanInterest = $post->loanInterest;
                $meminsert->penaltyInteresttype = $post->penalityType;
                $meminsert->months = $post->months;
                $meminsert->guarantorname = $post->guarantorname;
                $meminsert->guarantorno = $post->guarantorno;
                $meminsert->name = $post->name;
                $meminsert->penaltyInterest = $post->penaltyInterest;
                $meminsert->accountNo = $post->accountNo;
                $meminsert->loanby = $post->loanBy;
                $meminsert->installmentType = $loanmass->insType;
                $meminsert->recoveryDate = 'yes';
                $meminsert->advancementDate = 'yes';
                $meminsert->guarantoraddress = $post->guarantoraddress;
                $meminsert->guarantoraddresss = $post->guarantoraddresss;
                $meminsert->years = 0;
                $meminsert->days = 0;
                $meminsert->guarantornamee = $post->guarantornamee;
                $meminsert->guarantornoo = $post->guarantornoo;
                $meminsert->agentId = Session::get('adminloginid');
                $meminsert->updatedBy = Session::get('adminloginid');
                $meminsert->updatedbytype = Session::get('user_type');
                $meminsert->sessionId     = Session::get('sessionof');
                $meminsert->save();


                $ids = $meminsert->id;
                $loandetail = loan_masters::find($post->loanType);
                // if ($post->insType == 'Monthly') {
                loan_installments::where('LoanId', '=', $loanId)->delete();

                $loanamount = $post->loanAmount;
                $rateofinterest = $post->loanInterest;
                $tanure = $post->months;


                $monthlyPrincipaltotal = 0;
                $monthlyInstallmenttottal = 0;
                $monthlyround = 0;




                // dd($monthlyInterest,$totalintrest,$principlleadd,$monthlyInstallment,$monthlyPrincipal);

                $monthly_roi = $rateofinterest * $tanure;
                $monthlyInterest = ((($loanamount * $monthly_roi) / 100) / $tanure);
                $totalintrest = $monthlyInterest * $tanure;
                $principlleadd = $loanamount + $totalintrest;
                $monthlyInstallment = $principlleadd / $tanure;
                $monthlyPrincipal = $monthlyInstallment - $monthlyInterest;



                $startDate = Carbon::parse($emidate);
                for ($i = 0; $i < $tanure; $i++) {
                    $loanInstallment = new loan_installments();
                    $loanInstallment->LoanId = $ids;


                    if ($i == 0) {
                        $loanInstallment->installmentDate = $startDate->toDateString();
                    } else {
                        $nextDate = $startDate->copy()->addMonth($i);
                        if ($nextDate->month == 2) {
                            if ($nextDate->day > 28) {
                                $nextDate->day = $nextDate->isLeapYear() ? 29 : 28;
                            }
                        }

                        $loanInstallment->installmentDate = $nextDate->toDateString();
                    }


                    if ($i == $tanure - 1) {
                        $monthlp = $loanamount - $monthlyPrincipaltotal;
                        $monthlyInt = $loanamount - $monthlyround;

                        $loanInstallment->principal = $monthlp;
                        $loanInstallment->interest = round($monthlyInterest);
                        $loanInstallment->totalinstallmentamount = round($monthlyInterest) + $monthlp;
                    } else {
                        $monthlyPrincipaltotal += round($monthlyPrincipal);
                        $monthlyInstallmenttottal += round($monthlyInstallment);
                        $monthlyround += round($monthlyInterest);

                        $loanInstallment->principal = round($monthlyPrincipal);
                        $loanInstallment->interest = round($monthlyInterest);
                        $loanInstallment->totalinstallmentamount = round($monthlyPrincipal) + round($monthlyInterest);
                    }

                    $loanInstallment->paid_date = null;
                    $loanInstallment->status = 'false';
                    $loanInstallment->re_amount = 0;
                    $loanInstallment->agentId = Session::get('adminloginid');
                    $loanInstallment->updatedBy = Session::get('adminloginid');
                    $loanInstallment->updatedbytype = Session::get('user_type');
                    $loanInstallment->sessionId = Session::get('sessionof');

                    // Save the installment and handle errors
                    if (!$loanInstallment->save()) {
                        // Handle error
                    }
                }





                $totalPaymenttotal = DB::table('loan_recoveries')->where('loanId', $loanId)->sum('total');
                $getinstallmentsbydatse = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $loanId)->orderby('id', 'ASC')->get();
                foreach ($getinstallmentsbydatse as $inst) {
                    $installmentAmount = $inst->principal + $inst->interest;
                    if ($totalPaymenttotal >= $installmentAmount) {
                        DB::table('loan_installments')->where('id', $inst->id)->update([
                            'status' => 'paid',
                        ]);
                        $totalPaymenttotal -= $installmentAmount;
                    }
                }


                // } else {
                //     return back();
                // }

                general_ledgers::where('LoanId', '=', $loanId)->whereNull('refid')->delete();

                $ledhai = ledger_masters::where('loan', '=', $loanmass->id)->first();
                $ledgerme = new general_ledgers();
                $ledgerme->LoanId = $ids;
                $ledgerme->accountNo = $post->accountNo;
                $ledgerme->groupCode = $ledhai->groupCode;
                $ledgerme->ledgerCode = $ledhai->ledgerCode;
                $ledgerme->formName = $post->name;
                $ledgerme->transactionDate = $newdate;
                $ledgerme->transactionType = 'Dr';
                $ledgerme->transactionAmount = $advancement_amount;
                $ledgerme->narration = $loandetail->loanname;
                $ledgerme->branchId = Null;
                $ledgerme->agentId = Session::get('adminloginid');
                $ledgerme->updatedBy = Session::get('adminloginid');
                $ledgerme->updatedbytype = Session::get('user_type');
                $ledgerme->sessionId     = Session::get('sessionof');
                $ledgerme->save();

                $ledgerme = new general_ledgers();
                $ledgerme->LoanId = $ids;
                $ledgerme->accountNo = $post->accountNo;
                $ledgerme->groupCode = 'C002';
                $ledgerme->ledgerCode = 'CAS001';
                $ledgerme->formName = $post->name;
                $ledgerme->transactionDate = $newdate;
                $ledgerme->transactionType = 'Cr';
                $ledgerme->transactionAmount = $advancement_amount;
                $ledgerme->narration = $loandetail->loanname;
                $ledgerme->branchId = Null;
                $ledgerme->agentId = Session::get('adminloginid');
                $ledgerme->updatedBy = Session::get('adminloginid');
                $ledgerme->updatedbytype = Session::get('user_type');
                $ledgerme->sessionId     = Session::get('sessionof');
                $ledgerme->save();


                // general_ledgers::where('LoanId', '=', $id)->delete();
                // $ledhai = ledger_masters::where('loan', '=', $loanmass->id)->first();
                //__________If Processing Fees
                if ($post->processingFee > 0) {
                    $ledgerme = new general_ledgers();
                    $ledgerme->LoanId = $ids;
                    $ledgerme->accountNo = $post->accountNo;
                    $ledgerme->groupCode = 'IINC01';
                    $ledgerme->ledgerCode = 'PRC001';
                    $ledgerme->formName = $post->name;
                    $ledgerme->transactionDate = $newdate;
                    $ledgerme->transactionType = 'Cr';
                    $ledgerme->transactionAmount = $post->processingFee;
                    $ledgerme->narration = $loandetail->loanname;
                    $ledgerme->branchId = Null;
                    $ledgerme->agentId = Session::get('adminloginid');
                    $ledgerme->updatedBy = Session::get('adminloginid');
                    $ledgerme->updatedbytype = Session::get('user_type');
                    $ledgerme->sessionId     = Session::get('sessionof');
                    $ledgerme->save();

                    $ledgerme = new general_ledgers();
                    $ledgerme->LoanId = $ids;
                    $ledgerme->accountNo = $post->accountNo;
                    $ledgerme->groupCode = 'C002';
                    $ledgerme->ledgerCode = 'CAS001';
                    $ledgerme->formName = $post->name;
                    $ledgerme->transactionDate = $newdate;
                    $ledgerme->transactionType = 'Dr';
                    $ledgerme->transactionAmount = $post->processingFee;
                    $ledgerme->narration = $loandetail->loanname;
                    $ledgerme->branchId = Null;
                    $ledgerme->agentId = Session::get('adminloginid');
                    $ledgerme->updatedBy = Session::get('adminloginid');
                    $ledgerme->updatedbytype = Session::get('user_type');
                    $ledgerme->sessionId     = Session::get('sessionof');
                    $ledgerme->save();
                }

                DB::commit();
                return response()->json([
                    'status' => true,
                    'id' => $post->accountNo
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => false, 'messages' => $e->getMessage(), 'lines' => $e->getLine()]);
            }
        } else {
            return response()->json(['status' => false, 'messages' => 'Record Not Found']);
        }
    }

    public function loandvancementdelete(Request $post)
    {
        $loanId = $post->loanId;
        $check_recoveries = DB::table('loan_recoveries')->where('loanId', $loanId)->first();

        if (!empty($check_recoveries)) {
            return response()->json(['status' => false, 'messages' => 'this Loan Has Received Loan Recovery You Access this Account']);
        }

        if (!empty($loanId)) {

            DB::beginTransaction();

            try {

                $member_loans = DB::table('member_loans')->where('id', $loanId)->where('status', '=', 'Disbursed')->first();

                if (!empty($member_loans)) {
                    general_ledgers::where('LoanId', '=', $loanId)->whereNull('refid')->delete();
                    DB::table('loan_installments')->where('LoanId', $member_loans->id)->delete();
                    DB::table('member_loans')->where('id', $loanId)->where('status', '=', 'Disbursed')->delete();
                } else {
                    return response()->json(['status' => false, 'messages' => 'Loan A/c Not Found']);
                }

                DB::commit();

                return response()->json(['status' => true, 'messages' => 'Loan Deteled Successfully']);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => false, 'messages' => $e->getMessage(), 'lines' => $e->getLine()]);
            }
        } else {
            return response()->json(['status' => false, 'messages' => 'Record Not Found']);
        }
    }

    public function editloanrecovery(Request $post)
    {
        $id = $post->id;
        if (is_null($id)) {
            return response()->json(['status' => false, 'messages' => 'Record Not Found']);
        } else {
            $exitsId = DB::table('loan_recoveries')
                ->select(
                    'loan_recoveries.id as recoveryId',
                    'member_loans.id as loanId',
                    'member_loans.name',
                    'loan_recoveries.receiptDate',
                    'loan_recoveries.receivedAmount',
                    'loan_recoveries.remarks',
                    'loan_recoveries.penalInterest'
                )
                ->leftJoin('member_loans', 'member_loans.id', '=', 'loan_recoveries.loanId')
                ->where('loan_recoveries.id', $id)
                ->first();

            if (!empty($exitsId)) {
                return response()->json(['status' => true, 'messages' => 'successfull', 'editloanrecovery' => $exitsId]);
            } else {
                return response()->json(['status' => false, 'messages' => 'Record Not Found']);
            }
        }
    }

    public function getallloanrecoveries(Request $post)
    {
        $loanId = $post->loanId;
        if (is_null($loanId)) {
            return response()->json(['status' => false, 'messages' => 'Record Not Found']);
        } else {
            $exitsId = DB::table('loan_recoveries')
                ->select(
                    'loan_recoveries.id as recoveryId',
                    'member_loans.id as loanId',
                    'member_loans.name',
                    'loan_recoveries.receiptDate',
                    'loan_recoveries.receivedAmount',
                    'loan_recoveries.remarks',
                    'loan_recoveries.penalInterest'
                )
                ->leftJoin('member_loans', 'member_loans.id', '=', 'loan_recoveries.loanId')
                ->where('loan_recoveries.loanId', $loanId)
                ->get();

            if (!empty($exitsId)) {
                return response()->json(['status' => true, 'messages' => 'successfull', 'getallloanrecoveries' => $exitsId]);
            } else {
                return response()->json(['status' => false, 'messages' => 'Record Not Found']);
            }
        }
    }


    public function updateloanrecovery(Request $post)
    {
        $rules = [
            "recoveryId" => "required",
            // "loanId" => "required",
            "receiptDate" => "required",
            "receivedAmount" => "required",
        ];


        $validator = Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }

        $loans = DB::table('loan_recoveries')->where('id',$post->recoveryId)->first();

        if(is_null($loans)){
            return response()->json(['status' => false,'messages' => 'Record Not Found']);
        }



        $exitsId = DB::table('loan_recoveries')
            ->select(
                'loan_recoveries.id as recoveryId',
                'member_loans.id as loanId',
                'member_loans.name',
                'loan_recoveries.receiptDate',
                'loan_recoveries.receivedAmount',
                'loan_recoveries.remarks',
                'loan_recoveries.penalInterest',
                'member_loans.accountNo'
            )
            ->leftJoin('member_loans', 'member_loans.id', '=', 'loan_recoveries.loanId')
            ->where('loan_recoveries.loanId', $loans->loanId)
            ->first();


        $paneltyis = $post->penalInterest ?? 0;

        $cdatee = date("Y-m-d", strtotime($post->receiptDate));
        $totalPayment = floatval($post->receivedAmount);
        $customer_Id = $exitsId->accountNo;

        $thisid = DB::table('member_loans')
            ->where('accountNo', '=', $customer_Id)
            ->where('status', '=', 'Disbursed')
            ->first();

        if (!$thisid) {
            return response()->json(['status' => false, 'message' => 'NO Loan Found'], 400);
        }

        $id = $thisid->id;
        $checkdate = $thisid->loanDate;

        if ($cdatee < $checkdate) {
            return response()->json(['status' => false, 'message' => 'Loan date must be after the account opening date'], 400);
        }

        $firstis = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->first();
        $sumprincipal = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->sum('principal');
        $suminterest = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->sum('interest');
        $naihonachiye = $sumprincipal + $suminterest;

        if ($naihonachiye < $totalPayment) {
            return response()->json(['status' => false, 'message' => 'Amount is greater than Pending Amount'], 400);
        }

        $getinstallmentsbydate = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->orderby('id', 'ASC')->get();


        if ($getinstallmentsbydate->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'No installments found'], 400);
        }

        $recoveries = DB::table('loan_recoveries')->where('id', $post->recoveryId)->first();


        DB::beginTransaction();

        try {

            $pendingpayment = $totalPayment;
            $totalintest = 0;
            $totalprinciple = 0;

            foreach ($getinstallmentsbydate as $list) {
                if ($pendingpayment >= $list->principal + $list->interest) {
                    $totalintest += $list->interest;
                    $totalprinciple += $list->principal;
                    $pendingpayment -= ($list->principal + $list->interest);
                } else {

                    if ($pendingpayment > 0) {
                        if ($pendingpayment > $list->interest) {
                            $totalintest += $list->interest;
                            $bchahuaprinciple = $pendingpayment - $list->interest;
                            $pendingpayment -= $list->interest;
                            $totalprinciple += $bchahuaprinciple;
                            $pendingpayment -= ($bchahuaprinciple + $list->interest);
                        }
                    }
                }
            }


            $generalLedgers = DB::table('general_ledgers')->where('loanRecoveryidis', $recoveries->id)->delete();
            DB::table('loan_recoveries')->where('id', $post->recoveryId)->delete();
            // dd($generalLedgers);

            $principleis = $totalPayment - $totalintest;
            $totalpaneltywithinstallmentis = $principleis + $totalintest + $paneltyis;

            $loanRecovery = new loan_recoveries();
            $loanRecovery->receiptDate = $cdatee;
            $loanRecovery->loanId = $id;
            $loanRecovery->principal = $principleis;
            $loanRecovery->interest = $totalintest;
            $loanRecovery->penalInterest = $paneltyis;
            $loanRecovery->total = $totalpaneltywithinstallmentis;
            $loanRecovery->receivedAmount = $totalpaneltywithinstallmentis;
            $loanRecovery->status = 'True';
            $loanRecovery->remarks = $post->remarks;
            $loanRecovery->agentId = Session::get('adminloginid');
            $loanRecovery->updatedBy = Session::get('adminloginid');
            $loanRecovery->updatedbytype = Session::get('user_type');
            $loanRecovery->sessionId = Session::get('sessionof');
            $loanRecovery->save();

            $loanRecoveryidis = $loanRecovery->id;

            $member_loans = member_loans::find($id);
            $ledhai = ledger_masters::where('loan', '=', $member_loans->loanType)->first();
            $formname = $member_loans->name;
            $accountNo = $member_loans->accountNo;

            DB::table('general_ledgers')->insert([
                'LoanId' => $member_loans->id,
                'accountNo' => $member_loans->accountNo,
                'groupCode' => 'C002',
                'ledgerCode' => 'CAS001',
                'formName' => $member_loans->name,
                'transactionDate' => $cdatee,
                'transactionType' => 'Dr',
                'transactionAmount' => $totalpaneltywithinstallmentis,
                'narration' => 'Principle received',
                'branchId' => null,
                'refid' => 'recovery',
                'loanRecoveryidis' => $loanRecoveryidis,
                'agentId' => $post->agentid,
                'updatedBy' => $post->agentid,
                'updatedbytype' => DB::table('agent_masters')->where('id', '=', $post->agentid)->value('user_type'),
                'sessionId'     => $post->sessionid,
            ]);

            DB::table('general_ledgers')->insert([
                'LoanId' => $member_loans->id,
                'accountNo' => $member_loans->accountNo,
                'groupCode' => $ledhai->groupCode,
                'ledgerCode' => $ledhai->ledgerCode,
                'formName' => $member_loans->name,
                'transactionDate' => $cdatee,
                'transactionType' => 'Cr',
                'transactionAmount' => $principleis,
                'narration' => 'Principle received',
                'branchId' => null,
                'refid' => 'recovery',
                'loanRecoveryidis' => $loanRecoveryidis,
                'agentId' => $post->agentid,
                'updatedBy' => $post->agentid,
                'updatedbytype' => DB::table('agent_masters')->where('id', '=', $post->agentid)->value('user_type'),
                'sessionId' => $post->sessionid,
            ]);


            // Create ledger entries
            // $this->createLedgerEntry($id, $accountNo, 'C002', 'CAS001', $formname, $cdatee, 'Dr', $totalpaneltywithinstallmentis, $ledhai->name, $loanRecoveryidis);
            // $this->createLedgerEntry($id, $accountNo, $ledhai->groupCode, $ledhai->ledgerCode, $formname, $cdatee, 'Cr', $principleis, 'Principle received', $loanRecoveryidis);

            if ($totalintest > 0) {
                $loan_intt_ledger = ledger_masters::where('loan_intt_id', '=', $member_loans->loanType)->first();
                // $this->createLedgerEntry($id, $accountNo, $loan_intt_ledger->groupCode, $loan_intt_ledger->ledgerCode, $formname, $cdatee, 'Cr', $totalintest, 'Interest Received On Loan', $loanRecoveryidis);

                // DB::table('general_ledgers')->insert([
                //     'LoanId' => $member_loans->id,
                //     'accountNo' => $member_loans->accountNo,
                //     'groupCode' => 'C002',
                //     'ledgerCode' => 'CAS001',
                //     'formName' => $member_loans->name,
                //     'transactionDate' => $cdatee,
                //     'transactionType' => 'Dr',
                //     'transactionAmount' => $totalintest,
                //     'narration' => 'Interest Received On Loan',
                //     'branchId' => null,
                //     'refid' => 'recovery',
                //     'loanRecoveryidis' => $loanRecoveryidis,
                //     'agentId' => $post->agentid,
                //     'updatedBy' => $post->agentid,
                //     'updatedbytype' => DB::table('agent_masters')->where('id', '=', $post->agentid)->value('user_type'),
                //     'sessionId'     => $post->sessionid,
                // ]);

                DB::table('general_ledgers')->insert([
                    'LoanId' => $member_loans->id,
                    'accountNo' => $member_loans->accountNo,
                    'groupCode' => $loan_intt_ledger->groupCode,
                    'ledgerCode' => $loan_intt_ledger->ledgerCode,
                    'formName' => $member_loans->name,
                    'transactionDate' => $cdatee,
                    'transactionType' => 'Cr',
                    'transactionAmount' => $totalintest,
                    'narration' => 'Interest Received On Loan',
                    'branchId' => null,
                    'refid' => 'recovery',
                    'loanRecoveryidis' => $loanRecoveryidis,
                    'agentId' => $post->agentid,
                    'updatedBy' => $post->agentid,
                    'updatedbytype' => DB::table('agent_masters')->where('id', '=', $post->agentid)->value('user_type'),
                    'sessionId' => $post->sessionid,
                ]);
            }

            if ($paneltyis > 0) {

                // $this->createLedgerEntry($id, $accountNo, 'IINC01', 'PAN001', $formname, $cdatee, 'Cr', $paneltyis, 'Penalty Received On Loan', $loanRecoveryidis);
                // DB::table('general_ledgers')->insert([
                //     'LoanId' => $member_loans->id,
                //     'accountNo' => $member_loans->accountNo,
                //     'groupCode' => 'C002',
                //     'ledgerCode' => 'CAS001',
                //     'formName' => $member_loans->name,
                //     'transactionDate' => $cdatee,
                //     'transactionType' => 'Dr',
                //     'transactionAmount' => $paneltyis,
                //     'narration' => 'Interest Received On Loan',
                //     'branchId' => null,
                //     'refid' => 'recovery',
                //     'loanRecoveryidis' => $loanRecoveryidis,
                //     'agentId' => $post->agentid,
                //     'updatedBy' => $post->agentid,
                //     'updatedbytype' => DB::table('agent_masters')->where('id', '=', $post->agentid)->value('user_type'),
                //     'sessionId'     => $post->sessionid,
                // ]);

                DB::table('general_ledgers')->insert([
                    'LoanId' => $member_loans->id,
                    'accountNo' => $member_loans->accountNo,
                    'groupCode' => 'IINC01',
                    'ledgerCode' => 'PAN001',
                    'formName' => $member_loans->name,
                    'transactionDate' => $cdatee,
                    'transactionType' => 'Cr',
                    'transactionAmount' => $paneltyis,
                    'narration' => 'Interest Received On Loan',
                    'branchId' => null,
                    'refid' => 'recovery',
                    'loanRecoveryidis' => $loanRecoveryidis,
                    'agentId' => $post->agentid,
                    'updatedBy' => $post->agentid,
                    'updatedbytype' => DB::table('agent_masters')->where('id', '=', $post->agentid)->value('user_type'),
                    'sessionId' => $post->sessionid,
                ]);
            }

            // Update installments
            $totalPayment = DB::table('loan_recoveries')->where('loanId', $id)->sum('principal');
            DB::table('loan_installments')->where('LoanId', $id)->update(['status' => 'false']);


            $getinstallmentsbydatse = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->orderby('id', 'ASC')->get();


            foreach ($getinstallmentsbydatse as $inst) {
                $installmentAmount = $inst->principal;
                if ($totalPayment >= $installmentAmount) {
                    DB::table('loan_installments')->where('id', $inst->id)->update([
                        'paid_date' => $cdatee,
                        'status' => 'paid',
                        'loanRecoveryidis' => $loanRecoveryidis,
                    ]);
                    $totalPayment -= $installmentAmount;
                }
            }

            $member_loanscheck = DB::table('member_loans')
                ->join('loan_masters', 'loan_masters.id', '=', 'member_loans.loanType')
                ->join('agent_masters', 'agent_masters.id', '=', 'member_loans.agentId')
                ->where('member_loans.accountNo', '=', $accountNo)
                ->select(
                    'member_loans.*',
                    'loan_masters.loanname',
                    'agent_masters.name as agent_name',
                    DB::raw('COALESCE((SELECT SUM(principal) FROM loan_recoveries WHERE loan_recoveries.loanId = member_loans.id), 0) as total_recovered')
                )
                ->orderBy('member_loans.loanDate', 'DESC') // Order by loanDate in ascending order
                ->get();

            foreach ($member_loanscheck as $member_loanscheckedit) {

                if ($member_loanscheckedit->loanAmount <= $member_loanscheckedit->total_recovered) {


                    $mamam = member_loans::find($member_loanscheckedit->id);
                    $mamam->status = 'Closed';
                    $mamam->save();
                } else {
                    $mamam = member_loans::find($member_loanscheckedit->id);
                    $mamam->status = 'Disbursed';
                    $mamam->save();
                }
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Recovery Updated Successfully  '], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Transaction Failed',
                'errors' => $e->getMessage(),
                'lines' => $e->getLine()
            ]);
        }
    }


    public function deleteloanrecovery(Request $post){
        $recoveryId = $post->recoveryId;
        if(is_null($recoveryId)){
            return response()->json(['status' => false,'messages' => 'Record Not Found']);
        }else{
            $exitsId = DB::table('loan_recoveries')->where('id',$post->recoveryId)->first();

            DB::beginTransaction();
            try{
                if(!empty($exitsId)){

                    $member_loan = DB::table('member_loans')->where('id',$exitsId->loanId)->first();

                    if($member_loan->status === 'Closed'){

                        DB::table('member_loans')->where('id',$exitsId->loanId)->update(['status' => 'Disbursed']);
                        DB::table('general_ledgers')->where('loanRecoveryidis',$exitsId->id)->delete();
                        DB::table('loan_installments')->where('loanRecoveryidis',$exitsId->id)->update(['paid_date' => null,'status' => 'false']);
                        DB::table('loan_recoveries')->where('id',$post->recoveryId)->delete();

                    }else{

                        DB::table('general_ledgers')->where('loanRecoveryidis',$exitsId->id)->delete();
                        DB::table('loan_installments')->where('loanRecoveryidis',$exitsId->id)->update(['paid_date' => null,'status' => 'false']);
                        DB::table('loan_recoveries')->where('id',$post->recoveryId)->delete();

                    }

                    DB::commit();

                    return response()->json(['status' => true,'messages' => 'Record Deleted Successfully']);

                }else{
                    return response()->json(['status' => false,'messages' => 'Record Not Found']);
                }

            }catch(\Exception $e){
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'messages' => $e->getMessage(),
                    'lines' => $e->getLine()
                ]);
            }
        }
    }

}
