<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\items;
use App\Models\item_sale;
use App\Models\item_rates;
use App\Models\retails;
use App\Models\ledgers;
use App\Models\purchasemaster;
use App\Models\item_purchase;
use App\Models\purchase_book;
use App\Models\tbl_ledger;
use DB;
use Session;
use Illuminate\Support\Facades\Log;
class purchasecontroller extends Controller
{
    public function getdatabyinvoice(Request $request)
    {
        $getinvoice = purchasemaster::find($request->invoicenumber);

        $getinvoiceitems = item_purchase::where(
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

    public function getinviocenumber(Request $request)
    {
        $getinvoice = purchasemaster::where(
            "invoiceno",
            "like",
            $request->invoicenumber . "%"
        )->where('status', '=', 'save')->pluck("invoiceno", "id");

        return response()->json($getinvoice);
    }

    public function getitemdataunit(Request $request)
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

        $data = [
            "balance" => $balance,
            "perbalance" => $perbalance,
            "gatdata" => $gatdata,
            "getit" => $getit,
        ];
        return response()->json($data);
    }

    public function getitemdata(Request $request)
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


        // $boxopeningstock = items::where("itemcode", "=", $getitcode->itemcode)->value("openingstock");
        // $peropeningstock = items::where("itemcode", "=", $getitcode->itemcode)->value("singleopeningstock");
        // $boxquantityplus = item_purchase::where("baltype", "=", 'box')->where("itemcode", "=", $getitcode->itemcode)->sum("quantity");
        // $boxquantityminu = item_sale::where("baltype", "=", 'box')->where("itemcode", "=", $getitcode->itemcode)->sum("quantity");
        // $perquantityplus = item_purchase::where("baltype", "=", 'single')->where("itemcode", "=", $getitcode->itemcode)->sum("quantity");
        // $perquantityminu = item_sale::where("baltype", "=", 'single')->where("itemcode", "=", $getitcode->itemcode)->sum("quantity");
        // $balance = $boxopeningstock + $boxquantityplus-$boxquantityminu;
        // $perbalance = $peropeningstock + $perquantityplus-$perquantityminu;
        // $balance = floor($perbalance/$getitcode->unitquantity);
        $gatdata = items::where("itemcode", "=", $getitcode->itemcode)->first();

        $data = [
            "balance" => $balance,
            "perbalance" => $perbalance,
            "gatdata" => $gatdata,
            "getit" => $getit,
        ];
        return response()->json($data);
    }

    public function itemgetitemsname(Request $request)
    {
        $gatdata = items::where("name", "like", $request->name . "%")->get();

        return response()->json($gatdata);
    }

    public function itemgetitems(Request $request)
    {



        $gatdata = items::join('item_rates', 'items.id', '=', 'item_rates.itemid')
            ->select('items.*', 'item_rates.id as rateid', 'item_rates.mrp', 'item_rates.salerate', 'item_rates.purchaserate', 'item_rates.mrpsingle', 'item_rates.saleratesingle', 'item_rates.purchaseratesingle')
            ->where("itemcode", "like", $request->name . "%")->orwhere("name", "like", $request->name . "%")
            ->orderBy('itemcode') // Ensure items are ordered by itemcode
            ->get();


        return response()->json($gatdata);
    }

    public function getretaildata(Request $request)
    {
        $gatdatabyitemcode = retails::where("retailercode","=",$request->name)->where("groupname","=",'17')->first();

        return response()->json($gatdatabyitemcode);
    }
    public function getitems(Request $request)
    {
        $gatdata = retails::where("retailercode","like","%" . $request->name . "%")->where("groupname","=",'17')->get();

        return response()->json($gatdata);
    }


    public function purchases()
    {



        $pagetitle="Add Purchase";
        $pageto=url('purchase');
        $formurl=url('formurl');
        $getinvoice = purchasemaster::where('status', '=', 'save')->get();

        return view("purchases",compact('pagetitle','pageto','formurl','getinvoice'));
    }


    public function purchaseProduct()
    {
        $pagetitle="Add Purchase";
        $pageto=url('purchase');
        $formurl=url('formurl');
        $getinvoice = purchasemaster::where('status', '=', 'save')->get();

        return view("purchase",compact('pagetitle','pageto','formurl','getinvoice'));
    }

    public function submitpurchasehold(Request $request)
    {
        dd($request->all());
    }

