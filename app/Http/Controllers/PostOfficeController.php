<?php

namespace App\Http\Controllers;

use App\Models\district_masters;
use App\Models\state_masters;
use App\Models\post_office_masters;
use App\Models\tehsil_masters;
use Illuminate\Http\Request;
use Toastr;
class PostOfficeController extends Controller
{
    public function postoffice(Request $request){
        $formurl=url('postoffice');
        $pagetitle="District Master";
        $pageto=url('postoffice');
        $district="";
        $tehsil_masters="";
        $post_office_masters=post_office_masters::all();
        $state_masters=state_masters::where('status','=','Active')->get();
        $data=compact('formurl','pagetitle','pageto','district','state_masters','post_office_masters','tehsil_masters');
        if(!empty($request->all())){

                $up=new post_office_masters();
                $up->stateId=$request->stateId;
                $up->districtId=$request->districtId;
                $up->tehsilId=$request->tehsilId;
                $up->name=$request->name;
                $up->pincode=$request->pincode;
                $up->status=$request->status;
                $up->save();




            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('postoffice')->with('success', 'Data Inserted Successfully');
        }else{
            return view('postoffice')->with($data);
        }
    }
    public function editpostoffice($id,Request $request){
        $formurl=url('editpostoffice/'.$id);
        $pagetitle="District Master";
        $pageto=url('postoffice');

        $post_office_mastersid=post_office_masters::find($id);
        $district=district_masters::where('stateId','=',$post_office_mastersid->stateId)->where('status','=','Active')->get();
        $tehsil_masters=tehsil_masters::where('stateId','=',$post_office_mastersid->stateId)->where('districtId','=',$post_office_mastersid->districtId)->where('status','=','Active')->get();
        $state_masters=state_masters::where('status','=','Active')->get();
        $post_office_masters=post_office_masters::all();
        $data=compact('formurl','pagetitle','pageto','district','state_masters','post_office_masters','post_office_mastersid','tehsil_masters');
        if(!empty($request->all())){

                $up=post_office_masters::find($id);
                $up->stateId=$request->stateId;
                $up->districtId=$request->districtId;
                $up->tehsilId=$request->tehsilId;
                $up->name=$request->name;
                $up->pincode=$request->pincode;
                $up->status=$request->status;
                $up->save();
            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('postoffice')->with('success', 'Data Inserted Successfully');
        }else{
            return view('postoffice')->with($data);
        }
    }
    public function deletepostoffice($id){



        $post_office_masters = post_office_masters::find($id);
        if (is_null($post_office_masters)) {
        Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('fail', 'No Record Found');
        } else {
            $post_office_masters->delete();
            Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('success', 'Delete Successfully');
        }


    }
}
