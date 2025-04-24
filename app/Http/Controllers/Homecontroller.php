<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\login;
use App\Models\stategstcodes;
use App\Models\item_purchase;
use App\Models\groups;
use App\Models\ledgers;
use App\Models\items;
use App\Models\retails;
use App\Models\employe_master;
use App\Models\tbl_ledger;
use App\Models\city;
use App\Models\codes;
use App\Models\admin_master;
use App\Models\AgentMaster;
use App\Models\unique_usernames;
use App\Models\unique_emails;
use App\Models\yearly_session;
use App\Models\login_masters;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\Storage;
use Hash;
use Session;
use Mail;
use DB;
class Homecontroller extends Controller
{
    public function getbarcode(Request $request)
    {
       if($request->id){
        $generatorPNG = new BarcodeGeneratorPNG();
        $barcodeImageData = $generatorPNG->getBarcode($request->id, $generatorPNG::TYPE_CODE_128);
        // return response($image)->header('Content-type','image/png');
        $barcodeImageName = $request->id.$request->key.'.png';
        $storedBarcodeImagePath = Storage::put('public/barcodes/' . $barcodeImageName, $barcodeImageData);
        return response()->json(['barcode_image_name' => $barcodeImageName]);
        }else{
            return response()->json(['barcode_image_name' => '']);
        }
}


public function generatecode(Request $request)
    {
        $users = codes::where('code', 'like', substr($request->id,0,1).'%')->count();
        if(strlen($users+1)==1){
        $code=ucfirst(substr($request->id,0,1).'000'.$users+1);
        }
        if(strlen($users+1)==2){
        $code=ucfirst(substr($request->id,0,1).'00'.$users+1);
        }
        if(strlen($users+1)==3){
        $code=ucfirst(substr($request->id,0,1).'0'.$users+1);
        }
        if(strlen($users+1)>=4){
        $code=ucfirst(substr($request->id,0,1).'0'.$users+1);
        }
        return response()->json($code);
    }

public function report(Request $request)
    {
        $loaditems = items::all();
        if (!empty($request->all())) {
            if ($request->itemcode) {
                $getdata = item_purchase::selectRaw(
                    "itemcode, MAX(id) AS id, SUM(quantity) AS quantity,  SUM(sgstamount) AS sgstamount, SUM(cgstamount) AS cgstamount,   SUM(igstamount) AS igstamount,   MAX(bill_date) AS bill_date,  MAX(itemname) AS itemname"
                )
                    ->where("itemcode", "=", $request->itemcode)
                    ->whereBetween("bill_date", [
                        date("Y-m-d", strtotime($request->datefrom)),
                        date("Y-m-d", strtotime($request->dateto)),
                    ])
                    ->groupBy("itemcode")
                    ->orderByDesc("id") // Order by id in descending order
                    ->get();
            } else {
                $getdata = item_purchase::selectRaw(
                    "itemcode, MAX(id) AS id, SUM(quantity) AS quantity,  SUM(sgstamount) AS sgstamount, SUM(cgstamount) AS cgstamount,   SUM(igstamount) AS igstamount,   MAX(bill_date) AS bill_date,  MAX(itemname) AS itemname"
                )
                    ->whereBetween("bill_date", [
                        date("Y-m-d", strtotime($request->datefrom)),
                        date("Y-m-d", strtotime($request->dateto)),
                    ])
                    ->groupBy("itemcode")
                    ->orderByDesc("id") // Order by id in descending order
                    ->get();
            }
            $data = compact("loaditems", "getdata");
            return view("main.report")->with($data);
        } else {
            $data = compact("loaditems");
            return view("main.report")->with($data);
        }
    }
    // public function groupledgers(Request $request)
    // {
    //     $data['substates'] = ledgers::where("groupid", $request->id)->get(["name", "id"]);
    //     return response()->json($data);
    // }

public function ledgerreport(Request $request)
    {
        $groups = groups::all();
        $ledgers = ledgers::all();
        if (!empty($request->all())) {
            $datefrom=date("Y-m-d", strtotime($request->datefrom));
            $dateto=date("Y-m-d", strtotime($request->dateto));
                        $groupscode=groups::find($request->group)->group_code;
                        $ledgerscode=ledgers::find($request->ledger)->ledger_code;
                        $getled=tbl_ledger::where('group_code','=',$groupscode)
                        ->where('gr_rr','=',$ledgerscode)
                        ->whereBetween("entry_date", [
                            $datefrom,
                            $dateto,
                        ])
                        ->orderby('entry_date')->get();
//  print_r(json_encode($getled->toArray())); die;
            $data = compact("groups", "ledgers",'getled');
            return view("main.ledgerreport")->with($data);
        } else {
            $data = compact("groups", "ledgers");
            return view("main.ledgerreport")->with($data);
        }
    }

public function productForm()
    {
        return view("main.productForm");
    }

public function loginpage()
    {
        return view("main.logindesign");
    }

public function dash()
    {
        $pagetitle="Dashboard";
        $pageto=url('dashboard');
        return view("dash",compact('pagetitle','pageto'));
    }

