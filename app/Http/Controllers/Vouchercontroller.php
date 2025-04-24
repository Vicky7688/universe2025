<?php
namespace App\Http\Controllers;
use App\Models\ledgers;
use App\Models\voucher;
use App\Models\groups;
use App\Models\voucher_detail;
use App\Models\tbl_ledger;
use App\Models\general_ledgers;
use DB;
use Session;
use Illuminate\Http\Request;
class Vouchercontroller extends Controller
{
    //
    public function voucher()
    {
        $pagetitle ="Add voucher";
        $pageto =url('voucher');
        $formurl =url('voucher');
        $vouchers = Voucher::all();
        $maxVoucherNo = Voucher::max("voucherno");
        return view("voucher", compact("maxVoucherNo","pagetitle","pageto","formurl","vouchers"));
    }
    public function vsearch(Request $request)
    {
        $query = $request->input("query");
        $results = voucher::where("voucherno", "LIKE", "%{$query}%")->get(); // Adjust the query as per your requirement

        return response()->json($results);
    }
    public function getvoucherdata(Request $request)
    {
        $voucher = voucher::where("voucherno", "=", $request->value)->first();
        $voucherdetail = voucher_detail::where(
            "voucherno",
            "=",
            $request->value
        )->get();

        $data = [
            "voucher" => $voucher,
            "voucherdetail" => $voucherdetail,
        ];
        return response()->json($data);
    }










    public function previosgetvoucherdata(Request $request)
    {

                    $voucher = Voucher::where("voucherno", $request->value)->first();

                    if ($voucher) {
                        // Get the previous voucher ID
                        $previousVoucher = Voucher::where('id', '<', $voucher->id)
                                                ->orderBy('id', 'desc')
                                                ->first();
                        if ($previousVoucher) {
                            $previousVoucherId = $previousVoucher->id;
                        } else {
                            $previousVoucherId = '';
                        }
                    } else {
                        $previousVoucherId = '';
                    }
                    if($previousVoucherId==''){
                        $voucher = voucher::orderby('id','DESC')->first();
                    }else{
                    $voucher = Voucher::find($previousVoucherId);
                    }
        $voucherdetail = voucher_detail::where(
            "voucherno",
            "=",
            $voucher->voucherno
        )->get();

        $data = [
            "voucher" => $voucher,
            "voucherdetail" => $voucherdetail,
        ];
        return response()->json($data);
    }


    public function nextgetvoucherdata(Request $request)
    {

        $voucher = Voucher::where("voucherno", $request->value)->first();

        if ($voucher) {
            // Get the previous voucher ID
            $previousVoucher = Voucher::where('id', '>', $voucher->id)
                                    ->orderBy('id', 'ASC')
                                    ->first();
            if ($previousVoucher) {
                $previousVoucherId = $previousVoucher->id;
            } else {
                $previousVoucherId = '';
            }
        } else {
            $previousVoucherId = '';
        }
        if($previousVoucherId==''){
            $voucher = voucher::orderby('id','ASC')->first();
        }else{
        $voucher = Voucher::find($previousVoucherId);
        }
$voucherdetail = voucher_detail::where(
"voucherno",
"=",
$voucher->voucherno
)->get();

$data = [
"voucher" => $voucher,
"voucherdetail" => $voucherdetail,
];
return response()->json($data);
}





    public function getlastgetvoucherdata(Request $request)
    {
        $voucher = voucher::orderby('id','DESC')->first();
        $voucherdetail = voucher_detail::where(
            "voucherno",
            "=",
            $voucher->voucherno
        )->get();

        $data = [
            "voucher" => $voucher,
            "voucherdetail" => $voucherdetail,
        ];
        return response()->json($data);
    }

    public function getfirstgetvoucherdata(Request $request)
    {

        $voucher = voucher::orderby('id','ASC')->first();
        $voucherdetail = voucher_detail::where(
            "voucherno",
            "=",
            $voucher->voucherno
        )->get();

        $data = [
            "voucher" => $voucher,
            "voucherdetail" => $voucherdetail,
        ];
        return response()->json($data);
    }

