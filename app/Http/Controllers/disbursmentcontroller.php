<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\member_loans;
use DB;
class disbursmentcontroller extends Controller
{
    public function disbursment(){


        $pagetitle = "Disbursment list";
        $pageto = url('disbursment');
       $formurl = url('disbursment');

       $advancements = DB::table('member_loans')
       ->join('loan_masters', 'loan_masters.id', '=', 'member_loans.loanType')
       ->join('agent_masters', 'agent_masters.id', '=', 'member_loans.agentId')
       ->select(
           'member_loans.*',
           'loan_masters.loanname',
           'agent_masters.name as agent_name',
           DB::raw('COALESCE((SELECT SUM(principal) FROM loan_recoveries WHERE loan_recoveries.loanId = member_loans.id), 0) as total_recovered')
       )
       ->orderBy('member_loans.loanDate', 'DESC') // Order by loanDate in ascending order
       ->get();
        $data=compact('advancements','pagetitle','pageto','formurl');
        return view('disbursment')->with($data);
    }
}
