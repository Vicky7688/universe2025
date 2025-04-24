<?php

namespace App\Http\Controllers;
use App\DataTables\InvoiceDataTable;

use App\Models\retails;
use App\Models\items;
use App\Models\salemaster;
use App\Models\item_sale;
use App\Models\sale_book;
use App\Models\tbl_ledger;
use App\Models\item_purchase;
use App\Models\item_rates;
use App\Models\ledgers;
use App\Models\ret_discount;
use App\Models\invoice_nos;
use App\Models\purchase_order;
use App\Models\discounts;
use Illuminate\Http\Request;
use DB;
use Session;

class salecontroller extends Controller
{


  

    public function sales()
    {



        $pagetitle="Add Sale";
        $pageto=url('sale');
        $formurl=url('formurl');
        $getinvoice = salemaster::where('status', '=', 'save')->get();

        return view("sales",compact('pagetitle','pageto','formurl','getinvoice'));
    }


    public function sale()
    {

        $result = DB::table('invoice_nos')->select(DB::raw('IFNULL(MAX(numbers), 0) as max_numbers'))->first();
        $maxNumbers = $result->max_numbers;

        $newNumbers = $maxNumbers + 1;
        DB::table('invoice_nos')->insert([
        'numbers' => $newNumbers,
        'start_date' => Session::get('sessionstartdate'), // Replace with your start_date value
        'end_date' => Session::get('sessionenddate'),   // Replace with your end_date value
        'created_at' => now(),
        'updated_at' => now(),
        ]);
        // $newNumbers=4;

        $pagetitle="Add Sale";
        $pageto=url('sale');
        $formurl=url('formurl');
        $getinvoice = salemaster::where('status', '=', 'save')->get();

        return view("sale",compact('pagetitle','pageto','formurl','getinvoice','newNumbers'));
    }
    public function addtopurchase(Request $request)
    {

        $code=$request->code;
        $quantity=$request->quantity;
        purchase_order::insert([
            'itemid' => $code,
            'numberofitems' => $quantity,
            'entrydate' => now(),
            'updatedby' => Session::get('adminloginid'),
            'updatedbytype' => Session::get('logintype'),
        ]);
        return response()->json();
    }
    public function getholdsale()
    {

        $gatdata = item_sale::all();

        return response()->json($gatdata);
    }

    public function sgetitems(Request $request)
    {
        $gatdata = retails::where(
            "retailercode",
            "like",
            "%" . $request->name . "%"
        )->where("groupname","=",'18')->get();

        return response()->json($gatdata);
    }

    public function sgetretaildata(Request $request)
    {
        $data['gatdatabyitemcode'] = retails::where("retailercode","=",$request->name)->first();
        $data['curbal'] = tbl_ledger::where("account_code","=",$request->name)->where("type","=",'CreditPayment')->sum('cr_amount');

        return response()->json($data);
    }

    public function sitemgetitemsname(Request $request)
    {
        $gatdata = items::where("name", "like", $request->name . "%")->pluck(
            "name",
            "itemcode"
        );

        return response()->json($gatdata);
    }

    public function sitemgetitems(Request $request)
    {
        // $gatdata = items::where('id','!=','')->get();




        $gatdata = items::join('item_rates', 'items.id', '=', 'item_rates.itemid')
            ->select('items.*', 'item_rates.id as rateid', 'item_rates.mrp', 'item_rates.salerate', 'item_rates.purchaserate')
            ->where("itemcode", "like", $request->name . "%")->orwhere("name", "like", $request->name . "%")
            ->orderBy('itemcode')
            ->get();

        return response()->json($gatdata);
    }

