<?php

namespace App\Http\Controllers;

use App\Models\employe_master;
use App\Models\groups;
use App\Models\unique_emails;
use App\Models\unique_usernames;
use Illuminate\Http\Request;
use Hash;
use Mail;
use Toastr;
use Session;
class EmployeController extends Controller
{





    public function employe(Request $request){
        $pagetitle="User Login";
        $pageto=url('employe');
        $employe_master=employe_master::all();
                $formurl=url('employe');
                $loadbrands=groups::all();
                $data=compact('formurl','loadbrands','pagetitle','pageto','employe_master');
                if(!empty($request->all())){



                            $addemploye=new employe_master();
                            $addemploye->name=$request->name;
                            $addemploye->phone=$request->phone;
                            $addemploye->status=$request->status;
                            $addemploye->email=$request->email;
                            $addemploye->username=$request->ubuser;
                            $addemploye->password=Hash::make($request->password);
                            $addemploye->updatedby = Session::get('adminloginid');
                            $addemploye->updatedbytype = Session::get('logintype');
                            $addemploye->session = Session::get('sessionof');
                            $addemploye->save();
                            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                            return redirect('employe')->with('success', 'Data Inserted Successfully');





                }else{
                    return view('employe')->with($data);
                }


   }


    public function editemploye($id,Request $request){
        $pagetitle="User Login";
        $pageto=url('employe');
        $employe_master=employe_master::all();
                $formurl=url('editemploye/'.$id);
                $loadbrands=groups::all();
                $employeid=employe_master::find($id);
                $data=compact('formurl','loadbrands','employeid','pagetitle','pageto','employe_master');
                if(!empty($request->all())){

                            $emailin=new unique_emails();
                            $emailin->email=$request->email;
                            $emailin->save();

                            $emailinuser=new unique_usernames();
                            $emailinuser->username=$request->ubuser;
                            $emailinuser->save();

                            $addemploye=employe_master::find($id);
                            $addemploye->name=$request->name;
                            $addemploye->phone=$request->phone;
                            $addemploye->status=$request->status;
                            $addemploye->email=$request->email;
                            $addemploye->username=$request->ubuser;
                            if(!empty($request->password)){
                                $addemploye->password=Hash::make($request->password);
                            }
                            $addemploye->updatedby = Session::get('adminloginid');
                            $addemploye->updatedbytype = Session::get('logintype');
                            $addemploye->session = Session::get('sessionof');
                            $addemploye->save();
                            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                            return redirect('employe')->with('success', 'Data Inserted Successfully');




                }else{
                    return view('employe')->with($data);
                }


   }


   public function table(Request $request){
    $data = employe_master::all();
    return response()->json($data);
   }


  
}
