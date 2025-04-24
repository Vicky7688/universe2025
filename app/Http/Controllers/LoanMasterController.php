<?php

namespace App\Http\Controllers;

use App\Models\loan_masters;
use App\Models\ledger_masters;
use App\Models\loan_type_masters;
use Illuminate\Http\Request;
use Session;
use Toastr;
class LoanMasterController extends Controller
{






    public function loanmaster(Request $request){

        $pagetitle="Loan Master";
        $pageto=url('loanmaster');
        $loan_masters=loan_masters::all();
        $loan_type_masters=loan_type_masters::where('status','=','Active')->get();
        $formurl=url('loanmaster');
        $data=compact('formurl','loan_masters','pagetitle','pageto','loan_type_masters');

                if (!empty($request->all())) {
                    $branchsin = new loan_masters();
                    $branchsin->loanname = $request->loanname;
                    $branchsin->processingFee = $request->processingFee;
                    $branchsin->interest = $request->interest;
                    $branchsin->paneltytype = $request->paneltytype;
                    $branchsin->penaltyInterest = $request->penaltyInterest;
                    $branchsin->insType = $request->insType;
                    $branchsin->years = $request->years;
                    $branchsin->months = $request->months;
                    $branchsin->days = $request->days;
                    $branchsin->status = $request->status;
                    $branchsin->updatedBy = Session::get('adminloginid');
                    $branchsin->updatedbytype = Session::get('logintype');
                    $branchsin->session = Session::get('sessionof');
                    $branchsin->save();

                    $maxId = ledger_masters::max('id');
                    $inled=new ledger_masters();
                    $inled->groupCode='LOAN01';
                    $inled->name=$request->loanname;
                    $inled->ledgerCode='LEDLOAN'.$maxId+1;
                    $inled->openingAmount='0.00';
                    $inled->openingType='Cr';
                    $inled->status='Active';
                    $inled->is_delete='No';
                    $inled->loan=$branchsin->id;
                    $inled->loan_intt_id='';
                    $inled->save();


                    $maxId = ledger_masters::max('id');
                    $inled=new ledger_masters();
                    $inled->groupCode='DINC01';
                    $inled->name='Intt.on'.$request->loanname;
                    $inled->ledgerCode='LEDLOAN'.$maxId+1;
                    $inled->openingAmount='0.00';
                    $inled->openingType='Cr';
                    $inled->status='Active';
                    $inled->is_delete='No';
                    $inled->loan=null;
                    $inled->loan_intt_id=$branchsin->id;
                    $inled->save();

                    Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                    return redirect('loanmaster')->with('success', 'Data Inserted Successfully');
                }
                else{
                    return view('loanmasters')->with($data);
                }
    }








    public function edloanmaster($id,Request $request){

        $pagetitle="Loan Type";
        $pageto=url('loanmaster');
        $loan_masters=loan_masters::all();
        $loan_mastersid=loan_masters::find($id);
        $loan_type_masters=loan_type_masters::where('status','=','Active')->get();
        $formurl=url('loanmaster/'.$id);
        $data=compact('formurl','loan_masters','pagetitle','pageto','loan_type_masters','loan_mastersid');

                if (!empty($request->all())) {

                    $branchsin = loan_masters::find($id);
                    $branchsin->loanname = $request->loanname;
                    $branchsin->processingFee = $request->processingFee;
                    $branchsin->interest = $request->interest;
                    $branchsin->penaltyInterest = $request->penaltyInterest;
                    $branchsin->paneltytype = $request->paneltytype;
                    $branchsin->insType = $request->insType;
                    $branchsin->years = $request->years;
                    $branchsin->months = $request->months;
                    $branchsin->days = $request->days;
                    $branchsin->years = $request->years;
                    $branchsin->status = $request->status;
                    $branchsin->updatedBy = Session::get('adminloginid');
                    $branchsin->updatedbytype = Session::get('logintype');
                    $branchsin->session = Session::get('sessionof');
                    $branchsin->save();

                    Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                    return redirect('loanmaster')->with('success', 'Data Inserted Successfully');
                }
                else{
                    return view('loanmasters')->with($data);
                }
    }





    public function deleteloanmaster($id){


        $branchId = loan_masters::find($id);
        if(is_null($branchId))
        {
            Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('fail', 'No Record Found');
        }else{
            $branchId->delete();
            Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('success', 'Delete Successfully');
        }

    }


}
