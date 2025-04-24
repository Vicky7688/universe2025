<?php

namespace App\Http\Controllers;

use App\Models\commetee;
use App\Models\member_accounts;
use App\Models\commetee_members;
use App\Models\commeti_recoveries;
use App\Models\general_ledgers;
use App\Models\ledger_masters;
use App\Models\commeti_widraw;
use App\Models\CommitteeInstallment;
use Illuminate\Http\Request;
use DB;
use Session;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB as FacadesDB;

class Commeteecontroller extends Controller
{
    public function commetee()
    {
        $pagetitle = "Commetee Master";
        $pageto = url("commetee");
        $formurl = url("commetee");
        $commeteelist = commetee::all();
        $data = compact("formurl", "pagetitle", "pageto", "commeteelist");
        return view("commetee")->with($data);
    }


    public function deleteeditcometee($id, Request $request)
    {
        $categorysid = commetee::where('id', $id)->first();

        if (is_null($categorysid)) {

            return back()->with('fail', 'No Record Found');
        } else {

            DB::beginTransaction();
            try {

                $committe_member = DB::table('commetee_members')->where('comm_id', $categorysid->id)->pluck('id');
                $installments =  DB::table('committee_installments')->whereIn('com_mee_id', $committe_member)->get();

                DB::table('committee_installments')->whereIn('com_mee_id', $committe_member)->delete();
                DB::table('commetee_members')->where('comm_id', $categorysid->id)->delete();
                $categorysid->delete();


                DB::commit();

                return back()->with('success', 'Delete Successfully');
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'status' => 'Fail',
                    'messages' => 'Some Technical Error',
                    'error' => $e->getMessage(),
                    'lines' => $e->getLine(),
                ]);
            }
        }
    }


    public function commeteesubmit(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required',
            'durationtype' => 'required',
            'duration' => 'required|numeric',
            'datefrom' => 'required|date',
            'totalamount' => 'required|numeric',
            'commetiamount' => 'required|numeric',
        ]);



        $commt = new commetee();
        $commt->name = $request->name;
        $commt->durationtype = $request->durationtype;
        $commt->duration = $request->duration;
        $commt->datefrom = Carbon::parse($request->datefrom)->format('Y-m-d');
        $commt->dateto = Carbon::parse($request->datefrom)->addMonths((int)$request->duration)->format('Y-m-d');
        $commt->totalamount = $request->totalamount;
        $commt->commetiamount = $request->commetiamount;
        $commt->save();


        $appled = new ledger_masters();
        $appled->groupCode = 'MEM001';
        $appled->name = $request->name;
        $appled->ledgerCode = 'COM' . $commt->id . rand(10, 99);
        $appled->openingAmount = 0;
        $appled->openingType = 'Cr';
        $appled->status = 'Active';
        $appled->commetiid = $commt->id;
        $appled->is_delete = 'No';
        $appled->save();

        return redirect('cometeerecovery')->with('success', 'Data Inserted Successfully');
    }


    public function addmemcometee($id)
    {
        $pagetitle = "Add Commetee Members";
        $pageto = url("addmemcometee/" . $id);
        $formurl = url("submitaddmemcometee");
        $commeteeid = commetee::find($id);
        $member_accounts = member_accounts::all();
        $data = compact("formurl", "pagetitle", "pageto", "commeteeid", "member_accounts");
        return view("addmemcometee")->with($data);
    }
    public function submitaddmemcometee(Request $request)
    {

        FacadesDB::beginTransaction();
        try {
            commetee_members::where('comm_id', '=', $request->commeteeid)->delete();

            $id = $request->commeteeid;
            $committees = FacadesDB::table('commetee')->where('id', $id)->first();

            $count = 1;
            foreach ($request->customer as $custme) {
                $add = new commetee_members();
                $add->comm_id = $request->commeteeid;
                $add->member_id = $custme;
                $add->save();
                $ids = $add->id;

                $dateFrom = $committees->datefrom;

                for ($i = 0; $i < $committees->duration; $i++) {
                    $installmentDate = (new DateTime($dateFrom))->modify("+$i month")->format('Y-m-d');

                    FacadesDB::table('committee_installments')->insert([
                        'com_mee_id' => $ids,
                        'member_id' => $custme,
                        'committee_id' => $request->commeteeid,
                        'installment_date' => $installmentDate,
                        'amount' => $committees->commetiamount,
                       
                        'intallment_no' => $count,
                        'paid_amount' => null,
                        'recpt_id' => null,
                        'payment_status' => 'Unpaid',
                    ]);

                    $count++;
                }
            }

            FacadesDB::commit();

            return redirect('commetee')->with('success', 'Data Inserted Successfully');
        } catch (\Exception $e) {
            FacadesDB::rollBack();
            return response()->json([
                'status' => 'Fail',
                'messages' => 'Some Technical Issue',
                'error' => $e->getMessage(),
                'lines' => $e->getLine()
            ]);
        }
    }

    public function widrawcometeerecovery(Request $request)
    {

        $pagetitle = "Widraw Comettee";
        $pageto = url("widrawcometeerecovery");
        $formurl = url("recoverycometeemembers");
        $commetee = commetee::all();
        $data = compact("formurl", "pagetitle", "pageto", "commetee");
        return view("widrawcometeerecovery")->with($data);
    }

    public function getmemberstotal(Request $request)
    {


        $comati = commetee::find($request->id);

        $widrawtable = commeti_widraw::where('commeti_widraw.comm_id', '=', $request->id)
            ->join('member_accounts', 'commeti_widraw.member_id', '=', 'member_accounts.id')
            ->select('commeti_widraw.*', 'member_accounts.name', 'member_accounts.customer_Id')
            ->get();
        $commeti_recoverie = commeti_recoveries::where('cometee', '=', $request->id)->sum('amount');
        $commeti_widraw = commeti_widraw::where('comm_id', '=', $request->id)->sum('amount');

        $commeti_recoveries = $commeti_recoverie - $commeti_widraw;
        $memberIds = commetee_members::where('comm_id', '=', $request->id)->pluck('member_id');
        $members = member_accounts::whereIn('id', $memberIds)->pluck('name', 'id');

        return response()->json(['status' => 'success', 'comati' => $comati, 'commeti_recoveries' => $commeti_recoveries, 'members' => $members, 'widrawtable' => $widrawtable]);
    }



    public function getmembersforwidrawl(Request $request)
    {

        $memberIds = commetee_members::where('comm_id', '=', $request->idd)->pluck('member_id');
        $memberIdsww = commeti_widraw::where('comm_id', '=', $request->idd)->pluck('member_id');
        $members = member_accounts::whereIn('id', $memberIds)
            ->whereNotIn('id', $memberIdsww)
            ->where('name', 'like', '%' . $request->id . '%')
            ->orwhere('customer_Id', 'like', '%' . $request->id . '%')
            ->get();
        return response()->json(['status' => 'success', 'members' => $members]);
    }

    public function widrawlcometeee(Request $request)
    {

        // $comati=commetee::find($request->cometee);
        // $commeti_recoveries=commeti_recoveries::where('cometee','=',$request->cometee)->sum('amount');

        $commeti_recoverie = commeti_recoveries::where('cometee', '=', $request->cometee)->sum('amount');
        $commeti_widraw = commeti_widraw::where('comm_id', '=', $request->cometee)->sum('amount');
        $commeti_recoveries = $commeti_recoverie - $commeti_widraw;
        if ($request->amount > $commeti_recoveries) {

            return response()->json(['success' => false, 'message' => 'Total amount exceeds the allowed limit  ']);
        }
        $paymentdate = date('Y-m-d', strtotime($request->date));

        $widrawl = new commeti_widraw();
        $widrawl->comm_id = $request->cometee;
        $widrawl->member_id = $request->member_id;
        $widrawl->amount = $request->amount;
        $widrawl->paymentdate = $paymentdate;
        $widrawl->save();


        $ledhai = ledger_masters::where('commetiid', '=', $request->cometee)->first();
        $accountNo = member_accounts::where('id', '=', $request->member_id)->first();

        $this->createLedgerEntry(0, $accountNo->customer_Id, 'C002', 'CAS001', 'Widrawcometee', $paymentdate, 'Cr', $request->amount, $ledhai->name, 0, 0, $widrawl->id);
        $this->createLedgerEntry(0, $accountNo->customer_Id, $ledhai->groupCode, $ledhai->ledgerCode, $accountNo->name, $paymentdate, 'Dr', $request->amount, $accountNo->name, 0, 0, $widrawl->id);




        return response()->json(['success' => true, 'message' => 'Data Inserted Successfully']);
    }




    public function cometeerecovery(Request $request)
    {
        $pagetitle = "Comettee Collection";
        $pageto = url("cometeerecovery");
        $formurl = url("recoverycometeemembers");
        $commetee = commetee::all();
        $data = compact("formurl", "pagetitle", "pageto", "commetee");
        return view("cometeerecovery")->with($data);
    }





    public function pluginsajax(Request $request)
    {

        if (!empty($request->name)) {
            $plugins = article::join('tags', 'tags.id', '=', 'article.user_tags')
                ->where('article.category', '=', "24")
                ->where('article.title', 'LIKE', "%$request->name%")
                ->orWhere('tags.tagsname', 'LIKE', "%$request->name%")
                ->select('article.*')
                ->get();
        } else {
            $plugins = article::where('category', '=', '24')->orderby('sort')->get();
        }
        $view = view('front.inc.pluginsajax', compact('plugins'))->render();
        return response()->json(['html' => $view]);
    }


    public function getcometeemembers(Request $request)
    {



        $comatidate = date('Y-m-d', strtotime($request->dat));

        $comati = commetee::find($request->id);

        $cometlist = DB::table('commetee_members')
            ->join('member_accounts', 'commetee_members.member_id', '=', 'member_accounts.id')
            ->where('commetee_members.comm_id', '=', $request->id)
            ->get();


        $view = view('memofcometi', compact('cometlist', 'comati', 'comatidate'))->render();
        return response()->json(['html' => $view]);

        /* $cometlist = DB::table('commetee_members')
        ->join('member_accounts', 'commetee_members.member_id', '=', 'member_accounts.id')
        ->leftJoin('commeti_recoveries', function($join) {
            $join->on('commeti_recoveries.cometee', '=', 'commetee_members.comm_id')
                 ->on('commeti_recoveries.memberid', '=', 'member_accounts.id');
        })
        ->where('commetee_members.comm_id', '=', $request->id)
        ->select(
            'commetee_members.id as cometeid',
            'member_accounts.customer_Id as customer_Id',
            'member_accounts.name as name',
            'member_accounts.id as memberid',
            'commeti_recoveries.id as paymentdate',
            DB::raw("
                CASE
                    WHEN MONTH(commeti_recoveries.paymentdate) = MONTH(?)
                         AND YEAR(commeti_recoveries.paymentdate) = YEAR(?)
                    THEN 'paid'
                    ELSE 'unpaid'
                END as payment_status
            "),
            DB::raw("
                CASE
                    WHEN MONTH(commeti_recoveries.paymentdate) = MONTH(?)
                         AND YEAR(commeti_recoveries.paymentdate) = YEAR(?)
                    THEN commeti_recoveries.amount
                    ELSE NULL
                END as paidamount
            ")
        )
        ->addBinding($comatidate, 'select')
        ->addBinding($comatidate, 'select')
        ->addBinding($comatidate, 'select')
        ->addBinding($comatidate, 'select')
        ->get(); */





        // $cometlist = DB::table('commetee_members')
        // ->join('member_accounts', 'commetee_members.member_id', '=', 'member_accounts.id')
        // ->leftJoin('commeti_recoveries', function($join) {
        //     $join->on('commeti_recoveries.cometee', '=', 'commetee_members.comm_id')
        //          ->on('commeti_recoveries.memberid', '=', 'member_accounts.id');
        // })
        // ->where('commetee_members.comm_id', '=', $request->id)
        // ->select(
        //     'commetee_members.id as cometeid',
        //     'member_accounts.customer_Id as customer_Id',
        //     'member_accounts.name as name',
        //     'member_accounts.id as memberid',
        //     DB::raw("CASE
        //                     WHEN MONTH(commeti_recoveries.paymentdate) = MONTH(CAST(? AS DATE))
        //                         AND YEAR(commeti_recoveries.paymentdate) = YEAR(CAST(? AS DATE))
        //                         AND commeti_recoveries.paymentdate = CAST(? AS DATE)
        //                     THEN 'paid'
        //                     ELSE 'unpaid'
        //                 END as payment_status", [$comatidate, $comatidate, $comatidate]),
        //         DB::raw("CASE
        //                     WHEN MONTH(commeti_recoveries.paymentdate) = MONTH(CAST(? AS DATE))
        //                         AND YEAR(commeti_recoveries.paymentdate) = YEAR(CAST(? AS DATE))
        //                         AND commeti_recoveries.paymentdate = CAST(? AS DATE)
        //                     THEN commeti_recoveries.amount
        //                     ELSE NULL
        //                 END as paidamount", [$comatidate, $comatidate, $comatidate])
        // )
        // ->get();


        return response()->json(['status' => 'success', 'comati' => $comati, 'cometlist' => $cometlist]);
    }


    public function deleterecoverycometti(Request $request)
    {
        $id = $request->id;
        $recoveries = commeti_recoveries::where('id', $id)->first();
        if (!empty($recoveries)) {
            DB::beginTransaction();
            try {

                CommitteeInstallment::where('recpt_id', $recoveries->id)->update([
                    'payment_date' => null,
                    'paid_amount' => null,
                    'recpt_id' => null,
                    'payment_status' => 'Unpaid'
                ]);

                commeti_recoveries::find($request->id)->delete();

                DB::commit();
                return response()->json(['status' => 'success']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'status' => 'Fail',
                    'messages' => 'Some Thing Went Wrong',
                    'error' => $e->getMessage(),
                    'lines' => $e->getLine()
                ]);
            }
        } else {
            return response()->json(['status' => 'Fail', 'messages' => 'Record Not Found']);
        }
    }


    public function recoverycometeemembers(Request $request)
    {
        if (!empty($request->memberid)) {
            if (sizeof($request->memberid) > 0) {
                foreach ($request->memberid as $key => $memberId) {
                    $paymentDate = date('Y-m-d', strtotime($request->paymentdate[$key]));
                    $amount = isset($request->amount[$key]) ? $request->amount[$key] : null;

                    $paymentYearMonth = date('Y-m', strtotime($paymentDate));
                    $cometee = commetee::find($request->cometee);

                    if (!$cometee) {
                        return response()->json(['success' => false, 'message' => 'Cometee not found']);
                    }

                    if ($paymentDate < $cometee->datefrom || $paymentDate > $cometee->dateto) {
                        return response()->json(['success' => false, 'message' => 'Payment date must be between the start and end date of the cometee']);
                    }

                    $totalAmountPaid = commeti_recoveries::where('cometee', $request->cometee)
                        ->where('memberid', $memberId)
                        ->sum('amount');

                    if (($totalAmountPaid + $amount) > $cometee->totalamount) {
                        return response()->json(['success' => false, 'message' => 'Total amount exceeds the allowed limit for the cometee']);
                    }

                    $paymentYearMonth = date('Y-m', strtotime($paymentDate));
                    $existingRecord = commeti_recoveries::where('cometee', $request->cometee)
                        ->where('memberid', $memberId)
                        ->whereRaw('DATE_FORMAT(paymentdate, "%Y-%m") = ?', [$paymentYearMonth])
                        ->first();

                    DB::beginTransaction(); // Start transaction

                    try {
                        if ($existingRecord) {

                            // Fetch previous installments linked to the receipt ID
                            $existingInstallments = CommitteeInstallment::where('recpt_id', $existingRecord->id)->get();

                            // Reset all previously linked installments to 'Unpaid'
                            foreach ($existingInstallments as $row) {
                                $row->payment_date = null;
                                $row->paid_amount = null;
                                $row->recpt_id = null;
                                $row->payment_status = 'Unpaid';
                                $row->save(); // Save the changes
                            }

                            // Calculate the number of installments to update
                            $installmentAmount = $cometee->commetiamount; // Amount per installment
                            $installmentCount = floor($amount / $installmentAmount); // How many full installments the payment covers

                            // Fetch unpaid installments for the member
                            $unpaidInstallments = CommitteeInstallment::where([
                                'committee_id' => $request->cometee,
                                'member_id' => $memberId,
                                'payment_status' => 'Unpaid',
                            ])
                                ->orderBy('id') // Ensure installments are processed sequentially
                                ->get();

                                $installmentsUpdated = 0;
                                foreach ($unpaidInstallments as $installment) {
                                    if ($installmentsUpdated < $installmentCount) {
                                        $installment->payment_date = $paymentDate;
                                        $installment->paid_amount = $installmentAmount;
                                        $installment->payment_status = 'Paid';
                                        $installment->recpt_id = $existingRecord->id;
                                        $installment->save(); // Save the changes
                                        $installmentsUpdated++;
                                    } else {
                                        break; // Exit loop once the required installments are updated
                                    }
                                }

                            // Update the recovery record with the latest payment information
                            $existingRecord->amount = $amount;
                            $existingRecord->paymentdate = $paymentDate;
                            $existingRecord->save();

                            // Delete old ledger entries
                            general_ledgers::where('commetiid', '=', $existingRecord->id)->delete();

                            // Create new ledger entries
                            $ledhai = ledger_masters::where('commetiid', '=', $request->cometee)->first();
                            $accountNo = member_accounts::where('id', '=', $memberId)->first();

                            $this->createLedgerEntry(0, $accountNo->customer_Id, 'C002', 'CAS001', 'cometee', $paymentDate, 'Dr', $amount, $ledhai->name, 0, $existingRecord->id, 0);
                            $this->createLedgerEntry(0, $accountNo->customer_Id, $ledhai->groupCode, $ledhai->ledgerCode, $accountNo->name, $paymentDate, 'Cr', $amount, $accountNo->name, 0, $existingRecord->id, 0);


                        } else {
                            $addcomm = new commeti_recoveries();
                            $addcomm->cometee = $request->cometee;
                            $addcomm->paymentdate = $paymentDate;
                            $addcomm->amount = $amount;
                            $addcomm->memberid = $memberId;
                            $addcomm->save();

                            $ledhai = ledger_masters::where('commetiid', '=', $request->cometee)->first();
                            $accountNo = member_accounts::where('id', '=', $memberId)->first();

                            $this->createLedgerEntry(0, $accountNo->customer_Id, 'C002', 'CAS001', 'cometee', $paymentDate, 'Dr', $amount, $ledhai->name, 0, $addcomm->id, 0);
                            $this->createLedgerEntry(0, $accountNo->customer_Id, $ledhai->groupCode, $ledhai->ledgerCode, $accountNo->name, $paymentDate, 'Cr', $amount, $accountNo->name, 0, $addcomm->id, 0);


                            $installments = CommitteeInstallment::where([
                                'committee_id' => $request->cometee,
                                'member_id' => $memberId,
                                'payment_status' => 'Unpaid',
                                'payment_date' => null
                            ])->get();

                            $paidinstallments = $installments->sum('amount');

                            $installmentAmount = $cometee->commetiamount;
                            $installmentCount = floor($amount / $installmentAmount);

                            $installmentsUpdated = 0;
                            foreach ($installments as $installment) {
                                if ($installmentsUpdated < $installmentCount) {
                                    $installment->payment_date = $paymentDate;
                                    $installment->paid_amount = $installmentAmount;
                                    $installment->payment_status = 'Paid';
                                    $installment->recpt_id = $addcomm->id;
                                    $installment->save();
                                    $installmentsUpdated++;
                                } else {
                                    break;
                                }
                            }
                        }

                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'Fail',
                            'messages' => 'Some Technical Issue',
                            'error' => $e->getMessage(),
                            'line' => $e->getLine(),
                        ]);
                    }
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Data Inserted Successfully']);
    }



    // Helper function to create ledger entries
    protected function createLedgerEntry($loanId, $accountNo, $groupCode, $ledgerCode, $formName, $transactionDate, $transactionType, $transactionAmount, $narration, $loanRecoveryidis, $commetiid, $widrawid)
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
        $ledger->refid = 'cometee';
        $ledger->loanRecoveryidis = $loanRecoveryidis;
        $ledger->commetiid = $commetiid;
        $ledger->widrawid = $widrawid;
        $ledger->agentId = Session::get('adminloginid');
        $ledger->updatedBy = Session::get('adminloginid');
        $ledger->updatedbytype = Session::get('user_type');
        $ledger->sessionId = Session::get('sessionof');
        $ledger->save();
    }
}
