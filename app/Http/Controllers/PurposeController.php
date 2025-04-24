<?php

namespace App\Http\Controllers;

use App\Models\purpose_masters;
use Illuminate\Http\Request;
use Session;
use Toastr;
class PurposeController extends Controller
{


    public function purposemaster(Request $request){

        $pagetitle="Purpose Master";
        $pageto=url('purposemaster');
        $purpose_masters=purpose_masters::all();
        $formurl=url('purposemaster');
        $data=compact('formurl','purpose_masters','pagetitle','pageto');

                if (!empty($request->all())) {


                    $branchsin = new purpose_masters();
                    $branchsin->name = $request->name;
                    $branchsin->status = $request->status;
                    $branchsin->updatedBy = Session::get('adminloginid');
                    $branchsin->updatedbytype = Session::get('logintype');
                    $branchsin->session = Session::get('sessionof');
                    $branchsin->save();
                    Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                    return redirect('purposemaster')->with('success', 'Data Inserted Successfully');
                }
                else{
                    return view('purposemaster')->with($data);
                }
    }

    public function epurposemaster($id,Request $request){

        $pagetitle="Purpose Master";
        $pageto=url('purposemaster');
        $purpose_masters=purpose_masters::all();
        $purpose_mastersid=purpose_masters::find($id);
        $formurl=url('purposemaster/'.$id);
        $data=compact('formurl','purpose_masters','pagetitle','pageto','purpose_mastersid');

            if (!empty($request->all())) {


                $branchsin =purpose_masters::find($id);
                $branchsin->name = $request->name;
                $branchsin->status = $request->status;
                $branchsin->updatedBy = Session::get('adminloginid');
                $branchsin->updatedbytype = Session::get('logintype');
                $branchsin->session = Session::get('sessionof');
                $branchsin->save();
                Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                return redirect('purposemaster')->with('success', 'Data Inserted Successfully');
            }
    else{
        return view('purposemaster')->with($data);
    }
    }
    public function deletepurposemaster($id){

        $branchId = purpose_masters::find($id);
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