    public function sgetitemdataunit(Request $request)
    {


        $getit = item_rates::find($request->name);
        $getitcode = items::find($getit->itemid);



         $boxopeningstock = items::where("itemcode", "=", $getitcode->itemcode)->value("openingstock");
         $boxquantityplus = item_purchase::where("itemcode", "=", $getitcode->itemcode)->sum("quantity");
         $boxquantityminu = item_sale::where("itemcode", "=", $getitcode->itemcode)->sum("quantity");

         $peropeningstock = items::where("itemcode", "=", $getitcode->itemcode)->value("singleopeningstock");
         $perquantityplus = item_purchase::where("itemcode", "=", $getitcode->itemcode)->sum("pquantity");
         $perquantityminu = item_sale::where("itemcode", "=", $getitcode->itemcode)->sum("pquantity");

        $balance = $boxopeningstock + $boxquantityplus-$boxquantityminu;
        $perbalance = $peropeningstock + $perquantityplus-$perquantityminu;
        $balance =floor($perbalance/$getitcode->unitquantity);

        $gatdata = items::where("itemcode", "=", $getitcode->itemcode)->first();

        $discounthai=ret_discount::where('retailercode','=',$request->accountcode)->where('itemcode','=',$getitcode->itemcode)->sum('price');
        if($discounthai){
            $discount=$discounthai;
        }else{



            $discountishai = discounts::where('discounttype', 'box')
                    ->where('itemid', $getitcode->id)
                    ->where(function($query) {
                        $query->where('qtfrom', '<=', 1)
                            ->where('qtto', '>=', 1);
                    })
                    ->value('price');
                        if($discountishai){
                            $discount=$discountishai;
                        }else{
                            $discount=0;
                        }


        }

        $data = [
            "discount" => $discount,
            "balance" => $balance,
            "perbalance" => $perbalance,
            "gatdata" => $gatdata,
            "getit" => $getit,
        ];
        return response()->json($data);
    }

    public function sgetitemdata(Request $request)
    {


        $getit = item_rates::find($request->name);
        $getitcode = items::find($getit->itemid);



       $boxopeningstock = items::where("itemcode", "=", $getitcode->itemcode)->value("openingstock");
       $boxquantityplus = item_purchase::where("itemcode", "=", $getitcode->itemcode)->sum("quantity");
       $boxquantityminu = item_sale::where("itemcode", "=", $getitcode->itemcode)->sum("quantity");
       $peropeningstock = items::where("itemcode", "=", $getitcode->itemcode)->value("singleopeningstock");
       $perquantityplus = item_purchase::where("itemcode", "=", $getitcode->itemcode)->sum("pquantity");
       $perquantityminu = item_sale::where("itemcode", "=", $getitcode->itemcode)->sum("pquantity");
       $balance = $boxopeningstock + $boxquantityplus-$boxquantityminu;
       $perbalance = $peropeningstock + $perquantityplus-$perquantityminu;
       $balance =floor($perbalance/$getitcode->unitquantity);
        $gatdata = items::where("itemcode", "=", $getitcode->itemcode)->first();


        $discounthai=ret_discount::where('retailercode','=',$request->accountcode)->where('itemcode','=',$getitcode->itemcode)->sum('price');
        if($discounthai){
            $discount=$discounthai;
        }else{

            $discountishai = discounts::where('discounttype', 'box')
                    ->where('itemid', $getitcode->id)
                    ->where(function($query) {
                        $query->where('qtfrom', '<=', 1)
                            ->where('qtto', '>=', 1);
                    })
                    ->value('price');
                        if($discountishai){
                            $discount=$discountishai;
                        }else{
                            $discount=0;
                        }
        }
        $data = [
            "discount" => $discount,
            "balance" => $balance,
            "perbalance" => $perbalance,
            "gatdata" => $gatdata,
            "getit" => $getit,
        ];
        return response()->json($data);
    }


    public function sgetinviocenumber(Request $request)
    {
        $getinvoice = salemaster::where(
            "invoiceno",
            "like",
            $request->invoicenumber . "%"
        )->where('status', '=', 'save')->pluck("invoiceno", "id");

        return response()->json($getinvoice);
    }

    public function sgetdatabyinvoice(Request $request)
    {
        $getinvoice = salemaster::find($request->invoicenumber);

        $getinvoiceitems = item_sale::where(
            "invoiceno",
            "=",
            $getinvoice->invoiceno
        )->get();

        $data = [
            "getinvoice" => $getinvoice,
            "getinvoiceitems" => $getinvoiceitems,
        ];
        return response()->json($data);
    }






