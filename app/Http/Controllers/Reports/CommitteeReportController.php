<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CommitteeReportController extends Controller
{
    public function committeereportIndex(){
        $pagetitle = "Committee Report";
        $pageto = url('committeereportIndex');
        $allCommittees = DB::table('commetee')->orderBy('id','ASC')->get();
        $data['pagetitle'] = $pagetitle;
        $data['pageto'] = $pageto;
        $data['allCommittees'] = $allCommittees;
        return view('Reports.committereport',$data);
    }

    public function getcommittes(Request $post){
        $rules = [
            "startDate" => "required",
            "endDate" => "required",
            "status" => "required",
            "committeetype" => "required"
        ];

        $validator = Validator::make($post->all(),$rules);
        if($validator->fails()){
            return response()->json(['status' => 'Fail','error' => $validator->errors()]);
        }
        $startDate = date('Y-m-d', strtotime($post->startDate));
        $endDate = date('Y-m-d', strtotime($post->endDate));
        $status = $post->status;
        $committeetype = $post->committeetype;

        if ($committeetype === 'All') {
            $query = DB::table('committee_installments')
                ->select(
                    'member_accounts.id as mid',
                    'member_accounts.name',
                    'member_accounts.customer_Id',
                    'committee_installments.payment_status',
                    'commetee.id as cmid',
                    'commetee.name as cmname',
                    DB::raw('SUM(CASE WHEN committee_installments.amount IS NOT NULL THEN committee_installments.amount ELSE 0 END) as total_paid_amount'),
                    DB::raw('COUNT(committee_installments.id) as total_installments')
                )
                ->leftJoin('member_accounts', 'member_accounts.id', '=', 'committee_installments.member_id')
                ->leftJoin('commetee', 'commetee.id', '=', 'committee_installments.committee_id')
                ->whereDate('installment_date', '>=', $startDate)
                ->whereDate('installment_date', '<=', $endDate)
                ->where('payment_status', '=', $status)
                ->groupBy(
                    'committee_installments.member_id',
                    'member_accounts.id',
                    'member_accounts.name',
                    'member_accounts.customer_Id',
                    'committee_installments.payment_status',
                    'commetee.id',
                    'commetee.name',
                )
                ->get();


            return response()->json(['status' => 'success', 'alldata' => $query]);

        } else {
            $query = DB::table('committee_installments')
            ->select(
                'member_accounts.id as mid',
                'member_accounts.name',
                'member_accounts.customer_Id',
                'committee_installments.payment_status',
                'commetee.id as cmid',
                'commetee.name as cmname',
                DB::raw('SUM(CASE WHEN committee_installments.amount IS NOT NULL THEN committee_installments.amount ELSE 0 END) as total_paid_amount'),
                DB::raw('COUNT(committee_installments.id) as total_installments')
            )
            ->leftJoin('member_accounts', 'member_accounts.id', '=', 'committee_installments.member_id')
            ->leftJoin('commetee', 'commetee.id', '=', 'committee_installments.committee_id')
            ->whereDate('installment_date', '>=', $startDate)
            ->whereDate('installment_date', '<=', $endDate)
            ->where('payment_status', '=', $status)
            ->where('committee_id', $committeetype)
            ->groupBy(
                'committee_installments.member_id',
                'member_accounts.id',
                'member_accounts.name',
                'member_accounts.customer_Id',
                'committee_installments.payment_status',
                'commetee.id',
                'commetee.name',
            )
            ->get();


            return response()->json(['status' => 'success', 'alldata' => $query]);
        }

    }
}
