<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\purchasemaster;
use App\Models\item_sale;
use App\Models\item_purchase;
use App\Models\items;
use Toastr;
class adjustcontroller extends Controller
{
    public function adjust(Request $request){

        if(!empty($request->all())){

            $itemss=items::where('itemcode', '=', $request->item)->first();
                if($request->unittype=="single"){ 
                    $pquantity=$request->quantity;
                    $quantity=floor($request->quantity/$itemss->unitquantity);
                }else{
                    $pquantity=$request->quantity*$itemss->unitquantity;
                    $quantity=$request->quantity;
                }

            if($request->adjustmenttype=='sale'){ 
                $insert=new item_sale();
                $insert->itemcode=$request->item;
                $insert->baltype=$request->unittype;
                $insert->pquantity=$pquantity;
                $insert->quantity=$quantity;
                $insert->status='adjustment';
                $insert->updatedby = Session::get('adminloginid');
                $insert->updatedbytype =Session::get('logintype');
                $insert->session =Session::get('sessionof');
                $insert->save();
            }else{
                $insert=new item_purchase();
                $insert->itemcode=$request->item;
                $insert->baltype=$request->unittype;
                $insert->pquantity=$pquantity;
                $insert->quantity=$quantity;
                $insert->status='adjustment';
                $insert->updatedby = Session::get('adminloginid');
                $insert->updatedbytype =Session::get('logintype');
                $insert->session =Session::get('sessionof');
                $insert->save();
            }
            Toastr::success('Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return back();
        }else{
            $pagetitle="Add Adjustment";
            $pageto=url('adjust');
            $formurl=url('adjust');
            $items = items::where('status', '=', 'active')->get();
            return view("adjust",compact('pagetitle','pageto','formurl','items'));
        }

    }
}