    public function getled(Request $request)
    {

        $query = DB::table("ledger_masters")
            ->join("group_masters", "group_masters.groupCode", "=", "ledger_masters.groupCode")
            ->select(
                "ledger_masters.id",
                "ledger_masters.name as lname",
                "ledger_masters.ledgerCode as lcode",
                "group_masters.groupCode as gcode",
                "group_masters.name as gname"
            );

            if ($request->has("name") && $request->name != "") {
                $query->where(function($q) use ($request) {
                    $q->where("ledger_masters.name", "like", $request->name . "%")
                    ->orWhere("group_masters.name", "like", $request->name . "%");
                });
            }

$gatdata = $query->get();


        return response()->json($gatdata);
    }
    public function getdatadat(Request $request)
    {
        $gatdata = ledgers::find($request->name);
        return response()->json($gatdata);
    }
    public function submitvoucher(Request $request)
    {

        $vodatess = date("Y-m-d", strtotime($request->voucherdate));
        if (!empty($request->id)) {
dd($request->id);
            $voucher = voucher::find($request->id);
            $voucher->vouchertype = $request->vouchertype;
            $voucher->voucherdate = $vodatess;
            $voucher->voucherno = $request->voucherno;
            $voucher->transport = $request->transport;
            $voucher->updatedby = Session::get('adminloginid');
            $voucher->updatedbytype =Session::get('logintype');
            $voucher->session =Session::get('sessionof');
            $voucher->save();


            $allcount = count($request->drcr);



            if ($allcount > 0) {

                voucher_detail::where('voucherno','=',$request->voucherno)->delete();
                tbl_ledger::where('invoiceno','=',$request->voucherno)->where('voucher','=','yes')->delete();

                for ($x = 0; $x < $allcount; $x++) {
                    $vodetail = new voucher_detail();
                    $vodetail->voucher_date = $vodatess;
                    $vodetail->voucherno = $request->voucherno;
                    $vodetail->drcr = $request->drcr[$x];
                    $vodetail->code = $request->code[$x];
                    $vodetail->description = $request->description[$x];
                    $vodetail->dramount = $request->dramount[$x];
                    $vodetail->cramount = $request->cramount[$x];
                    $vodetail->narration = $request->narration[$x];
                    $vodetail->updatedby = Session::get('adminloginid');
                    $vodetail->updatedbytype =Session::get('logintype');
                    $vodetail->session =Session::get('sessionof');
                    $vodetail->save();

                    // ------------------------------------------------------tbl_ledgerDd-------------------------------------------------//

                    if ($request->dramount[$x] == 0) {
                        $dr_cr = "Cr";
                    } else {
                        $dr_cr = "Dr";
                    }
                    $getl = ledgers::where(
                        "ledger_code",
                        "=",
                        $request->code[$x]
                    )->first();
                    $narrartion = new tbl_ledger();
                    $narrartion->entry_date = $vodatess;
                    $narrartion->narration =
                        "Voucher No. " . $request->voucherno;
                    $narrartion->type = $request->vouchertype;
                    $narrartion->dr_amount = $request->dramount[$x];
                    $narrartion->cr_amount = $request->cramount[$x];
                    $narrartion->account_code = $getl->group_code;
                    $narrartion->account_name = $request->description[$x];
                    $narrartion->group_code = $getl->group_code;
                    $narrartion->dr_cr = $dr_cr;
                    $narrartion->intro_code = "";
                    $narrartion->token_no = "";
                    $narrartion->transport = $request->transport;
                    $narrartion->gr_rr = $request->code[$x];
                    $narrartion->remarks = "";
                    $narrartion->invoiceno = $request->voucherno;
                    $narrartion->voucher = 'yes';
                    $narrartion->invoicetype = 'voucher';
                    $narrartion->status = "save";
                    $narrartion->updatedby = Session::get('adminloginid');
                    $narrartion->updatedbytype =Session::get('logintype');
                    $narrartion->session =Session::get('sessionof');
                    $narrartion->save();
                }
            }


        } else {


            $voucher = new voucher();
            $voucher->vouchertype = $request->vouchertype;
            $voucher->voucherdate = $vodatess;
            $voucher->voucherno = $request->voucherno;
            $voucher->transport = $request->transport;
            $voucher->updatedby = Session::get('adminloginid');
            $voucher->updatedbytype =Session::get('logintype');
            $voucher->session =Session::get('sessionof');
            $voucher->save();

            $allcount = count($request->drcr);
            if ($allcount > 0) {
                for ($x = 0; $x < $allcount; $x++) {
                    $vodetail = new voucher_detail();
                    $vodetail->voucher_date = $vodatess;
                    $vodetail->voucherno = $request->voucherno;
                    $vodetail->drcr = $request->drcr[$x];
                    $vodetail->code = $request->code[$x];
                    $vodetail->description = $request->description[$x];
                    $vodetail->dramount = $request->dramount[$x];
                    $vodetail->cramount = $request->cramount[$x];
                    $vodetail->narration = $request->narration[$x];
                    $vodetail->updatedby = Session::get('adminloginid');
                    $vodetail->updatedbytype =Session::get('logintype');
                    $vodetail->session =Session::get('sessionof');
                    $vodetail->save();

                    // ------------------------------------------------------tbl_ledgerDd-------------------------------------------------//

                    if ($request->drcr[$x] != "Debit") {
                        $dr_cr = "Cr";
                        $amount = $request->cramount[$x];
                    } else {
                        $dr_cr = "Dr";
                        $amount = $request->dramount[$x];
                    }

                    $getl = DB::table('ledger_masters')->where("ledgerCode","=",$request->code[$x])->first();
                    $ledgerme=new general_ledgers();
                    $ledgerme->LoanId="";
                    $ledgerme->accountNo="";
                    $ledgerme->groupCode=$getl->groupCode;
                    $ledgerme->ledgerCode=$getl->ledgerCode;
                    $ledgerme->formName="Voucher";
                    $ledgerme->transactionDate=date('Y-m-d');
                    $ledgerme->transactionType=$dr_cr;
                    $ledgerme->transactionAmount=$amount;
                    $ledgerme->refid=$request->voucherno;
                    $ledgerme->narration="Voucher No. " . $request->voucherno;
                    $ledgerme->branchId=Null;
                    $ledgerme->agentId=Session::get('adminloginid');
                    $ledgerme->updatedBy = Session::get('adminloginid');
                    $ledgerme->updatedbytype = Session::get('user_type');
                    $ledgerme->sessionId	 = Session::get('sessionof');
                    $ledgerme->save();


                }
            }
        }
    }
}
