<?php

namespace App\Http\Controllers;

use App\Models\branchs;
use App\Models\states;
use App\Models\state_masters;
use App\Models\district_masters;
use App\Models\tehsil_masters;
use App\Models\post_office_masters;
use App\Models\village_masters;
use App\Models\session_masters;
use Illuminate\Support\Facades\Session;
use Toastr;
use Illuminate\Http\Request;
class branchcontroller extends Controller
{
    public function update(Request $request){

        switch ($request->actiontype) {
            case 'getdistrict':
                return response()->json(['status' => "success", "dist" => district_masters::where('status','=','Active')->where("stateId", $request->stateid)->get()], 200);
                break;
            case 'gettehsil':
                return response()->json(['status' => "success", "data" => tehsil_masters::where('status','=','Active')->where("districtId", $request->distId)->get()], 200);
                break;
            case 'getpostoffice':
                return response()->json(['status' => "success", "data" => post_office_masters::where('status','=','Active')->where("tehsilId", $request->tehsilId)->get()], 200);
                break;
            case 'getvillage':
                return response()->json(['status' => "success", "data" => village_masters::where('status','=','Active')->where("postOfficeId", $request->postOfficeId)->get()], 200);
                break;
            }
    }


    //++++ Add Branch
    public function addbranch(Request $request){
            $pagetitle="Head Office";
            $pageto=url('addbranch');
            $branch=branchs::all();
            $states=state_masters::where('status','=','active')->get();
            $formurl=url('addbranch');
            $data=compact('formurl','branch','pagetitle','pageto','states');

            // dd($data);
        if (!empty($request->all())) {
            $request->validate(
                        [
                            'name' => 'required'
                        ]
                    );

                    $branchsin = new branchs();
                    $branchsin->registrationDate = date('Y-m-d',strtotime($request->registrationDate));
                    $branchsin->name = $request->name;
                    $branchsin->type = $request->type;
                    $branchsin->registrationNo = $request->registrationNo;
                    $branchsin->branch_code = $request->branch_code;
                    $branchsin->branch_limit = $request->branch_limit;
                    $branchsin->stateId = $request->stateId;
                    $branchsin->districtId = $request->districtId;
                    $branchsin->tehsilId = $request->tehsilId;
                    $branchsin->postOfficeId = $request->postOfficeId;
                    $branchsin->villageId = $request->villageId;
                    $branchsin->phone = $request->phone;
                    $branchsin->pincode = $request->pincode;
                    $branchsin->address = $request->address;
                    $branchsin->updatedBy = Session::get('adminloginid');
                    $branchsin->updatedbytype = Session::get('logintype');
                    $branchsin->session = Session::get('sessionof');
                    $branchsin->save();


                    Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('addbranch')->with('success', 'Data Inserted Successfully');
        } else {
            return view('addbranch')->with($data);
        }
    }


    //+++++++++Edit Branch
    public function editbranch(Request $request , $id)
    {
        $formurl = url('editbranch/'.$id);
        $pagetitle = "Head Office";
        $pageto = url('addbranch');
        $disttt = district_masters::all();
        $tehsils = tehsil_masters::all();
        $postOffices = post_office_masters::all();
        $villages = village_masters::all();
        $branchId = branchs::find($id);
        $states = state_masters::where('status','=','active')->get();
        $data = compact('formurl','branchId','disttt','tehsils','postOffices','villages','pagetitle','pageto','states');

        if(!empty($request->all())){
            $edit = branchs::find($id);
            $edit->registrationDate = date('Y-m-d',strtotime($request->registrationDate));
            $edit->name = $request->name;
            $edit->type = $request->type;
            $edit->registrationNo = $request->registrationNo;
            $edit->branch_code = $request->branch_code;
            $edit->branch_limit = $request->branch_limit;
            $edit->stateId = $request->stateId;
            $edit->districtId = $request->districtId;
            $edit->tehsilId = $request->tehsilId;
            $edit->postOfficeId = $request->postOfficeId;
            $edit->villageId = $request->villageId;
            $edit->phone = $request->phone;
            $edit->pincode = $request->pincode;
            $edit->address = $request->address;
            $edit->updatedBy = Session::get('adminloginid');
            $edit->updatedbytype = Session::get('logintype');
            $edit->session = Session::get('sessionof');
            $edit->save();
            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('addbranch')->with('success', 'Data Inserted Successfully');
        }else{
            return view('addbranch')->with($data);
        }



    }


    //++++++++++++++ Delete Branch
    public function deletebranch($id)
    {
        $branchId = branchs::find($id);
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
