<?php

namespace App\Http\Controllers;

use App\Models\ledgers;
use App\Models\groups;
use App\Models\codes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Toastr;

class ledgercontroller extends Controller
{

    //++++++++Ledger Code Generate
    public function generateLedgerCode(Request $request)
    {
        $firstName = strtoupper($request->ledgerName);
        $newledgerCode = '';
        if ($firstName) {
            $lastname = substr($firstName, 0, 3);
            $lastledgerCode = ledgers::where('ledgerCode', 'LIKE', $lastname . '%')
                ->where('status', '=', 'Active')
                ->orderBy('ledgerCode', 'desc')
                ->first();

            if ($lastledgerCode) {
                $lastledgerNumer = intval(substr($lastledgerCode->ledgerCode, 3));
                $groups = $lastledgerNumer + 1;
            } else {
                $groups = 1;
            }
            $newledgerCode = $lastname . str_pad($groups, 3, '0', STR_PAD_LEFT);
        }

        return response()->json(['status' => 'success', 'ledgerCode' => $newledgerCode]);
    }

    //+++++++++++++++Group Nature
    public function groupnature(Request $request)
    {
        $groupCode = $request->groupCodeNature;
        $groups =  groups::where('groupCode',$groupCode)
                    ->where('status','Active')
                    ->where('is_delete','No')
                    ->first();
        if($groups)
        {
            return response()->json(['status'=>'success','groups'=>$groups]);
        }else{
            return response()->json(['status'=>'fail','message'=> 'Group Not Found']);
        }
    }

    //++++++++++Ledger Insert
    public function ledger(Request $request)
    {
        $formurl = url('ledger');
        $pagetitle = "Ledger Master";
        $pageto = url('ledger');
        $groups = groups::all();
        $ledgers = ledgers::all();
        $data = compact('formurl','pagetitle','pageto','groups','ledgers');

        if($request->all())
        {
            $request->validate([
                'groupname' => 'required',
                'name' => 'required',
                'ledger_code' => 'required|unique:ledger_masters,ledgerCode,'.$request->ledger_code,
                'opening_amt' => 'required|numeric'
            ]);

            $ledger = new ledgers();
            $ledger->groupCode = $request->groupname;
            $ledger->name = $request->name;
            $ledger->ledgerCode = $request->ledger_code;
            $ledger->openingAmount = $request->opening_amt;
            $ledger->openingType = $request->nature;
            $ledger->status = $request->status;
            $ledger->updatedBy = Session::get('adminloginid');
            $ledger->is_delete = 'No';
            $ledger->save();
            Toastr::success('Ledger Created Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('ledger')->with('success', 'Data Inserted Successfully');
        }else{
            return view('addledger')->with($data);
        }
    }


    //+++++++++++++ Ledger Edit
    public function editledger(Request $request ,$id)
    {
        $formurl = url('editledger/'.$id);
        $pagetitle = "Ledger Master";
        $pageto = url('ledger');
        $groups = groups::all();
        $ledgers = ledgers::all();
        $ledgerId = ledgers::find($id);
        $ledgg = groups::where('groupCode','=',$ledgerId->groupCode)->where('status','=','Active')->get();
        $data = compact('formurl','pagetitle','pageto','groups','ledgers','ledgerId');

        if($request->all())
        {
            $request->validate([
                'groupname' => 'required',
            ]);


            $ledger = ledgers::find($id);
            $ledger->groupCode = $request->groupname;
            $ledger->name = $request->name;
            $ledger->openingAmount = $request->opening_amt;
            $ledger->openingType = $request->nature;
            $ledger->status = $request->status;
            $ledger->updatedBy = Session::get('adminloginid');
            $ledger->is_delete = 'No';
            $ledger->save();
            Toastr::success('Ledger Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('ledger')->with('success', 'Data Inserted Successfully');
        }else{
            return view('addledger')->with($data);
        }
    }


    public function deleteledger($id)
    {
        $ledgerId = ledgers::find($id);
        if(is_null($ledgerId))
        {
            Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('fail', 'No Record Found');
        }else{
            $ledgerId->delete();
            Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('success', 'Delete Successfully');
        }
    }
}