    public function submitsale(Request $request)
    {


        $cont = count($request->itemcode);
        $update = $request->id;
        if (!empty($update)) {


















































            DB::beginTransaction();

            try {
        $getinvoiceno = salemaster::find($update)->invoiceno;
            $insert = salemaster::find($update);
            $insert->invpicetype = $request->invpicetype;
            $insert->invoiceno = $request->invoiceno;
            $insert->invoicenodate = date("Y-m-d",strtotime($request->invoicenodate));
            $insert->mode = $request->mode;
            $insert->effectstock = $request->effectstock;
            $insert->accountcode = $request->accountcode;
            $insert->currentbalance = $request->currentbalance;
            $insert->accountname = $request->accountname;
            $insert->gstnoforref = $request->gstnoforref;
            $insert->vehichleno = $request->vehichleno;
            $insert->grandtotal = $request->grandtotal;
            $insert->basicamount = $request->basicamount;
            $insert->totalmrpvalue = $request->totalmrpvalue;
            $insert->refund = $request->refund;
            $insert->cashrecieved = $request->cashrecieved;
            $insert->bsgstamount = $request->bsgstamount;
            $insert->totalsaving = $request->totalsaving;
            $insert->payment = $request->payment;
            $insert->distotal = $request->distotal;
            $insert->paymenttype = $request->paymenttype;
            $insert->csgstamount = $request->csgstamount;
            $insert->totalsalerate = $request->totalsalerate;
            $insert->cardpayment = $request->cardpayment;
            $insert->creditpayment = $request->creditpayment;
            $insert->isgstamount = $request->isgstamount;
            $insert->status = $request->status;
            $insert->updatedby = Session::get('adminloginid');
            $insert->updatedbytype =Session::get('logintype');
            $insert->session =Session::get('sessionof');
            $insert->save();


            item_sale::where('invoiceno','=',$getinvoiceno)->delete();

            for ($x = 0; $x < $cont; $x++) {

                $getquantity=items::where('itemcode','=',$request->itemcode[$x])->value('unitquantity');

                if($request->baltype[$x]=='box'){
                    $quantity=$request->quantity[$x];
                    $pquantity=$request->quantity[$x]*$getquantity;
                }else{
                    $quantity=floor($request->quantity[$x]/$getquantity);
                    $pquantity=$request->quantity[$x];
                }
                $ins = new item_sale();
                $ins->invoiceno = $request->invoiceno;
                $ins->ledger_code = "S0004";
                $ins->group_code = "S0001";
                $ins->bill_date = date("Y-m-d", strtotime($request->invoicenodate));
                $ins->party_code = $request->accountcode;
                $ins->party_name = $request->accountname;
                $ins->getunit = $request->getunit[$x];
                $ins->itemcode = $request->itemcode[$x];
                $ins->baltype = $request->baltype[$x];
                $ins->itemname = $request->itemname[$x];
                $ins->balance = $request->balance[$x];
                $ins->sbalance = $request->sbalance[$x];
                $ins->quantity = $quantity;
                $ins->pquantity = $pquantity;
                $ins->mrp = $request->mrp[$x];
                $ins->salerate = $request->salerate[$x];
                $ins->discount = $request->discount[$x];
                $ins->discountamt = $request->discountamt[$x];
                $ins->sgst = $request->sgst[$x];
                $ins->sgstamount = $request->sgstamount[$x];
                $ins->cgst = $request->cgst[$x];
                $ins->cgstamount = $request->cgstamount[$x];
                $ins->igst = $request->igst[$x];
                $ins->igstamount = $request->igstamount[$x];
                $ins->total = $request->total[$x];
                $ins->status = $request->status;
                $ins->updatedby = Session::get('adminloginid');
                $ins->updatedbytype =Session::get('logintype');
                $ins->session =Session::get('sessionof');
                $ins->save();
            }



            $narrartion = new sale_book();
            $narrartion->date = date("Y-m-d", strtotime($request->invoicenodate));
            $narrartion->narration = "Bill No. " . $request->invoiceno;
            $narrartion->name = $request->accountname;
            $narrartion->tax_rate =
                $request->bsgstamount +
                $request->csgstamount +
                $request->isgstamount;
            $narrartion->basic = $request->basicamount;
            $narrartion->tax_amount =
                $request->bsgstamount +
                $request->csgstamount +
                $request->isgstamount;
            $narrartion->total = $request->grandtotal;
            $narrartion->invoiceno = $request->invoiceno;
            $narrartion->status = $request->status;
            $narrartion->updatedby = Session::get('adminloginid');
            $narrartion->updatedbytype =Session::get('logintype');
            $narrartion->session =Session::get('sessionof');
            $narrartion->save();


            // ------------------------------------------------------tbl_ledger-------------------------------------------------//
            // ------------------------------------------------------tbl_ledger-------------------------------------------------//
            // ------------------------------------------------------tbl_ledger-------------------------------------------------//
            // ------------------------------------------------------tbl_ledger-------------------------------------------------//

            // ------------------------------------------------------tbl_ledgerDd-------------------------------------------------//
            tbl_ledger::where("invoiceno", "=", $getinvoiceno)->where("invoicetype", "=",'sale')->delete();

            $getl = ledgers::where('ledger_code', '=', $request->accountcode)->first();
            $narrartion = new tbl_ledger();
            $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
            $narrartion->narration = "Bill No. " . $request->invoiceno;
            $narrartion->type = "Sale";
            $narrartion->dr_amount = 0;
            $narrartion->cr_amount = $request->grandtotal;
            $narrartion->account_code = $request->accountcode;
            $narrartion->account_name = $request->accountname;
            $narrartion->group_code =  $getl->group_code;
            $narrartion->dr_cr = "Cr";
            $narrartion->intro_code = "";
            $narrartion->token_no = "";
            $narrartion->transport = "";
            $narrartion->gr_rr = $getl->ledger_code;
            $narrartion->remarks = "grandtotal";
            $narrartion->invoiceno = $request->invoiceno;
            $narrartion->status = $request->status;
            $narrartion->invoicetype = 'sale';
            $narrartion->updatedby = Session::get('adminloginid');
            $narrartion->updatedbytype =Session::get('logintype');
            $narrartion->session =Session::get('sessionof');
            $narrartion->save();


            if ($request->cashrecieved > 0) {


                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "Cash";
                $narrartion->dr_amount =   $request->cashrecieved;
                $narrartion->cr_amount =0;
                $narrartion->account_code = "GRC001";
                $narrartion->account_name = "Cash Account";
                $narrartion->group_code = "GRC001";
                $narrartion->dr_cr = "Dr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = "";
                $narrartion->gr_rr = "C0009";
                $narrartion->remarks = "cashrecieved";
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'sale';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();
            }





            if ($request->cardpayment > 0) {


                if ($request->paymenttype == 'Paytm') {
                    $ledger = 'P0010';
                }
                if ($request->paymenttype == 'Credit Card') {
                    $ledger = 'C0012';
                }
                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "Bank";
                $narrartion->dr_amount =$request->cardpayment;
                $narrartion->cr_amount = 0;
                $narrartion->account_code = "GRB001";
                $narrartion->account_name = "Bank";
                $narrartion->group_code = "GRB001";
                $narrartion->dr_cr = "Dr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = "";
                $narrartion->gr_rr = $ledger;
                $narrartion->remarks = "bank";
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'sale';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();
            }

            if ($request->creditpayment > 0) {




            $getl = ledgers::where('ledger_code', '=', $request->accountcode)->first();
                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "CreditPayment";
                $narrartion->dr_amount =0;
                $narrartion->cr_amount = $request->creditpayment;
                $narrartion->account_code = $request->accountcode;
                $narrartion->account_name = $request->accountname;
                $narrartion->group_code =  $getl->group_code;
                $narrartion->dr_cr = "Cr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = "";
                $narrartion->gr_rr = $getl->ledger_code;
                $narrartion->remarks ="Credit by ".$request->accountcode;
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'sale';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();
            }







            $getl = ledgers::where('ledger_code', '=', $request->accountcode)->first();
            $narrartion = new tbl_ledger();
            $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
            $narrartion->narration = "Bill No. " . $request->invoiceno;
            $narrartion->type = "Payment Recieved ";
            $narrartion->dr_amount = $request->grandtotal;
            $narrartion->cr_amount = 0;
            $narrartion->account_code = $request->accountcode;
            $narrartion->account_name = $request->accountname;
            $narrartion->group_code =  $getl->group_code;
            $narrartion->dr_cr = "Dr";
            $narrartion->intro_code = "";
            $narrartion->token_no = "";
            $narrartion->transport = "";
            $narrartion->gr_rr = $getl->ledger_code;
            $narrartion->remarks = "grandtotal";
            $narrartion->invoiceno = $request->invoiceno;
            $narrartion->status = $request->status;
            $narrartion->invoicetype = 'sale';
            $narrartion->updatedby = Session::get('adminloginid');
            $narrartion->updatedbytype =Session::get('logintype');
            $narrartion->session =Session::get('sessionof');
            $narrartion->save();




            if ($request->basicamount > 0) {

                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));

                $getl = ledgers::where('ledger_code', '=', $request->accountcode)->first();
                // dd($getl);
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "SALE ACCOUNT";
                $narrartion->dr_amount = 0;
                $narrartion->cr_amount = $request->basicamount;
                $narrartion->account_code = 'GPS002';
                $narrartion->account_name = 'sale account';
                $narrartion->group_code = 'GPS002';
                $narrartion->dr_cr = "Cr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = "";
                $narrartion->gr_rr = 'S0011';
                $narrartion->remarks = "basicamount";
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'sale';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();
            }




            // ------------------------------------------------------tbl_ledgersgst-------------------------------------------------//
            // ------------------------------------------------------tbl_ledgersgst-------------------------------------------------//
            // ------------------------------------------------------tbl_ledgersgst-------------------------------------------------//
            if ($request->bsgstamount > 0) {
                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "SALE ACCOUNT";
                $narrartion->dr_amount = 0;
                $narrartion->cr_amount = $request->bsgstamount;
                $narrartion->account_code = "GRT001";
                $narrartion->account_name = "SALE PSGST";
                $narrartion->group_code = "GRT001";
                $narrartion->dr_cr = "Cr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = "";
                $narrartion->gr_rr = "S0012";
                $narrartion->remarks = "bsgstamount";
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'sale';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();
            }

            // ------------------------------------------------------tbl_ledgersgst-------------------------------------------------//
            // ------------------------------------------------------tbl_ledgercgst-------------------------------------------------//

            if ($request->csgstamount > 0) {
                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "SALE ACCOUNT";
                $narrartion->dr_amount = 0;
                $narrartion->cr_amount = $request->csgstamount;
                $narrartion->account_code = "GRT001";
                $narrartion->account_name = "SALE PCGST";
                $narrartion->group_code = "GRT001";
                $narrartion->dr_cr = "Cr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = "";
                $narrartion->gr_rr = "S0013";
                $narrartion->remarks = "csgstamount";
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'sale';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();
            }
            // ------------------------------------------------------tbl_ledgersgst-------------------------------------------------//
            // ------------------------------------------------------tbl_ledgerigst-------------------------------------------------//

            if ($request->isgstamount > 0) {
                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "SALE ACCOUNT";
                $narrartion->dr_amount = 0;
                $narrartion->cr_amount = $request->isgstamount;
                $narrartion->account_code = "GRT001";
                $narrartion->account_name = "SALE PIGST";
                $narrartion->group_code = "GRT001";
                $narrartion->dr_cr = "Cr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = "";
                $narrartion->gr_rr = "S0014";
                $narrartion->remarks = "isgstamount";
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'sale';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();
            }




            if ($request->distotal > 0) {


                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "SALE ACCOUNT DISCOUNT";
                $narrartion->dr_amount =  $request->distotal;
                $narrartion->cr_amount =0;
                $narrartion->account_code = "GRD001";
                $narrartion->account_name = "DISCOUNT";
                $narrartion->group_code = "GRD001";
                $narrartion->dr_cr = "Dr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = "";
                $narrartion->gr_rr = "D0005";
                $narrartion->remarks = "DISCOUNT";
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'sale';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();




                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "SALE ACCOUNT DISCOUNT";
                $narrartion->dr_amount = 0;
                $narrartion->cr_amount =  $request->distotal;
                $narrartion->account_code = $request->accountcode;
                $narrartion->account_name = $request->accountname;
                $narrartion->group_code = DB::table('ledgers')->where('ledger_code','=',$request->accountcode)->value('group_code');
                $narrartion->dr_cr = "Cr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = "";
                $narrartion->gr_rr = $request->accountcode;
                $narrartion->remarks = "DISCOUNT";
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'sale';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();


            }




            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
          Log::error('Transaction failed: ' . $e->getMessage());
        }



