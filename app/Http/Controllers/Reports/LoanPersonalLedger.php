<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LoanPersonalLedger extends Controller
{
    public function loanPersonalLedgers(){
        $pagetitle = "Perosnal Ledger";
        $pageto = url('loanpersonalledgers');
        $formurl = url('loanpersonalledgers');
        $fyears = DB::table('yearly_session')->get();
        $data=compact('pagetitle','pageto','formurl');
        return view('Reports.personalledger',$data);
    }


    //___________Get Customer Loan Account Number
    public function getCustomerLoanAccount(Request $post){
        $customer_no = $post->account_no;
        if(!empty($customer_no)){
            $account_no = DB::table('member_loans')
                ->where('accountNo',$customer_no)
                ->get();


            return response()->json([
                'status' => 'success',
                'account_numbers' => $account_no
            ]);
        }else{
            return response()->json([
                'status' => 'Fail',
                'messages' => 'Record Not Found'
            ]);
        }

    }


    //__________Get Person Loan Details
    public function getLoanDetails(Request $post){
        $start_date = date('Y-m-d',strtotime($post->datefrom));
        $end_date = date('Y-m-d',strtotime($post->dateto));
        $customer_no = $post->account_no;
        $loan_id = $post->loan_no;

        $loan_details = DB::table('member_loans')
            ->select(
                'member_loans.*','loan_recoveries.*',
                )
            ->leftJoin('loan_recoveries','loan_recoveries.loanId','=','member_loans.id')
            ->where('member_loans.accountNo','=',$customer_no)
            ->where('member_loans.id','=',$loan_id)
            ->whereDate('member_loans.loanDate','>=',$start_date)
            ->whereDate('member_loans.loanDate','<=',$end_date)
            ->get()
            ->groupBy('member_loans.name');
            // dd($loan_details);

        if($loan_details){
            return response()->json([
                'status' => 'success',
                'loan_details' => $loan_details
            ]);
        }else{
            return response()->json([
                'status' => 'Fail',
                'messages' => 'Record Not Found'
            ]);
        }
    }
}
