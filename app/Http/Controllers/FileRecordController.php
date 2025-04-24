<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FileRecordController extends Controller
{
    public function filecustomerindex()
    {
        $pagetitle = "Customer File";
        $pageto = url('File');
        $customerDetails = DB::table('customer_file')->orderBy('customer_Id','ASC')->get();
        $data['pagetitle'] = $pagetitle;
        $data['pageto'] = $pageto;
        $data['customerDetails'] = $customerDetails;
        return view('fileinsert', $data);
    }

    public function checkalreadymember(Request $post){
        $customers = $post->customerId;
        $customerDetails = DB::table('customer_file')
            ->where('customer_id', 'LIKE', $customers . '%')
            // ->orWhere('name', 'LIKE', $customers . '%')
            ->first();

        if(!empty($customerDetails)){
            return response()->json(['status' => 'success','messages' => 'Record Already Exists']);
        }else{
            return response()->json(['status' => 'Fail','messages' => 'Record Not Found']);
        }
    }

    public function fileinsert(Request $post){
        $rules = [
            "customer_id" => "required|numeric",
            "currentDate" => 'nullable|date',
            "name" => 'required'
        ];

        $validator = Validator::make($post->all(),$rules);

        if($validator->fails()){
            return response()->json(['status' => 'Fail','messages' => $validator->errors()]);
        }

        $customer_id = $post->customer_id;

        if(!empty($customer_id)){

            DB::table('customer_file')->insert([
                'customer_id' => $post->customer_id,
                'file_no' => $post->file_no,
                'name' => $post->name,
                'received_by' => $post->receivedby,
                'files_dates'  => !empty($post->currentDate) && strtotime($post->currentDate) ? date('Y-m-d', strtotime($post->currentDate)) : null
            ]);

            return response()->json(['status' => 'success','messages' => 'Record Insert Successfully']);
        }else{

            return response()->json(['status' => 'Fail','messages' => 'Record Not Found']);

        }
    }

    public function editfiles(Request $post){
        $id = $post->id;
        if(is_null($id)){
            return response()->json(['status' => 'Record Not Found']);
        }else{
            $customerDetails = DB::table('customer_file')->where('id',$id)->first();

            if(!empty($customerDetails)){
                return response()->json(['status' => 'success','customerDetails' => $customerDetails ]);
            }else{
                return response()->json(['status' => 'Fail','messages' => 'Record Not Found']);
            }
        }
    }

    public function fileupdate(Request $post){
        $rules = [
            "updateid" => "required",
            "customer_id" => "required",
            "name" => "required",
            'currentDate' => 'nullable|date',
        ];

        $validator = Validator::make($post->all(),$rules);
        if($validator->fails()){
            return response()->json(['status' => 'Fail','messages' => $validator->errors()]);
        }

        $id = $post->updateid;
        $customerDetails = DB::table('customer_file')->where('id',$id)->first();

        if($customerDetails){
            $customerDetails = DB::table('customer_file')->where('id',$id)->update([
                'name' => $post->name,
                'file_no' => $post->file_no,
                'received_by' => $post->receivedby,
                'files_dates' => !empty($post->currentDate) && strtotime($post->currentDate) ? date('Y-m-d', strtotime($post->currentDate)) : null,
            ]);


           return response()->json(['status' => 'success','messages' => 'Record Updated Successfully']);

        }else{

            return response()->json(['status' => 'Fail','messages' => 'Some Thing Went Wrong']);

        }
    }
}
