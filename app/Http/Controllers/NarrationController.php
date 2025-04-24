<?php

namespace App\Http\Controllers;

use App\Models\Narration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Toastr;

class NarrationController extends Controller
{
    public function narration(Request $request)
    {
        $formurl = url('narration');
        $pagetitle = "Narration Master";
        $pageto = url('narration');
        $narrations = Narration::all();
        $data = compact('formurl','pagetitle','pageto','narrations');

        if($request->all())
        {
            $request->validate([
                'name' => 'required',
                'status' => 'required'
            ]);

            $narration = new Narration();
            $narration->name = $request->name;
            $narration->status = $request->status;
            $narration->updatedBy = Session::get('adminloginid');
            $narration->is_delete = 'No';
            $narration->save();
            Toastr::success('Narration Created Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('narration')->with('success', 'Data Inserted Successfully');
        }else{
            return view('narration')->with($data);
        }
    }


    public function edinarration(Request $request , $id)
    {
        $formurl = url('edinarration/'.$id);
        $pagetitle = "Narration Master";
        $pageto = url('edinarration');
        $narrations = Narration::all();
        $narrationId = Narration::find($id);
        $data = compact('formurl','pagetitle','pageto','narrations','narrationId');

        if($request->all())
        {
            $editNarration = Narration::find($id);
            $editNarration->name = $request->name;
            $editNarration->status = $request->status;
            $editNarration->updatedBy = Session::get('adminloginid');
            $editNarration->is_delete = 'No';
            $editNarration->save();
            Toastr::success('Narration Edit Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('narration')->with('success', 'Data Inserted Successfully');
        }
        return view('narration')->with($data);
    }

    public function deletenarration($id)
    {
        $narrationId = Narration::find($id);
        if(is_null($narrationId))
        {
            Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('fail', 'No Record Found');

        }else{
            $narrationId->delete();
            Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('success', 'Delete Successfully');
        }
    }



}
