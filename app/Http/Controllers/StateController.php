<?php

namespace App\Http\Controllers;

use App\Models\state_masters;
use Illuminate\Http\Request;
use Toastr;
class StateController extends Controller
{

    public function state(Request $request){
        $formurl=url('state');
        $pagetitle="State Master";
        $pageto=url('state');
        $state=state_masters::all();
        $data=compact('formurl','pagetitle','pageto','state');
        if(!empty($request->all())){
            $up=new state_masters();
            $up->name=$request->name;
            $up->status=$request->status;
            $up->save();
            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('state')->with('success', 'Data Inserted Successfully');
        }else{
            return view('state')->with($data);
        }
    }
    public function editstate($id,Request $request){
        $formurl=url('editstate/'.$id);
        $pagetitle="State Master";
        $pageto=url('state');
        $stateid=state_masters::find($id);
        $state=state_masters::all();
        $data=compact('formurl','pagetitle','pageto','state','stateid');
        if(!empty($request->all())){
           $up=state_masters::find($id);
           $up->name=$request->name;
           $up->status=$request->status;
           $up->save();
           Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
           return redirect('state')->with('success', 'Data Inserted Successfully');
        }else{
            return view('state')->with($data);
        }
    }
    public function deletestate($id){
        
    $state_masters = state_masters::find($id);
    if (is_null($state_masters)) {
       Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
        return back()->with('fail', 'No Record Found');
    } else {
        $state_masters->delete();
        Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
        return back()->with('success', 'Delete Successfully');
    }

      }

}
