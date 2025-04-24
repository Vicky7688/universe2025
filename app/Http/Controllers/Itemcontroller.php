<?php
namespace App\Http\Controllers;
use App\Models\items;
use App\Models\brands;
use App\Models\categorys;
use App\Models\codes;
use App\Models\taxs;
use App\Models\units;
use App\Models\subcategorys;
use App\Models\subchildcategorys;
use App\Models\item_rates;
use App\Models\retails;
use App\Models\discounts;
use Illuminate\Http\Request;
use Session;
use DB;
use Toastr;
class Itemcontroller extends Controller
{
    public function itemlist()
    {
        $items = items::orderby("sort")->get();
        $data = compact("items");
        return view("main.itemlist")->with($data);
    }

    public function additem(Request $request)
    {
        $pagetitle="Items Master";
        $pageto=url('additem');
        $formurl = url("additem");
        $brandlist = brands::orderby("sort")->get();
        $taxslist = taxs::all();
        $units = units::all();
        $retails = retails::all(); 
        $itemsall = items::join("brands", "items.brand", "=", "brands.id")
        ->join("categorys", "items.category", "=", "categorys.id")
        ->join("subcategorys", "items.subcategory", "=", "subcategorys.id")
        ->join("subchildcategorys", "items.subchildcategory", "=", "subchildcategorys.id")
        ->select(
            "items.*",
            "brands.name as brand_name",
            "categorys.name as category_name",
            "subcategorys.name as subcategory_name",
            "subchildcategorys.name as child_name",
        )
        ->get();
      
        $categorylist = "";
        $subcategorylist = "";
        $subchildcategorylist = "";
        $itemrates = "";
        $data = compact(
            "formurl",
            "brandlist",
            "categorylist",
            "subcategorylist",
            "taxslist",
            "units",
            "pagetitle",
            "pageto",
            "retails",
            "itemsall",
            "itemrates"
        );
        if (!empty($request->all())) {
           
            $request->validate([
                "itemcode" => "required",
                "hsn" => "required",
                "brand" => "required",
                "category" => "required",
                "subcategory" => "required",
                "name" => "required",
                "unit" => "required",
                "openingstock" => "required",
                "image" => "required",
            ]);

            if ($request->file("image")) {
                $nimage =
                    rand() .
                    "image." .
                    $request->file("image")->getClientOriginalExtension();
                $rounimage = $request
                    ->file("image")
                    ->storeAs("public/image", $nimage);
            } else {
                $rounimage = "";
            }
            $itemsin = new items();
            $itemsin->itemcode = $request->itemcode;
            $itemsin->hsn = $request->hsn;
            $itemsin->name = $request->name;
            $itemsin->pname = $request->pname;
            $itemsin->unit = $request->unit;
            $itemsin->openingstock = $request->openingstock;
            $itemsin->brand = $request->brand;
            $itemsin->category = $request->category;
            $itemsin->subcategory = $request->subcategory;
            $itemsin->subchildcategory = $request->subchildcategory;
            $itemsin->unitquantity = $request->unitquantity;
            $itemsin->singleopeningstock = $request->singleopeningstock;
            $itemsin->status = $request->status;
            $itemsin->reorderlable = $request->reorderlable;
            $itemsin->sgstcode = $request->sgstcode;
            $itemsin->salesgstamount = $request->salesgstamount;
            $itemsin->salecgstamount = $request->salecgstamount;
            $itemsin->igstcode = $request->igstcode;
            $itemsin->saleigstamount = $request->saleigstamount;
            $itemsin->pursgstcode = $request->pursgstcode;
            $itemsin->pursgstamount = $request->pursgstamount;
            $itemsin->purcgstamount = $request->purcgstamount;
            $itemsin->purigstcode = $request->purigstcode;
            $itemsin->purigstamount = $request->purigstamount;

            $itemsin->op_stock_pc_amount = $request->op_stock_pc_amount;
            $itemsin->openingstock_amount = $request->openingstock_amount;


            $itemsin->image = $rounimage;
            $itemsin->updatedby = Session::get('adminloginid');
            $itemsin->updatedbytype =Session::get('logintype');
            $itemsin->session =Session::get('sessionof'); 
            $itemsin->save();

            // dd($itemsin->id);
            $cont = count($request->mrp);
            if ($cont > 0) {
                for ($x = 0; $x < $cont; $x++) {
                    $itemsrateadd = new item_rates();
                    $itemsrateadd->itemid = $itemsin->id;
                    $itemsrateadd->customer =  $request->customer[$x];
                    $itemsrateadd->discount = $request->discount[$x];
                    $itemsrateadd->mrp = $request->mrp[$x];
                    $itemsrateadd->salerate = $request->salerate[$x];
                    $itemsrateadd->purchaserate = $request->purchaserate[$x];
                    $itemsrateadd->mrpsingle = $request->mrpsingle[$x];
                    $itemsrateadd->saleratesingle = $request->saleratesingle[$x];
                    $itemsrateadd->purchaseratesingle = $request->purchaseratesingle[$x];
                    $itemsrateadd->barcodenumber = $request->barcodenumber[$x];
                    $itemsrateadd->barcodeimage = $request->barcodename[$x];
                    $itemsrateadd->updatedby = Session::get('adminloginid');
                    $itemsrateadd->updatedbytype =Session::get('logintype');
                    $itemsrateadd->session =Session::get('sessionof');
                    $itemsrateadd->save();
                }
            }
            $contr = count($request->discounttype);
         
            if ($contr > 0) { 
                for ($x = 0; $x < $contr; $x++) { 
                    $discountsin = new discounts(); 
                    $discountsin->itemid = $itemsin->id;
                    $discountsin->qtfrom = $request->qtfrom[$x];
                    $discountsin->discounttype = $request->discounttype[$x];
                    $discountsin->qtto = $request->qtto[$x];
                    $discountsin->price = $request->price[$x];
                    $discountsin->status = 'active'; 
                    $discountsin->updatedby = Session::get('adminloginid');
                    $discountsin->updatedbytype =Session::get('logintype');
                    $discountsin->session =Session::get('sessionof');
                    $discountsin->save(); 
                }
            }


            $codeup = new codes();
            $codeup->name = "Product";
            $codeup->code = $request->itemcode;
            $codeup->save();
            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect("additem")->with(
                "success",
                "Data Inserted Successfully"
            );
        } else {
            return view("additem")->with($data);
        }
    }
    public function edititem($id, Request $request)
    {

        $pagetitle="Items Master";
        $pageto=url('additem');
        $formurl = url("edititem/" . $id);
        $itemsid = items::find($id);
        $retails = retails::all();
        $itemrates = item_rates::where("itemid", "=", $id)->get();
        $discountsis = discounts::where("itemid", "=", $id)->get();
        $brandlist = brands::orderby("sort")->get();
        $categorylist = categorys::where("brand", $itemsid->brand)->get();
        $subcategorylist = subcategorys::where("brand", $itemsid->brand)
            ->where("category", $itemsid->category)
            ->get();
        $subchildcategorylist = subchildcategorys::where("brand", $itemsid->brand)
            ->where("category", $itemsid->category)
            ->where("subcategory", $itemsid->subcategory)
            ->get();
            // dd($subchildcategorylist);

            $itemsall = items::join("brands", "items.brand", "=", "brands.id")
        ->join("categorys", "items.category", "=", "categorys.id")
        ->join("subcategorys", "items.subcategory", "=", "subcategorys.id")
        ->join("subchildcategorys", "items.subchildcategory", "=", "subchildcategorys.id")
        ->select(
            "items.*",
            "brands.name as brand_name",
            "categorys.name as category_name",
            "subcategorys.name as subcategory_name",
            "subchildcategorys.name as child_name",
        )
        ->get();
        $taxslist = taxs::all();
        $units = units::all();
        $data = compact(
            "itemsid",
            "formurl",
            "brandlist",
            "categorylist",
            "subcategorylist",
            "subchildcategorylist",
            "units",
            "taxslist",
            "pagetitle",
            "pageto",
            "retails",
            "itemsall",
            "discountsis",
            "itemrates"
        );
        if (!empty($request->all())) {
            $request->validate([
                "itemcode" => "required",
                "hsn" => "required",
                "name" => "required",
                "unit" => "required",
                "openingstock" => "required",
                "brand" => "required",
                "category" => "required",
                "subcategory" => "required",
            ]);

            if ($request->file("image")) {
                $nimage =
                    rand() .
                    "image." .
                    $request->file("image")->getClientOriginalExtension();
                $rounimage = $request
                    ->file("image")
                    ->storeAs("public/image", $nimage);
            } else {
                $rounimage = $itemsid->image;
            }
            $itemsup = items::find($id);
            $itemsup->itemcode = $request->itemcode;
            $itemsup->hsn = $request->hsn;
            $itemsup->name = $request->name;
            $itemsup->pname = $request->pname;
            $itemsup->unit = $request->unit;
            $itemsup->openingstock = $request->openingstock;
            $itemsup->brand = $request->brand;
            $itemsup->category = $request->category;
            $itemsup->subcategory = $request->subcategory;
            $itemsup->subchildcategory = $request->subchildcategory;
            $itemsup->unitquantity = $request->unitquantity;
            $itemsup->singleopeningstock = $request->singleopeningstock;
            $itemsup->status = $request->status;
            $itemsup->reorderlable = $request->reorderlable;
            $itemsup->sgstcode = $request->sgstcode;
            $itemsup->salesgstamount = $request->salesgstamount;
            $itemsup->salecgstamount = $request->salecgstamount;
            $itemsup->igstcode = $request->igstcode;
            $itemsup->saleigstamount = $request->saleigstamount;
            $itemsup->pursgstcode = $request->pursgstcode;
            $itemsup->pursgstamount = $request->pursgstamount;
            $itemsup->purcgstamount = $request->purcgstamount;
            $itemsup->purigstcode = $request->purigstcode;
            $itemsup->purigstamount = $request->purigstamount;
            $itemsup->image = $rounimage;

            $itemsup->op_stock_pc_amount = $request->op_stock_pc_amount;
            $itemsup->openingstock_amount = $request->openingstock_amount;

            $itemsup->updatedby = Session::get('adminloginid');
            $itemsup->updatedbytype =Session::get('logintype');
            $itemsup->session =Session::get('sessionof'); 
            $itemsup->save();


            item_rates::where("itemid", "=", $id)->delete();
            $cont = count($request->mrp);
            if ($cont > 0) {
                for ($x = 0; $x < $cont; $x++) {
                    $itemsrateadd = new item_rates();
                    $itemsrateadd->itemid = $id;
                    $itemsrateadd->customer =  $request->customer[$x];
                    $itemsrateadd->discount = $request->discount[$x];
                    $itemsrateadd->mrp = $request->mrp[$x];
                    $itemsrateadd->salerate = $request->salerate[$x];
                    $itemsrateadd->purchaserate = $request->purchaserate[$x];
                    $itemsrateadd->mrpsingle = $request->mrpsingle[$x];
                    $itemsrateadd->saleratesingle = $request->saleratesingle[$x];
                    $itemsrateadd->purchaseratesingle = $request->purchaseratesingle[$x];
                    $itemsrateadd->barcodenumber = $request->barcodenumber[$x];
                    $itemsrateadd->barcodeimage = $request->barcodename[$x];
                    $itemsrateadd->updatedby = Session::get('adminloginid');
                    $itemsrateadd->updatedbytype =Session::get('logintype');
                    $itemsrateadd->session =Session::get('sessionof'); 
                    $itemsrateadd->save();
                }
            }


            $contr = count($request->discounttype);
            discounts::where("itemid", "=", $id)->delete();
            if ($contr > 0) { 
                for ($x = 0; $x < $contr; $x++) { 
                    $discountsin = new discounts(); 
                    $discountsin->itemid = $id;
                    $discountsin->qtfrom = $request->qtfrom[$x];
                    $discountsin->discounttype = $request->discounttype[$x];
                    $discountsin->qtto = $request->qtto[$x];
                    $discountsin->price = $request->price[$x];
                    $discountsin->status = 'active'; 
                    $discountsin->updatedby = Session::get('adminloginid');
                    $discountsin->updatedbytype =Session::get('logintype');
                    $discountsin->session =Session::get('sessionof');
                    $discountsin->save(); 
                }
            }
            $codeup = new codes();
            $codeup->name = "Product";
            $codeup->code = $request->itemcode;
            $codeup->save();
            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect("additem")->with(
                "success",
                "Data Inserted Successfully"
            );
        } else {
            return view("additem")->with($data);
        }
    }
    public function deleteitem($id, Request $request)
    {
        $itemsid = items::find($id);
        if (is_null($itemsid)) {
            Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with("fail", "No Record Found");
        } else {
            $itemsid->delete();
            Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with("success", "Delete Successfully");
        }
    }
    public function fetchcategory(Request $request)
    {
        $data["states"] = categorys::where("brand", $request->idbrand)->get([
            "name",
            "id",
        ]);
        return response()->json($data);
    }
    public function fetchsubcategory(Request $request)
    {
        $data["substates"] = subcategorys::where("brand", $request->idbrand)
            ->where("category", $request->idcat)
            ->get(["name", "id"]);
        return response()->json($data);
    }
    public function fetchsubchildcategory(Request $request)
    {
        $data["subchildstates"] = subchildcategorys::where(
            "brand",$request->idbrand)
            ->where("category", $request->idbrandcategory)
            ->where("subcategory", $request->idcat)
            ->get(["name", "id"]);
        return response()->json($data);
    }
    public function getamoutgst(Request $request)
    {
        if ($request->gsttype == "igst") {
            $percentage = taxs::where("id", $request->id)->value(
                "igstpercentage"
            );
        } else {
            $percentage = taxs::where("id", $request->id)->value(
                "sgstpercentage"
            );
        }
        if ($percentage) {
            return response()->json($percentage);
        }
    }

