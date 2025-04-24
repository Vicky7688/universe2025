<?php

namespace App\Http\Controllers;

use App\Models\loan_type_masters;
use Illuminate\Http\Request;
use Session;
use Toastr;

class LoanTypeController extends Controller
{




    public function loantype(Request $request){

        $pagetitle="Loan Type";
        $pageto=url('loantype');
        $loan_type_masters=loan_type_masters::all();
        $formurl=url('loantype');
        $data=compact('formurl','loan_type_masters','pagetitle','pageto');

                if (!empty($request->all())) {


                    $branchsin = new loan_type_masters();
                    $branchsin->name = $request->name;
                    $branchsin->status = $request->status;
                    $branchsin->updatedBy = Session::get('adminloginid');
                    $branchsin->updatedbytype = Session::get('logintype');
                    $branchsin->session = Session::get('sessionof');
                    $branchsin->save();
                    Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                    return redirect('loantype')->with('success', 'Data Inserted Successfully');
                }
                else{
                    return view('loantype')->with($data);
                }
    }

    public function editloantype($id,Request $request){

        $pagetitle="Loan Type";
        $pageto=url('loantype');
        $loan_type_masters=loan_type_masters::all();
        $loan_type_mastersid=loan_type_masters::find($id);
        $formurl=url('loantype/'.$id);
        $data=compact('formurl','loan_type_masters','pagetitle','pageto','loan_type_mastersid');

    if (!empty($request->all())) {


        $branchsin =loan_type_masters::find($id);
        $branchsin->name = $request->name;
        $branchsin->status = $request->status;
        $branchsin->updatedBy = Session::get('adminloginid');
        $branchsin->updatedbytype = Session::get('logintype');
        $branchsin->session = Session::get('sessionof');
        $branchsin->save();
        Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
        return redirect('loantype')->with('success', 'Data Inserted Successfully');
    }
    else{
        return view('loantype')->with($data);
    }
    }
    public function deleteloantype($id){

        $branchId = loan_type_masters::find($id);
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