    public function returnPurchase()
    {
        return view("main.returnPurchase");
    }

public function getgstcode(Request $request)
    {
        $data['gst'] = stategstcodes::find($request->id);
        $data['city'] =city::where('state_id','=',$request->id)->get(["city_name", "id"]);
        return response()->json($data);
    }

public function index()
    {
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;
        $records = yearly_session::whereYear('startdate', $currentYear)
        ->orWhereYear('enddate', $previousYear)
        ->first();
        $retailsid="";
        $groups=groups::all();
        $yearly_session=yearly_session::all();
        $stategstcodes=stategstcodes::orderby('state','ASC')->get();
        $citycodes="";
         $data=compact('retailsid','stategstcodes','groups','citycodes','yearly_session','records');
        return view("login")->with($data);
    }

public function setpassword(Request $request)
    {
        if($request->password==$request->cpassword){
            if(Session::get('forgottype')=='supeadmin'){
            $employeup=login::find(Session::get('forgotid'));
            $employeup->password=Hash::make($request->password);
            $employeup->save();
            }
            if(Session::get('forgottype')=='admin'){
                $employeup=admin_master::find(Session::get('forgotid'));
                $employeup->password=Hash::make($request->password);
                $employeup->save();
            }
            if(Session::get('forgottype')=='retailer'){
            $employeup=retails::find(Session::get('forgotid'));
            $employeup->password=Hash::make($request->password);
            $employeup->save();
            }
            if(Session::get('forgottype')=='distributer'){
            $employeup=retails::find(Session::get('forgotid'));
            $employeup->password=Hash::make($request->password);
            $employeup->save();
            }
            if(Session::get('forgottype')=='user'){
            $employeup=employe_master::find(Session::get('forgotid'));
            $employeup->password=Hash::make($request->password);
            $employeup->save();
            }
            return response()->json([
                "success" => true,
                "message" => 'Password updated',
            ]);
        }else{
            return response()->json([
                "success" => false,
                "message" => 'Password Does not Match',
            ]);
        }
    }

public function otp(Request $request)
    {
        if(Session::get('forgototp')==$request->otp){
            return response()->json([
                "success" => true,
                "message" => 'succeess',
            ]);
        }else{
            return response()->json([
                "success" => false,
                "message" => 'Wrong Otp',
            ]);
        }
    }

public function forgot(Request $request)
    {
        $otp=rand(1111,9999);
        $message="123";
        if(!empty($request->all())){
                if(!empty($request->username)){
        if($request->type=='supeadmin'){
            $admin = login::where("email", "=", $request->username)->first();
        }
        if($request->type=='admin'){
            $admin = admin_master::where("username", "=", $request->username)->first();
        }
        if($request->type=='retailer'){
            $admin = retails::where("username", "=", $request->username)->first();
        }
        if($request->type=='distributer'){
            $admin = retails::where("username", "=", $request->username)->first();
        }
        if($request->type=='user'){
            $admin = employe_master::where("username", "=", $request->username)->first();
        }
                    if($admin){
                        // $to_email=$admin->email;
                        $to_email='rs707406@gmail.com';
                        if($request->type=='supeadmin'){
                            $employeup=login::find($admin->id);
                            $employeup->otpcocde=$otp;
                            $employeup->save();
                           }
                           if($request->type=='admin'){
                            $employeup=admin_master::find($admin->id);
                            $employeup->otpcocde=$otp;
                            $employeup->save();
                           }
                           if($request->type=='retailer'){
                            $employeup=retails::find($admin->id);
                            $employeup->otpcocde=$otp;
                            $employeup->save();
                           }
                           if($request->type=='distributer'){
                            $employeup=retails::find($admin->id);
                            $employeup->otpcocde=$otp;
                            $employeup->save();
                           }
                           if($request->type=='user'){
                            $employeup=employe_master::find($admin->id);
                            $employeup->otpcocde=$otp;
                            $employeup->save();
                           }
                            $message="123";
                        $request->session()->put("forgototp", $otp);
                        $request->session()->put("forgotid", $admin->id);
                        $request->session()->put("forgottype",$request->type);
                        $data = array('otp' => $otp, 'username' => $request->username);
                        Mail::send('emails.otp', $data, function ($message) use ($to_email) {
                            $message->to($to_email)->subject('Password Reset mail');
                        });
                        return response()->json([
                            "success" => true,
                            "message" => 'Mail Sent',
                        ]);
                    }else{
                        return response()->json([
                            "success" => false,
                            "message" => 'Mail Sent',
                        ]);
                    }
                }else{
                    return response()->json([
                        "success" => false,
                        "message" => 'Empty username',
                    ]);
                }
        }else{
        return view("main.forgot");
    }
    }

public function login(Request $request)
    {
        if (!empty($request->all())) {
            $validator = Validator::make($request->all(), [
                "session" => "required",
                // "type" => "required",
                "username" => "required",
                "password" => "required",
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                $errorMessage = "";
                foreach ($errors as $field => $messages) {
                    foreach ($messages as $message) {
                        return response()->json([
                            "success" => false,
                            "message" => $message,
                        ]);
                    }
                }
                return response()->json([
                    "success" => false,
                    "message" => $errorMessage,
                ]);
            }
                $admin = AgentMaster::where("user_name", "=", $request->username)->first();
                if ($admin) {
                    $loginname=$admin->name;
                    if (Hash::check($request->password, $admin->password)) {
                        $request->session()->put("adminloginid", $admin->id);
                        $sest=yearly_session::find($request->session);
                        $request->session()->put("sessionof", $request->session);
                        $request->session()->put("sessionstartdate", $sest->startdate);
                        $request->session()->put("sessionenddate", $sest->enddate);
                        $request->session()->put("logintype",$admin->user_type);
                        $request->session()->put("loginname",$loginname);
                        $request->session()->put("setcurrentdate",date('d-m-Y'));
                        return response()->json(["success" => true]);
                    } else {
                        return response()->json([
                            "success" => false,
                            "message" => "Password does not match",
                        ]);
                    }
                }else{
                    return response()->json([
                        "success" => false,
                        "message" => "Username does not match",
                    ]);
                }
        } else {
            return view("login");
        }
    }

public function setcurrentdate(Request $request)
    {
        $request->session()->put("setcurrentdate",$request->setcurrentdate);
    }

public function reqretailer(Request $request)
    {
        if (!empty($request->all())) {
            $retailsin = new retails();
            $retailsin->name = $request->name;
            $retailsin->groupname = 18;
            $retailsin->retailercode = $request->retailercode;
            $retailsin->contactperson = $request->contactperson;
            $retailsin->phone = $request->phone;
            $retailsin->designation = $request->designation;
            $retailsin->gstno = $request->gstno;
            $retailsin->status = $request->status;
            $retailsin->state = $request->state;
            $retailsin->city = $request->city;
            $retailsin->area = $request->area;
            $retailsin->username = $request->ubuser;
            $retailsin->email = $request->email;
            $retailsin->password = Hash::make($request->password);
            $retailsin->typeoftrader = $request->typeoftrader;
            $retailsin->address = $request->address;
            $retailsin->addresss = $request->addresss;
            $retailsin->updated_by = "0";
            $retailsin->save();
            $codeup=new codes();
            $codeup->name = 'retailer/ledger';
            $codeup->code = $request->retailercode;
            $codeup->save();
            return response()->json(['status' =>'success']);
        }
    }

public function dashboard()
    {
        return view("main.dashboard");
    }

public function logout()
    {
        Session::forget("adminloginid");
        return redirect("");
    }

public function acheckemail(Request $request)
    {
        if ($request->email) {
            if($request->thisemail){
                $exist = admin_master::where('email', '=', $request->email)->where('email', '!=', $request->thisemail)->first();
            }else{
                $exist = admin_master::where('email', '=', $request->email)->first();
            }
            if ($exist) {
                return response()->json([
                    'status' => true,
                    'message' => 'Email Already Exist',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => '',
                ]);
            }
        }
    }

public function acheckusername(Request $request)
        {
            if ($request->username) {
                if($request->thisusername){
                    $exist = admin_master::where('usrename', '=', $request->username)->where('username', '!=', $request->thisusername)->first();
                }else{
                    $exist = admin_master::where('username', '=', $request->username)->first();
                }
                if ($exist) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Username Already Exist',
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => '',
                    ]);
                }
            }
        }