            return response()->json(["status" => true]);
            die;
















           } else {


            DB::beginTransaction();

            try {

            $insert = new salemaster();
            $insert->invpicetype = $request->invpicetype;
            $insert->invoiceno = $request->invoiceno;
            $insert->invoicenodate = date("Y-m-d",strtotime($request->invoicenodate));
            $insert->mode = $request->mode;
            $insert->effectstock = $request->effectstock;
            $insert->accountcode = $request->accountcode;
            $insert->currentbalance = $request->currentbalance;
            $insert->accountname = $request->accountname;
            $insert->gstnoforref = $request->gstnoforref;
            $insert->vehichleno = $request->vehichleno;
            $insert->grandtotal = $request->grandtotal;
            $insert->basicamount = $request->basicamount;
            $insert->totalmrpvalue = $request->totalmrpvalue;
            $insert->refund = $request->refund;
            $insert->cashrecieved = $request->cashrecieved;
            $insert->bsgstamount = $request->bsgstamount;
            $insert->totalsaving = $request->totalsaving;
            $insert->payment = $request->payment;
            $insert->distotal = $request->distotal;
            $insert->paymenttype = $request->paymenttype;
            $insert->csgstamount = $request->csgstamount;
            $insert->totalsalerate = $request->totalsalerate;
            $insert->cardpayment = $request->cardpayment;
            $insert->creditpayment = $request->creditpayment;
            $insert->isgstamount = $request->isgstamount;
            $insert->status = $request->status;
            $insert->updatedby = Session::get('adminloginid');
            $insert->updatedbytype =Session::get('logintype');
            $insert->session =Session::get('sessionof');
            $insert->save();



            for ($x = 0; $x < $cont; $x++) {

                $getquantity=items::where('itemcode','=',$request->itemcode[$x])->value('unitquantity');

                if($request->baltype[$x]=='box'){
                    $quantity=$request->quantity[$x];
                    $pquantity=$request->quantity[$x]*$getquantity;
                }else{
                    $quantity=floor($request->quantity[$x]/$getquantity);
                    $pquantity=$request->quantity[$x];
                }
                $ins = new item_sale();
                $ins->invoiceno = $request->invoiceno;
                $ins->ledger_code = "S0004";
                $ins->group_code = "S0001";
                $ins->bill_date = date("Y-m-d", strtotime($request->invoicenodate));
                $ins->party_code = $request->accountcode;
                $ins->party_name = $request->accountname;
                $ins->getunit = $request->getunit[$x];
                $ins->itemcode = $request->itemcode[$x];
                $ins->baltype = $request->baltype[$x];
                $ins->itemname = $request->itemname[$x];
                $ins->balance = $request->balance[$x];
                $ins->sbalance = $request->sbalance[$x];
                $ins->quantity = $quantity;
                $ins->pquantity = $pquantity;
                $ins->mrp = $request->mrp[$x];
                $ins->salerate = $request->salerate[$x];
                $ins->discount = $request->discount[$x];
                $ins->discountamt = $request->discountamt[$x];
                $ins->sgst = $request->sgst[$x];
                $ins->sgstamount = $request->sgstamount[$x];
                $ins->cgst = $request->cgst[$x];
                $ins->cgstamount = $request->cgstamount[$x];
                $ins->igst = $request->igst[$x];
                $ins->igstamount = $request->igstamount[$x];
                $ins->total = $request->total[$x];
                $ins->status = $request->status;
                $ins->updatedby = Session::get('adminloginid');
                $ins->updatedbytype =Session::get('logintype');
                $ins->session =Session::get('sessionof');
                $ins->save();
            }



            $narrartion = new sale_book();
            $narrartion->date = date("Y-m-d", strtotime($request->invoicenodate));
            $narrartion->narration = "Bill No. " . $request->invoiceno;
            $narrartion->name = $request->accountname;
            $narrartion->tax_rate =
                $request->bsgstamount +
                $request->csgstamount +
                $request->isgstamount;
            $narrartion->basic = $request->basicamount;
            $narrartion->tax_amount =
                $request->bsgstamount +
                $request->csgstamount +
                $request->isgstamount;
            $narrartion->total = $request->grandtotal;
            $narrartion->invoiceno = $request->invoiceno;
            $narrartion->status = $request->status;
            $narrartion->updatedby = Session::get('adminloginid');
            $narrartion->updatedbytype =Session::get('logintype');
            $narrartion->session =Session::get('sessionof');
            $narrartion->save();


            // ------------------------------------------------------tbl_ledger-------------------------------------------------//
            // ------------------------------------------------------tbl_ledger-------------------------------------------------//
            // ------------------------------------------------------tbl_ledger-------------------------------------------------//
            // ------------------------------------------------------tbl_ledger-------------------------------------------------//

            // ------------------------------------------------------tbl_ledgerDd-------------------------------------------------//


            $getl = ledgers::where('ledger_code', '=', $request->accountcode)->first();
            $narrartion = new tbl_ledger();
            $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
            $narrartion->narration = "Bill No. " . $request->invoiceno;
            $narrartion->type = "Sale";
            $narrartion->dr_amount = 0;
            $narrartion->cr_amount = $request->grandtotal;
            $narrartion->account_code = $request->accountcode;
            $narrartion->account_name = $request->accountname;
            $narrartion->group_code =  $getl->group_code;
            $narrartion->dr_cr = "Cr";
            $narrartion->intro_code = "";
            $narrartion->token_no = "";
            $narrartion->transport = "";
            $narrartion->gr_rr = $getl->ledger_code;
            $narrartion->remarks = "grandtotal";
            $narrartion->invoiceno = $request->invoiceno;
            $narrartion->status = $request->status;
            $narrartion->invoicetype = 'sale';
            $narrartion->updatedby = Session::get('adminloginid');
            $narrartion->updatedbytype =Session::get('logintype');
            $narrartion->session =Session::get('sessionof');
            $narrartion->save();


            if ($request->cashrecieved > 0) {


                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "Cash";
                $narrartion->dr_amount =   $request->cashrecieved;
                $narrartion->cr_amount =0;
                $narrartion->account_code = "GRC001";
                $narrartion->account_name = "Cash Account";
                $narrartion->group_code = "GRC001";
                $narrartion->dr_cr = "Dr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = "";
                $narrartion->gr_rr = "C0009";
                $narrartion->remarks = "cashrecieved";
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'sale';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();
            }





            if ($request->cardpayment > 0) {


                if ($request->paymenttype == 'Paytm') {
                    $ledger = 'P0010';
                }
                if ($request->paymenttype == 'Credit Card') {
                    $ledger = 'C0012';
                }
                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "Bank";
                $narrartion->dr_amount =$request->cardpayment;
                $narrartion->cr_amount = 0;
                $narrartion->account_code = "GRB001";
                $narrartion->account_name = "Bank";
                $narrartion->group_code = "GRB001";
                $narrartion->dr_cr = "Dr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = "";
                $narrartion->gr_rr = $ledger;
                $narrartion->remarks = "bank";
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'sale';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();
            }

            if ($request->creditpayment > 0) {




            $getl = ledgers::where('ledger_code', '=', $request->accountcode)->first();
                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "CreditPayment";
                $narrartion->dr_amount =0;
                $narrartion->cr_amount = $request->creditpayment;
                $narrartion->account_code = $request->accountcode;
                $narrartion->account_name = $request->accountname;
                $narrartion->group_code =  $getl->group_code;
                $narrartion->dr_cr = "Cr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = "";
                $narrartion->gr_rr = $getl->ledger_code;
                $narrartion->remarks ="Credit by ".$request->accountcode;
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'sale';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();
            }







            $getl = ledgers::where('ledger_code', '=', $request->accountcode)->first();
            $narrartion = new tbl_ledger();
            $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
            $narrartion->narration = "Bill No. " . $request->invoiceno;
            $narrartion->type = "Payment Recieved ";
            $narrartion->dr_amount = $request->grandtotal;
            $narrartion->cr_amount = 0;
            $narrartion->account_code = $request->accountcode;
            $narrartion->account_name = $request->accountname;
            $narrartion->group_code =  $getl->group_code;
            $narrartion->dr_cr = "Dr";
            $narrartion->intro_code = "";
            $narrartion->token_no = "";
            $narrartion->transport = "";
            $narrartion->gr_rr = $getl->ledger_code;
            $narrartion->remarks = "grandtotal";
            $narrartion->invoiceno = $request->invoiceno;
            $narrartion->status = $request->status;
            $narrartion->invoicetype = 'sale';
            $narrartion->updatedby = Session::get('adminloginid');
            $narrartion->updatedbytype =Session::get('logintype');
            $narrartion->session =Session::get('sessionof');
            $narrartion->save();




            if ($request->basicamount > 0) {

                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));

                $getl = ledgers::where('ledger_code', '=', $request->accountcode)->first();
                // dd($getl);
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "SALE ACCOUNT";
                $narrartion->dr_amount = 0;
                $narrartion->cr_amount = $request->basicamount;
                $narrartion->account_code = 'GPS002';
                $narrartion->account_name = 'sale account';
                $narrartion->group_code = 'GPS002';
                $narrartion->dr_cr = "Cr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = "";
                $narrartion->gr_rr = 'S0011';
                $narrartion->remarks = "basicamount";
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'sale';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();
            }




            // ------------------------------------------------------tbl_ledgersgst-------------------------------------------------//
            // ------------------------------------------------------tbl_ledgersgst-------------------------------------------------//
            // ------------------------------------------------------tbl_ledgersgst-------------------------------------------------//
            if ($request->bsgstamount > 0) {
                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "SALE ACCOUNT";
                $narrartion->dr_amount = 0;
                $narrartion->cr_amount = $request->bsgstamount;
                $narrartion->account_code = "GRT001";
                $narrartion->account_name = "SALE PSGST";
                $narrartion->group_code = "GRT001";
                $narrartion->dr_cr = "Cr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = "";
                $narrartion->gr_rr = "S0012";
                $narrartion->remarks = "bsgstamount";
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'sale';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();
            }

            // ------------------------------------------------------tbl_ledgersgst-------------------------------------------------//
            // ------------------------------------------------------tbl_ledgercgst-------------------------------------------------//

            if ($request->csgstamount > 0) {
                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "SALE ACCOUNT";
                $narrartion->dr_amount = 0;
                $narrartion->cr_amount = $request->csgstamount;
                $narrartion->account_code = "GRT001";
                $narrartion->account_name = "SALE PCGST";
                $narrartion->group_code = "GRT001";
                $narrartion->dr_cr = "Cr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = "";
                $narrartion->gr_rr = "S0013";
                $narrartion->remarks = "csgstamount";
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'sale';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();
            }
            // ------------------------------------------------------tbl_ledgersgst-------------------------------------------------//
            // ------------------------------------------------------tbl_ledgerigst-------------------------------------------------//

            if ($request->isgstamount > 0) {
                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "SALE ACCOUNT";
                $narrartion->dr_amount = 0;
                $narrartion->cr_amount = $request->isgstamount;
                $narrartion->account_code = "GRT001";
                $narrartion->account_name = "SALE PIGST";
                $narrartion->group_code = "GRT001";
                $narrartion->dr_cr = "Cr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = "";
                $narrartion->gr_rr = "S0014";
                $narrartion->remarks = "isgstamount";
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'sale';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();
            }




            if ($request->distotal > 0) {


                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "SALE ACCOUNT DISCOUNT";
                $narrartion->dr_amount =  $request->distotal;
                $narrartion->cr_amount =0;
                $narrartion->account_code = "GRD001";
                $narrartion->account_name = "DISCOUNT";
                $narrartion->group_code = "GRD001";
                $narrartion->dr_cr = "Dr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = "";
                $narrartion->gr_rr = "D0005";
                $narrartion->remarks = "DISCOUNT";
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'sale';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();




                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date("Y-m-d",strtotime($request->invoicenodate));
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "SALE ACCOUNT DISCOUNT";
                $narrartion->dr_amount = 0;
                $narrartion->cr_amount =  $request->distotal;
                $narrartion->account_code = $request->accountcode;
                $narrartion->account_name = $request->accountname;
                $narrartion->group_code = DB::table('ledgers')->where('ledger_code','=',$request->accountcode)->value('group_code');
                $narrartion->dr_cr = "Cr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = "";
                $narrartion->gr_rr = $request->accountcode;
                $narrartion->remarks = "DISCOUNT";
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'sale';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();


            }




            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
          Log::error('Transaction failed: ' . $e->getMessage());
        }



            return response()->json(["status" => true]);
            die;
        }
    }

    public function deletesale(Request $request)
    {

        $getinvoiceno = salemaster::find($request->delid)->invoiceno;
        salemaster::where('invoiceno', '=', $getinvoiceno)->delete();
        item_sale::where('invoiceno', '=', $getinvoiceno)->delete();
        sale_book::where('invoiceno', '=', $getinvoiceno)->delete();
        tbl_ledger::where('invoiceno', '=', $getinvoiceno)->delete();
    }




    public function getbybarcodenumber(Request $request)
    {

        $allis = items::where("barcodenumber", "=", $request->name)->first();
        $openingstock = items::where("barcodenumber", "=", $request->name)->value(
            "openingstock"
        );
        $quantity = item_purchase::where("itemcode", "=", $allis->name)->sum(
            "quantity"
        );
        $balance = $openingstock + $quantity;
        $gatdata = items::where("barcodenumber", "=", $request->name)->first();
        if ($gatdata) {

            $data = [
                "status" => true,
                "balance" => $balance,
                "gatdata" => $gatdata,
            ];
        } else {
            $data = [
                "status" => false,
                "balance" => $balance,
                "gatdata" => $gatdata,
            ];
        }

        return response()->json($data);
    }
}
