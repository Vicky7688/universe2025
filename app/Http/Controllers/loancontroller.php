<?php

namespace App\Http\Controllers;

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
use Illuminate\Http\Request;
use App\Models\group_masters;
use Toastr;
use DB;
use Session;
use Carbon\Carbon;
use DateTime;

class loancontroller extends Controller
{


    /// monthly 12
    ///daily 365
    /// weekly 52


    public function confirmDeletere(Request $request)
    {
        $reci = loan_recoveries::find($request->id);

        $closeloanopen = member_loans::find($reci->loanId);
        $closeloanopen->status = 'Disbursed';
        $closeloanopen->save();
        loan_recoveries::find($request->id)->delete();
        $krupdate = loan_installments::where('loanRecoveryidis', '=', $request->id)->get();
        if (sizeof($krupdate) > 0) {
            foreach ($krupdate as $krupdatelist) {
                $krupdateddd = loan_installments::find($krupdatelist->id);
                $krupdateddd->status = 'false';
                $krupdateddd->paid_date = Null;
                $krupdateddd->loanRecoveryidis = Null;
                $krupdateddd->save();
            }
        }
        general_ledgers::where('loanRecoveryidis', '=', $request->id)->delete();
    }

    public function takerecovery(Request $request)
    {

        DB::beginTransaction();

        try {
            if (!empty($request->recoveryid)) {

                $loanRecoveryidis = $request->recoveryid;
                $cdatee = date("Y-m-d", strtotime($request->currentdate));
                $totalPayment = floatval($request->totalpaneltywithinstallment);
                $paidInstallmentIds = [];
                $principle = $request->totalinstallment - ($request->totalpanelty + $request->totalintrest);


                $id = $request->thisid;
                $checkloanid = DB::table('member_loans')->where('id', $id)->first();
                $checkdate = $checkloanid->loanDate;




                $closeloanopen = member_loans::find($id);
                $closeloanopen->status = 'Disbursed';
                $closeloanopen->save();


                //__________Check if the loan date is greater than the account opening date
                if ($cdatee >= $checkdate) {
                    $newdate = $cdatee;
                } else {
                    Toastr::error('Loan date must be after the account opening date', 'Error', ["positionClass" => "toast-top-center"]);
                    return back(); // Stop further execution if condition fails
                }

                $loanRecovery = loan_recoveries::find($loanRecoveryidis);
                $loanRecovery->receiptDate = $newdate;
                $loanRecovery->loanId = $request->thisid;
                $loanRecovery->principal = $principle;
                $loanRecovery->principalround = round($principle);
                $loanRecovery->interest = $request->totalintrest;
                $loanRecovery->penalInterest = $request->totalpanelty;
                $loanRecovery->total = $request->totalinstallment;
                $loanRecovery->receivedAmount = $request->totalpaneltywithinstallment;
                $loanRecovery->status = 'True';
                $loanRecovery->agentId = Session::get('adminloginid');
                $loanRecovery->updatedBy = Session::get('adminloginid');
                $loanRecovery->updatedbytype = Session::get('user_type');
                $loanRecovery->sessionId = Session::get('sessionof');
                $loanRecovery->save();



                $alllon = loan_installments::where('loanRecoveryidis', '=', $loanRecoveryidis)->get();

                foreach ($alllon as $alllonkkk) {
                    $krupdate = loan_installments::find($alllonkkk->id);
                    $krupdate->status = 'false';
                    $krupdate->loanRecoveryidis = Null;
                    $krupdate->save();
                }

                DB::transaction(function () use ($request, $cdatee, $loanRecoveryidis, &$totalPayment, &$paidInstallmentIds) {
                    // $installments = loan_installments::where('LoanId', $request->thisid)
                    //     ->where('installmentDate', '<=', $cdatee)
                    //     ->where('status', '!=', 'paid')
                    //     ->orderBy('installmentDate', 'asc')
                    //     ->get();

                    // foreach ($installments as $installment) {
                    //     $installmentAmount = floatval($installment->principal);
                    //     $paid_date = date("Y-m-d", strtotime($cdatee));
                    //     if ($totalPayment >= $installmentAmount) {
                    //         $installment->update([
                    //             'paid_date' => $paid_date,
                    //             'status' => 'paid',
                    //             'loanRecoveryidis' => $loanRecoveryidis,
                    //         ]);
                    //         $paidInstallmentIds[] = $installment->id;


                    //         $totalPayment -= $installmentAmount;
                    //     } else {

                    //         break;
                    //     }
                    // }
                    //______________Loan Recoveries Sum of Loan Amount
                    $loanid = $request->thisid;
                    $totalPayment = DB::table('loan_recoveries')->where('loanId', $loanid)->sum('principal');

                    //______________Update Installment Status Paid to False
                    DB::table('loan_installments')
                        ->where('LoanId', $loanid)
                        ->update(['status' => 'false']);

                    //_____________Check Installment False Status
                    $check_installment = DB::table('loan_installments')
                        ->where('LoanId', $loanid)
                        ->where('status', 'false')
                        ->orderBy('installmentDate', 'asc')
                        ->get();

                    foreach ($check_installment as $inst) {
                        $installmentAmount = $inst->principal;
                        $paid_date = date("Y-m-d", strtotime($cdatee));
                        if ($totalPayment >= $installmentAmount) {
                            DB::table('loan_installments')
                                ->where('id', $inst->id)
                                ->update([
                                    'paid_date' => $paid_date,
                                    'status' => 'paid',
                                    're_amount' => $request->totalpaneltywithinstallment,
                                    'loanRecoveryidis' => $loanRecoveryidis,
                                ]);

                            $totalPayment -= $installmentAmount;
                        } else {
                            DB::table('loan_installments')
                                ->where('id', $inst->id)
                                ->update(['status' => 'false']);
                        }
                    }

                    $loanRecoveryww = loan_recoveries::find($loanRecoveryidis);
                    $loanRecoveryww->instaId = implode(",", $paidInstallmentIds);
                    $loanRecoveryww->save();
                    general_ledgers::where('loanRecoveryidis', '=', $loanRecoveryidis)->delete();
                });

                $member_loans = member_loans::find($request->thisid);
                $ledhai = ledger_masters::where('loan', '=', $member_loans->loanType)->first();
                $formname = $member_loans->name;
                $accountNo = $member_loans->accountNo;

                // $ledgerme=new general_ledgers();
                // $ledgerme->LoanId=$request->thisid;
                // $ledgerme->accountNo=$accountNo;
                // $ledgerme->groupCode=$ledhai->groupCode;
                // $ledgerme->ledgerCode=$ledhai->ledgerCode;
                // $ledgerme->formName=$formname;
                // $ledgerme->transactionDate=$cdatee;
                // $ledgerme->transactionType='Cr';
                // $ledgerme->transactionAmount=$request->totalpaneltywithinstallment;
                // $ledgerme->narration=$ledhai->name;
                // $ledgerme->branchId=Null;
                // $ledgerme->refid='recovery';
                // $ledgerme->loanRecoveryidis=$loanRecoveryidis;
                // $ledgerme->agentId = Session::get('adminloginid');
                // $ledgerme->updatedBy = Session::get('adminloginid');
                // $ledgerme->updatedbytype = Session::get('user_type');
                // $ledgerme->sessionId	 = Session::get('sessionof');
                // $ledgerme->save();


                $ledgerme = new general_ledgers();
                $ledgerme->LoanId = $request->thisid;
                $ledgerme->accountNo = $accountNo;
                $ledgerme->groupCode = 'C002';
                $ledgerme->ledgerCode = 'CAS001';
                $ledgerme->formName = $formname;
                $ledgerme->transactionDate = $newdate;
                $ledgerme->transactionType = 'Dr';
                $ledgerme->transactionAmount = $request->totalpaneltywithinstallment;
                $ledgerme->narration = $ledhai->name;
                $ledgerme->branchId = Null;
                $ledgerme->refid = 'recovery';
                $ledgerme->loanRecoveryidis = $loanRecoveryidis;
                $ledgerme->agentId = Session::get('adminloginid');
                $ledgerme->updatedBy = Session::get('adminloginid');
                $ledgerme->updatedbytype = Session::get('user_type');
                $ledgerme->sessionId     = Session::get('sessionof');
                $ledgerme->save();


                $ledgerme = new general_ledgers();
                $ledgerme->LoanId = $request->thisid;
                $ledgerme->accountNo = $accountNo;
                $ledgerme->groupCode = $ledhai->groupCode;
                $ledgerme->ledgerCode = $ledhai->ledgerCode;
                $ledgerme->formName = $formname;
                $ledgerme->transactionDate = $newdate;
                $ledgerme->transactionType = 'Cr';
                $ledgerme->transactionAmount = $request->totalprinciple;
                $ledgerme->narration = 'Principle recieved';
                $ledgerme->branchId = Null;
                $ledgerme->refid = 'recovery';
                $ledgerme->loanRecoveryidis = $loanRecoveryidis;
                $ledgerme->agentId = Session::get('adminloginid');
                $ledgerme->updatedBy = Session::get('adminloginid');
                $ledgerme->updatedbytype = Session::get('user_type');
                $ledgerme->sessionId     = Session::get('sessionof');
                $ledgerme->save();


                $loan_intt_ledger = ledger_masters::where('loan_intt_id', '=', $member_loans->loanType)->first();


                if ($request->totalintrest > 0) {
                    $ledgerme = new general_ledgers();
                    $ledgerme->LoanId = $request->thisid;
                    $ledgerme->accountNo = $accountNo;
                    $ledgerme->groupCode = $loan_intt_ledger->groupCode;
                    $ledgerme->ledgerCode = $loan_intt_ledger->ledgerCode;
                    $ledgerme->formName = $formname;
                    $ledgerme->transactionDate = $newdate;
                    $ledgerme->transactionType = 'Cr';
                    $ledgerme->transactionAmount = $request->totalintrest;
                    $ledgerme->narration = 'Intrest Recived On Loan';
                    $ledgerme->branchId = Null;
                    $ledgerme->refid = 'recovery';
                    $ledgerme->loanRecoveryidis = $loanRecoveryidis;
                    $ledgerme->agentId = Session::get('adminloginid');
                    $ledgerme->updatedBy = Session::get('adminloginid');
                    $ledgerme->updatedbytype = Session::get('user_type');
                    $ledgerme->sessionId     = Session::get('sessionof');
                    $ledgerme->save();
                }

                if ($request->totalpanelty > 0) {

                    $ledgerme = new general_ledgers();
                    $ledgerme->LoanId = $request->thisid;
                    $ledgerme->accountNo = $accountNo;
                    $ledgerme->groupCode = 'IINC01';
                    $ledgerme->ledgerCode = 'PAN001';
                    $ledgerme->formName = $formname;
                    $ledgerme->transactionDate = $newdate;
                    $ledgerme->transactionType = 'Cr';
                    $ledgerme->transactionAmount = $request->totalpanelty;
                    $ledgerme->narration = 'Penalty Recived On Loan';
                    $ledgerme->branchId = Null;
                    $ledgerme->refid = 'recovery';
                    $ledgerme->loanRecoveryidis = $loanRecoveryidis;
                    $ledgerme->agentId = $member_loans->agentId;
                    $ledgerme->save();
                }
                $loanamountofthisid = DB::table('member_loans')->where('id', '=', $request->thisid)->value('loanAmount');
                $recoveredfthisid = DB::table('loan_recoveries')->where('loanId', '=', $request->thisid)->sum('principal');
                if ($recoveredfthisid >= $loanamountofthisid) {


                    $closeloan = member_loans::find($request->thisid);
                    $closeloan->status = 'Closed';
                    $closeloan->save();
                }
            } else {

                $cdatee = date("Y-m-d", strtotime($request->currentdate));
                $totalPayment = floatval($request->totalprinciple);


                $paidInstallmentIds = [];

                $id = $request->thisid;
                $checkloanid = DB::table('member_loans')->where('id', $id)->first();
                $checkdate = $checkloanid->loanDate;

                //__________Check if the loan date is greater than the account opening date
                if ($cdatee >= $checkdate) {
                    $newdate = $cdatee;
                } else {
                    Toastr::error('Loan date must be after the account opening date', 'Error', ["positionClass" => "toast-top-center"]);
                    return back(); // Stop execution and return to the previous page if condition fails
                }






                // Proceed with saving loan recovery data
                $loanRecovery = new loan_recoveries();
                $loanRecovery->receiptDate = $newdate; // $newdate is guaranteed to be defined here
                $loanRecovery->loanId = $request->thisid;
                $loanRecovery->principal = $request->totalprinciple;
                $loanRecovery->principalround = round($request->totalprinciple);
                $loanRecovery->interest = $request->totalintrest;
                $loanRecovery->penalInterest = $request->totalpanelty;
                $loanRecovery->total = $request->totalinstallment;
                $loanRecovery->receivedAmount = $request->totalpaneltywithinstallment;
                $loanRecovery->status = 'True';
                $loanRecovery->agentId = Session::get('adminloginid');
                $loanRecovery->updatedBy = Session::get('adminloginid');
                $loanRecovery->updatedbytype = Session::get('user_type');
                $loanRecovery->sessionId = Session::get('sessionof');
                $loanRecovery->save();
                $loanRecoveryidis = $loanRecovery->id;



                DB::transaction(function () use ($request, $cdatee, $loanRecoveryidis, &$totalPayment, &$paidInstallmentIds) {

                    //_________________________________New Work_____________________




                    //______________Loan Recoveries Sum of Loan Amount
                    $loanid = $request->thisid;
                    $totalRecoveryAmount = DB::table('loan_recoveries')->where('loanId', $loanid)->sum('principal');

                    //______________Update Installment Status Paid to False
                    DB::table('loan_installments')
                        ->where('LoanId', $loanid)
                        ->update(['status' => 'false']);

                    //_____________Check Installment False Status
                    $check_installment = DB::table('loan_installments')
                        ->where('LoanId', $loanid)
                        ->where('status', 'false')
                        ->orderBy('installmentDate', 'asc')
                        ->get();

                    foreach ($check_installment as $inst) {
                        $installmentAmount = $inst->principal;
                        $paid_date = date("Y-m-d", strtotime($cdatee));
                        if ($totalRecoveryAmount >= $installmentAmount) {
                            DB::table('loan_installments')
                                ->where('id', $inst->id)
                                ->update([
                                    'paid_date' => $paid_date,
                                    'status' => 'paid',
                                    're_amount' => $request->totalpaneltywithinstallment,
                                    'loanRecoveryidis' => $loanRecoveryidis,
                                ]);

                            $totalRecoveryAmount -= $installmentAmount;
                        } else {
                            DB::table('loan_installments')
                                ->where('id', $inst->id)
                                ->update(['status' => 'false']);
                        }
                    }

                    //________________________Rahul sir work

                    // $installments = loan_installments::where('LoanId', $loanid)
                    //     // ->where('installmentDate', '<=', $cdatee)
                    //     ->where('status', '!=', 'paid')
                    //     ->orderBy('installmentDate', 'asc')
                    //     ->get();






                    // foreach ($installments as $installment) {

                    //     $installmentAmount = floatval($installment->principal);
                    //     $paid_date = date("Y-m-d", strtotime($cdatee));
                    //     if ($totalPayment >= $installmentAmount) {


                    //         $installment->update([
                    //             'paid_date' => $paid_date,
                    //             'status' => 'paid',
                    //             're_amount' => $installmentAmount,
                    //             're_amountround' => round($installmentAmount),
                    //             'loanRecoveryidis' => $loanRecoveryidis,
                    //         ]);
                    //         $paidInstallmentIds[] = $installment->id;


                    //         $totalPayment -= $installmentAmount;
                    //     } else {
                    //         //     if($totalPayment>0){
                    //         //     $installment->update([
                    //         //         're_amount' => $totalPayment,
                    //         //         're_amountround' => round($totalPayment),
                    //         //         'loanRecoveryidis' => $loanRecoveryidis,
                    //         //     ]);
                    //         // }
                    //         //     $paidInstallmentIds[] = $installment->id;
                    //         //     $totalPayment -= $installmentAmount;
                    //     }
                    // }

                    $loanRecoveryww = loan_recoveries::find($loanRecoveryidis);
                    $loanRecoveryww->instaId = implode(",", $paidInstallmentIds);
                    $loanRecoveryww->save();
                });

                $member_loans = member_loans::find($request->thisid);
                $ledhai = ledger_masters::where('loan', '=', $member_loans->loanType)->first();
                $formname = $member_loans->name;
                $accountNo = $member_loans->accountNo;

                // $ledgerme=new general_ledgers();
                // $ledgerme->LoanId=$request->thisid;
                // $ledgerme->accountNo=$accountNo;
                // $ledgerme->groupCode=$ledhai->groupCode;
                // $ledgerme->ledgerCode=$ledhai->ledgerCode;
                // $ledgerme->formName=$formname;
                // $ledgerme->transactionDate=$cdatee;
                // $ledgerme->transactionType='Cr';
                // $ledgerme->transactionAmount=$request->totalpaneltywithinstallment;
                // $ledgerme->narration=$ledhai->name;
                // $ledgerme->branchId=Null;
                // $ledgerme->refid='recovery';
                // $ledgerme->loanRecoveryidis=$loanRecoveryidis;
                // $ledgerme->agentId = Session::get('adminloginid');
                // $ledgerme->updatedBy = Session::get('adminloginid');
                // $ledgerme->updatedbytype = Session::get('user_type');
                // $ledgerme->sessionId	 = Session::get('sessionof');
                // $ledgerme->save();


                $ledgerme = new general_ledgers();
                $ledgerme->LoanId = $request->thisid;
                $ledgerme->accountNo = $accountNo;
                $ledgerme->groupCode = 'C002';
                $ledgerme->ledgerCode = 'CAS001';
                $ledgerme->formName = $formname;
                $ledgerme->transactionDate = $newdate;
                $ledgerme->transactionType = 'Dr';
                $ledgerme->transactionAmount = $request->totalpaneltywithinstallment;
                $ledgerme->narration = $ledhai->name;
                $ledgerme->branchId = Null;
                $ledgerme->refid = 'recovery';
                $ledgerme->loanRecoveryidis = $loanRecoveryidis;
                $ledgerme->agentId = Session::get('adminloginid');
                $ledgerme->updatedBy = Session::get('adminloginid');
                $ledgerme->updatedbytype = Session::get('user_type');
                $ledgerme->sessionId     = Session::get('sessionof');
                $ledgerme->save();


                $ledgerme = new general_ledgers();
                $ledgerme->LoanId = $request->thisid;
                $ledgerme->accountNo = $accountNo;
                $ledgerme->groupCode = $ledhai->groupCode;
                $ledgerme->ledgerCode = $ledhai->ledgerCode;
                $ledgerme->formName = $formname;
                $ledgerme->transactionDate = $newdate;
                $ledgerme->transactionType = 'Cr';
                $ledgerme->transactionAmount = $request->totalprinciple;
                $ledgerme->narration = 'Principle recieved';
                $ledgerme->branchId = Null;
                $ledgerme->refid = 'recovery';
                $ledgerme->loanRecoveryidis = $loanRecoveryidis;
                $ledgerme->agentId = Session::get('adminloginid');
                $ledgerme->updatedBy = Session::get('adminloginid');
                $ledgerme->updatedbytype = Session::get('user_type');
                $ledgerme->sessionId     = Session::get('sessionof');
                $ledgerme->save();



                $loan_intt_ledger = ledger_masters::where('loan_intt_id', '=', $member_loans->loanType)->first();

                if ($request->totalintrest > 0) {
                    $ledgerme = new general_ledgers();
                    $ledgerme->LoanId = $request->thisid;
                    $ledgerme->accountNo = $accountNo;
                    $ledgerme->groupCode = $loan_intt_ledger->groupCode;
                    $ledgerme->ledgerCode = $loan_intt_ledger->ledgerCode;
                    $ledgerme->formName = $formname;
                    $ledgerme->transactionDate = $newdate;
                    $ledgerme->transactionType = 'Cr';
                    $ledgerme->transactionAmount = $request->totalintrest;
                    $ledgerme->narration = 'Intrest Recived On Loan';
                    $ledgerme->branchId = Null;
                    $ledgerme->refid = 'recovery';
                    $ledgerme->loanRecoveryidis = $loanRecoveryidis;
                    $ledgerme->agentId = Session::get('adminloginid');
                    $ledgerme->updatedBy = Session::get('adminloginid');
                    $ledgerme->updatedbytype = Session::get('user_type');
                    $ledgerme->sessionId     = Session::get('sessionof');
                    $ledgerme->save();
                }
                if ($request->totalpanelty > 0) {

                    $ledgerme = new general_ledgers();
                    $ledgerme->LoanId = $request->thisid;
                    $ledgerme->accountNo = $accountNo;
                    $ledgerme->groupCode = 'IINC01';
                    $ledgerme->ledgerCode = 'PAN001';
                    $ledgerme->formName = $formname;
                    $ledgerme->transactionDate = $newdate;
                    $ledgerme->transactionType = 'Cr';
                    $ledgerme->transactionAmount = $request->totalpanelty;
                    $ledgerme->narration = 'Penalty Recived On Loan';
                    $ledgerme->branchId = Null;
                    $ledgerme->refid = 'recovery';
                    $ledgerme->loanRecoveryidis = $loanRecoveryidis;
                    $ledgerme->agentId = Session::get('adminloginid');
                    $ledgerme->updatedBy = Session::get('adminloginid');
                    $ledgerme->updatedbytype = Session::get('user_type');
                    $ledgerme->sessionId     = Session::get('sessionof');
                    $ledgerme->save();
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed: ' . $e->getMessage());
        }




        $loanamountofthisid = DB::table('member_loans')->where('id', '=', $request->thisid)->value('loanAmount');
        $recoveredfthisid = DB::table('loan_recoveries')->where('loanId', '=', $request->thisid)->sum('principal');
        if ($recoveredfthisid >= $loanamountofthisid) {


            $closeloan = member_loans::find($request->thisid);
            $closeloan->status = 'Closed';
            $closeloan->save();
        }
        return response()->json([
            'status' => true,
            'thisid' => $request->thisid,
            'message' => 'Installments updated and recovery recorded successfully.'
        ]);
    }


    public function loan()
    {

        $pagetitle = "Loan";
        $pageto = url('loan');
        $formurl = url('loan');
        $data = compact('formurl', 'pagetitle', 'pageto');
        return view('loan')->with($data);
    }

    public function deleteadvancement(Request $request)
    {
        member_loans::where('id', '=', $request->id)->delete();
        general_ledgers::where('LoanId', '=', $request->id)->delete();
        loan_installments::where('LoanId', '=', $request->id)->delete();

        Toastr::error('Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
        return back()->with('success', 'Delete Successfully');
    }


    public function editadvancement(Request $request)
    {
        $member_loans = member_loans::find($request->id);
        return response()->json([
            'status' => true,
            'member_loans' => $member_loans,

        ]);
    }


    public function editadvancementre(Request $request)
    {
        $loan_recoveries = loan_recoveries::find($request->id);
        return response()->json([
            'status' => true,
            'loan_recoveries' => $loan_recoveries,

        ]);
    }


    public function search(Request $request)
    {
        $query = $request->input('query');

        $clients = member_accounts::where('customer_id', 'LIKE', "%{$query}%")
            // ->limit(10)
            ->pluck('customer_id');

        return response()->json($clients);
    }

    public function editfourcloseraddrecovery(Request $request)
    {

        DB::beginTransaction();
        try {

            $reci = loan_recoveries::find($request->id);
            $closeloanopen = member_loans::find($reci->loanId);
            $cdatee = date("Y-m-d", strtotime($request->currentdate));
            $paymentmode = $request->paymentmode;
            $customer_Id = $request->customer_Id;
            $principleis = $request->principle;
            $totalintest = $request->intrest;
            $paneltyis = $request->panelty;
            $totalpaneltywithinstallmentis = $request->totalpayment;
            $remarks = $request->remarks;
            $id = $closeloanopen->id;

            loan_recoveries::find($request->id)->delete();
            general_ledgers::where('loanRecoveryidis', '=', $request->id)->delete();

            $loanRecovery = new loan_recoveries();
            $loanRecovery->receiptDate = $cdatee;
            $loanRecovery->loanId = $id;
            $loanRecovery->principal = $principleis;
            $loanRecovery->interest = $totalintest;
            $loanRecovery->penalInterest = $paneltyis;
            $loanRecovery->total = $totalpaneltywithinstallmentis;
            $loanRecovery->receivedAmount = $totalpaneltywithinstallmentis;
            $loanRecovery->status = 'True';
            $loanRecovery->foreclosure = 'yes';
            $loanRecovery->remarks = $request->remarks;
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

            $this->createLedgerEntry($id, $accountNo, 'C002', 'CAS001', $formname, $cdatee, 'Dr', $totalpaneltywithinstallmentis, $ledhai->name, $loanRecoveryidis);
            $this->createLedgerEntry($id, $accountNo, $ledhai->groupCode, $ledhai->ledgerCode, $formname, $cdatee, 'Cr', $principleis, 'Principle received', $loanRecoveryidis);

            if ($totalintest > 0) {
                $loan_intt_ledger = ledger_masters::where('loan_intt_id', '=', $member_loans->loanType)->first();
                $this->createLedgerEntry($id, $accountNo, $loan_intt_ledger->groupCode, $loan_intt_ledger->ledgerCode, $formname, $cdatee, 'Cr', $totalintest, 'Interest Received On Loan', $loanRecoveryidis);
            }

            if ($paneltyis > 0) {
                $this->createLedgerEntry($id, $accountNo, 'IINC01', 'PAN001', $formname, $cdatee, 'Cr', $paneltyis, 'Penalty Received On Loan', $loanRecoveryidis);
            }

            $meclosure = member_loans::find($id);
            $meclosure->status = "Closed";
            $meclosure->save();

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Success'], 200);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => 'Transaction Failed',
                'errors' => $e->getMessage()
            ]);
        }
    }

    public function addfourcloserecovery(Request $request)
    {
        DB::beginTransaction();
        try {
            $cdatee = date("Y-m-d", strtotime($request->currentdate));
            $paymentmode = $request->paymentmode;
            $customer_Id = $request->customer_Id;
            $principleis = $request->totalpayment;
            $totalintest = $request->totalintrest;
            $paneltyis = $request->panelty;
            $totalpaneltywithinstallmentis = $request->totalloanpayment;
            $remarks = $request->remarks;

            $thisid = DB::table('member_loans')
                ->where('accountNo', '=', $customer_Id)
                ->where('status', '=', 'Disbursed')
                ->first();

            $id = $thisid->id;


            $loanRecovery = new loan_recoveries();
            $loanRecovery->receiptDate = $cdatee;
            $loanRecovery->loanId = $id;
            $loanRecovery->principal = $principleis;
            $loanRecovery->interest = $totalintest;
            $loanRecovery->penalInterest = $paneltyis;
            $loanRecovery->total = $totalpaneltywithinstallmentis;
            $loanRecovery->receivedAmount = $totalpaneltywithinstallmentis;
            $loanRecovery->status = 'True';
            $loanRecovery->foreclosure = 'yes';
            $loanRecovery->remarks = $request->remarks;
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

            $this->createLedgerEntry($id, $accountNo, 'C002', 'CAS001', $formname, $cdatee, 'Dr', $totalpaneltywithinstallmentis, $ledhai->name, $loanRecoveryidis);
            $this->createLedgerEntry($id, $accountNo, $ledhai->groupCode, $ledhai->ledgerCode, $formname, $cdatee, 'Cr', $principleis, 'Principle received', $loanRecoveryidis);

            if ($totalintest > 0) {
                $loan_intt_ledger = ledger_masters::where('loan_intt_id', '=', $member_loans->loanType)->first();
                $this->createLedgerEntry($id, $accountNo, $loan_intt_ledger->groupCode, $loan_intt_ledger->ledgerCode, $formname, $cdatee, 'Cr', $totalintest, 'Interest Received On Loan', $loanRecoveryidis);
            }

            if ($paneltyis > 0) {
                $this->createLedgerEntry($id, $accountNo, 'IINC01', 'PAN001', $formname, $cdatee, 'Cr', $paneltyis, 'Penalty Received On Loan', $loanRecoveryidis);
            }

            $meclosure = member_loans::find($id);
            $meclosure->status = "Closed";
            $meclosure->save();

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Success'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Transaction Failed',
                'errors' => $e->getMessage()
            ]);
        }
    }

    public function foreclosure(Request $request)
    {
        $pagetitle = "Foreclosure Recovery";
        $pageto = url('quickrecovery');
        $formurl = url('quickrecovery');
        $loan_masters = loan_masters::where('status', '=', 'Active')->get();
        $purpose_masters = purpose_masters::where('status', '=', 'Active')->get();
        $agent_masters = AgentMaster::where('status', '=', 'Active')->get();
        $ledger_masters = ledgers::where('groupCode', '=', 'BANK01')->get();
        $member_accounts = member_accounts::all();
        $data = compact('formurl', 'pagetitle', 'pageto', 'loan_masters', 'purpose_masters', 'ledger_masters', 'agent_masters', 'member_accounts');
        return view('foreclosure')->with($data);
    }

    public function getforclosure(Request $request)
    {
        $customer_hai = DB::table('member_accounts')->where('customer_Id', '=', $request->id)->first();
        if (!$customer_hai) {
            return response()->json(['status' => false, 'message' => 'Customer not found.'], 404);
        }

        $loanhai = DB::table('member_loans')
            ->where('accountNo', '=', $request->id)
            ->where('status', '=', 'Disbursed')
            ->first();


        // $loan_amount = ((($loanhai->loanAmount * $loanhai->loanInterest)/100)*$loanhai->months);
        $totalinterest = (((($loanhai->loanAmount * $loanhai->loanInterest)/100)*$loanhai->months));


        // dd($loanhai);




        if (!$loanhai) {
            return response()->json(['status' => false, 'message' => 'Loan not found.'], 404);
        }

        // Get sum of principal and interest paid
        $principle = DB::table('loan_recoveries')->where('loanId', '=', $loanhai->id)->sum('principal') ?? 0;
        $interest = DB::table('loan_recoveries')->where('loanId', '=', $loanhai->id)->sum('interest') ?? 0;




        // Calculate remaining balance
        $remaining = ($loanhai->loanAmount ?? 0) - $principle;

        // Calculate interest amount
        $roi = $loanhai->loanInterest ?? 0;
        $interestAmount = ($remaining * $roi) / 100;

        // Prepare response data
        $data = [
            'amount' => round($remaining),
            'roi' => round($roi),
            'interest' => round($interest),
            'month' => $loanhai->months,
            'name' => $loanhai->name,
            'principle' => $principle,
            'loanamount' => $loanhai->loanAmount,
            'loanDate' => date('d-m-Y', strtotime($loanhai->loanDate))
        ];

        return response()->json(['status' => 'success', 'message' => $data], 200);
    }


    public function quickrecovery(Request $request)
    {
        $pagetitle = "Quick Recovery";
        $pageto = url('quickrecovery');
        $formurl = url('quickrecovery');
        $loan_masters = loan_masters::where('status', '=', 'Active')->get();
        $purpose_masters = purpose_masters::where('status', '=', 'Active')->get();
        $agent_masters = AgentMaster::where('status', '=', 'Active')->get();
        $ledger_masters = DB::table('group_masters')->whereIn('groupCode', ['BANK01', 'C002'])->get();;
        $member_accounts = member_accounts::all();
        $data = compact('formurl', 'pagetitle', 'pageto', 'loan_masters', 'purpose_masters', 'ledger_masters', 'agent_masters', 'member_accounts');
        return view('quickrecovery')->with($data);
    }


    public function getgroupsLedgers(Request $post)
    {
        $groupcode = $post->groupcode;

        $ledgers_details = DB::table('ledger_masters')->where('groupCode', $groupcode)->get();
        if (!empty($ledgers_details)) {
            return response()->json(['status' => 'success', 'ledgers_details' => $ledgers_details]);
        } else {
            return response()->json(['status' => 'Fail', 'messages' => 'Record Not Found']);
        }
    }


    public function getquickrecovery(Request $request)
    {

        $receiptDate = date("Y-m-d", strtotime($request->date));

        $data = DB::table('loan_recoveries')
            ->join('agent_masters', 'loan_recoveries.agentId', '=', 'agent_masters.id')
            ->join('member_loans', 'loan_recoveries.loanId', '=', 'member_loans.id')
            ->join('loan_masters', 'member_loans.loanType', '=', 'loan_masters.id')
            ->join('member_accounts', 'member_loans.accountNo', '=', 'member_accounts.customer_Id')
            ->select('loan_recoveries.*', 'agent_masters.name as agent_name', 'loan_masters.loanname as loanName', 'member_accounts.customer_Id', 'member_accounts.name as accountName')
            ->where('foreclosure', 'no')
            ->where('loan_recoveries.receiptDate', '=', $receiptDate)->orderby('loan_recoveries.updated_at', 'DESC')
            ->get();

        return response()->json($data);
    }


    public function getquickrecoveryfor(Request $request)
    {

        $receiptDate = date("Y-m-d", strtotime($request->date));

        $data = DB::table('loan_recoveries')
            ->join('agent_masters', 'loan_recoveries.agentId', '=', 'agent_masters.id')
            ->join('member_loans', 'loan_recoveries.loanId', '=', 'member_loans.id')
            ->join('loan_masters', 'member_loans.loanType', '=', 'loan_masters.id')
            ->join('member_accounts', 'member_loans.accountNo', '=', 'member_accounts.customer_Id')
            ->select('loan_recoveries.*', 'agent_masters.name as agent_name', 'loan_masters.loanname as loanName', 'member_accounts.customer_Id', 'member_accounts.name as accountName')
            ->where('foreclosure', 'yes')
            ->where('loan_recoveries.receiptDate', '=', $receiptDate)->orderby('loan_recoveries.updated_at', 'DESC')
            ->get();
        return response()->json($data);
    }

    public function loangetdatarecovery(Request $request)
    {

        $loandeytail = member_loans::find($request->id);

        return response()->json(['status' => false, 'data' => $loandeytail], 200);
    }

    public function getdatarecovery(Request $request)
    {
        $loan_recovery = loan_recoveries::find($request->id);

        if (!$loan_recovery) {
            return response()->json(['status' => false, 'message' => 'Loan recovery not found.'], 404);
        }

        // Get the associated member loan
        $member_loan = DB::table('member_loans')->where('id', $loan_recovery->loanId)->first();

        if (!$member_loan) {
            return response()->json(['status' => false, 'message' => 'Member loan not found.'], 404);
        }

        $installmentId = DB::table('loan_installments')->where('LoanId', $loan_recovery->loanId)->first();
        // dd($installmentId);

        // Get the customer account details
        $customer_account = DB::table('member_accounts')->where('customer_Id', $member_loan->accountNo)->first();

        // Prepare the response data
        $data = [
            'status' => true,
            'loan_recovery' => $loan_recovery,
            'member_loan' => $member_loan,
            'customer_account' => $customer_account,
            'installmentId' => $installmentId
        ];


        return response()->json($data, 200);
    }


    public function recconfirmDeleterefourclose(Request $request)
    {

        $reci = loan_recoveries::find($request->id);
        $closeloanopen = member_loans::find($reci->loanId);
        $closeloanopen->status = 'Disbursed';
        $closeloanopen->save();
        loan_recoveries::find($request->id)->delete();
        general_ledgers::where('loanRecoveryidis', '=', $request->id)->delete();
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
    }

    public function loaneditpaymentForm(Request $request){
        $id = $request->id;
        $loanmass = loan_masters::find($request->loan);
        $account_no = $request->accountNo;

        $checkaccount_no = DB::table('member_accounts')->where('customer_Id', $account_no)->first();
        $checkdate = $checkaccount_no->openingDate;
        $loan_date = date("Y-m-d", strtotime($request->loanDate));
        $emidate = date('Y-m-d', strtotime($request->emi_date));


        //__________Check if the loan date is greater than the account opening date
        if ($loan_date >= $checkdate) {
            $newdate = $loan_date;
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Loan date must be after the account opening date'
            ], 400);
        }

        $loanAmountofid = member_loans::find($id)->loanAmount;
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
        $loan_amounts = $request->amount;
        $processing_fee = $request->processingFee;
        $processing_fee_amount = (($loan_amounts * $processing_fee) / 100);



        $meminsert = member_loans::find($id);
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
        $meminsert->name = $request->name;
        $meminsert->caskback = $request->cashback;



        $meminsert->groupCode = $request->paymentby;
        $meminsert->ledgerCode = $request->cashbanktype;



        // $meminsert->guarantorname = $request->guarantorname;
        // $meminsert->guarantorno = $request->guarantorno;
        // $meminsert->guarantoraddress = $request->guarantoraddress;
        // $meminsert->guarantornamee = $request->guarantornamee;
        // $meminsert->guarantornoo = $request->guarantornoo;
        // $meminsert->guarantoraddresss = $request->guarantoraddresss;
        $meminsert->agentId = Session::get('adminloginid');
        $meminsert->updatedBy = Session::get('adminloginid');
        $meminsert->updatedbytype = Session::get('user_type');
        $meminsert->sessionId     = Session::get('sessionof');
        $meminsert->save();

        $loandetail = loan_masters::find($request->loan);
        // if ($request->insType == 'Monthly') {
        loan_installments::where('LoanId', '=', $id)->delete();

        $loanamount = $request->amount;
        $rateofinterest = $request->interest;
        $tanure = $request->months;


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
            $loanInstallment->LoanId = $meminsert->id;

            if ($i == 0) {
                $loanInstallment->installmentDate = $startDate->toDateString(); // First installment date
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





        $totalPaymenttotal = DB::table('loan_recoveries')->where('loanId', $id)->sum('total');
        $getinstallmentsbydatse = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->orderby('id', 'ASC')->get();
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

        general_ledgers::where('LoanId', '=', $id)->whereNull('refid')->delete();
        $ledhai = ledger_masters::where('loan', '=', $loanmass->id)->first();
        $ledgerme = new general_ledgers();
        $ledgerme->LoanId = $meminsert->id;
        $ledgerme->accountNo = $request->accountNo;
        $ledgerme->groupCode = $ledhai->groupCode;
        $ledgerme->ledgerCode = $ledhai->ledgerCode;
        $ledgerme->formName = $request->name;
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
        $ledgerme->LoanId = $meminsert->id;
        $ledgerme->accountNo = $request->accountNo;
        $ledgerme->groupCode = $request->paymentby;
        $ledgerme->ledgerCode = $request->cashbanktype;
        $ledgerme->formName = $request->name;
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
        if ($request->processingFee > 0) {
            $ledgerme = new general_ledgers();
            $ledgerme->LoanId = $meminsert->id;
            $ledgerme->accountNo = $request->accountNo;
            $ledgerme->groupCode = 'IINC01';
            $ledgerme->ledgerCode = 'PRC001';
            $ledgerme->formName = $request->name;
            $ledgerme->transactionDate = $newdate;
            $ledgerme->transactionType = 'Cr';
            $ledgerme->transactionAmount = $request->processingFee;
            $ledgerme->narration = $loandetail->loanname;
            $ledgerme->branchId = Null;
            $ledgerme->agentId = Session::get('adminloginid');
            $ledgerme->updatedBy = Session::get('adminloginid');
            $ledgerme->updatedbytype = Session::get('user_type');
            $ledgerme->sessionId     = Session::get('sessionof');
            $ledgerme->save();





            $ledgerme = new general_ledgers();
            $ledgerme->LoanId = $meminsert->id;
            $ledgerme->accountNo = $request->accountNo;
            $ledgerme->groupCode = $request->paymentby;
            $ledgerme->ledgerCode = $request->cashbanktype;
            $ledgerme->formName = $request->name;
            $ledgerme->transactionDate = $newdate;
            $ledgerme->transactionType = 'Dr';
            $ledgerme->transactionAmount = $request->processingFee;
            $ledgerme->narration = $loandetail->loanname;
            $ledgerme->branchId = Null;
            $ledgerme->agentId = Session::get('adminloginid');
            $ledgerme->updatedBy = Session::get('adminloginid');
            $ledgerme->updatedbytype = Session::get('user_type');
            $ledgerme->sessionId     = Session::get('sessionof');
            $ledgerme->save();
        }

        return response()->json([
            'status' => true,
            'id' => $request->accountNo
        ]);
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
            $loanRecovery->group_code = $request->paymentmode;
            $loanRecovery->ledger_code = $request->editledgercodesss;
            $loanRecovery->penalInterest = $paneltyis;
            $loanRecovery->total = $totalpaneltywithinstallmentis;
            $loanRecovery->receivedAmount = $totalpaneltywithinstallmentis;
            $loanRecovery->status = 'True';
            $loanRecovery->remarks = $request->remarks;
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

            // Create ledger entries
            $this->createLedgerEntry($id, $accountNo, $request->paymentmode, $request->editledgercodesss, $formname, $cdatee, 'Dr', $totalpaneltywithinstallmentis, $ledhai->name, $loanRecoveryidis);
            $this->createLedgerEntry($id, $accountNo, $ledhai->groupCode, $ledhai->ledgerCode, $formname, $cdatee, 'Cr', $principleis, 'Principle received', $loanRecoveryidis);

            if ($totalintest > 0) {
                $loan_intt_ledger = ledger_masters::where('loan_intt_id', '=', $member_loans->loanType)->first();
                $this->createLedgerEntry($id, $accountNo, $loan_intt_ledger->groupCode, $loan_intt_ledger->ledgerCode, $formname, $cdatee, 'Cr', $totalintest, 'Interest Received On Loan', $loanRecoveryidis);
            }

            if ($paneltyis > 0) {
                $this->createLedgerEntry($id, $accountNo, 'IINC01', 'PAN001', $formname, $cdatee, 'Cr', $paneltyis, 'Penalty Received On Loan', $loanRecoveryidis);
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
                ->orderBy('member_loans.loanDate', 'DESC')
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
                'message' => 'Transaction Failed',
                'errors' => $e->getMessage()
            ]);
        }
    }

    public function addrecovery(Request $request)
    {

        DB::beginTransaction();

        try {
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


            $recoveries = DB::table('loan_recoveries')->where('loanId', '=', $id)->get();
            $pr = $recoveries->sum('principal');
            $in = $recoveries->sum('interest');
            $naihonachiye = $pr + $in;
            // $firstis = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->first();
            // $sumprincipal = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->sum('principal');
            // $suminterest = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->sum('interest');
            // $naihonachiye = $sumprincipal + $suminterest;
            // dd($pr,$in);

            // if ($naihonachiye > $totalPayment) {
            //     return response()->json(['status' => false, 'message' => 'Amount is greater than Pending Amount'], 400);
            // }


            $getinstallmentsbydate = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->orderby('id', 'ASC')->get();



            if ($getinstallmentsbydate->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'No installments found'], 400);
            }

            $pendingpayment = $totalPayment;
            $totalintest = 0;
            $totalprinciple = 0;




            // foreach ($getinstallmentsbydate as $list) {
            foreach ($getinstallmentsbydate as $list) {
                $installmentAmount = $list->principal + $list->interest;

                // If recovery amount covers the full installment
                if ($pendingpayment >= $installmentAmount) {
                    $totalintest += $list->interest;
                    $totalprinciple += $list->principal;
                    $pendingpayment -= $installmentAmount;
                } else {
                    // Partial payment logic
                    if ($pendingpayment > 0) {
                        // First allocate to interest
                        if ($pendingpayment > $list->interest) {
                            $totalintest += $list->interest;
                            $remainingForPrincipal = $pendingpayment - $list->interest;
                            $totalprinciple += $remainingForPrincipal;
                            $pendingpayment = 0;
                        } else {
                            // Partial interest payment only
                            $totalintest += $pendingpayment;
                            $pendingpayment = 0;
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
            $loanRecovery->group_code = $request->paymentmode;
            $loanRecovery->ledger_code = $request->ledgercodesss;
            $loanRecovery->interest = $totalintest;
            $loanRecovery->penalInterest = $paneltyis;
            $loanRecovery->total = $totalpaneltywithinstallmentis;
            $loanRecovery->receivedAmount = $totalpaneltywithinstallmentis;
            $loanRecovery->status = 'True';
            $loanRecovery->remarks = $request->remarks;
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

            // Create ledger entries
            $this->createLedgerEntry($id, $accountNo, $request->paymentmode, $request->ledgercodesss, $formname, $cdatee, 'Dr', $totalpaneltywithinstallmentis, $ledhai->name, $loanRecoveryidis);
            $this->createLedgerEntry($id, $accountNo, $ledhai->groupCode, $ledhai->ledgerCode, $formname, $cdatee, 'Cr', $principleis, 'Principle received', $loanRecoveryidis);

            if ($totalintest > 0) {
                $loan_intt_ledger = ledger_masters::where('loan_intt_id', '=', $member_loans->loanType)->first();
                $this->createLedgerEntry($id, $accountNo, $loan_intt_ledger->groupCode, $loan_intt_ledger->ledgerCode, $formname, $cdatee, 'Cr', $totalintest, 'Interest Received On Loan', $loanRecoveryidis);
            }

            if ($paneltyis > 0) {
                $this->createLedgerEntry($id, $accountNo, 'IINC01', 'PAN001', $formname, $cdatee, 'Cr', $paneltyis, 'Penalty Received On Loan', $loanRecoveryidis);
            }

            // Update installments
            $totalPendingPayment = $totalPayment; // Total payment to allocate
            $getinstallmentsbydate = DB::table('loan_installments')
                ->where('status', '=', 'false')
                ->where('LoanId', '=', $id)
                ->orderBy('id', 'ASC')
                ->get();

            foreach ($getinstallmentsbydate as $inst) {
                $installmentAmount = $inst->principal + $inst->interest;

                if ($totalPendingPayment >= $installmentAmount) {
                    // Fully pay this installment
                    DB::table('loan_installments')
                        ->where('id', $inst->id)
                        ->update([
                            'paid_date' => $cdatee,
                            'status' => 'paid',
                            'loanRecoveryidis' => $loanRecoveryidis,
                        ]);
                    $totalPendingPayment -= $installmentAmount;
                } elseif ($totalPendingPayment > 0) {
                    // Partially pay the installment
                    $remainingInterest = $inst->interest;

                    if ($totalPendingPayment > $remainingInterest) {
                        $paidPrincipal = $totalPendingPayment - $remainingInterest;
                        DB::table('loan_installments')
                            ->where('id', $inst->id)
                            ->update([
                                'paid_date' => $cdatee,
                                'status' => 'paid',
                                'loanRecoveryidis' => $loanRecoveryidis,
                            ]);
                        $totalPendingPayment = 0;
                    }
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
                'message' => 'Transaction Failed',
                'errors' => $e->getMessage()
            ]);
        }
    }

    // Helper function to create ledger entries
    protected function createLedgerEntry($loanId, $accountNo, $groupCode, $ledgerCode, $formName, $transactionDate, $transactionType, $transactionAmount, $narration, $loanRecoveryidis)
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
        $ledger->agentId = Session::get('adminloginid');
        $ledger->updatedBy = Session::get('adminloginid');
        $ledger->updatedbytype = Session::get('user_type');
        $ledger->sessionId = Session::get('sessionof');
        $ledger->save();
    }

    public function advancement(Request $request)
    {
        // dd($request->all());
        $pagetitle = "Loan Advancement";
        $pageto = url('advancement');
        $formurl = url('advancement');
        $loan_masters = loan_masters::where('status', '=', 'Active')->get();
        $purpose_masters = purpose_masters::where('status', '=', 'Active')->get();
        $agent_masters = AgentMaster::where('status', '=', 'Active')->get();
        $groups = DB::table('group_masters')->whereIn('groupCode', ['BANK01', 'C002'])->get();
        $member_accounts = member_accounts::pluck('customer_id');
        $data = compact('formurl', 'pagetitle', 'pageto', 'loan_masters', 'purpose_masters', 'groups', 'agent_masters', 'member_accounts');
        if (!empty($request->all())) {
            if (!empty($request->id)) {


                $id = $request->id;
                $loanmass = loan_masters::find($request->loan);
                $account_no = $request->accountNo;

                $checkaccount_no = DB::table('member_accounts')->where('customer_Id', $account_no)->first();
                $checkdate = $checkaccount_no->openingDate;
                $loan_date = date("Y-m-d", strtotime($request->loanDate));
                // $emidate = date('Y-m-d',strtotime($request->emi_date));

                if ($loan_date >= $checkdate) {
                    $newdate = $loan_date;
                    // $emidate = Carbon::parse($newdate)->addMonth()->day(10)->format('Y-m-d');


                    if (!empty($request->emi_date)) {
                        $emidate = date('Y-m-d', strtotime($request->emi_date));
                    } else {
                        $emidate = Carbon::parse($newdate)->addMonth()->day(10)->format('Y-m-d');
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Loan date must be after the account opening date'
                    ], 400);
                }

                $loanAmountofid = member_loans::find($id)->loanAmount;
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
                $loan_amounts = $request->amount;
                $processing_fee = $request->processingFee;
                $processing_fee_amount = (($loan_amounts * $processing_fee) / 100);



                $meminsert = member_loans::find($id);
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
                $meminsert->name = $request->name;
                $meminsert->guarantorname = $request->guarantorname;
                $meminsert->guarantorno = $request->guarantorno;
                $meminsert->guarantoraddress = $request->guarantoraddress;
                $meminsert->guarantornamee = $request->guarantornamee;
                $meminsert->guarantornoo = $request->guarantornoo;
                $meminsert->caskback = $request->caskback;
                $meminsert->guarantoraddresss = $request->guarantoraddresss;

                $meminsert->groupCode = $request->paymentby;
                $meminsert->ledgerCode = $request->cashbanktype;

                $meminsert->agentId = Session::get('adminloginid');
                $meminsert->updatedBy = Session::get('adminloginid');
                $meminsert->updatedbytype = Session::get('user_type');
                $meminsert->sessionId     = Session::get('sessionof');
                $meminsert->save();

                $loandetail = loan_masters::find($request->loan);
                // if ($request->insType == 'Monthly') {
                loan_installments::where('LoanId', '=', $id)->delete();

                $loanamount = $request->amount;
                $rateofinterest = $request->interest;
                $tanure = $request->months;


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
                    $loanInstallment->LoanId = $meminsert->id;


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





                $totalPaymenttotal = DB::table('loan_recoveries')->where('loanId', $id)->sum('total');
                $getinstallmentsbydatse = DB::table('loan_installments')->where('status', '=', 'false')->where('LoanId', '=', $id)->orderby('id', 'ASC')->get();
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

                general_ledgers::where('LoanId', '=', $id)->whereNull('refid')->delete();
                $ledhai = ledger_masters::where('loan', '=', $loanmass->id)->first();
                $ledgerme = new general_ledgers();
                $ledgerme->LoanId = $meminsert->id;
                $ledgerme->accountNo = $request->accountNo;
                $ledgerme->groupCode = $ledhai->groupCode;
                $ledgerme->ledgerCode = $ledhai->ledgerCode;
                $ledgerme->formName = $request->name;
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
                $ledgerme->LoanId = $meminsert->id;
                $ledgerme->accountNo = $request->accountNo;
                $ledgerme->groupCode = $request->paymentby;
                $ledgerme->ledgerCode = $request->cashbanktype;
                $ledgerme->formName = $request->name;
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
                if ($request->processingFee > 0) {
                    $ledgerme = new general_ledgers();
                    $ledgerme->LoanId = $meminsert->id;
                    $ledgerme->accountNo = $request->accountNo;
                    $ledgerme->groupCode = 'IINC01';
                    $ledgerme->ledgerCode = 'PRC001';
                    $ledgerme->formName = $request->name;
                    $ledgerme->transactionDate = $newdate;
                    $ledgerme->transactionType = 'Cr';
                    $ledgerme->transactionAmount = $request->processingFee;
                    $ledgerme->narration = $loandetail->loanname;
                    $ledgerme->branchId = Null;
                    $ledgerme->agentId = Session::get('adminloginid');
                    $ledgerme->updatedBy = Session::get('adminloginid');
                    $ledgerme->updatedbytype = Session::get('user_type');
                    $ledgerme->sessionId     = Session::get('sessionof');
                    $ledgerme->save();

                    $ledgerme = new general_ledgers();
                    $ledgerme->LoanId = $meminsert->id;
                    $ledgerme->accountNo = $request->accountNo;
                    $ledgerme->groupCode = $request->paymentby;
                    $ledgerme->ledgerCode = $request->cashbanktype;
                    $ledgerme->formName = $request->name;
                    $ledgerme->transactionDate = $newdate;
                    $ledgerme->transactionType = 'Dr';
                    $ledgerme->transactionAmount = $request->processingFee;
                    $ledgerme->narration = $loandetail->loanname;
                    $ledgerme->branchId = Null;
                    $ledgerme->agentId = Session::get('adminloginid');
                    $ledgerme->updatedBy = Session::get('adminloginid');
                    $ledgerme->updatedbytype = Session::get('user_type');
                    $ledgerme->sessionId     = Session::get('sessionof');
                    $ledgerme->save();
                }



                return response()->json([
                    'status' => true,
                    'id' => $request->accountNo
                ]);
            } else {


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

                if ($loan_date >= $checkdate) {
                    $newdate = $loan_date;

                    if (!empty($request->emi_date)) {
                        $emidate = date('Y-m-d', strtotime($request->emi_date));
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
                    ->where('accountNo', $request->accountNo)
                    ->sum('loanAmount');
                $debitAmount = DB::table('loan_recoveries')
                    ->join('member_loans', 'loan_recoveries.loanId', '=', 'member_loans.id')
                    ->where('member_loans.accountNo', $request->accountNo)
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
                $meminsert->name = $request->name;
                $meminsert->guarantorname = $request->guarantorname;
                $meminsert->caskback = $request->caskback;
                $meminsert->groupCode = $request->paymentby;
                $meminsert->ledgerCode = $request->cashbanktype;

                $meminsert->guarantorno = $request->guarantorno;
                $meminsert->guarantoraddress = $request->guarantoraddress;
                $meminsert->guarantornamee = $request->guarantornamee;
                $meminsert->guarantornoo = $request->guarantornoo;
                $meminsert->guarantoraddresss = $request->guarantoraddresss;
                $meminsert->agentId = Session::get('adminloginid');
                $meminsert->updatedBy = Session::get('adminloginid');
                $meminsert->updatedbytype = Session::get('user_type');
                $meminsert->sessionId     = Session::get('sessionof');
                $meminsert->save();


                $loandetail = loan_masters::find($request->loan);


                // if ($request->insType == 'Monthly') {


                $loanamount = $request->amount;
                $rateofinterest = $request->interest;
                $tanure = $request->months;


                $monthly_roi = $rateofinterest * $tanure;
                $monthlyPrincipaltotal = 0;
                $monthlyInstallmenttottal = 0;
                $monthlyround = 0;

                $monthlyInterest = ((($loanamount * $monthly_roi) / 100) / $tanure);
                $totalintrest = $monthlyInterest * $tanure;
                $principlleadd = $loanamount + $totalintrest;
                $monthlyInstallment = $principlleadd / $tanure;
                $monthlyPrincipal = $monthlyInstallment - $monthlyInterest;
                $startDate = Carbon::parse($emidate);


                for ($i = 0; $i < $tanure; $i++) {
                    $loanInstallment = new loan_installments();
                    $loanInstallment->LoanId = $meminsert->id;

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
                    $loanInstallment->agentId = Session::get('adminloginid');
                    $loanInstallment->updatedBy = Session::get('adminloginid');
                    $loanInstallment->updatedbytype = Session::get('user_type');
                    $loanInstallment->sessionId = Session::get('sessionof');

                    // Save the installment and handle errors
                    if (!$loanInstallment->save()) {
                        // Handle error
                    }
                }

                // } else {
                //     return back();
                // }

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
                $ledgerme->agentId = Session::get('adminloginid');
                $ledgerme->updatedBy = Session::get('adminloginid');
                $ledgerme->updatedbytype = Session::get('user_type');
                $ledgerme->sessionId     = Session::get('sessionof');
                $ledgerme->save();

                $ledgerme = new general_ledgers();
                $ledgerme->LoanId = $meminsert->id;
                $ledgerme->accountNo = $request->accountNo;
                $ledgerme->groupCode = $request->paymentby;
                $ledgerme->ledgerCode = $request->cashbanktype;
                $ledgerme->formName = $request->name;
                $ledgerme->formName = $request->name;
                $ledgerme->transactionDate = $newdate;;
                $ledgerme->transactionType = 'Cr';
                $ledgerme->transactionAmount = $advancement_amount;
                $ledgerme->narration = $loandetail->loanname;
                $ledgerme->branchId = Null;
                $ledgerme->agentId = Session::get('adminloginid');
                $ledgerme->updatedBy = Session::get('adminloginid');
                $ledgerme->updatedbytype = Session::get('user_type');
                $ledgerme->sessionId     = Session::get('sessionof');
                $ledgerme->save();


                //__________If Processing Fees
                if ($request->processingFee > 0) {
                    $ledhai = ledger_masters::where('loan', '=', $loanmass->id)->first();
                    $ledgerme = new general_ledgers();
                    $ledgerme->LoanId = $meminsert->id;
                    $ledgerme->accountNo = $request->accountNo;
                    $ledgerme->groupCode = 'IINC01';
                    $ledgerme->ledgerCode = 'PRC001';
                    $ledgerme->formName = $request->name;
                    $ledgerme->transactionDate = $newdate;;
                    $ledgerme->transactionType = 'Cr';
                    $ledgerme->transactionAmount = $request->processingFee;
                    $ledgerme->narration = $loandetail->loanname;
                    $ledgerme->branchId = Null;
                    $ledgerme->agentId = Session::get('adminloginid');
                    $ledgerme->updatedBy = Session::get('adminloginid');
                    $ledgerme->updatedbytype = Session::get('user_type');
                    $ledgerme->sessionId     = Session::get('sessionof');
                    $ledgerme->save();

                    $ledgerme = new general_ledgers();
                    $ledgerme->LoanId = $meminsert->id;
                    $ledgerme->accountNo = $request->accountNo;
                    $ledgerme->groupCode = $request->paymentby;
                    $ledgerme->ledgerCode = $request->cashbanktype;
                    $ledgerme->formName = $request->name;
                    $ledgerme->transactionDate = $newdate;;
                    $ledgerme->transactionType = 'Dr';
                    $ledgerme->transactionAmount = $request->processingFee;
                    $ledgerme->narration = $loandetail->loanname;
                    $ledgerme->branchId = Null;
                    $ledgerme->agentId = Session::get('adminloginid');
                    $ledgerme->updatedBy = Session::get('adminloginid');
                    $ledgerme->updatedbytype = Session::get('user_type');
                    $ledgerme->sessionId     = Session::get('sessionof');
                    $ledgerme->save();
                }


                return response()->json([
                    'status' => true,
                    'id' => $request->accountNo
                ]);
            }
        } else {
            return view('advancement')->with($data);
        }
    }



    public function getloanname(Request $request)
    {
        return response()->json(loan_masters::find($request->id));
    }



    public function getintrest(Request $request)
    {

        if (!empty($request->date)) {
            $cdatee = date("Y-m-d", strtotime($request->date));
            $cdatePlusOneDay = Carbon::createFromFormat('Y-m-d', $cdatee);
            $cdate = $cdatePlusOneDay->format('Y-m-d');
        } else {
            $startDate = Carbon::today();
            $cdate = $startDate->toDateString();
        }
        $member_loans = member_loans::find($request->id);
        $penaltyInteresttype = $member_loans->penaltyInteresttype;
        $penaltyInterest = $member_loans->penaltyInterest;
        $paneltywithinstallment = 0;
        $totalinstallment = 0;
        $totalpanelty = 0;
        $totalpaneltywithinstallment = 0;
        $totalintrest  = 0;
        $totalprinciple = 0;


        $allinst = loan_installments::where('LoanId', '=', $request->id)
            ->where('installmentDate', '<=', $cdate)
            ->where('status', '!=', 'paid')
            ->orderBy('installmentDate', 'asc')
            ->get();

        $installmentDate = '';

        foreach ($allinst as $row) {
            $installmentDate = $row->installmentDate;
            $installment = $row->totalinstallmentamount;
            $panelty = 0;
            $paneltywithinstallment = $installment;
            if ($installmentDate > $cdate) {
                if ($penaltyInteresttype == 'percentage') {
                } else {
                    $totalinstallment += $installment;
                    $totalpanelty += $panelty;
                }
            } else {
                if ($penaltyInteresttype == 'percentage') {
                } else {
                    $panelty = $penaltyInterest;
                    $paneltywithinstallment = $installment + $panelty;
                }
                $totalpanelty += $panelty;
            }

            // Calculate total installment and other amounts
            $totalinstallment += $installment;
            $totalpaneltywithinstallment += $paneltywithinstallment;
            $totalintrest += $row->interest;
            $totalprinciple += $row->principal;
        }








        // Get all installments for the loan up to the given date and not paid
        // $allinst = loan_installments::where('LoanId', '=', $request->id)
        //     ->where('installmentDate', '<=', $cdate)
        //     ->where('status', '!=', 'paid')
        //     ->orderBy('installmentDate', 'asc')
        //     ->get();




        // if (sizeof($allinst) > 0) {
        //     $lastInstallmentDate = $allinst->last()->installmentDate;



        //     foreach ($allinst as $allinstlist) {
        //         $panelty=0;
        //         $installmentDate = $allinstlist->installmentDate;

        //         if ($penaltyInteresttype == 'percentage') {
        //         } else {
        //             $installment = $allinstlist->totalinstallmentamount;
        //             $panelty = $penaltyInterest;
        //             $paneltywithinstallment = $installment + $panelty;
        //             $totalintrest = $totalintrest + $allinstlist->interest;
        //             $totalprinciple = $totalprinciple + $allinstlist->principal;
        //         }
        //         $totalinstallment = $totalinstallment + $installment;
        //         $totalpanelty = $totalpanelty + $panelty;
        //         $totalpaneltywithinstallment = $totalpaneltywithinstallment + $paneltywithinstallment;
        //     }


        //     $allinsSAast = loan_installments::where('LoanId', '=', $request->id)->orderBy('installmentDate', 'DESC')->first();
        //     $monthsDiff = Carbon::parse($lastInstallmentDate)->diffInMonths(Carbon::parse($cdate));
        //     dd($monthsDiff);




        //     if ($cdate > $allinsSAast->installmentDate) {
        //         $monthsDiff = Carbon::parse($lastInstallmentDate)->diffInMonths(Carbon::parse($cdate));
        //         for ($i = 1; $i < $monthsDiff; $i++) {
        //             $totalpanelty =   $totalpanelty + $panelty;
        //             $totalpaneltywithinstallment = $totalpaneltywithinstallment + $panelty;
        //         }
        //     }


        // } else {
        //     $allinst = loan_installments::where('LoanId', '=', $request->id)
        //         ->where('status', '!=', 'paid')
        //         // ->where('installmentDate', '<=', $cdatee)
        //         ->orderBy('installmentDate', 'asc')
        //         ->first();



        //     if ($allinst) {
        //         $installmentDate = $allinst->installmentDate;
        //         $totalinstallment = $allinst->principal;
        //         $totalintrest = 0;
        //         $totalprinciple = $allinst->principal;
        //         $totalpanelty = 0;
        //         $totalpaneltywithinstallment = $allinst->principal;
        //     } else {
        //         $installmentDate = 0;
        //         $totalinstallment = 0;
        //         $totalintrest = 0;
        //         $totalprinciple = 0;
        //         $totalpanelty = 0;
        //         $totalpaneltywithinstallment = 0;
        //         $installmentDate = date('Y-m-d');
        //     }
        // }
        //   $loan_recoveries=loan_recoveries::where('loanId','=',$request->id)->get();

        $loan_recoveries = DB::table('loan_recoveries')
            ->join('agent_masters', 'loan_recoveries.agentId', '=', 'agent_masters.id')
            ->select('loan_recoveries.*', 'agent_masters.name as agent_name')
            ->where('loan_recoveries.loanId', $request->id)
            ->get();


        $loaninst = loan_installments::where('LoanId', '=', $request->id)->sum('principal');
        $loanrecoveries = loan_recoveries::where('loanId', '=', $request->id)->sum('principal');
        $remaining = round($loaninst - $loanrecoveries);
        return response()->json([
            'status' => true,
            'installmentDate' => $installmentDate,
            'totalintrest' => round($totalintrest, 2),
            'totalprinciple' => round($totalprinciple, 2),
            'totalinstallment' => round($totalinstallment, 2),
            'totalpanelty' => round($totalpanelty, 2),
            'totalpaneltywithinstallment' => round($totalpaneltywithinstallment, 2),
            'loan_recoveries' => $loan_recoveries,
            'thisid' => $request->id,
            'remaining' => $remaining,
        ]);
    }



    public function getemi(Request $request)
    {
        $loanId = $request->input('id');

        // Fetch installment details based on loan ID
        $installments = loan_installments::where('LoanId', $loanId)->get();

        return response()->json([
            'installments' => $installments
        ]);
    }

    public function getdetail(Request $request)
    {

        $detail = member_accounts::where('customer_Id', '=', $request->accountno)
            ->select('customer_Id', 'name', 'father_husband', 'father_husband', 'loan_limit', 'mobile_first', 'address', 'city', 'state')
            ->first();

        // $member_loans = DB::table('member_loans')
        // ->join('loan_masters', 'loan_masters.id', '=', 'member_loans.loanType')
        // ->where('member_loans.accountNo', '=', $request->accountno)
        // ->select('member_loans.*', 'loan_masters.loanname') // Select fields as needed
        // ->get();


        $member_loans = DB::table('member_loans')
            ->join('loan_masters', 'loan_masters.id', '=', 'member_loans.loanType')
            ->join('agent_masters', 'agent_masters.id', '=', 'member_loans.agentId')
            ->where('member_loans.accountNo', '=', $request->accountno)
            ->select(
                'member_loans.*',
                'loan_masters.loanname',
                'agent_masters.name as agent_name',
                DB::raw('COALESCE((SELECT SUM(principal) FROM loan_recoveries WHERE loan_recoveries.loanId = member_loans.id), 0) as total_recovered')
            )
            ->orderBy('member_loans.loanDate', 'DESC') // Order by loanDate in ascending order
            ->get();
        return response()->json([
            'status' => true,
            'detail' => $detail,
            'member_loans' => $member_loans

        ]);
    }


    //______________Search by Name
    public function getCustomerDetails(Request $post)
    {
        $customer_name = $post->customer_name;

        $details = DB::table('member_accounts')
            ->where('name', 'LIKE', $customer_name . '%')
            ->get();

        if (!empty($details)) {
            return response()->json(['status' => 'success', 'details' => $details]);
        } else {
            return response()->json(['status' => 'Fail', 'messages' => 'Record Not Found']);
        }
    }


    //______________Select by Name
    public function getDetails(Request $post)
    {
        $customer_id = $post->selected_id;

        $details = member_accounts::where('customer_Id', '=', $customer_id)
            ->select(
                'customer_Id',
                'name',
                'father_husband',
                'father_husband',
                'loan_limit',
                'mobile_first',
                'address',
                'city',
                'state'
            )
            ->first();


        $member_loans = DB::table('member_loans')
            ->join('loan_masters', 'loan_masters.id', '=', 'member_loans.loanType')
            ->join('agent_masters', 'agent_masters.id', '=', 'member_loans.agentId')
            ->where('member_loans.accountNo', '=', $customer_id)
            ->select(
                'member_loans.*',
                'loan_masters.loanname',
                'agent_masters.name as agent_name',
                DB::raw('COALESCE((SELECT SUM(principal) FROM loan_recoveries WHERE loan_recoveries.loanId = member_loans.id), 0) as total_recovered')
            )
            ->orderBy('member_loans.loanDate', 'DESC')
            ->get();

        return response()->json([
            'status' => true,
            'details' => $details,
            'member_loans' => $member_loans

        ]);
    }

    private function adjustEmiDate($date)
    {
        $dayInMonth = $date->daysInMonth;
        return ($date->day > $dayInMonth) ? $date->endOfMonth()->toDateString() : $date->toDateString();
    }

    public function checkLoanLimit(Request $post)
    {
        $customer_id = $post->customer_id;
        $loan_amount = $post->loan_amount;
        $loan_limit = DB::table('member_accounts')->where('customer_Id', '=', $customer_id)->value('loan_limit');

        $total_loan_amount = DB::table('member_loans')
            ->where('accountNo', '=', $customer_id)
            ->sum('loanAmount');

        $loan_ids = DB::table('member_loans')
            ->where('accountNo', '=', $customer_id)
            ->pluck('id');

        $total_loan_recovery = DB::table('loan_recoveries')
            ->whereIn('loanId', $loan_ids)
            ->sum('principal');

        $loan = $total_loan_amount;
        $recovery = $total_loan_recovery;
        $bal_amount = $loan - $recovery;
        $remaining_limit = $loan_limit - $bal_amount;

        return response()->json([
            'status' => 'success',
            'remaining_limit' => $remaining_limit
        ]);
    }

    public function gecustomertloans(Request $post)
    {
        $accountNo = $post->id;

        $loans = DB::table('member_loans')->where('accountNo', $accountNo)->where('status', '=', 'Disbursed')->first();
        if ($loans) {
            $loan_installments = DB::table('loan_installments')->where('LoanId', $loans->id)->get();
            return response()->json(['status' => 'success', 'loanInstallments' => $loan_installments]);
        } else {
            return response()->json(['status' => 'Fail', 'messages' => 'Record Not Found']);
        }
    }

    public function checkduplicateentryaccount(Request $post)
    {
        // Validation Rules
        $rules = [
            "currentDate" => "required|date",
            "accountno" => "required",
            "enteredamount" => "required|numeric",
        ];

        // Validator
        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'Fail',
                'messages' => $validator->errors()->all()
            ]);
        }

        // Extract Inputs
        $date = date('Y-m-d', strtotime($post->currentDate));
        $accountNo = $post->accountno;
        $enteredamount = $post->enteredamount;

        // Check Loan Status
        $loan = DB::table('member_loans')
            ->where('accountNo', $accountNo)
            ->where('status', '=', 'Disbursed')
            ->first();

        if ($loan) {

            $loan_recovery = DB::table('loan_recoveries')
                ->where('loanId', $loan->id)
                ->whereDate('receiptDate', '=', $date)
                ->where('total', '=', $enteredamount)
                ->first();

            if ($loan_recovery) {
                return response()->json([
                    'status' => 'success',
                    'messages' => 'An entry with the same account, date, and amount already exists.'
                ]);
            } else {
                return response()->json([
                    'status' => 'success',
                    'messages' => 'No duplicate entry found. You can proceed with this payment.'
                ]);
            }
        } else {
            return response()->json([
                'status' => 'Fail',
                'messages' => 'No disbursed loan found for the provided account number.'
            ]);
        }
    }

    public function getinterestType(Request $post)
    {
        $interestType = $post->interestType;
        $customer_Id = $post->customer_Id;
        $currentdate = date('Y-m-d', strtotime($post->currentdate));

        $memberLoan = DB::table('member_loans')
            ->where('accountNo', $customer_Id)
            ->whereDate('loanDate', '<=', $currentdate)
            ->where('status', '=', 'Disbursed')
            ->first();

        $currentdate = new DateTime(date('Y-m-d', strtotime($post->currentdate)));
        $loanDate = new DateTime($memberLoan->loanDate);

        $differencemonth = $loanDate->diff($currentdate)->m + ($loanDate->diff($currentdate)->y * 12);



        $recoveries = DB::table('loan_recoveries')
            ->where('loanId', $memberLoan->id)
            ->whereDate('receiptDate', '<=', $currentdate)
            ->get();


        return response()->json([
            'status' => 'success',
            'memberLoan' => $memberLoan,
            'recoveries' => $recoveries ? $recoveries : 0,
            'differencemonth' => $differencemonth
        ]);
    }


    public function getcashbankledgers(Request $post)
    {
        $group = $post->group;

        $ledgers = DB::table('ledger_masters')->where('groupCode', $group)->get();
        if (!empty($ledgers)) {
            return response()->json(['status' => 'success', 'ledgers' => $ledgers]);
        } else {
            return response()->json(['status' => 'Fail', 'messages' => 'Record Not Found']);
        }
    }
}
