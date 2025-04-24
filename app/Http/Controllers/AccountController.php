<?php

namespace App\Http\Controllers;

use App\Models\AgentMaster;
use App\Models\post_office_masters;
use App\Models\village_masters;
use App\Models\state_masters;
use App\Models\tehsil_masters;
use App\Models\district_masters;
use App\Models\AccountOpening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MembersImport;
use Toastr;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function formimport(Request $request)
    {
        return view('formimport');
    }
    public function import(Request $request)
    {
        // Validate the request to ensure a file is provided
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);
        // Import the data from the file
        Excel::import(new MembersImport, $request->file('file'));
        // Return a response or redirect
        return redirect()->back()->with('success', 'Data imported successfully!');
    }
    public function accounts(Request $request)
    {
        $pagetitle = "Account Opening";
        $pageto = url('account_opening');
        $agents = AgentMaster::where('status', '=', 'Active')->where('is_delete', '=', 'No')->get();
        $formurl = url('account_opening');
        $district = "";
        $tehsil_masters = "";
        $post_office_masters = post_office_masters::all();
        $villages = village_masters::all();
        $state_masters = state_masters::where('status', '=', 'Active')->get();
        $accountDetails = AccountOpening::all();
        $data = compact('formurl', 'pagetitle', 'pageto', 'agents', 'accountDetails', 'state_masters', 'villages', 'post_office_masters', 'tehsil_masters', 'district');
        return view('accounts')->with($data);
    }
    public function getAccountData()
    {
        $query = AccountOpening::select([
            'id',
            'openingDate',
            'customer_Id',
            'name',
            'father_husband',
            'mobile_first',
            'status',
        ]);
        return DataTables::of($query)
            ->addIndexColumn() // For the Sr. No column
            ->editColumn('openingDate', function ($row) {
                return date('d-m-Y', strtotime($row->openingDate));
            })
            ->addColumn('edit', function ($row) {
                return '<a href="' . url('account_opening/' . $row->id) . '"><img src="' . url('public/admin/images/edit.png') . '"></a>';
            })
            ->addColumn('delete', function ($row) {
                return '<a onclick="return confirm(\'Are you Sure?\')" href="' . url('deleteaccount/' . $row->id) . '"><img src="' . url('public/admin/images/delete.png') . '"></a>';
            })
            ->rawColumns(['edit', 'delete']) // To render HTML for edit and delete columns
            ->make(true);
    }

    public function deleteaccount($id, Request $request)
    {
        AccountOpening::find($id)->delete();
        return back();
    }

    public function account_opening(Request $request)
    {
        $last_account = DB::table('member_accounts')->max('customer_Id') + 1;
        $pagetitle = "Account Opening";
        $pageto = url('account_opening');
        $agents = AgentMaster::where('status', '=', 'Active')->where('is_delete', '=', 'No')->get();
        $formurl = url('account_opening');
        $district = "";
        $tehsil_masters = "";
        $post_office_masters = post_office_masters::all();
        $villages = village_masters::all();
        $state_masters = state_masters::where('status', '=', 'Active')->get();
        $accountDetails = AccountOpening::all();
        $data = compact('last_account', 'formurl', 'pagetitle', 'pageto', 'agents', 'accountDetails', 'state_masters', 'villages', 'post_office_masters', 'tehsil_masters', 'district');
        if ($request->all()) {
            // $request->validate([
            //     'customerImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2020',
            //     'idProofImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2020',
            //     'firstguarantorImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2020',
            //     'secondguarantorImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2020',

            // ]);
            $rules = array(
                'customer_Id' => 'required|unique:member_accounts,customer_Id,' . $request->customer_id,
                'customerImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'idProofImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'firstguarantorImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'secondguarantorImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            );

            $validator = Validator::make($request->all(),$rules);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }



            if ($request->file('customerImage')) {
                $ncustomerImagecustomerImage = rand() . 'customerImageall.' . $request->file('customerImage')->getClientOriginalExtension();
                $rouncustomerImagecustomerImage = $request->file('customerImage')->storeAs('public/featcustomerImages', $ncustomerImagecustomerImage);
            } else {
                $rouncustomerImagecustomerImage = "";
            }
            //+++++++++++++ Customer Id Proof
            if ($request->file('idProofImage')) {
                $nidProofImageidProofImage = rand() . 'idProofImageall.' . $request->file('idProofImage')->getClientOriginalExtension();
                $rounidProofImageidProofImage = $request->file('idProofImage')->storeAs('public/featidProofImages', $nidProofImageidProofImage);
            } else {
                $rounidProofImageidProofImage = "";
            }
            //+++++++++++++ First Guarantor Image
            if ($request->file('firstguarantorImage')) {
                $nfirstguarantorImagefirstguarantorImage = rand() . 'firstguarantorImageall.' . $request->file('firstguarantorImage')->getClientOriginalExtension();
                $rounfirstguarantorImagefirstguarantorImage = $request->file('firstguarantorImage')->storeAs('public/featfirstguarantorImages', $nfirstguarantorImagefirstguarantorImage);
            } else {
                $rounfirstguarantorImagefirstguarantorImage = "";
            }
            //+++++++++++++ Second Guarantor Image
            if ($request->file('secondguarantorImage')) {
                $nsecondguarantorImagesecondguarantorImage = rand() . 'secondguarantorImageall.' . $request->file('secondguarantorImage')->getClientOriginalExtension();
                $rounsecondguarantorImagesecondguarantorImage = $request->file('secondguarantorImage')->storeAs('public/featsecondguarantorImages', $nsecondguarantorImagesecondguarantorImage);
            } else {
                $rounsecondguarantorImagesecondguarantorImage = "";
            }
            //++++++++++ Customer Insert
            $customer = new AccountOpening();
            $customer->openingDate = date('Y-m-d', strtotime($request->openingdate));
            $customer->customer_Id = $request->customer_Id;
            $customer->name = $request->name;
            $customer->father_husband = $request->father_husband;
            $customer->gender = $request->gender;
            $customer->adhaar_no     = $request->adhaar_no;
            $customer->pan_number = $request->pan_number;
            $customer->email = $request->email;
            $customer->address = $request->address;
            $customer->stateId = $request->stateId;
            $customer->districtId = $request->districtId;
            $customer->tehsilId = $request->tehsilId;
            $customer->postOfficeId = $request->postOfficeId;
            $customer->villageId = $request->villageId;
            $customer->houseType = $request->houseType;
            $customer->landmark = $request->landmark;
            $customer->mobile_first = $request->mobile_first;
            $customer->mobile_second = $request->mobile_second;
            $customer->work_place = $request->work_place;
            $customer->relationship = $request->relationship;
            $customer->relative_mobile_no = $request->relative_mobile_no;
            $customer->guarantor_first = $request->guarantor_first;
            $customer->first_guarantor_mobile = $request->first_guarantor_mobile;
            $customer->guarantor_second = $request->guarantor_second;
            $customer->second_guarantor_mobile = $request->second_guarantor_mobile;
            $customer->loan_limit = $request->loan_limit;
            $customer->openingbal = $request->openingbal;
            $customer->cibilscore = $request->cibilscore;
            $customer->worked = $request->worked;
            $customer->customerInput = $rouncustomerImagecustomerImage;
            $customer->idProofImageInput = $rounidProofImageidProofImage;
            $customer->firstguarantorImageInput = $rounfirstguarantorImagefirstguarantorImage;
            $customer->secondguarantorImageInput = $rounsecondguarantorImagesecondguarantorImage;
            $customer->agentId = Session::get('adminloginid');
            $customer->updatedBy = Session::get('adminloginid');
            $customer->updatedbytype = Session::get('logintype');
            $customer->sessionId     = Session::get('sessionof');
            $customer->is_delete = 'No';
            // dd($request->all());
            $customer->save();
            Toastr::success('Data Created Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('account_opening')->with('success', 'Data Inserted Successfully');
        } else {
            return view('account_opening')->with($data);
        }
    }


    public function eaccount_opening($id, Request $request)
    {
        $pagetitle = "Account Opening";
        $pageto = url('account_opening');
        $agents = AgentMaster::where('status', '=', 'Active')->where('is_delete', '=', 'No')->get();
        $formurl = url('account_opening/' . $id);
        $district = "";
        $tehsil_masters = "";
        $post_office_masters = post_office_masters::all();
        $villages = village_masters::all();
        $state_masters = state_masters::where('status', '=', 'Active')->get();
        $accountDetails = AccountOpening::all();
        $accountopening = AccountOpening::find($id);
        $state_masters = state_masters::where('status', '=', 'active')->get();
        $district = district_masters::where('id', '=', $accountopening->districtId)->where('status', '=', 'Active')->get();
        $tehsil_masters = tehsil_masters::where('stateId', '=', $accountopening->stateId)->where('districtId', '=', $accountopening->districtId)->where('status', '=', 'Active')->get();
        $post_office_masters = post_office_masters::where('stateId', '=', $accountopening->stateId)->where('tehsilId', '=', $accountopening->tehsilId)->where('status', '=', 'Active')->get();
        $data = compact('district', 'tehsil_masters', 'formurl', 'pagetitle', 'pageto', 'agents', 'accountDetails', 'state_masters', 'villages', 'post_office_masters', 'tehsil_masters', 'district', 'accountopening');
        if ($request->all()) {

            $rules = array(
                'customerImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'idProofImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'firstguarantorImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'secondguarantorImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            );

            $validator = Validator::make($request->all(),$rules);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }




            if ($request->file('customerImage')) {
                $ncustomerImagecustomerImage = rand() . 'customerImageall.' . $request->file('customerImage')->getClientOriginalExtension();
                $rouncustomerImagecustomerImage = $request->file('customerImage')->storeAs('public/featcustomerImages', $ncustomerImagecustomerImage);
            } else {
                $rouncustomerImagecustomerImage = $accountopening->customerInput;
            }
            //+++++++++++++ Customer Id Proof
            if ($request->file('idProofImage')) {
                $nidProofImageidProofImage = rand() . 'idProofImageall.' . $request->file('idProofImage')->getClientOriginalExtension();
                $rounidProofImageidProofImage = $request->file('idProofImage')->storeAs('public/featidProofImages', $nidProofImageidProofImage);
            } else {
                $rounidProofImageidProofImage = $accountopening->idProofImageInput;
            }
            //+++++++++++++ First Guarantor Image
            if ($request->file('firstguarantorImage')) {
                $nfirstguarantorImagefirstguarantorImage = rand() . 'firstguarantorImageall.' . $request->file('firstguarantorImage')->getClientOriginalExtension();
                $rounfirstguarantorImagefirstguarantorImage = $request->file('firstguarantorImage')->storeAs('public/featfirstguarantorImages', $nfirstguarantorImagefirstguarantorImage);
            } else {
                $rounfirstguarantorImagefirstguarantorImage = $accountopening->firstguarantorImageInput;
            }
            //+++++++++++++ Second Guarantor Image
            if ($request->file('secondguarantorImage')) {
                $nsecondguarantorImagesecondguarantorImage = rand() . 'secondguarantorImageall.' . $request->file('secondguarantorImage')->getClientOriginalExtension();
                $rounsecondguarantorImagesecondguarantorImage = $request->file('secondguarantorImage')->storeAs('public/featsecondguarantorImages', $nsecondguarantorImagesecondguarantorImage);
            } else {
                $rounsecondguarantorImagesecondguarantorImage = $accountopening->secondguarantorImageInput;
            }
            //++++++++++ Customer Insert
            $customer = AccountOpening::find($id);
            $customer->openingDate = date('Y-m-d', strtotime($request->openingdate));
            $customer->customer_Id = $request->customer_Id;
            $customer->name = $request->name;
            $customer->father_husband = $request->father_husband;
            $customer->gender = $request->gender;
            $customer->adhaar_no     = $request->adhaar_no;
            $customer->pan_number = $request->pan_number;
            $customer->email = $request->email;
            $customer->address = $request->address;
            $customer->stateId = $request->stateId;
            $customer->districtId = $request->districtId;
            $customer->tehsilId = $request->tehsilId;
            $customer->postOfficeId = $request->postOfficeId;
            $customer->villageId = $request->villageId;
            $customer->houseType = $request->houseType;
            $customer->landmark = $request->landmark;
            $customer->mobile_first = $request->mobile_first;
            $customer->mobile_second = $request->mobile_second;
            $customer->work_place = $request->work_place;
            $customer->relationship = $request->relationship;
            $customer->relative_mobile_no = $request->relative_mobile_no;
            $customer->guarantor_first = $request->guarantor_first;
            $customer->first_guarantor_mobile = $request->first_guarantor_mobile;
            $customer->guarantor_second = $request->guarantor_second;
            $customer->second_guarantor_mobile = $request->second_guarantor_mobile;
            $customer->loan_limit = $request->loan_limit;
            $customer->openingbal = $request->openingbal;
            $customer->cibilscore = $request->cibilscore;
            $customer->worked = $request->worked;
            $customer->customerInput = $rouncustomerImagecustomerImage;
            $customer->idProofImageInput = $rounidProofImageidProofImage;
            $customer->firstguarantorImageInput = $rounfirstguarantorImagefirstguarantorImage;
            $customer->secondguarantorImageInput = $rounsecondguarantorImagesecondguarantorImage;
            $customer->agentId = Session::get('adminloginid');
            $customer->updatedBy = Session::get('adminloginid');
            $customer->updatedbytype = Session::get('logintype');
            $customer->sessionId     = Session::get('sessionof');
            $customer->is_delete = 'No';
            $customer->save();
            Toastr::success('Data Created Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect('account_opening')->with('success', 'Data Inserted Successfully');
        } else {
            return view('account_opening')->with($data);
        }
    }
    public function checkCustomerId(Request $request)
    {
        $customer_id = $request->customer_Id;
        $extitCustomerId = AccountOpening::where('customer_Id', $customer_id)->first();
        if ($extitCustomerId) {
            return response()->json(['status' => 'fail', 'messages' => 'Customer Id Already Taken']);
        } else {
            return response()->json(['status' => true]);
        }
    }
    //___________Generate Customer Number
    public function generateCustomerNumber(Request $post)
    {
        $new_account = '';
        $last_account = DB::table('member_accounts')->max('customer_Id');
        if ($last_account) {
            $new_account = $last_account + 1;
            return response()->json([
                'status' => 'success',
                'new_account' => $new_account
            ]);
        } else {
            return response()->json([
                'status' => 'Fail',
                'messages' => 'Some Technical Issue'
            ]);
        }
    }
}