    public function gettaxr(Request $request)
    {
        $getnames = taxs::where("code", "like", $request->id . "%")->pluck(
            "name",
            "id"
        );
        return response()->json($getnames);
    }
    public function purgettaxr(Request $request)
    {
        $getnames = taxs::where("code", "like", $request->id . "%")->pluck(
            "name",
            "id"
        );
        return response()->json($getnames);
    }
    public function getgstrates(Request $request)
    {
        $rates = taxs::where("id", "=", $request->id)->first();
        return response()->json($rates);
    }
    public function getsearchresults(Request $request)
    {
        $data = DB::table("items")
            ->join("brands", "items.brand", "=", "brands.id")
            ->join("categorys", "items.category", "=", "categorys.id")
            ->join("subcategorys", "items.subcategory", "=", "subcategorys.id")
            ->join("subchildcategorys", "items.subchildcategory", "=", "subchildcategorys.id")
            ->select(
                "items.*",
                "brands.name as brand_name",
                "categorys.name as category_name",
                "subcategorys.name as subcategory_name",
                "subchildcategorys.name as child_name",
            )
            ->where("items.name", "like", "%" . $request->id . "%")
            ->orWhere("items.itemcode", "like", "%" . $request->id . "%")
            ->paginate(10);

        return response()->json($data);
    }