public function echeckemail(Request $request)
    {
        if ($request->email) {
            if($request->thisemail){
                $exist = employe_master::where('email', '=', $request->email)->where('email', '!=', $request->thisemail)->first();
            }else{
                $exist = employe_master::where('email', '=', $request->email)->first();
            }
            if ($exist) {
                return response()->json([
                    'status' => true,
                    'message' => 'Email Already Exist',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => '',
                ]);
            }
        }
    }

public function echeckusername(Request $request)
        {
            if ($request->username) {
                if($request->thisusername){
                    $exist = employe_master::where('username', '=', $request->username)->where('username', '!=', $request->thisusername)->first();
                }else{
                    $exist = employe_master::where('username', '=', $request->username)->first();
                }
                if ($exist) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Username Already Exist',
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => '',
                    ]);
                }
            }
        }

public function changesession(Request $request)
    {
        $request->session()->put("sessionof", $request->session);
        $request->session()->put("sessionstartdate", DB::table('yearly_session')->find($request->session)->startdate);
        $request->session()->put("sessionenddate", DB::table('yearly_session')->find($request->session)->enddate);
        return response()->json([
            'status' => true,
            'message' => '',
        ]);
        }

public function recheckemail(Request $request)
    {
        if ($request->email) {
            if($request->thisemail){
                $exist = retails::where('email', '=', $request->email)->where('email', '!=', $request->thisemail)->first();
            }else{
                $exist = retails::where('email', '=', $request->email)->first();
            }
            if ($exist) {
                return response()->json([
                    'status' => true,
                    'message' => 'Email Already Exist',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => '',
                ]);
            }
        }
    }

public function recheckusername(Request $request)
        {
            if ($request->username) {
                if($request->thisusername){
                    $exist = retails::where('username', '=', $request->username)->where('username', '!=', $request->thisusername)->first();
                }else{
                    $exist = retails::where('username', '=', $request->username)->first();
                }
                if ($exist) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Username Already Exist',
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => '',
                    ]);
                }
            }
        }


        public function privacy(){
            return view('privacy');
           }

           public function terms(){
            return view('terms');
           }





           public function changepassword(Request $request)
{



    if ($request->ajax()) {
        $validated = $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string',
        ]);

        $user=AgentMaster::where('id','=',Session::get('adminloginid'))->first();


        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['errors' => ['old_password' => ['Old password is incorrect.']]], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully!']);
    }

    $pagetitle = "Change Password";
    $pageto = url('changepassword');
    return view("changepassword", compact('pagetitle', 'pageto'));
}

}
