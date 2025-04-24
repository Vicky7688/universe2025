<?php

namespace App\Http\Controllers;

use App\Models\yearly_session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use Toastr;

class SessionController extends Controller
{
    public function session(Request $request){
        $formurl = url('session');
        $pagetitle = "Session Master";
        $pageto = url('session');
        $session = yearly_session::all();
        $data = compact('formurl', 'pagetitle', 'pageto', 'session');

        if (!empty($request->all())) {

            $startdate = date('Y-m-d', strtotime($request->startdate));
            $enddate = date('Y-m-d', strtotime($request->enddate));

            //________Financial Year Start Date Format
            if ($request->startdate) {
                $financial_year_start_date = date('Y-m-d', strtotime('01-04-' . date('Y', strtotime($request->startdate))));
                $financial_year_end_date = date('Y-m-d', strtotime('31-03-' . (date('Y', strtotime($financial_year_start_date)) + 1)));
            } else {
                $financial_year_start_date = $startdate;
                $financial_year_end_date = $enddate;
            }


            //_________Check Exits Financial Year
            $exists = yearly_session::where('startdate', $financial_year_start_date)
                ->where('enddate', $financial_year_end_date)
                ->exists();


            //___________If Financial Year Not Exits
            if (!$exists) {
                $inser = new yearly_session();
                $inser->startdate = $financial_year_start_date;
                $inser->enddate = $financial_year_end_date;
                $inser->name = $request->name;
                $inser->status = $request->status;
                $inser->auditPerformed = $request->auditperform;
                $inser->updatedby = Session::get('adminloginid');
                $inser->updatedbytype = Session::get('logintype');
                $inser->session = Session::get('sessionof');
                $inser->save();
                Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                return redirect('session')->with('success', 'Data Inserted Successfully');

            } else {

                Toastr::error('Session Already Exits', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                return redirect('session')->with('success', 'Data Inserted Successfully');

            }
        } else {
            return view('session')->with($data);
        }
    }


    //_____________Check Exits Financial Year
    public function sessioname(Request $post) {
        $startDate = date('Y-m-d', strtotime($post->startDate));
        $endDate = date('Y-m-d', strtotime($post->endDate));

        if ($startDate) {
            $sYear = (int) date('Y', strtotime($startDate));
            $lastYear = $sYear + 1;
            $financialYear = $sYear . '-' . $lastYear;

            return response()->json(['status' => 'success', 'financialYear' => $financialYear]);
        } else {
            return response()->json(['status' => 'Fail', 'messages' => 'Invalid start date']);
        }

    }



    public function checkexitsessionname(Request $post) {
        $session_name = $post->session_name;
        if ($session_name) {
            $exists = yearly_session::where('name', $session_name)->first();
            if($exists){

            }else{
                Toastr::error('Session Name Already Exists', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                return response()->json(['status' => 'Fail', 'messages' => 'Session Name Already Exists']);
            }

        }

        return response()->json(['status' => 'Fail', 'messages' => 'Session name is required']);
    }








    public function table(Request $request){
        $data = yearly_session::all();
        return response()->json($data);
    }



    public function editsession($id, Request $request){
        $formurl = url('editsession/' . $id);
        $pagetitle = "Session Master";
        $pageto = url('session');
        $session = yearly_session::all();
        $sessionid = yearly_session::find($id);
        $data = compact('sessionid', 'formurl', 'pagetitle', 'pageto', 'session');

        if (!empty($request->all())) {

            $startdate = date('Y-m-d', strtotime($request->startdate));
            $enddate = date('Y-m-d', strtotime($request->enddate));

            //________Financial Year Start Date Format
            if ($request->startdate) {
                $financial_year_start_date = date('Y-m-d', strtotime('01-04-' . date('Y', strtotime($request->startdate))));
                $financial_year_end_date = date('Y-m-d', strtotime('31-03-' . (date('Y', strtotime($financial_year_start_date)) + 1)));
            } else {
                $financial_year_start_date = $startdate;
                $financial_year_end_date = $enddate;
            }


            //_________Check Exits Financial Year
            $exists = yearly_session::where('id','!=',$id)
                ->where('startdate', $financial_year_start_date)
                ->where('enddate', $financial_year_end_date)
                ->exists();


            if(!$exists){
                $update = yearly_session::find($id);
                if (!$update) {
                    return response()->json(['status' => 'fail', 'message' => 'Session not found']);
                }else{
                    $update = yearly_session::find($id);
                    $update->startdate = $startdate;
                    $update->enddate = $enddate;
                    $update->name = $request->name;
                    $update->status = $request->status;
                    $update->auditPerformed = $request->auditperform;
                    $update->updatedby = Session::get('adminloginid');
                    $update->updatedbytype = Session::get('logintype');
                    $update->session = Session::get('sessionof');
                    $update->save();
                    Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                    return redirect('session')->with('success', 'Data Inserted Successfully');
                }
            }else{
                Toastr::error('Session Already Exits', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                return response()->json(['status' => 'fail', 'message' => 'Session Already Exists']);
            }
        } else {
            return view('session')->with($data);
        }
    }

    public function deletesession($id, Request $request)
    {
        $yearly_session = yearly_session::find($id);
        if (is_null($yearly_session)) {
            Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('fail', 'No Record Found');
        } else {
            $yearly_session->delete();
            Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('success', 'Delete Successfully');
        }
    }
}
