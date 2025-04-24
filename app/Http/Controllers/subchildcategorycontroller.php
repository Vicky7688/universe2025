<?php

namespace App\Http\Controllers;

use App\Models\categorys;
use App\Models\subchildcategorys;
use App\Models\subcategorys;
use App\Models\brands;
use Illuminate\Http\Request;
use Session;
use Toastr;
class subchildcategorycontroller extends Controller
{
    public function subchildcategorylist()
    {
        $subchildcategorys = subchildcategorys::orderby("sort")->get();
        $data = compact("subchildcategorys");
        return view("main.subchildcategorylist")->with($data);
    }

    public function addsubchildcategory(Request $request)
    {
        
        $pagetitle="Sub Child Category Master"; 
        $pageto=url('addsubchildcategory');
        $formurl = url("addsubchildcategory"); 
        $loadbrands = brands::orderby("sort")->get();
        $categorylist = "";
        $subcategorylist = "";


        $subchildcategorys = subchildcategorys::join("categorys","subchildcategorys.category","=","categorys.id")
            ->join("brands", "subchildcategorys.brand", "=", "brands.id")
            ->join("subcategorys", "subchildcategorys.subcategory", "=", "subcategorys.id")
            ->select(
                "subchildcategorys.*",
                "subcategorys.name as subcategory_name",
                "categorys.name as category_name",
                "brands.name as brand_name"
            )
            ->get();


        $data = compact(
            "formurl",
            "loadbrands",
            "categorylist",
            "subcategorylist",
            "pagetitle",
            "pageto",
            "subchildcategorys"
        );
        if (!empty($request->all())) {
            $request->validate([
                "name" => "required",
                "brand" => "required",
                "category" => "required",
                "subcategory" => "required",
                "status" => "required",
            ]);

            $subchildcategorysin = new subchildcategorys();
            $subchildcategorysin->name = $request->name;
            $subchildcategorysin->brand = $request->brand;
            $subchildcategorysin->category = $request->category;
            $subchildcategorysin->subcategory = $request->subcategory;
            $subchildcategorysin->status = $request->status;
            $subchildcategorysin->updatedby = Session::get('adminloginid');
            $subchildcategorysin->updatedbytype = Session::get('logintype');
            $subchildcategorysin->session = Session::get('sessionof');
            $subchildcategorysin->save();
            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect("addsubchildcategory")->with(
                "success",
                "Data Inserted Successfully"
            );
        } else {
            return view("addsubchildcategory")->with($data);
        }
    }

    public function editsubchildcategory($id, Request $request)
    {
        $pagetitle="Sub Child Category Master"; 
        $pageto=url('addsubchildcategory');
        $pagetitle="Sub Child Category Master"; 
        $pageto=url('addsubchildcategory');
        $formurl = url("editsubchildcategory/" . $id);
        $subchildcategorysid = subchildcategorys::find($id);
        $loadbrands = brands::orderby("sort")->get();
        $categorylist = categorys::where(
            "brand",
            $subchildcategorysid->brand
        )->get();
        $subcategorylist = subcategorys::where(
            "brand",
            $subchildcategorysid->brand
        )
            ->where("category", $subchildcategorysid->category)
            ->get();
            $subchildcategorys = subchildcategorys::join("categorys","subchildcategorys.category","=","categorys.id")
            ->join("brands", "subchildcategorys.brand", "=", "brands.id")
            ->join("subcategorys", "subchildcategorys.subcategory", "=", "subcategorys.id")
            ->select(
                "subchildcategorys.*",
                "subcategorys.name as subcategory_name",
                "categorys.name as category_name",
                "brands.name as brand_name"
            )
            ->get();
            $data = compact(
                "subchildcategorysid",
                "formurl",
                "loadbrands",
                "categorylist",
                "subcategorylist",
                "pagetitle",
                "pageto",
                "subchildcategorys"
            );
        if (!empty($request->all())) {
            $request->validate([
                "name" => "required",
                "brand" => "required",
                "category" => "required",
                "subcategory" => "required",
                "status" => "required",
            ]);

            $subchildcategorysup = subchildcategorys::find($id);
            $subchildcategorysup->name = $request->name;
            $subchildcategorysup->brand = $request->brand;
            $subchildcategorysup->category = $request->category;
            $subchildcategorysup->subcategory = $request->subcategory;
            $subchildcategorysup->status = $request->status;
            $subchildcategorysup->updatedby = Session::get('adminloginid');
            $subchildcategorysup->updatedbytype = Session::get('logintype');
            $subchildcategorysup->session = Session::get('sessionof');
            $subchildcategorysup->save();
            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
            return redirect("addsubchildcategory")->with(
                "success",
                "Data Inserted Successfully"
            );
        } else {
            return view("addsubchildcategory")->with($data);
        }
    }

    public function deletesubchildcategory($id, Request $request)
    {
        $subchildcategorysid = subchildcategorys::find($id);
        if (is_null($subchildcategorysid)) {
            Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with("fail", "No Record Found");
        } else {
            $subchildcategorysid->delete();
            Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with("success", "Delete Successfully");
        }
    }

    public function table(Request $request)
    {
        $data = subchildcategorys::join("categorys","subchildcategorys.category","=","categorys.id")
            ->join("brands", "subchildcategorys.brand", "=", "brands.id")
            ->join("subcategorys", "subchildcategorys.subcategory", "=", "subcategorys.id")
            ->select(
                "subchildcategorys.*",
                "subcategorys.name as subcategory_name",
                "categorys.name as category_name",
                "brands.name as brand_name"
            )
            ->get();
        return response()->json($data);
    }
}
