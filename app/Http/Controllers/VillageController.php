<?php

namespace App\Http\Controllers;

use App\Models\district_masters;
use App\Models\post_office_masters;
use App\Models\village_masters;
use Illuminate\Http\Request;
use App\Models\state_masters;
use App\Models\tehsil_masters;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Toastr;

class VillageController extends Controller
{
    public function village(Request $request)
    {
        $formurl=url('village');
        $pagetitle="Village Master";
        $pageto=url('village');
        $district="";
        $tehsil_masters="";
        $post_office_masters=post_office_masters::all();
        $villages = village_masters::all();
        $state_masters=state_masters::where('status','=','Active')->get();
        $data=compact('formurl','pagetitle','pageto','district','state_masters','post_office_masters','tehsil_masters','villages');

        if(!empty($request->all()))
        {
            $validator = Validator::make($request->all(),[
                'village' => 'required'
            ]);

            if($validator->fails())
            {
                return response()->json(['status' => 'fails','messages' => 'Field Required']);
            }

            $village = new village_masters();
            $village->stateId = $request->stateId;
            $village->districtId = $request->districtId;
            $village->tehsilId = $request->tehsilId;
            $village->postOfficeId = $request->postOfficeId;
            $village->name = $request->village;
            $village->status = $request->status;
            $village->updatedBy = Session::get('adminloginid');
            $village->updatedbytype = Session::get('logintype');
            $village->session = Session::get('sessionof');
            $village->save();
            Toastr::success('Data Created Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('village')->with('success', 'Data Inserted Successfully');

        }else{
            return view('village')->with($data);
        }
    }


    public function editvillage($id, Request $request)
    {
        $formurl = url('editvillage/'.$id);
        $pagetitle = "Village Master";
        $pageto = url('editvillage');
        $villageId = village_masters::find($id);
        $state_masters = state_masters::where('status','=','active')->get();
        $district = district_masters::where('id','=',$villageId->districtId)->where('status','=','Active')->get();
        $tehsil_masters = tehsil_masters::where('stateId','=',$villageId->stateId)->where('districtId','=',$villageId->districtId)->where('status','=','Active')->get();
        $post_office_masters = post_office_masters::where('stateId','=',$villageId->stateId)->where('tehsilId','=',$villageId->tehsilId)->where('status','=','Active')->get();
        $data = compact('formurl','pagetitle','pageto','state_masters','district','tehsil_masters','post_office_masters','villageId');

        if(!empty($request->all()))
        {
            $village = village_masters::find($id);
            $village->stateId = $request->stateId;
            $village->districtId = $request->districtId;
            $village->tehsilId = $request->tehsilId;
            $village->postOfficeId = $request->postOfficeId;
            $village->name = $request->village;
            $village->status = $request->status;
            $village->updatedBy = Session::get('adminloginid');
            $village->updatedbytype = Session::get('logintype');
            $village->session = Session::get('sessionof');
            $village->save();
            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('village')->with('success', 'Data Inserted Successfully');
        }else{
            return view('village')->with($data);
        }
    }


    public function deletevillage($id)
    {
        $villageId = village_masters::find($id);
        if(is_null($villageId))
        {
            Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('fail', 'No Record Found');
        }else{
            $villageId->delete();
            Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('success', 'Delete Successfully');
        }
    }
}
