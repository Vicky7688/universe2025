<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyCollection;
use Illuminate\Support\Facades\Session;
use Toastr;

class DailyCollectioncontroller extends Controller
{
    //+++++++++++ Daily Collection Insert Sechme & View Page
    public function dailyCollSechme(Request $request)
    {
        $pagetitle = "Daily Collection Scheme";
        $pageto = url('dailyCollSechme');
        $dailyCollection = DailyCollection::all();
        $formurl = url('dailyCollSechme');
        $data = compact('formurl','dailyCollection','pagetitle','pageto');

        if($request->all())
        {
            $collection_sechme = new DailyCollection();
            $collection_sechme->scheme_name = $request->scheme_name;
            $collection_sechme->scheme_type = $request->scheme_type;
            $collection_sechme->durration = $request->durration;
            $collection_sechme->interest = $request->interest;
            $collection_sechme->penalty = $request->penalty;
            $collection_sechme->status = $request->status;
            $collection_sechme->updatedBy = Session::get('adminloginid');
            $collection_sechme->updatedbytype = Session::get('logintype');
            $collection_sechme->session = Session::get('sessionof');
            $collection_sechme->is_delete = 'No';
            $collection_sechme->save();
            Toastr::success('Data Created Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('dailyCollSechme')->with('success', 'Data Inserted Successfully');
        }

        return view('dailyCollectionMaster')->with($data);
    }


    //+++++++++++ Daily Collection Edit/Update Sechme

    public function editdailyCollSechme(Request $request ,$id)
    {
        $pagetitle = "Daily Collection Scheme";
        $pageto = url('dailyCollectionMaster');
        $formurl = url('editdailyCollSechme/'.$id);
        $dailyCollection = DailyCollection::all();
        $sechmeId = DailyCollection::find($id);
        $data = compact('formurl','dailyCollection','pagetitle','pageto','sechmeId');

        if($request->all())
        {
            $collection_sechme = DailyCollection::find($id);
            $collection_sechme->scheme_name = $request->scheme_name;
            $collection_sechme->scheme_type = $request->scheme_type;
            $collection_sechme->durration = $request->durration;
            $collection_sechme->interest = $request->interest;
            $collection_sechme->penalty = $request->penalty;
            $collection_sechme->status = $request->status;
            $collection_sechme->updatedBy = Session::get('adminloginid');
            $collection_sechme->updatedbytype = Session::get('logintype');
            $collection_sechme->session = Session::get('sessionof');
            $collection_sechme->is_delete = 'No';
            $collection_sechme->save();
            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('dailyCollSechme')->with('success', 'Data Inserted Successfully');
        }

        return view('dailyCollectionMaster')->with($data);
    }


    //++++++++++ Delete Sechme
    public function deletedailyCollSechme($id)
    {
        $sechmeId = DailyCollection::find($id);
        if(is_null($sechmeId))
        {
            Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('fail', 'No Record Found');
        }else{
            $sechmeId->is_delete = 'Yes';
            $sechmeId->save();
            $sechmeId->delete();
            Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('success', 'Delete Successfully');
        }
    }
}
