<?php

namespace App\Http\Controllers;

use App\Models\retails;
use App\Models\stategstcodes;
use App\Models\codes;
use App\Models\groups;
use App\Models\ledgers;
use App\Models\city;
use App\Models\unique_emails;
use App\Models\unique_usernames;
use Illuminate\Http\Request;
use Session;
use Hash;
class retailcontroller extends Controller
{

    public function retaillist(){
        $retails=retails::all();
        $data=compact('retails');
        return view('main.retaillist')->with($data);
    }


    public function addretail(Request $request){
        $pagetitle="Customer Login";
        $pageto=url('addretail');
        $formurl=url('addretail');
        $stategstcodes=stategstcodes::orderby('state','ASC')->get();
        $retails=retails::leftJoin('groups', 'retails.groupname', '=', 'groups.id')
        ->select('retails.*', 'groups.name as groups_name')
        ->get();
        $groups=groups::all();
        $citycodes="";
        $data=compact('formurl','stategstcodes','groups','citycodes','pagetitle','pageto','retails');
   if (!empty($request->all())) { 
       $request->validate(
                [
               'retailercode' => 'required',
               'name' => 'required',
               'contactperson' => 'required',
               'phone' => 'required',
               'designation' => 'required',
               'gstno' => 'required',
               'email' => 'required',
               'ubuser' => 'required',
               'joiningdate' => 'required',
               'status' => 'required',
               'address' => 'required',
                ]
            );

            $retailsin = new retails();
            $retailsin->pname = $request->pname;
            $retailsin->name = $request->name;
            $retailsin->groupname = $request->groupname;
            $retailsin->retailercode = $request->retailercode;
            $retailsin->contactperson = $request->contactperson;
            $retailsin->phone = $request->phone;
            $retailsin->designation = $request->designation;
            $retailsin->gstno = $request->gstno;
            $retailsin->status = $request->status;
            $retailsin->state = $request->state;
            $retailsin->city = $request->city;
            $retailsin->area = $request->area;
            $retailsin->email=$request->email;
            $retailsin->username=$request->ubuser;
            $retailsin->password=Hash::make($request->password);
            $retailsin->typeoftrader = $request->typeoftrader;
            $retailsin->address = $request->address;
            $retailsin->addresss = $request->addresss;
            $retailsin->joiningdate = $request->joiningdate;
            $retailsin->leavingdate = $request->leavingdate;
            $retailsin->updatedby = Session::get('adminloginid');
            $retailsin->updatedbytype = Session::get('logintype');
            $retailsin->session = Session::get('sessionof');
            $retailsin->save();
            $codeup=new codes();


            $groupscode=groups::find($request->groupname)->group_code;
            $ledgersin = new ledgers();
            $ledgersin->name = $request->name;
            $ledgersin->group_code = $groupscode;
            $ledgersin->groupid = $request->groupname;
            $ledgersin->ledger_code = $request->retailercode;
            $ledgersin->status = $request->status;
            $ledgersin->updated_by = Session::get('adminloginid');
            $ledgersin->save();

            $codeup->name = 'retailer/ledger';
            $codeup->code = $request->retailercode;
            $codeup->save();


            $emailin=new unique_emails();
            $emailin->email=$request->email;
            $emailin->save();

            $emailinuser=new unique_usernames();
            $emailinuser->username=$request->ubuser;
            $emailinuser->save();

            return redirect('addretail')->with('success', 'Data Inserted Successfully');


        } else {
            return view('addretail')->with($data);
        }
    }



