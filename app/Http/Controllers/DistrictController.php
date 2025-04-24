<?php

namespace App\Http\Controllers;

use App\Models\district_masters;
use App\Models\state_masters;
use Illuminate\Http\Request;
use Toastr;
class DistrictController extends Controller
{


    public function district(Request $request){
        $formurl=url('district');
        $pagetitle="District Master";
        $pageto=url('district');
        $district=district_masters::all();
        $state_masters=state_masters::where('status','=','Active')->get();
        $data=compact('formurl','pagetitle','pageto','district','state_masters');
        if(!empty($request->all())){
            $up=new district_masters();
            $up->stateId=$request->stateId;
            $up->name=$request->name;
            $up->status=$request->status;
            $up->save();
            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('district')->with('success', 'Data Inserted Successfully');
        }else{
            return view('district')->with($data);
        }
    }
    public function editdistrict($id,Request $request){
        $formurl=url('editdistrict/'.$id);
        $pagetitle="District Master";
        $pageto=url('district');
        $districtid=district_masters::find($id);
        $district=district_masters::all();
        $state_masters=state_masters::where('status','=','Active')->get();
        $data=compact('formurl','pagetitle','pageto','district','districtid','state_masters');
        if(!empty($request->all())){
           $up=district_masters::find($id);
           $up->stateId=$request->stateId;
           $up->name=$request->name;
           $up->status=$request->status;
           $up->save();
           Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
           return redirect('district')->with('success', 'Data Inserted Successfully');
        }else{
            return view('district')->with($data);
        }
    }
    public function deletedistrict($id){

         $district_masters = district_masters::find($id);
            if (is_null($district_masters)) {
            Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
                return back()->with('fail', 'No Record Found');
            } else {
                $district_masters->delete();
                Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
                return back()->with('success', 'Delete Successfully');
            }

      }



}
