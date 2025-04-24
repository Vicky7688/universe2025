<?php

namespace App\Http\Controllers;
use App\Models\items;
use App\Models\discounts;
use App\Models\retails;
use App\Models\ret_discount;
use Illuminate\Http\Request;
use Session;
use DB;
class discountcontroller extends Controller
{

    
    public function discountlist(){
        $discounts=discounts::all();
        $data=compact('discounts');
        return view('main.discountlist')->with($data);
    }


    public function adddiscount(Request $request){
        $formurl=url('adddiscount');
        $loaditems=items::all();
        $data=compact('formurl','loaditems');
        if (!empty($request->all())) {

            $request->validate(
                        [
                    'itemid' => 'required',
                    'qtfrom' => 'required',
                    'qtto' => 'required',
                    'price' => 'required',
                    'status' => 'required',
                        ]
                    );

                    $discountsin = new discounts(); 
                    $discountsin->itemid = $request->itemid;
                    $discountsin->qtfrom = $request->qtfrom;
                    $discountsin->discounttype = $request->discounttype;
                    $discountsin->qtto = $request->qtto;
                    $discountsin->price = $request->price;
                    $discountsin->status = $request->status;
                    $discountsin->updated_by = Session::get('adminloginid');
                    $discountsin->save();
            return redirect('adddiscount')->with('success', 'Data Inserted Successfully');

        } else {
            return view('main.adddiscount')->with($data);
        }
    }



    public function editdiscount($id,Request $request){
            $formurl=url('editdiscount/'.$id);
            $discountsid=discounts::find($id);
            $loaditems=items::all();
             $data=compact('discountsid','formurl','loaditems');
       if (!empty($request->all())) {
                $request->validate(
                         [
                            'itemid' => 'required',
                            'qtfrom' => 'required',
                            'qtto' => 'required',
                            'price' => 'required',
                            'status' => 'required',
                         ]
                     );

                     $discountsup =discounts::find($id);
                     $discountsup->itemid = $request->itemid;
                     $discountsup->qtfrom = $request->qtfrom;
                     $discountsup->discounttype = $request->discounttype;
                     $discountsup->qtto = $request->qtto;
                     $discountsup->price = $request->price;
                     $discountsup->status = $request->status;
                     $discountsup->updated_by = Session::get('adminloginid');
                     $discountsup->save();
                return redirect('adddiscount')->with('success', 'Data Inserted Successfully');

                 } else {
                     return view('main.adddiscount')->with($data);
                 }


    }

    public function deletediscount($id,Request $request){
   $discountsid = discounts::find($id);
        if (is_null($discountsid)) {
            return back()->with('fail', 'No Record Found');
        } else {
            $discountsid->delete();
            return back()->with('success', 'Delete Successfully');
        }
    }


    public function table(Request $request)
    {
        // $data = discounts::all();

        $data = DB::table("discounts")
        ->join("items", "discounts.itemid", "=", "items.id") 
        ->select(
            "items.name",
            "items.itemcode",
            "discounts.*",
        )->get();

        return response()->json($data);

    }


    public function rediscount(Request $request)
    {
        return view('main.rediscount');

    }

    public function submitrediscount(Request $request)
    {
        
            if(!empty($request->itemcode)){
                foreach($request->itemcode as $key=>$value){
                   $exist=ret_discount::where('itemcode','=',$request->itemcode[$key])->where('retailercode','=',$request->retailercode)->first();
                 if($exist){
                    $update=ret_discount::find($exist->id); 
                    $update->price=$request->discounted[$key];
                    $update->save();
                 }else{ 
                    $update=new ret_discount();
                    $update->itemcode=$request->itemcode[$key];
                    $update->retailercode=$request->retailercode;
                    $update->price=$request->discounted[$key];
                    $update->save();
                 } 
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Updated!!!'
                ]);
            } else{
                return response()->json([
                    'status' => false,
                    'message' => 'Select Checkbox!!!'
                ]);
            }
    }

    public function itemsdata(Request $request)
    {
       
        







 $itemcode = $request->itemcode;
$retailercode = $request->retailercode;
if(!empty($retailercode)){
   $retailerexist= retails::where('retailercode','=',$retailercode)->first();
   if($retailerexist){
try {
    $query = DB::table('items')
        ->join('brands', 'items.brand', '=', 'brands.id')
        ->join('categorys', 'items.category', '=', 'categorys.id')
        ->join('subcategorys', 'items.subcategory', '=', 'subcategorys.id')
        ->join('subchildcategorys', 'items.subchildcategory', '=', 'subchildcategorys.id')
        ->join('item_rates', 'items.id', '=', 'item_rates.itemid')
        ->leftJoin('ret_discount', function ($join) use ($retailercode) {
            $join->on('items.itemcode', '=', 'ret_discount.itemcode')
                 ->where('ret_discount.retailercode', '=', $retailercode);
        })
        ->select(
            'items.id',
            'items.name as itemname',
            'items.itemcode as itemcode',
            DB::raw('AVG(item_rates.mrp) as mrp'),
            DB::raw('AVG(item_rates.salerate) as salerate'),
            'brands.name as brand_name',
            'categorys.name as category_name',
            'subcategorys.name as subcategory_name',
            'subchildcategorys.name as child_name',
            DB::raw('IFNULL(ret_discount.price, 0) as discount_price')
        )
        ->groupBy(
            'items.id',
            'items.name',
            'items.itemcode',
            'brands.name',
            'categorys.name',
            'subcategorys.name',
            'subchildcategorys.name',
            'ret_discount.price'
        );

    if (!empty($itemcode)) {
        $query->where('items.itemcode', $itemcode);
    }

    $data = $query->get();

    return response()->json([
        'status' => true,
        'message' => 'Data retrieved successfully',
        'data' => $data
    ]);

} catch (\Exception $e) {
    return response()->json([
        'status' => false,
        'message' => 'Error retrieving data: ' . $e->getMessage()
    ]);
}
}else{
    return response()->json([
        'status' => false,
        'message' => 'Retailer Not Found...!!!'
    ]);
}
}else{
    return response()->json([
        'status' => false,
        'message' => 'Retailer Field  Cannot be Empty...!!!'
    ]);
}

    }

}
