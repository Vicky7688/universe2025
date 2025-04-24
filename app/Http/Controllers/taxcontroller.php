<?php

namespace App\Http\Controllers;

use App\Models\taxs;
use App\Models\groups;
use App\Models\ledgers;
use App\Models\stategstcodes;
use App\Models\codes;
use App\Models\city;
use Illuminate\Http\Request;
use Session;
use Toastr;
class taxcontroller extends Controller
{

    public function taxlist(){
        $taxs=taxs::all();
        $data=compact('taxs');
        return view('taxlist')->with($data);
    }

    public function addtax(Request $request){
        $pagetitle="Tax Master";
        $pageto=url('addtax');
        $formurl=url('addtax'); 
        $groups=groups::all();
        $taxs=taxs::join('groups', 'taxs.group_name', '=', 'groups.id')
        ->select('taxs.*', 'groups.name as groups_name')
        ->get();
        $stategstcodes=stategstcodes::orderby('state','ASC')->get();
        $data=compact('formurl','groups','stategstcodes','pagetitle','pageto','taxs');
        if (!empty($request->all())) { 
            
            $request->validate(
                        [
                    'code' => 'required',
                    'group_name' => 'required',
                    'name' => 'required', 
                        ]
                    );

                    $taxsin = new taxs(); 
                    $taxsin->name = $request->name;
                    $taxsin->code = $request->code;
                    $taxsin->group_name = $request->group_name;
                    $taxsin->ledger_name = $request->ledger_name; 
                    $taxsin->sgstpercentage = $request->sgstpercentage;
                    $taxsin->cgstpercentage = $request->cgstpercentage;
                    $taxsin->igstpercentage = $request->igstpercentage;
                    $taxsin->status = $request->status;
                    $taxsin->updatedby = Session::get('adminloginid');
                    $taxsin->updatedbytype = Session::get('logintype');
                    $taxsin->session = Session::get('sessionof');
                    $taxsin->save();

                    $codeup=new codes();
                    $codeup->name = 'tax'; 
                    $codeup->code = $request->code; 
                    $codeup->save();
                    Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('addtax')->with('success', 'Data Inserted Successfully');

        } else {
            return view('addtax')->with($data);
        }
    }



    public function edittax($id,Request $request){
            $formurl=url('edittax/'.$id);
            $taxsid=taxs::find($id);
            $groups=groups::all();
            $pagetitle="Tax Master";
            $pageto=url('addtax');  
            $taxs=taxs::join('groups', 'taxs.group_name', '=', 'groups.id')
            ->select('taxs.*', 'groups.name as groups_name')
            ->get();
            $stategstcodes=stategstcodes::orderby('state','ASC')->get();
             $data=compact('taxsid','formurl','groups','stategstcodes','pagetitle','pageto','taxs');
       if (!empty($request->all())) {
                $request->validate(
                    [
                        'code' => 'required',
                        'group_name' => 'required',
                        'name' => 'required',
                            ]
                     );

                     $taxsup =taxs::find($id);
                     $taxsup->name = $request->name;
                     $taxsup->code = $request->code;
                     $taxsup->group_name = $request->group_name;
                     $taxsup->ledger_name = $request->ledger_name; 
                     $taxsup->sgstpercentage = $request->sgstpercentage;
                     $taxsup->cgstpercentage = $request->cgstpercentage;
                     $taxsup->igstpercentage = $request->igstpercentage;
                     $taxsup->status = $request->status;
                     $taxsup->updatedby = Session::get('adminloginid');
                     $taxsup->updatedbytype = Session::get('logintype');
                     $taxsup->session = Session::get('sessionof');
                     $taxsup->save();

                     $codeup=new codes();
                     $codeup->name = 'tax'; 
                     $codeup->code = $request->code; 
                     $codeup->save();
                     Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                return redirect('addtax')->with('success', 'Data Inserted Successfully');

                 } else {
                     return view('addtax')->with($data);
                 }


    }

    public function deletetax($id,Request $request){
   $taxsid = taxs::find($id);
        if (is_null($taxsid)) {
            Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('fail', 'No Record Found');
        } else {
            $taxsid->delete();
            Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('success', 'Delete Successfully');
        }
    }


    public function table(Request $request)
    {
        // $data = taxs::all(); // Retrieve data from the database using your model

        $data = taxs::join('groups', 'taxs.group_name', '=', 'groups.id')
                        ->select('taxs.*', 'groups.name as groups_name')
                        ->get();
        return response()->json($data);
    }
    public function getgstcode(Request $request)
    { 
        $data['gst'] = stategstcodes::find($request->id); 
        $data['city'] =city::where('state_id','=',$request->id)->get(["city_name", "id"]);
        return response()->json($data);
    }

    public function ledger(Request $request)
    {
        $data['substates'] = ledgers::where("groupid", $request->id)->get(["name", "id"]);
        return response()->json($data);
    }
}
