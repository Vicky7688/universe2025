<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgentMaster;
use Illuminate\Support\Facades\Session;
use Toastr;
use DateTime;
use Illuminate\Support\Facades\Hash;

class AgentMasterController extends Controller
{
    //++++++ Agent Page
    public function agent(Request $request)
    {
        $pagetitle = "Agent Master";
        $pageto = url('agent');
        $agents = AgentMaster::all();
        $formurl=url('agent');
        $data=compact('formurl','agents','pagetitle','pageto');
        if($request->all())
        {
            $request->validate([
                'name' => 'required',
                'user_name' => 'required|unique:agent_masters,user_name',
                'agentcode' => 'required|unique:agent_masters,agent_code,' . $request->agentcode,
                'agentphoneNo' => 'required|numeric|digits:10'
            ]);

            $agentsInsert = new AgentMaster();
            $agentsInsert->joiningDate = date('Y-m-d',strtotime($request->joiningDate));
            $agentsInsert->user_type = $request->userType;
            $agentsInsert->user_name = $request->user_name;
            $agentsInsert->password = Hash::make($request->password);
            $agentsInsert->name = $request->name;
            $agentsInsert->agent_code = $request->agentcode;
            $agentsInsert->phone = $request->agentphoneNo;
            $agentsInsert->address = $request->address;
            $agentsInsert->area_of_operation = $request->areaofOperation;
            $agentsInsert->panNo = $request->agentpan;
            $agentsInsert->commissionSaving = $request->commsaving;
            $agentsInsert->commissionFD = $request->commfd;
            $agentsInsert->commissionRD = $request->commrd;
            $agentsInsert->commissionLoan = $request->commloan;
            $agentsInsert->status = $request->status;
            $agentsInsert->updatedBy = Session::get('adminloginid');
            $agentsInsert->updatedbytype = Session::get('logintype');
            $agentsInsert->session = Session::get('sessionof');
            $agentsInsert->save();
            Toastr::success('Data Created Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('agent')->with('success', 'Data Inserted Successfully');

        }
        return view('agentmaster')->with($data);
    }


    //_______________Exits User Name
    public function checkExitsUserName(Request $post){
        $userName = $post->userName;
        if(!empty($userName)){
            $exits_user_name = AgentMaster::where('user_name','Like',$userName)->first();
            if($exits_user_name){
                return response()->json(['status' => 'Fail' , 'messages' => 'User Name Already Exits']);
            }
        }

    }

    //++++++++++++++++ Check Agent Code if Not Exits
    public function agentCodeCheck(Request $request){
        $agent_name = $request->agent_name;
        $new_agent_code = '';

        if (!empty($agent_name)) {
            $first_name = strtoupper(substr($agent_name, 0, 3));
            $exits_agent = AgentMaster::where('agent_code', 'LIKE', $first_name . '%')->first();

            if (!empty($exits_agent)) {
                $invoice = intval($exits_agent->agent_code) + 1;
                $newnumber = $invoice + 1;
            }else{
                $newnumber = 1;
            }

            $new_agent_code = $first_name . str_pad($newnumber, 3, '0', STR_PAD_LEFT);
            return response()->json(['status' => 'success','new_agent_code' => $new_agent_code ]);
        }

    }


    //++++++++++++ Edit/Update Agent
    public function editagent(Request $request,$id)
    {
        $pagetitle = "Agent Master";
        $pageto = url('agent');
        $agents = AgentMaster::all();
        $agentId = AgentMaster::find($id);
        $formurl = url('editagent/'.$id);
        $data=compact('formurl','agents','pagetitle','pageto','agentId');

        if($request->all())
        {
            function isValidDate($date, $format = 'd-m-Y') {
                $d = DateTime::createFromFormat($format, $date);
                return $d && $d->format($format) === $date;
            }

            $releavingDate = $request->releavingDate;
            if (isValidDate($releavingDate)) {
                $date = date('Y-m-d', strtotime($releavingDate));
            } else {
                $date = null;
            }

            $agentsUpdate = AgentMaster::find($id);
            $agentsUpdate->joiningDate = date('Y-m-d',strtotime($request->joiningDate));
            $agentsUpdate->name = $request->name;
            $agentsUpdate->phone = $request->agentphoneNo;
            $agentsUpdate->address = $request->address;
            $agentsUpdate->password = Hash::make($request->password) ? Hash::make($request->password) : $agentId->password;
            $agentsUpdate->area_of_operation = $request->areaofOperation;
            $agentsUpdate->panNo = $request->agentpan;
            $agentsUpdate->commissionSaving = $request->commsaving;
            $agentsUpdate->commissionFD = $request->commfd;
            $agentsUpdate->commissionRD = $request->commrd;
            $agentsUpdate->commissionLoan = $request->commloan;
            $agentsUpdate->releavingDate =  $date;
            $agentsUpdate->status = $request->status;
            $agentsUpdate->updatedBy = Session::get('adminloginid');
            $agentsUpdate->updatedbytype = Session::get('logintype');
            $agentsUpdate->session = Session::get('sessionof');
            $agentsUpdate->save();

            Toastr::success('Data Created Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('agent')->with('success', 'Data Inserted Successfully');
        }else{
            return view('agentmaster')->with($data);
        }
    }

    //+++++++++++++ Delete Agent
    public function deleteagent($id)
    {
        $agentId = AgentMaster::find($id);
        if(is_null($agentId))
        {
            Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('fail', 'No Record Found');
        }else{
            $agentId->delete();
            Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('success', 'Delete Successfully');
        }
    }
}