    public function editretail($id,Request $request){
        $pagetitle="Customer Login";
        $pageto=url('addretail');
            $formurl=url('editretail/'.$id);
            $retailsid=retails::find($id);
            $groups=groups::all();
            $stategstcodes=stategstcodes::orderby('state','ASC')->get();
            $retails=retails::leftJoin('groups', 'retails.groupname', '=', 'groups.id')
            ->select('retails.*', 'groups.name as groups_name')
            ->get();
            $citycodes=city::where('state_id','=',$retailsid->state)->get();
             $data=compact('retailsid','formurl','stategstcodes','groups','citycodes','pagetitle','pageto','retails');
       if (!empty($request->all())) {







                $request->validate(
                         [
             'retailercode' => 'required',
               'name' => 'required',
               'contactperson' => 'required',
               'phone' => 'required',
               'designation' => 'required',
               'gstno' => 'required',
               'email' => 'required',
               'ubuser' => 'required',
               'joiningdate' => 'required',
               'status' => 'required',
               'address' => 'required',
                         ]
                     );

                     $retailsup =retails::find($id);
                     $retailsup->name = $request->name;
                     $retailsup->pname = $request->pname;
                     $retailsup->groupname = $request->groupname;
                     $retailsup->retailercode = $request->retailercode;
                     $retailsup->contactperson = $request->contactperson;
                     $retailsup->phone = $request->phone;
                     $retailsup->designation = $request->designation;
                     $retailsup->gstno = $request->gstno;
                     $retailsup->status = $request->status;
                     $retailsup->state = $request->state;
                     $retailsup->city = $request->city;
                     $retailsup->area = $request->area;
                     $retailsup->email=$request->email;
                     $retailsup->username=$request->ubuser;
                     if(!empty($request->password)){
                        $retailsup->password=Hash::make($request->password);
                    }
                     $retailsup->typeoftrader = $request->typeoftrader;
                     $retailsup->address = $request->address;
                     $retailsup->addresss = $request->addresss;
                     $retailsup->joiningdate = $request->joiningdate;
                     $retailsup->leavingdate = $request->leavingdate;
                     $retailsup->updated_by = Session::get('adminloginid');
                     $retailsup->save();

                     $ludger=ledgers::where('ledger_code','=',$request->retailercode)->first();
                     if($ludger){


                    $groupscode=groups::find($request->groupname)->group_code;
                    $ledgersin = ledgers::find($ludger->id);
                    $ledgersin->name = $request->name;
                    $ledgersin->group_code = $groupscode;
                    $ledgersin->groupid = $request->groupname;
                    $ledgersin->ledger_code = $request->retailercode;
                    $ledgersin->status = $request->status;
                    $ledgersin->updated_by = Session::get('adminloginid');
                    $ledgersin->save();

                }

                     $codeup=new codes();
                     $codeup->name = 'retailer/ledger';
                     $codeup->code = $request->retailercode;
                     $codeup->save();

                return redirect('addretail')->with('success', 'Data Inserted Successfully');








            } else {
                     return view('addretail')->with($data);
                 }


    }

    public function deleteretail($id,Request $request){
   $retailsid = retails::find($id);
        if (is_null($retailsid)) {
            return back()->with('fail', 'No Record Found');
        } else {
            $retailsid->delete();
            return back()->with('success', 'Delete Successfully');
        }
    }

    public function table(Request $request)
    {


        $data = retails::leftJoin('groups', 'retails.groupname', '=', 'groups.id')
    ->select('retails.*', 'groups.name as groups_name')
    ->get();

        // $data = retails::join('groups', 'retails.groupname', '=', 'groups.id')
        // ->select('retails.*', 'groups.name as groups_name')
        // ->get();


        // $data = retails::all(); // Retrieve data from the database using your model
        return response()->json($data);
    }
    public function generatecode(Request $request)
    {

        // return response()->json();
        $users = retails::where('name', 'like', substr($request->id,0,1).'%')->count();
        if(strlen($users)==1){
        $code=ucfirst(substr($request->id,0,1).'000'.$users+1);
        }
        if(strlen($users)==2){
        $code=ucfirst(substr($request->id,0,1).'00'.$users+1);
        }
        if(strlen($users)==3){
        $code=ucfirst(substr($request->id,0,1).'0'.$users+1);
        }
        if(strlen($users)>=4){
        $code=ucfirst(substr($request->id,0,1).'0'.$users+1);
        }

        return response()->json($code);
    }
}
