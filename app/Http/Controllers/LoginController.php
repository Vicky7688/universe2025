<?php

namespace App\Http\Controllers;

use App\Models\login_masters;
use Illuminate\Http\Request;
use Session;
use Toastr;
use Hash;
class LoginController extends Controller
{


    public function loginmaster(Request $request){

        $pagetitle="login Master";
        $pageto=url('loginmaster');
        $login_masters=login_masters::where('type','!=','superadmin')->get();
        $formurl=url('loginmaster');
        $data=compact('formurl','login_masters','pagetitle','pageto');

                if (!empty($request->all())) {


                    $branchsin = new login_masters();
                    $branchsin->name = $request->name;
                    $branchsin->username = $request->username;
                    $branchsin->password = Hash::make($request->password);
                    $branchsin->status = $request->status;
                    $branchsin->updatedBy = Session::get('adminloginid');
                    $branchsin->updatedbytype = Session::get('logintype');
                    $branchsin->session = Session::get('sessionof');
                    $branchsin->save();
                    Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                    return redirect('loginmaster')->with('success', 'Data Inserted Successfully');
                }
                else{
                    return view('loginmaster')->with($data);
                }
    }

    public function eloginmaster($id,Request $request){

        $pagetitle="login Master";
        $pageto=url('loginmaster');
        $login_masters=login_masters::where('type','!=','superadmin')->get();
        $login_mastersid=login_masters::find($id);
        $formurl=url('loginmaster/'.$id);
        $data=compact('formurl','login_masters','pagetitle','pageto','login_mastersid');

            if (!empty($request->all())) {





                $branchsin =login_masters::find($id);
                $branchsin->name = $request->name;
                $branchsin->username = $request->username;
                if(!empty($request->password)){
                $branchsin->password = Hash::make($request->password);
                }
                $branchsin->status = $request->status;
                $branchsin->updatedBy = Session::get('adminloginid');
                $branchsin->updatedbytype = Session::get('logintype');
                $branchsin->session = Session::get('sessionof');
                $branchsin->save();
                Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                return redirect('loginmaster')->with('success', 'Data Inserted Successfully');
            }
    else{
        return view('loginmaster')->with($data);
    }
    }
    public function deleteloginmaster($id){

        $branchId = login_masters::find($id);
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
