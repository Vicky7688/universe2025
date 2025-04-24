<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OverdueReportController extends Controller
{
    public function PendingEmiReport()
    {
        $pagetitle = "OverDue Report";
        $pageto = url('balancesheetindex');
        $loanissueds = DB::table('loan_masters')->orderBy('loanname', 'ASC')->where('status', '=', 'Active')->get();
        $data['pagetitle'] = $pagetitle;
        $data['pageto'] = $pageto;
        $data['loanissueds'] = $loanissueds;
        return view('Reports.overdueloan', $data);
    }

    public function GetPendingEmis(Request $post)
    {
        $end_date = date('Y-m-d', strtotime($post->endDate));
        $loan_type = $post->loanType;

        if ($loan_type === "All") {

            //_______All Details of Pending OverDue Loan
            $overdue_loans = DB::table('member_loans')
                ->select(
                    'loan_installments.LoanId as loan_id',
                    'member_accounts.customer_Id as customer_Id',
                    'member_accounts.name as customer_name',
                    DB::raw('COUNT(loan_installments.id) as overdue_emi'),
                    DB::raw('SUM(loan_installments.totalinstallmentamount) as overdue_amount'),
                    DB::raw('MAX(loan_installments.installmentDate) as last_due_date'),
                    'member_loans.loanDate','member_loans.loanAmount','member_loans.months'
                )
                ->leftJoin('member_accounts', 'member_loans.accountNo', '=', 'member_accounts.customer_Id')
                ->leftJoin('loan_masters', 'member_loans.loanType', '=', 'loan_masters.id')
                ->leftJoin('loan_installments', 'member_loans.id', '=', 'loan_installments.LoanId')
                ->where('member_loans.status', 'Disbursed')
                ->where(function ($query) use ($end_date) {
                    $query->where('loan_installments.installmentDate', '<=', $end_date)
                        ->orWhereNull('loan_installments.installmentDate')
                        ->orWhereDate('member_loans.loanDate', '=', $end_date);
                })
                ->groupBy(
                    'member_loans.loanAmount', 'loan_installments.LoanId', 'member_accounts.customer_Id',
                    'member_accounts.name', 'member_loans.loanDate','member_loans.months'
                    )
                ->havingRaw('SUM(loan_installments.totalinstallmentamount) > 0')
                ->havingRaw('COUNT(loan_installments.id) > 0')
                ->where('loan_installments.status', '=', 'false')
                ->orderBy('member_accounts.name', 'ASC')
                ->get();



            //______________Total Emi's Count
            $total_loan_emi = DB::table('member_loans')
                ->select(
                    'loan_installments.LoanId as loan_id',
                    DB::raw('COUNT(loan_installments.LoanId) as total_emi'),
                    DB::raw('SUM(loan_installments.re_amount) as received_amount')
                )
                ->leftJoin('loan_installments', 'member_loans.id', '=', 'loan_installments.LoanId')
                ->where('member_loans.status', 'Disbursed')
                ->where('loan_installments.status', 'paid')
                ->groupBy('loan_installments.LoanId')
                ->havingRaw('COUNT(loan_installments.id) > 0')
                ->get();

            $total_loan_emi_array = $total_loan_emi->keyBy('loan_id')->map(function ($item) {
                return [
                    'total_emi' => $item->total_emi,
                    'received_amount' => $item->received_amount,
                ];
            })->toArray();

            $overdue_loans = $overdue_loans->map(function ($item) use ($total_loan_emi_array) {
                $item->total_emi = $total_loan_emi_array[$item->loan_id]['total_emi'] ?? 0;
                $item->received_amount = $total_loan_emi_array[$item->loan_id]['received_amount'] ?? 0;
                $loandate = $item->loanDate;
                $months = $item->months;
                $loanStartDate = Carbon::parse($loandate);
                $loanEndDate = $loanStartDate->copy()->addMonths($months);
                $item->loan_end_date = $loanEndDate->toDateString();
                return $item;
            });

            foreach ($overdue_loans as $days) {
                $current_date = Carbon::parse($end_date);
                $loan_date = Carbon::parse($days->loanDate);
                $daysDifference = $loan_date->diffInDays($current_date);
                $days->difference = $daysDifference;
            }

            if (!empty($overdue_loans)) {
                return response()->json(['status' => 'success', 'overdue_loans' => $overdue_loans]);
            } else {
                return response()->json(['status' => 'Fail']);
            }
        } else {

            $overdue_loans = DB::table('member_loans')
                ->select(
                    'loan_installments.LoanId as loan_id',
                    'member_accounts.customer_Id as customer_Id',
                    'member_accounts.name as customer_name',
                    DB::raw('COUNT(loan_installments.id) as overdue_emi'),
                    DB::raw('SUM(loan_installments.totalinstallmentamount) as overdue_amount'),

                    DB::raw('MAX(loan_installments.installmentDate) as last_due_date'),
                    'member_loans.loanDate',
                    'member_loans.loanAmount','member_loans.months'
                )
                ->leftJoin('member_accounts', 'member_loans.accountNo', '=', 'member_accounts.customer_Id')
                ->leftJoin('loan_masters', 'member_loans.loanType', '=', 'loan_masters.id')
                ->leftJoin('loan_installments', 'member_loans.id', '=', 'loan_installments.LoanId')
                ->where('member_loans.loanType', $loan_type)
                ->where('member_loans.status', 'Disbursed')
                ->where(function ($query) use ($end_date) {
                    $query->where('loan_installments.installmentDate', '<=', $end_date)
                        ->orWhereNull('loan_installments.installmentDate')
                        ->orWhereDate('member_loans.loanDate', '=', $end_date);
                })
                ->groupBy('member_loans.months','member_loans.loanAmount', 'loan_installments.LoanId', 'member_accounts.customer_Id', 'member_accounts.name', 'member_loans.loanDate')
                ->havingRaw('SUM(loan_installments.totalinstallmentamount) > 0')
                ->havingRaw('COUNT(loan_installments.id) > 0')
                ->where('loan_installments.status', '=', 'false')
                ->orderBy('member_accounts.name', 'ASC')
                ->get();




            //______________Total Emi's Count
            $total_loan_emi = DB::table('member_loans')
                ->select(
                    'loan_installments.LoanId as loan_id',
                    DB::raw('COUNT(loan_installments.LoanId) as total_emi'),
                    DB::raw('SUM(loan_installments.re_amount) as received_amount')
                )
                ->leftJoin('loan_installments', 'member_loans.id', '=', 'loan_installments.LoanId')
                ->where('member_loans.status', 'Disbursed')
                ->where('loan_installments.status', 'paid')
                ->groupBy('loan_installments.LoanId')
                ->havingRaw('COUNT(loan_installments.id) > 0')
                ->get();

                $total_loan_emi_array = $total_loan_emi->keyBy('loan_id')->map(function ($item) {
                    return [
                        'total_emi' => $item->total_emi,
                        'received_amount' => $item->received_amount,
                    ];
                })->toArray();

                $overdue_loans = $overdue_loans->map(function ($item) use ($total_loan_emi_array) {
                    $item->total_emi = $total_loan_emi_array[$item->loan_id]['total_emi'] ?? 0;
                    $item->received_amount = $total_loan_emi_array[$item->loan_id]['received_amount'] ?? 0;
                    $loandate = $item->loanDate;
                    $months = $item->months;
                    $loanStartDate = Carbon::parse($loandate);
                    $loanEndDate = $loanStartDate->copy()->addMonths($months);
                    $item->loan_end_date = $loanEndDate->toDateString();
                    return $item;
                });

                foreach ($overdue_loans as $days) {
                    $current_date = Carbon::parse($end_date);
                    $loan_date = Carbon::parse($days->loanDate);
                    $daysDifference = $loan_date->diffInDays($current_date);
                    $days->difference = $daysDifference;
                }


            if (!empty($overdue_loans)) {
                return response()->json(['status' => 'success', 'overdue_loans' => $overdue_loans]);
            } else {
                return response()->json(['status' => 'Fail']);
            }
        }
    }
}