    public function submitpurchase(Request $request)
    {
    //    dd($request->toArray());
        $cont = count($request->itemcode);
        $update = $request->id;
        if (!empty($update)) {










































            DB::beginTransaction();

            try {
                // ------------------------------------------------------purchasemaster-------------------------------------------------//
                // ------------------------------------------------------purchasemaster-------------------------------------------------//
                // ------------------------------------------------------purchasemaster-------------------------------------------------//
                $getinvoiceno = purchasemaster::find($update)->invoiceno;
                $upload = purchasemaster::find($update);
                $upload->invoicetype = $request->invoicetype;
                $upload->grno = $request->grno;
                $upload->grdate = date("Y-m-d", strtotime($request->grdate));
                $upload->invoiceno = $request->invoiceno;
                $upload->invoicedate = date(
                    "Y-m-d",
                    strtotime($request->invoicedate)
                );
                $upload->accountcode = $request->accountcode;
                $upload->accountname = $request->accountname;
                $upload->accountaddress = $request->accountaddress;
                $upload->gstnoforref = $request->gstnoforref;
                $upload->accountaddresss = $request->accountaddresss;
                $upload->transportname = $request->transportname;
                $upload->transportvehicleno = $request->transportvehicleno;
                $upload->transportgrno = $request->transportgrno;
                $upload->memo = $request->memo;
                $upload->remarks = $request->remarks;
                $upload->totalamount = $request->totalamount;
                $upload->uploadingloadingname = $request->uploadingloadingname;
                $upload->uploadingloading = $request->uploadingloading;
                $upload->cartname = $request->frieghtname;
                $upload->cart = $request->frieght;
                $upload->distotal = $request->distotal;
                $upload->grandtotal = $request->grandtotal;
                $upload->basictotalamount = $request->basictotalamount;
                $upload->sgsttotal = $request->sgsttotal;
                $upload->igsttotal = $request->igsttotal;
                $upload->cgsttotal = $request->cgsttotal;
                $upload->status = $request->status;
                $upload->updatedby = Session::get('adminloginid');
                $upload->updatedbytype =Session::get('logintype');
                $upload->session =Session::get('sessionof');
                $upload->save();

                // ------------------------------------------------------item_purchase-------------------------------------------------//
                // ------------------------------------------------------item_purchase-------------------------------------------------//
                // ------------------------------------------------------item_purchase-------------------------------------------------//
                item_purchase::where("invoiceno", "=", $getinvoiceno)->delete();
                for ($x = 0; $x < $cont; $x++) {


                    $getquantity=items::where('itemcode','=',$request->itemcode[$x])->value('unitquantity');

                    if($request->baltype[$x]=='box'){
                        $quantity=$request->quantity[$x];
                        $pquantity=$request->quantity[$x]*$getquantity;
                    }else{
                        $quantity=floor($request->quantity[$x]/$getquantity);
                        $pquantity=$request->quantity[$x];
                    }


                    $ins = new item_purchase();
                    $ins->invoiceno = $request->invoiceno;
                    $ins->ledger_code = "P0011";
                    $ins->group_code = "GPR002";
                    $ins->bill_date = date(
                        "Y-m-d",
                        strtotime($request->invoicedate)
                    );
                    $ins->party_code = $request->accountcode;
                    $ins->party_name = $request->accountname;
                    $ins->getunit = $request->getunit[$x];
                    $ins->itemcode = $request->itemcode[$x];
                    $ins->itemname = $request->itemname[$x];
                    $ins->margin = $request->margin[$x];
                    $ins->baltype = $request->baltype[$x];
                    $ins->balance = $request->balance[$x];
                    $ins->sbalance = $request->sbalance[$x];
                    $ins->hsn = $request->hsn[$x];
                    $ins->quantity = $quantity;
                    $ins->pquantity = $pquantity;
                    $ins->unit = $request->unit[$x];
                    $ins->mrp = $request->mrp[$x];
                    $ins->salerate = $request->salerate[$x];
                    $ins->purchaserate = $request->purchaserate[$x];
                    $ins->discount = $request->discount[$x];
                    $ins->discountamt = $request->discountamt[$x];
                    $ins->netamount = $request->netamount[$x];
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

                // ------------------------------------------------------purchase_book-------------------------------------------------//
                // ------------------------------------------------------purchase_book-------------------------------------------------//
                // ------------------------------------------------------purchase_book-------------------------------------------------//

                purchase_book::where("invoiceno", "=", $getinvoiceno)->delete();

                $narrartion = new purchase_book();
                $narrartion->date = date(
                    "Y-m-d",
                    strtotime($request->invoicedate)
                );
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->name = $request->accountname;
                $narrartion->tax_rate =
                    $request->sgsttotal +
                    $request->cgsttotal +
                    $request->igsttotal;
                $narrartion->basic = $request->basictotalamount;
                $narrartion->tax_amount =
                    $request->sgsttotal +
                    $request->cgsttotal +
                    $request->igsttotal;
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
                // ------------------------------------------------------tbl_ledgerCd-------------------------------------------------//

                tbl_ledger::where("invoiceno", "=", $getinvoiceno)->where("invoicetype", "=",'purchase')->delete();



                $getl = ledgers::where('ledger_code', '=', $request->accountcode)->first();
                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date(
                    "Y-m-d",
                    strtotime($request->invoicedate)
                );
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "PURCHASE ACCOUNT";
                $narrartion->dr_amount = 0;
                $narrartion->cr_amount = $request->grandtotal;
                $narrartion->account_code = $request->accountcode;
                $narrartion->account_name = $request->accountname;
                $narrartion->group_code =  $getl->group_code;
                $narrartion->dr_cr = "Cr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = $request->transportname;
                $narrartion->gr_rr = $getl->ledger_code;
                $narrartion->remarks = "grandtotal";
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'purchase';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();

                // ------------------------------------------------------tbl_ledgerCd-------------------------------------------------//
                // ------------------------------------------------------tbl_ledgerDd-------------------------------------------------//
                $narrartion = new tbl_ledger();
                $narrartion->entry_date = date(
                    "Y-m-d",
                    strtotime($request->invoicedate)
                );
                $narrartion->narration = "Bill No. " . $request->invoiceno;
                $narrartion->type = "PURCHASE ACCOUNT";
                $narrartion->dr_amount = $request->basictotalamount;
                $narrartion->cr_amount = 0;
                $narrartion->account_code = "GPR002";
                $narrartion->account_name = "Purchase Account";
                $narrartion->group_code = "GPR002";
                $narrartion->dr_cr = "Dr";
                $narrartion->intro_code = "";
                $narrartion->token_no = "";
                $narrartion->transport = $request->transportname;
                $narrartion->gr_rr = "P0011";
                $narrartion->remarks = "basictotalamount";
                $narrartion->invoiceno = $request->invoiceno;
                $narrartion->status = $request->status;
                $narrartion->invoicetype = 'purchase';
                $narrartion->updatedby = Session::get('adminloginid');
                $narrartion->updatedbytype =Session::get('logintype');
                $narrartion->session =Session::get('sessionof');
                $narrartion->save();

                // ------------------------------------------------------tbl_ledgerDd-------------------------------------------------//
                // ------------------------------------------------------tbl_ledgeruploadingloading-------------------------------------------------//


                // ------------------------------------------------------tbl_ledgerfrieght-------------------------------------------------//
                // ------------------------------------------------------tbl_ledgersgst-------------------------------------------------//
                if ($request->sgsttotal > 0) {
                    $narrartion = new tbl_ledger();
                    $narrartion->entry_date = date(
                        "Y-m-d",
                        strtotime($request->invoicedate)
                    );
                    $narrartion->narration = "Bill No. " . $request->invoiceno;
                    $narrartion->type = "PURCHASE ACCOUNT";
                    $narrartion->dr_amount = $request->sgsttotal;
                    $narrartion->cr_amount = 0;
                    $narrartion->account_code = "GRT001";
                    $narrartion->account_name = "Purchase SGST";
                    $narrartion->group_code = "GRT001";
                    $narrartion->dr_cr = "Dr";
                    $narrartion->intro_code = "";
                    $narrartion->token_no = "";
                    $narrartion->transport = $request->transportname;
                    $narrartion->gr_rr = "P0012";
                    $narrartion->remarks = "sgsttotal";
                    $narrartion->invoiceno = $request->invoiceno;
                    $narrartion->status = $request->status;
                    $narrartion->invoicetype = 'purchase';
                    $narrartion->updatedby = Session::get('adminloginid');
                    $narrartion->updatedbytype =Session::get('logintype');
                    $narrartion->session =Session::get('sessionof');
                    $narrartion->save();
                }

                // ------------------------------------------------------tbl_ledgersgst-------------------------------------------------//
                // ------------------------------------------------------tbl_ledgercgst-------------------------------------------------//

                if ($request->cgsttotal > 0) {
                    $narrartion = new tbl_ledger();
                    $narrartion->entry_date = date(
                        "Y-m-d",
                        strtotime($request->invoicedate)
                    );
                    $narrartion->narration = "Bill No. " . $request->invoiceno;
                    $narrartion->type = "PURCHASE ACCOUNT";
                    $narrartion->dr_amount = $request->cgsttotal;
                    $narrartion->cr_amount = 0;
                    $narrartion->account_code = "GRT001";
                    $narrartion->account_name = "Purchase CGST";
                    $narrartion->group_code = "GRT001";
                    $narrartion->dr_cr = "Dr";
                    $narrartion->intro_code = "";
                    $narrartion->token_no = "";
                    $narrartion->transport = $request->transportname;
                    $narrartion->gr_rr = "P0013";
                    $narrartion->remarks = "cgsttotal";
                    $narrartion->invoiceno = $request->invoiceno;
                    $narrartion->status = $request->status;
                    $narrartion->invoicetype = 'purchase';
                    $narrartion->updatedby = Session::get('adminloginid');
                    $narrartion->updatedbytype =Session::get('logintype');
                    $narrartion->session =Session::get('sessionof');
                    $narrartion->save();
                }
                // ------------------------------------------------------tbl_ledgersgst-------------------------------------------------//
                // ------------------------------------------------------tbl_ledgercgst-------------------------------------------------//

                if ($request->igsttotal > 0) {
                    $narrartion = new tbl_ledger();
                    $narrartion->entry_date = date(
                        "Y-m-d",
                        strtotime($request->invoicedate)
                    );
                    $narrartion->narration = "Bill No. " . $request->invoiceno;
                    $narrartion->type = "PURCHASE ACCOUNT";
                    $narrartion->dr_amount = $request->igsttotal;
                    $narrartion->cr_amount = 0;
                    $narrartion->account_code = "GRT001";
                    $narrartion->account_name = "Purchase IGST";
                    $narrartion->group_code = "GRT001";
                    $narrartion->dr_cr = "Dr";
                    $narrartion->intro_code = "";
                    $narrartion->token_no = "";
                    $narrartion->transport = $request->transportname;
                    $narrartion->gr_rr = "P0014";
                    $narrartion->remarks = "igsttotal";
                    $narrartion->invoiceno = $request->invoiceno;
                    $narrartion->status = $request->status;
                    $narrartion->invoicetype = 'purchase';
                    $narrartion->updatedby = Session::get('adminloginid');
                    $narrartion->updatedbytype =Session::get('logintype');
                    $narrartion->session =Session::get('sessionof');
                    $narrartion->save();
                }


                if ($request->distotal > 0) {



                    $narrartion = new tbl_ledger();
                    $narrartion->entry_date = date(
                        "Y-m-d",
                        strtotime($request->invoicedate)
                    );
                    $narrartion->narration = "Bill No. " . $request->invoiceno;
                    $narrartion->type = "PURCHASE ACCOUNT DISCOUNT";
                    $narrartion->dr_amount =0;
                    $narrartion->cr_amount = $request->distotal;
                    $narrartion->account_code = "GRD003";
                    $narrartion->account_name = "DISCOUNT";
                    $narrartion->group_code = "GRD003";
                    $narrartion->dr_cr = "Cr";
                    $narrartion->intro_code = "";
                    $narrartion->token_no = "";
                    $narrartion->transport = $request->transportname;
                    $narrartion->gr_rr = "D0006";
                    $narrartion->remarks = "discount";
                    $narrartion->invoiceno = $request->invoiceno;
                    $narrartion->status = $request->status;
                    $narrartion->invoicetype = 'purchase';
                    $narrartion->updatedby = Session::get('adminloginid');
                    $narrartion->updatedbytype =Session::get('logintype');
                    $narrartion->session =Session::get('sessionof');
                    $narrartion->save();



                    $narrartion = new tbl_ledger();
                    $narrartion->entry_date = date(
                        "Y-m-d",
                        strtotime($request->invoicedate)
                    );
                    $narrartion->narration = "Bill No. " . $request->invoiceno;
                    $narrartion->type = "PURCHASE ACCOUNT DISCOUNT";
                    $narrartion->dr_amount = $request->distotal;
                    $narrartion->cr_amount = 0;
                    $narrartion->account_code = $request->accountcode;
                    $narrartion->account_name = $request->accountname;
                    $narrartion->group_code = DB::table('ledgers')->where('ledger_code','=',$request->accountcode)->value('group_code');
                    $narrartion->dr_cr = "Dr";
                    $narrartion->intro_code = "";
                    $narrartion->token_no = "";
                    $narrartion->transport = $request->transportname;
                    $narrartion->gr_rr = $request->accountcode;
                    $narrartion->remarks = "discount";
                    $narrartion->invoiceno = $request->invoiceno;
                    $narrartion->status = $request->status;
                    $narrartion->invoicetype = 'purchase';
                    $narrartion->updatedby = Session::get('adminloginid');
                    $narrartion->updatedbytype =Session::get('logintype');
                    $narrartion->session =Session::get('sessionof');
                    $narrartion->save();


                }

                DB::commit(); // All database operations are successful, commit the transaction
            } catch (Exception $e) {
                DB::rollBack(); // Something went wrong, roll back the transaction
                // Log::error('Transaction failed: ' . $e->getMessage());
            }












































         } else {
            if ($cont > 0) {

                DB::beginTransaction();

                try {
                    // ------------------------------------------------------purchasemaster-------------------------------------------------//
                    // ------------------------------------------------------purchasemaster-------------------------------------------------//
                    // ------------------------------------------------------purchasemaster-------------------------------------------------//

                    $upload = new purchasemaster();
                    $upload->invoicetype = $request->invoicetype;
                    $upload->grno = $request->grno;
                    $upload->grdate = date("Y-m-d", strtotime($request->grdate));
                    $upload->invoiceno = $request->invoiceno;
                    $upload->invoicedate = date(
                        "Y-m-d",
                        strtotime($request->invoicedate)
                    );
                    $upload->accountcode = $request->accountcode;
                    $upload->accountname = $request->accountname;
                    $upload->gstnoforref = $request->gstnoforref;
                    $upload->accountaddress = $request->accountaddress;
                    $upload->accountaddresss = $request->accountaddresss;
                    $upload->transportname = $request->transportname;
                    $upload->transportvehicleno = $request->transportvehicleno;
                    $upload->transportgrno = $request->transportgrno;
                    $upload->memo = $request->memo;
                    $upload->remarks = $request->remarks;
                    $upload->totalamount = $request->totalamount;
                    $upload->uploadingloadingname = $request->uploadingloadingname;
                    $upload->uploadingloading = $request->uploadingloading;
                    $upload->cartname = $request->frieghtname;
                    $upload->cart = $request->frieght;
                    $upload->distotal = $request->distotal;
                    $upload->grandtotal = $request->grandtotal;
                    $upload->basictotalamount = $request->basictotalamount;
                    $upload->sgsttotal = $request->sgsttotal;
                    $upload->igsttotal = $request->igsttotal;
                    $upload->cgsttotal = $request->cgsttotal;
                    $upload->status = $request->status;
                    $upload->updatedby = Session::get('adminloginid');
                    $upload->updatedbytype =Session::get('logintype');
                    $upload->session =Session::get('sessionof');
                    $upload->save();

                    // ------------------------------------------------------item_purchase-------------------------------------------------//
                    // ------------------------------------------------------item_purchase-------------------------------------------------//
                    // ------------------------------------------------------item_purchase-------------------------------------------------//

                    for ($x = 0; $x < $cont; $x++) {


                        $getquantity=items::where('itemcode','=',$request->itemcode[$x])->value('unitquantity');

                        if($request->baltype[$x]=='box'){
                            $quantity=$request->quantity[$x];
                            $pquantity=$request->quantity[$x]*$getquantity;
                        }else{
                            $quantity=floor($request->quantity[$x]/$getquantity);
                            $pquantity=$request->quantity[$x];
                        }


                        $ins = new item_purchase();
                        $ins->invoiceno = $request->invoiceno;
                        $ins->ledger_code = "P0011";
                        $ins->group_code = "GPR002";
                        $ins->bill_date = date(
                            "Y-m-d",
                            strtotime($request->invoicedate)
                        );
                        $ins->party_code = $request->accountcode;
                        $ins->party_name = $request->accountname;
                        $ins->getunit = $request->getunit[$x];
                        $ins->itemcode = $request->itemcode[$x];
                        $ins->itemname = $request->itemname[$x];
                        $ins->margin = $request->margin[$x];
                        $ins->baltype = $request->baltype[$x];
                        $ins->balance = $request->balance[$x];
                        $ins->sbalance = $request->sbalance[$x];
                        $ins->hsn = $request->hsn[$x];
                        $ins->quantity = $quantity;
                        $ins->pquantity = $pquantity;
                        $ins->unit = $request->unit[$x];
                        $ins->mrp = $request->mrp[$x];
                        $ins->salerate = $request->salerate[$x];
                        $ins->purchaserate = $request->purchaserate[$x];
                        $ins->discount = $request->discount[$x];
                        $ins->discountamt = $request->discountamt[$x];
                        $ins->netamount = $request->netamount[$x];
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

                    // ------------------------------------------------------purchase_book-------------------------------------------------//
                    // ------------------------------------------------------purchase_book-------------------------------------------------//
                    // ------------------------------------------------------purchase_book-------------------------------------------------//
                    $narrartion = new purchase_book();
                    $narrartion->date = date(
                        "Y-m-d",
                        strtotime($request->invoicedate)
                    );
                    $narrartion->narration = "Bill No. " . $request->invoiceno;
                    $narrartion->name = $request->accountname;
                    $narrartion->tax_rate =
                        $request->sgsttotal +
                        $request->cgsttotal +
                        $request->igsttotal;
                    $narrartion->basic = $request->basictotalamount;
                    $narrartion->tax_amount =
                        $request->sgsttotal +
                        $request->cgsttotal +
                        $request->igsttotal;
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
                    // ------------------------------------------------------tbl_ledgerCd-------------------------------------------------//
                    $getl = ledgers::where('ledger_code', '=', $request->accountcode)->first();
                    $narrartion = new tbl_ledger();
                    $narrartion->entry_date = date(
                        "Y-m-d",
                        strtotime($request->invoicedate)
                    );
                    $narrartion->narration = "Bill No. " . $request->invoiceno;
                    $narrartion->type = "PURCHASE ACCOUNT";
                    $narrartion->dr_amount = 0;
                    $narrartion->cr_amount = $request->grandtotal;
                    $narrartion->account_code = $request->accountcode;
                    $narrartion->account_name = $request->accountname;
                    $narrartion->group_code =  $getl->group_code;
                    $narrartion->dr_cr = "Cr";
                    $narrartion->intro_code = "";
                    $narrartion->token_no = "";
                    $narrartion->transport = $request->transportname;
                    $narrartion->gr_rr = $getl->ledger_code;
                    $narrartion->remarks = "grandtotal";
                    $narrartion->invoiceno = $request->invoiceno;
                    $narrartion->status = $request->status;
                    $narrartion->invoicetype = 'purchase';
                    $narrartion->updatedby = Session::get('adminloginid');
                    $narrartion->updatedbytype =Session::get('logintype');
                    $narrartion->session =Session::get('sessionof');
                    $narrartion->save();

                    // ------------------------------------------------------tbl_ledgerCd-------------------------------------------------//
                    // ------------------------------------------------------tbl_ledgerDd-------------------------------------------------//
                    $narrartion = new tbl_ledger();
                    $narrartion->entry_date = date(
                        "Y-m-d",
                        strtotime($request->invoicedate)
                    );
                    $narrartion->narration = "Bill No. " . $request->invoiceno;
                    $narrartion->type = "PURCHASE ACCOUNT";
                    $narrartion->dr_amount = $request->basictotalamount;
                    $narrartion->cr_amount = 0;
                    $narrartion->account_code = "GPR002";
                    $narrartion->account_name = "Purchase Account";
                    $narrartion->group_code = "GPR002";
                    $narrartion->dr_cr = "Dr";
                    $narrartion->intro_code = "";
                    $narrartion->token_no = "";
                    $narrartion->transport = $request->transportname;
                    $narrartion->gr_rr = "P0011";
                    $narrartion->remarks = "basictotalamount";
                    $narrartion->invoiceno = $request->invoiceno;
                    $narrartion->status = $request->status;
                    $narrartion->invoicetype = 'purchase';
                    $narrartion->updatedby = Session::get('adminloginid');
                    $narrartion->updatedbytype =Session::get('logintype');
                    $narrartion->session =Session::get('sessionof');
                    $narrartion->save();

                    // ------------------------------------------------------tbl_ledgerDd-------------------------------------------------//
                    // ------------------------------------------------------tbl_ledgeruploadingloading-------------------------------------------------//


                    // ------------------------------------------------------tbl_ledgerfrieght-------------------------------------------------//
                    // ------------------------------------------------------tbl_ledgersgst-------------------------------------------------//
                    if ($request->sgsttotal > 0) {
                        $narrartion = new tbl_ledger();
                        $narrartion->entry_date = date(
                            "Y-m-d",
                            strtotime($request->invoicedate)
                        );
                        $narrartion->narration = "Bill No. " . $request->invoiceno;
                        $narrartion->type = "PURCHASE ACCOUNT";
                        $narrartion->dr_amount = $request->sgsttotal;
                        $narrartion->cr_amount = 0;
                        $narrartion->account_code = "GRT001";
                        $narrartion->account_name = "Purchase SGST";
                        $narrartion->group_code = "GRT001";
                        $narrartion->dr_cr = "Dr";
                        $narrartion->intro_code = "";
                        $narrartion->token_no = "";
                        $narrartion->transport = $request->transportname;
                        $narrartion->gr_rr = "P0012";
                        $narrartion->remarks = "sgsttotal";
                        $narrartion->invoiceno = $request->invoiceno;
                        $narrartion->status = $request->status;
                        $narrartion->invoicetype = 'purchase';
                        $narrartion->updatedby = Session::get('adminloginid');
                        $narrartion->updatedbytype =Session::get('logintype');
                        $narrartion->session =Session::get('sessionof');
                        $narrartion->save();
                    }

                    // ------------------------------------------------------tbl_ledgersgst-------------------------------------------------//
                    // ------------------------------------------------------tbl_ledgercgst-------------------------------------------------//

                    if ($request->cgsttotal > 0) {
                        $narrartion = new tbl_ledger();
                        $narrartion->entry_date = date(
                            "Y-m-d",
                            strtotime($request->invoicedate)
                        );
                        $narrartion->narration = "Bill No. " . $request->invoiceno;
                        $narrartion->type = "PURCHASE ACCOUNT";
                        $narrartion->dr_amount = $request->cgsttotal;
                        $narrartion->cr_amount = 0;
                        $narrartion->account_code = "GRT001";
                        $narrartion->account_name = "Purchase CGST";
                        $narrartion->group_code = "GRT001";
                        $narrartion->dr_cr = "Dr";
                        $narrartion->intro_code = "";
                        $narrartion->token_no = "";
                        $narrartion->transport = $request->transportname;
                        $narrartion->gr_rr = "P0013";
                        $narrartion->remarks = "cgsttotal";
                        $narrartion->invoiceno = $request->invoiceno;
                        $narrartion->status = $request->status;
                        $narrartion->invoicetype = 'purchase';
                        $narrartion->updatedby = Session::get('adminloginid');
                        $narrartion->updatedbytype =Session::get('logintype');
                        $narrartion->session =Session::get('sessionof');
                        $narrartion->save();
                    }
                    // ------------------------------------------------------tbl_ledgersgst-------------------------------------------------//
                    // ------------------------------------------------------tbl_ledgercgst-------------------------------------------------//

                    if ($request->igsttotal > 0) {
                        $narrartion = new tbl_ledger();
                        $narrartion->entry_date = date(
                            "Y-m-d",
                            strtotime($request->invoicedate)
                        );
                        $narrartion->narration = "Bill No. " . $request->invoiceno;
                        $narrartion->type = "PURCHASE ACCOUNT";
                        $narrartion->dr_amount = $request->igsttotal;
                        $narrartion->cr_amount = 0;
                        $narrartion->account_code = "GRT001";
                        $narrartion->account_name = "Purchase IGST";
                        $narrartion->group_code = "GRT001";
                        $narrartion->dr_cr = "Dr";
                        $narrartion->intro_code = "";
                        $narrartion->token_no = "";
                        $narrartion->transport = $request->transportname;
                        $narrartion->gr_rr = "P0014";
                        $narrartion->remarks = "igsttotal";
                        $narrartion->invoiceno = $request->invoiceno;
                        $narrartion->status = $request->status;
                        $narrartion->invoicetype = 'purchase';
                        $narrartion->updatedby = Session::get('adminloginid');
                        $narrartion->updatedbytype =Session::get('logintype');
                        $narrartion->session =Session::get('sessionof');
                        $narrartion->save();
                    }


                    if ($request->distotal > 0) {



                        $narrartion = new tbl_ledger();
                        $narrartion->entry_date = date(
                            "Y-m-d",
                            strtotime($request->invoicedate)
                        );
                        $narrartion->narration = "Bill No. " . $request->invoiceno;
                        $narrartion->type = "PURCHASE ACCOUNT DISCOUNT";
                        $narrartion->dr_amount =0;
                        $narrartion->cr_amount = $request->distotal;
                        $narrartion->account_code = "GRD003";
                        $narrartion->account_name = "DISCOUNT";
                        $narrartion->group_code = "GRD003";
                        $narrartion->dr_cr = "Cr";
                        $narrartion->intro_code = "";
                        $narrartion->token_no = "";
                        $narrartion->transport = $request->transportname;
                        $narrartion->gr_rr = "D0006";
                        $narrartion->remarks = "discount";
                        $narrartion->invoiceno = $request->invoiceno;
                        $narrartion->status = $request->status;
                        $narrartion->invoicetype = 'purchase';
                        $narrartion->updatedby = Session::get('adminloginid');
                        $narrartion->updatedbytype =Session::get('logintype');
                        $narrartion->session =Session::get('sessionof');
                        $narrartion->save();



                        $narrartion = new tbl_ledger();
                        $narrartion->entry_date = date(
                            "Y-m-d",
                            strtotime($request->invoicedate)
                        );
                        $narrartion->narration = "Bill No. " . $request->invoiceno;
                        $narrartion->type = "PURCHASE ACCOUNT DISCOUNT";
                        $narrartion->dr_amount = $request->distotal;
                        $narrartion->cr_amount = 0;
                        $narrartion->account_code = $request->accountcode;
                        $narrartion->account_name = $request->accountname;
                        $narrartion->group_code = DB::table('ledgers')->where('ledger_code','=',$request->accountcode)->value('group_code');
                        $narrartion->dr_cr = "Dr";
                        $narrartion->intro_code = "";
                        $narrartion->token_no = "";
                        $narrartion->transport = $request->transportname;
                        $narrartion->gr_rr = $request->accountcode;
                        $narrartion->remarks = "discount";
                        $narrartion->invoiceno = $request->invoiceno;
                        $narrartion->status = $request->status;
                        $narrartion->invoicetype = 'purchase';
                        $narrartion->updatedby = Session::get('adminloginid');
                        $narrartion->updatedbytype =Session::get('logintype');
                        $narrartion->session =Session::get('sessionof');
                        $narrartion->save();


                    }

                    DB::commit(); // All database operations are successful, commit the transaction
                } catch (Exception $e) {
                    DB::rollBack(); // Something went wrong, roll back the transaction
                    // Log::error('Transaction failed: ' . $e->getMessage());
                }
            }
        }
    }





    // if ($request->uploadingloading > 0) {
    //     $narrartion = new tbl_ledger();
    //     $narrartion->entry_date = date(
    //         "Y-m-d",
    //         strtotime($request->invoicedate)
    //     );
    //     $narrartion->narration = "Bill No. " . $request->invoiceno;
    //     $narrartion->type = "PURCHASE ACCOUNT";
    //     $narrartion->dr_amount = $request->uploadingloading;
    //     $narrartion->cr_amount = 0;
    //     $narrartion->account_code = "U0001";
    //     $narrartion->account_name = "Uploading/Loading";
    //     $narrartion->group_code = "D0001";
    //     $narrartion->dr_cr = "Dr";
    //     $narrartion->intro_code = "";
    //     $narrartion->token_no = "";
    //     $narrartion->transport = $request->transportname;
    //     $narrartion->gr_rr = "U0001";
    //     $narrartion->remarks = "";
    //     $narrartion->invoiceno = $request->invoiceno;
    //     $narrartion->status = $request->status;
    //     $narrartion->save();
    // }

    // ------------------------------------------------------tbl_ledgeruploadingloading-------------------------------------------------//
    // ------------------------------------------------------tbl_ledgerfrieght-------------------------------------------------//

    // if ($request->frieght > 0) {
    //     $narrartion = new tbl_ledger();
    //     $narrartion->entry_date = date(
    //         "Y-m-d",
    //         strtotime($request->invoicedate)
    //     );
    //     $narrartion->narration = "Bill No. " . $request->invoiceno;
    //     $narrartion->type = "PURCHASE ACCOUNT";
    //     $narrartion->dr_amount = $request->frieght;
    //     $narrartion->cr_amount = 0;
    //     $narrartion->account_code = "F0001";
    //     $narrartion->account_name = "Frieght";
    //     $narrartion->group_code = "D0001";
    //     $narrartion->dr_cr = "Dr";
    //     $narrartion->intro_code = "";
    //     $narrartion->token_no = "";
    //     $narrartion->transport = $request->transportname;
    //     $narrartion->gr_rr = "F0001";
    //     $narrartion->remarks = "";
    //     $narrartion->invoiceno = $request->invoiceno;
    //     $narrartion->status = $request->status;
    //     $narrartion->save();
    // }


    public function deletepurchase(Request $request)
    {
        $getinvoiceno = purchasemaster::find($request->delid)->invoiceno;
        purchasemaster::where("invoiceno", "=", $getinvoiceno)->delete();
        item_purchase::where("invoiceno", "=", $getinvoiceno)->delete();
        purchase_book::where("invoiceno", "=", $getinvoiceno)->delete();
        tbl_ledger::where("invoiceno", "=", $getinvoiceno)->delete();
    }
}
