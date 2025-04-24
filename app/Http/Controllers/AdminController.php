<?php

namespace App\Http\Controllers;

use App\Models\admin_master;
use App\Models\groups;
use App\Models\unique_emails;
use App\Models\unique_usernames;
use Illuminate\Http\Request;
use Hash;
use Mail;
use Toastr;
use Session;
class AdminController extends Controller
{





    public function admin(Request $request){
                        $pagetitle="Admin Login";
                        $pageto=url('admin');
                        $admin_master=admin_master::all();
                        $formurl=url('admin');
                        $loadbrands=groups::all();
                        $data=compact('formurl','loadbrands','admin_master','pagetitle','pageto');
                        if(!empty($request->all())){
                            $addadmin=new admin_master();
                            $addadmin->name=$request->name;
                            $addadmin->phone=$request->phone;
                            $addadmin->status=$request->status;
                            $addadmin->email=$request->email;
                            $addadmin->username=$request->ubuser;
                            $addadmin->password=Hash::make($request->password);
                            $addadmin->updatedby = Session::get('adminloginid');
                            $addadmin->updatedbytype = Session::get('logintype');
                            $addadmin->session = Session::get('sessionof');
                            $addadmin->save();
                            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                            return redirect('admin')->with('success', 'Data Inserted Successfully');





                }else{
                    return view('admin')->with($data);
                }


   }


    public function editadmin($id,Request $request){
        $pagetitle="Admin Login";
        $pageto=url('admin');
        $admin_master=admin_master::all();
                $formurl=url('editadmin/'.$id);
                $loadbrands=groups::all();
                $adminid=admin_master::find($id);
                $data=compact('formurl','loadbrands','adminid','pagetitle','pageto','admin_master');
                if(!empty($request->all())){

                            $emailin=new unique_emails();
                            $emailin->email=$request->email;
                            $emailin->save();

                            $emailinuser=new unique_usernames();
                            $emailinuser->username=$request->ubuser;
                            $emailinuser->save();

                            $addadmin=new admin_master();
                            $addadmin->name=$request->name;
                            $addadmin->phone=$request->phone;
                            $addadmin->status=$request->status;
                            $addadmin->email=$request->email;
                            $addadmin->username=$request->ubuser;
                            if(!empty($request->password)){
                                $addadmin->password=Hash::make($request->password);
                            }
                            $addadmin->updatedby = Session::get('adminloginid');
                            $addadmin->updatedbytype = Session::get('logintype');
                            $addadmin->session = Session::get('sessionof');
                            $addadmin->save();
                            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                            return redirect('admin')->with('success', 'Data Inserted Successfully');




                }else{
                    return view('admin')->with($data);
                }


   }


   public function table(Request $request){
    $data = admin_master::all();
    return response()->json($data);
   }
}
