<?php

namespace App\Http\Controllers;

use App\Models\member_accounts;
use App\Models\member_loans;
use App\Models\loan_recoveries;
use App\Models\loan_installments;
use Session;
use DB;
use Illuminate\Http\Request;

class logbookController extends Controller
{


    public function logbook(Request $request)
    {
        $pagetitle = "Log Book";
        $pageto = url('Log-Book');
        $formurl = url('Log-Book');
        $member_accounts = member_accounts::orderby('name')->get();
        $group_masters = DB::table('group_masters')->whereIn('groupCode', ['BANK01', 'C002'])->get();;
        $data = compact('formurl', 'pagetitle', 'pageto', 'member_accounts','group_masters');
        return view('logbook')->with($data);
    }





    public function getdataofloan(Request $request)
    {
        $accountNo = $request->id;

        $agents = \DB::table('agent_masters')->pluck('name', 'id')->map(function ($name) {
            return ucfirst($name);
        });


        $loanNames = \DB::table('loan_masters')->pluck('loanname', 'id');

        $loans = member_loans::with('recoveries')
            ->where('accountNo', $accountNo)
            ->orderBy('loanDate')
            ->get();

        $loansAndRecoveries = [];

        foreach ($loans as $loan) {

            $loansAndRecoveries[] = [
                'id' => $loan->id,
                'type' => 'credit',
                'date' => $loan->loanDate,
                'amount' => $loan->loanAmount,
                'description' => "Loan Disbursed",
                'interest' => DB::table('loan_installments')->where('LoanId', '=', $loan->id)->sum('interest'),
                'penalty' => '0',
                'agent_name' => $agents[$loan->agentId] ?? '-',
                'loan_id' => $loan->id,
                'loan_name' => $loanNames[$loan->loanType] ?? '-'
            ];

            foreach ($loan->recoveries as $recovery) {
                $loansAndRecoveries[] = [
                    'recoveryid' => $recovery->id,
                    'type' => 'debit',
                    'date' => $recovery->receiptDate,
                    'amount' => $recovery->principal + $recovery->interest,
                    'description' => "Loan Recovery",
                    'interest' => $recovery->interest,
                    'penalty' => $recovery->penalInterest,
                    'agent_name' => $agents[$recovery->agentId] ?? '-',
                    'loan_id' => $loan->id,
                    'loan_name' => $loanNames[$loan->loanType] ?? '-',
                    'remarks' => $recovery->remarks
                ];
            }
        }

        usort($loansAndRecoveries, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });
        Session::put('emisess', $accountNo);

