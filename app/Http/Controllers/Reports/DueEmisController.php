<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DueEmisController extends Controller
{
    public function dueEmiReport()
    {
        $pagetitle = "Pending Emi's Report";
        $pageto = url('due-emi-report');
        $data['pagetitle'] = $pagetitle;
        $data['pageto'] = $pageto;
        return view('Reports.emiduereport', $data);
    }


    public function EmiReport()
    {
        $pagetitle = "Pending Emi's Report";
        $pageto = url('due-emi-report');
        $data['pagetitle'] = $pagetitle;
        $data['pageto'] = $pageto;
        return view('Reports.emireport', $data);
    }


    public function GetPendingEmi(Request $post)
    {
        $date = date('Y-m-d', strtotime($post->date));
        $installments_number = $post->newmonth;

        // $emi_type = $post->emi_type;
        // $report_type = $post->report_type;
        // dd($installments_number);



        #________________Pending Email Details
        $emis_details = DB::table('loan_installments')
            ->select(
                'loan_installments.LoanId',
                DB::raw('COUNT(*) AS installment_count'),
                DB::raw('SUM(loan_installments.principal) as principal'),
                DB::raw('SUM(loan_installments.interest) as interest'),
                DB::raw('SUM(loan_installments.totalinstallmentamount) as totalinstallmentamount'),
                'member_loans.id',
                'member_loans.accountNo',
                'member_loans.name','member_loans.loanEndDate',
            )
            ->join('member_loans', 'member_loans.id', '=', 'loan_installments.LoanId')
            ->where('installmentDate', '<=', $date)
            ->where('loan_installments.status', '=', 'false')
            ->groupBy('loan_installments.LoanId', 'member_loans.id', 'member_loans.accountNo', 'member_loans.name','member_loans.loanEndDate',)
            ->having('installment_count', '=', $installments_number)
            ->get();

        if (!empty($emis_details)) {
            return response()->json(['status' => 'success', 'emis_details' => $emis_details]);
        } else {
            return response()->json(['status' => 'Fail', 'messages' => 'No Record Found']);
        }


        //_________________________________Old work ____________________________


        // $dates = Carbon::createFromFormat('d-m-Y', $post->date);
        // $threeMonthsBack = $dates->subMonths($post->months);
        // $newdate = date('Y-m-d',strtotime($threeMonthsBack));


        //     if($emi_type === 'TillDate'){
        //         if($report_type === 'Details'){
        //             $emis_details = DB::table('loan_installments')
        //     ->select(
        //         DB::raw('COUNT(*) AS installment_count'),
        //         'member_accounts.customer_Id',
        //         'loan_masters.id as loan_type',
        //         'member_loans.id as loan_id',
        //         'member_accounts.name',
        //         'member_loans.loanDate',
        //         DB::raw('SUM(loan_installments.totalinstallmentamount) as totalinstallmentamount'), // Use SUM for total amount
        //         'loan_installments.LoanId', // Include specific loan_installments fields
        //         'loan_installments.installmentDate', // Example of specific fields needed
        //         'loan_installments.status'
        //     )
        // ->join('member_loans', 'loan_installments.LoanId', '=', 'member_loans.id')
        // ->join('loan_masters', 'member_loans.loanType', '=', 'loan_masters.id')
        // ->join('member_accounts', 'member_loans.accountNo', '=', 'member_accounts.customer_Id')
        // ->where('installmentDate', '<=', $date)
        // ->where('loan_installments.status', 'false')
        // ->groupBy(
        //     'loan_installments.LoanId',
        //     'member_accounts.customer_Id',
        //     'loan_masters.id',
        //     'member_loans.id',
        //     'member_accounts.name',
        //     'member_loans.loanDate',
        //     'loan_installments.installmentDate', // Add this to group by as it's selected
        //     'loan_installments.status' // Add this to group by as it's selected
        // )
        // ->having('installment_count', '=', $installments_number)
        // ->get();


        //             dd($emis_details);

        //         }else{
        //             $emis_details = DB::table('loan_installments')
        //                     ->select('loan_installments.LoanId',
        //                         DB::raw('COUNT(*) AS installment_count'),
        //                         DB::raw('SUM(loan_installments.totalinstallmentamount) as totalinstallmentamount')
        //                     )
        //                     ->where('installmentDate', '<=', $date)
        //                     ->where('loan_installments.status', 'false')
        //                     ->groupBy('loan_installments.LoanId')
        //                     ->having('installment_count', '=', $installments_number)
        //                     ->get();
        //             dd($emis_details);
        //         }

        //     }else{

        //         if($report_type === 'Details'){
        //             $emis_details = DB::table('loan_installments')
        //                 ->where('installmentDate','=',$date)
        //                 ->where('status','false')
        //                 ->get();
        //         }else{
        //             $emis_details = DB::table('loan_installments')
        //                 ->where('installmentDate','=',$date)
        //                 ->where('status','false')
        //                 ->get();
        //         }

        //     }

        //___________________________________________________________________________________

    }



    public function GetEmi(Request $post){
        $startdate = date('Y-m-d', strtotime($post->startdate));
        $date = date('Y-m-d', strtotime($post->date));
        $installments_number = $post->newmonth;
        $status = $post->status;
        $report_type = $post->report_type;

        // $emi_type = $post->emi_type;
        // $report_type = $post->report_type;
        // dd($installments_number);

        if (empty($installments_number)) {

            if ($report_type == 'detail') {

                $emis_details = DB::table('loan_installments')
                    ->join('member_loans', 'member_loans.id', '=', 'loan_installments.LoanId')
                    ->join('loan_masters', 'loan_masters.id', '=', 'member_loans.loanType')
                    ->join('member_accounts', 'member_accounts.customer_Id', '=', 'member_loans.accountNo')
                    ->where('loan_installments.installmentDate', '>=', $startdate)
                    ->where('loan_installments.installmentDate', '<=', $date)
                    ->when(!empty($status), function ($query) use ($status) {
                        return $query->where('loan_installments.status', '=', $status);
                    })
                    ->select(
                        'member_accounts.customer_Id',
                        'member_accounts.name',
                        'loan_installments.installmentDate',
                        'loan_masters.id as loanid',
                        'loan_masters.loanname',
                        'loan_installments.totalinstallmentamount',
                        'loan_installments.status',
                        'member_loans.loanEndDate',
                        'member_loans.loanDate',
                        DB::raw('1 as pending_installments')
                    )
                    ->orderby('member_accounts.name','ASC')
                    ->get();
            } else {

                $emis_details = DB::table('loan_installments')
                ->join('member_loans', 'member_loans.id', '=', 'loan_installments.LoanId')
                ->leftJoin(DB::raw("(SELECT LoanId, SUM(receivedAmount) as total_received FROM loan_recoveries GROUP BY LoanId) as lr"), function ($join) {
                    $join->on('member_loans.id', '=', 'lr.LoanId');
                })
                ->join('loan_masters', 'loan_masters.id', '=', 'member_loans.loanType')
                ->join('member_accounts', 'member_accounts.customer_Id', '=', 'member_loans.accountNo')
                ->whereBetween('loan_installments.installmentDate', [$startdate, $date])
                ->when(!empty($status), function ($query) use ($status) {
                    return $query->where('loan_installments.status', '=', $status);
                })
                ->select(
                    'member_accounts.customer_Id',
                    'member_accounts.name',
                    'member_loans.loanEndDate',
                    'member_loans.loanDate',
                    DB::raw('MAX(loan_installments.installmentDate) as installmentDate'),
                    'loan_masters.id as loanid',
                    'loan_masters.loanname',
                    DB::raw('SUM(loan_installments.totalinstallmentamount) as totalinstallmentamount'),
                    'loan_installments.status',
                    DB::raw('COUNT(CASE WHEN loan_installments.status = "false" THEN 1 END) as pending_installments'),
                    'loan_installments.principal',
                    'loan_installments.interest',
                    DB::raw('COALESCE(lr.total_received, 0) as recoveries'),
                    'member_loans.loanInterest',
                    'member_loans.months',
                    'member_loans.loanAmount'
                )
                ->groupBy(
                    'member_accounts.customer_Id',
                    'member_accounts.name',
                    'loan_masters.id',
                    'loan_masters.loanname',
                    'loan_installments.status',
                    'member_loans.loanEndDate',
                    'loan_installments.principal',
                    'loan_installments.interest',
                    'member_loans.loanDate',
                    'lr.total_received',
                    'member_loans.loanInterest',
                    'member_loans.months',
                    'member_loans.loanAmount'
                )
                ->orderBy('member_accounts.name', 'ASC')
                ->get();






                // dd($emis_details);
            }
        } else {


            $emis_details = DB::table('loan_installments')
                ->select(
                    'loan_installments.LoanId',
                    DB::raw('COUNT(*) AS installment_count'),
                    DB::raw('SUM(loan_installments.principal) as principal'),
                    DB::raw('SUM(loan_installments.interest) as interest'),
                    DB::raw('SUM(loan_installments.totalinstallmentamount) as totalinstallmentamount'),
                    'member_loans.id',
                    'member_loans.accountNo',
                    'member_loans.name','member_loans.loanEndDate',
                )
                ->join('member_loans', 'member_loans.id', '=', 'loan_installments.LoanId')
                ->where('installmentDate', '<=', $date)
                ->where('loan_installments.status', '=', 'false')
                ->groupBy('loan_installments.LoanId', 'member_loans.id', 'member_loans.accountNo', 'member_loans.name','member_loans.loanEndDate',)
                ->having('installment_count', '=', $installments_number)
                ->get();
        }

        if (!empty($emis_details)) {
            return response()->json(['status' => 'success', 'emis_details' => $emis_details]);
        } else {
            return response()->json(['status' => 'Fail', 'messages' => 'No Record Found']);
        }
    }
}
