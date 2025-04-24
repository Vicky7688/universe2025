<?php

namespace App\Http\Controllers;

use App\Models\groups;
use App\Models\codes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Toastr;
class groupcontroller extends Controller
{
    public function group(Request $request)
    {
        $formurl = url('group');
        $pagetitle = "Group Master";
        $pageto = url('group');
        $groupslist = groups::all();
        $data = compact('formurl','pagetitle','pageto','groupslist');

        if(!empty($request->all()))
        {
            $request->validate([
                'name' => 'required|unique:group_masters,name,'.$request->name,
                'group_code' => 'required|unique:group_masters,groupCode,'.$request->group_code
            ]);

                $groups = new groups();
                $groups->name = $request->name;
                $groups->groupCode = $request->group_code;
                $groups->headName = $request->name;
                $groups->type = $request->type;
                // $groups->showJournalVoucher = $request->showJournalVoucher;
                $groups->status = $request->status;
                $groups->dr_cr = $request->nature;
                $groups->can_delete = 'Yes';
                $groups->updatedBy = Session::get('adminloginid');
                $groups->is_delete = 'No';
                $groups->save();

                Toastr::success('Group Created Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                return redirect('group')->with('success', 'Data Inserted Successfully');
        }else{
            return view('addgroup')->with($data);
        }
    }


    //++++++++++++++++ Generate Group Code
    public function generateGroupCode(Request $request)
    {
        $firstName = strtoupper($request->groupName);
        $newGroupCode = '';
        if ($firstName) {
            $lastname = substr($firstName, 0, 3);
            $lastGroupCode = groups::where('groupCode', 'LIKE', $lastname . '%')
                ->where('status', '=', 'Active')
                ->orderBy('groupCode', 'desc')
                ->first();

            if ($lastGroupCode) {
                $lastGroupNumber = intval(substr($lastGroupCode->groupCode, 3));
                $groups = $lastGroupNumber + 1;
            } else {
                $groups = 1;
            }
            $newGroupCode = $lastname . str_pad($groups, 3, '0', STR_PAD_LEFT);
        }

        return response()->json(['status' => 'success', 'groupCode' => $newGroupCode]);
    }


    public function editgroups(Request $request,$id)
    {
        $formurl = url('editgroups/'.$id);
        $pagetitle = "Group Master";
        $pageto = url('group');
        $groupslist = groups::all();
        $groupsid = groups::find($id);
        $data = compact('formurl','pagetitle','pageto','groupslist','groupsid');

        if($request->all())
        {
            $editgroup = groups::find($id);
            $editgroup->name = $request->name;
            $editgroup->headName = $request->name;
            $editgroup->type = $request->type;
            $editgroup->status = $request->status;
            $editgroup->dr_cr = $request->nature;
            $editgroup->can_delete = 'Yes';
            $editgroup->updatedBy = Session::get('adminloginid');
            $editgroup->is_delete = 'No';
            $editgroup->save();

            Toastr::success('Group Created Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('group')->with('success', 'Data Inserted Successfully');
        }
        return view('addgroup')->with($data);
    }

    public function deletegroups($id,Request $request)
    {
        $groupId = groups::find($id);
        
        if(is_null($groupId))
        {
            Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('fail', 'No Record Found');
        }else{
            $groupId->delete();
            Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('success', 'Delete Successfully');
        }
    }

}