        return response()->json($loansAndRecoveries);
    }


























    public function alllogbook(Request $request)
    {
        $pagetitle = "All Over Balance";
        $pageto = url('alllogbook');
        $formurl = url('alllogbook');
        $member_accounts = member_accounts::all();
        $data = compact('formurl', 'pagetitle', 'pageto', 'member_accounts');
        return view('alllogbook')->with($data);
    }


    public function getnamebycstmid(Request $request)
    {
        $nananme = '(' . $request->cstid . ')' . ' ' . member_accounts::where('customer_Id', '=', $request->cstid)->value('name');
        return response()->json($nananme);
    }


    public function allgetdataofloan(Request $request)
    {

        $dateLimit = date('Y-m-d', strtotime($request->id));

        $loans = member_loans::with('recoveries')
            ->where('loanDate', '<=', $dateLimit)
            ->get();

        // Initialize totals arrays
        $totalCredits = [];
        $totalDebits = [];
        $totalDebitsin = [];
        $loanDate = [];

        foreach ($loans as $loan) {

            if (!isset($totalCredits[$loan->accountNo])) {
                $totalCredits[$loan->accountNo] = 0;
            }

            $totalCredits[$loan->accountNo] += $loan->loanAmount;

            foreach ($loan->recoveries as $recovery) {
                if (!isset($totalDebits[$loan->accountNo])) {
                    $totalDebits[$loan->accountNo] = 0;
                }

                $totalDebits[$loan->accountNo] += $recovery->receivedAmount;
                $totalDebitsin[$loan->accountNo] = loan_installments::where('LoanId', '=', $loan->id)->sum('interest');
                $loanDate[$loan->accountNo] = $loan->loanDate;
            }
        }

        // Fetch customer details
        $customerData = DB::table('member_accounts')
            ->whereIn('customer_Id', array_keys($totalCredits))
            ->get(['customer_Id', 'name']);


        // Map customer names by customer_Id
        $customerNames = [];
        foreach ($customerData as $customer) {
            $customerNames[$customer->customer_Id] = $customer->name;
        }

        // Prepare final output
        $finalOutput = [];

        foreach (array_unique(array_merge(array_keys($totalCredits), array_keys($totalDebits))) as $accountNo) {
            $finalOutput[] = [
                'accountNo' => $accountNo,
                'customer_name' => $customerNames[$accountNo] ?? 'Unknown', // Add customer name
                // 'total_credit' => $totalCredits[$accountNo] ?? 0,
                'total_debit' => $totalDebits[$accountNo] ?? 0,
                'total_in' => $totalDebitsin[$accountNo] ?? 0,
                'net_balance' => (($totalCredits[$accountNo] ?? 0) + ($totalDebitsin[$accountNo] ?? 0)) - ($totalDebits[$accountNo] ?? 0),
                'total_credit' => (($totalCredits[$accountNo] ?? 0) + ($totalDebitsin[$accountNo] ?? 0)),

            ];
        }

        return response()->json(['totals' => $finalOutput]);
    }

    public function emireport(Request $request)
    {
        $pagetitle = "Emi Report";
        $pageto = url('emireport');
        $formurl = url('emireport');
        $member_accounts = member_accounts::orderby('name')->get();
        $data = compact('formurl', 'pagetitle', 'pageto', 'member_accounts');
        return view('emireport')->with($data);
    }


    public function emireportloan(Request $request)
    {
        // Session::forget('emisess');
        $accountNo = $request->id;
        $agents = \DB::table('agent_masters')->pluck('name', 'id')->map(function ($name) {
            return ucfirst($name);
        });
        $loanNames = \DB::table('loan_masters')->pluck('loanname', 'id');
        $loans = member_loans::where('accountNo', $accountNo)->get();
        $loansAndInstallments = [];
        foreach ($loans as $loan) {
            $installments = \DB::table('loan_installments')
                ->where('LoanId', $loan->id)
                ->get();


            $totalPrincipalRecovered = \DB::table('loan_recoveries')
                ->where('loanId', $loan->id)
                ->sum('principal');

            $totalinterestRecovered = \DB::table('loan_recoveries')
                ->where('loanId', $loan->id)
                ->sum('interest');



            $emiCounter = 1;
            foreach ($installments as $installment) {
                $paid = min($installment->principal, $totalPrincipalRecovered);
                $totalPrincipalRecovered -= $paid;

                $paidinterest = min($installment->interest, $totalinterestRecovered);
                $totalinterestRecovered -= $paidinterest;

                $pending = $installment->principal - $paid;
                $loansAndInstallments[] = [
                    'type' => 'debit',
                    'loanid' => $loan->id,
                    'loanname' => ucfirst(\DB::table('loan_masters')->where('id', '=', $loan->loanType)->value('loanname')),
                    'loan_date' => $loan->loanDate,
                    'installment_date' => $installment->installmentDate,
                    'emi_no' => $emiCounter++,
                    'principal' => $installment->principal,
                    'paid' => $paid,
                    'pending' => $pending,
                    'interestpp' => $installment->interest,
                    'paidinterest' => $paidinterest
                ];
            }
        }

        Session::put('emisess', $accountNo);
        return response()->json($loansAndInstallments);
    }
}
