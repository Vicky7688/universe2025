<?php

namespace App\Http\Controllers;

use App\Models\district_masters;
use App\Models\state_masters;
use App\Models\tehsil_masters;
use Illuminate\Http\Request;
use Toastr;
class TehsilController extends Controller
{
    public function tehsil(Request $request){
        $formurl=url('tehsil');
        $pagetitle="District Master";
        $pageto=url('tehsil');
        $district="";
        $state_masters=state_masters::where('status','=','Active')->get();
        $tehsil_masters=tehsil_masters::all();
        $data=compact('formurl','pagetitle','pageto','district','state_masters','tehsil_masters');
        if(!empty($request->all())){

                $up=new tehsil_masters();
                $up->stateId=$request->stateId;
                $up->districtId=$request->districtId;
                $up->name=$request->name;
                $up->status=$request->status;
                $up->save();
            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('tehsil')->with('success', 'Data Inserted Successfully');
        }else{
            return view('tehsil')->with($data);
        }
    }
    public function edittehsil($id,Request $request){
        $formurl=url('edittehsil/'.$id);
        $pagetitle="District Master";
        $pageto=url('tehsil');

        $tehsil_mastersid=tehsil_masters::find($id);
        $district=district_masters::where('stateId','=',$tehsil_mastersid->stateId)->where('status','=','Active')->get();
        $state_masters=state_masters::where('status','=','Active')->get();
        $tehsil_masters=tehsil_masters::all();
        $data=compact('formurl','pagetitle','pageto','district','state_masters','tehsil_masters','tehsil_mastersid');
        if(!empty($request->all())){

                $up=tehsil_masters::find($id);
                $up->stateId=$request->stateId;
                $up->districtId=$request->districtId;
                $up->name=$request->name;
                $up->status=$request->status;
                $up->save();
            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('tehsil')->with('success', 'Data Inserted Successfully');
        }else{
            return view('tehsil')->with($data);
        }
    }
    public function deletetehsil($id){



        $tehsil_masters = tehsil_masters::find($id);
        if (is_null($tehsil_masters)) {
        Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('fail', 'No Record Found');
        } else {
            $tehsil_masters->delete();
            Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('success', 'Delete Successfully');
        }


    }
}