    public function table(Request $request)
    {
        $data = DB::table("items")
            ->join("brands", "items.brand", "=", "brands.id")
            ->join("categorys", "items.category", "=", "categorys.id")
            ->join("subcategorys", "items.subcategory", "=", "subcategorys.id")
            ->join("subchildcategorys", "items.subchildcategory", "=", "subchildcategorys.id")
            ->select(
                "items.*",
                "brands.name as brand_name",
                "categorys.name as category_name",
                "subcategorys.name as subcategory_name",
                "subchildcategorys.name as child_name",
            )
            ->paginate(10);
        return response()->json($data);
    }


    public function barcodemaster(Request $request)
    {

        return view("main.barcode");
    }

    public function bartable(Request $request)
    {


        $data = DB::table("item_rates")
            ->join("items", "item_rates.itemid", "=", "items.id")
            ->select("item_rates.*","items.name as itemnname")->get();



    return response()->json($data);
    }

    public function changeprintname(Request $request)
    {

        $ii=item_rates::find($request->id);
        $ii->printname=$request->value;
        $ii->save();
    }
    public function changeprintprice(Request $request)
    {

        $ii=item_rates::find($request->id);
        $ii->printprice=$request->value;
        $ii->save();
    }
    public function changep(Request $request)
    {

        $data=item_rates::find($request->name);
        return response()->json($data);
    }

}
