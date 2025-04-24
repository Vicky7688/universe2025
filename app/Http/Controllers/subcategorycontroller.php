<?php

namespace App\Http\Controllers;

use App\Models\categorys;
use App\Models\subcategorys;
use App\Models\brands;
use Illuminate\Http\Request;
use Session;
use Toastr;
class subcategorycontroller extends Controller
{

    public function subcategorylist(){
        $subcategorys=subcategorys::orderby('sort')->get();
        $data=compact('subcategorys');
        return view('main.subcategorylist')->with($data);
    }

    public function addsubcategory(Request $request){
        $pagetitle="Sub Category Master"; 
        $pageto=url('addsubcategory');
        $formurl=url('addsubcategory');
        $loadbrands=brands::orderby('sort')->get();
        
        $subcategorys = subcategorys::join('categorys', 'subcategorys.category', '=', 'categorys.id')
        ->join('brands', 'subcategorys.brand', '=', 'brands.id')
        ->select('subcategorys.*', 'categorys.name as category_name', 'brands.name as brand_name')
        ->get();
        $categorylist="";
        $data=compact('formurl','loadbrands','categorylist','pagetitle','pageto','subcategorys');
            if (!empty($request->all())) { 
                $request->validate(
                            [
                        'name' => 'required',
                        'brand' => 'required',
                        'category' => 'required',
                        'status' => 'required',
                            ]
                        );

                        $subcategorysin = new subcategorys();
                        $subcategorysin->name = $request->name;
                        $subcategorysin->brand = $request->brand;
                        $subcategorysin->category = $request->category;
                        $subcategorysin->status = $request->status;
                        $subcategorysin->updatedby = Session::get('adminloginid');
                        $subcategorysin->updatedbytype = Session::get('logintype');
                        $subcategorysin->session = Session::get('sessionof');
                        $subcategorysin->save();
                        Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                return redirect('addsubcategory')->with('success', 'Data Inserted Successfully');

                    } else {
                        return view('addsubcategory')->with($data);
                    }
    }



    public function editsubcategory($id,Request $request){ 
        $pagetitle="Sub Category Master"; 
        $pageto=url('addsubcategory');
            $formurl=url('editsubcategory/'.$id);
            $subcategorysid=subcategorys::find($id);
            $loadbrands=brands::orderby('sort')->get();
         

            $subcategorys = subcategorys::join('categorys', 'subcategorys.category', '=', 'categorys.id')
        ->join('brands', 'subcategorys.brand', '=', 'brands.id')
        ->select('subcategorys.*', 'categorys.name as category_name', 'brands.name as brand_name')
        ->get();
            $categorylist=categorys::where("brand", $subcategorysid->brand)->get();
             $data=compact('subcategorysid','formurl','loadbrands','categorylist','pagetitle','pageto','subcategorys');
       if (!empty($request->all())) {
                $request->validate(
                         [
                            'name' => 'required',
                            'brand' => 'required',
                            'category' => 'required',
                            'status' => 'required',
                         ]
                     );

                     $subcategorysup =subcategorys::find($id);
                     $subcategorysup->name = $request->name;
                     $subcategorysup->brand = $request->brand;
                     $subcategorysup->category = $request->category;
                     $subcategorysup->status = $request->status;
                     $subcategorysup->updatedby = Session::get('adminloginid');
                     $subcategorysup->updatedbytype = Session::get('logintype');
                     $subcategorysup->session = Session::get('sessionof');
                     $subcategorysup->save();
                     Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                return redirect('addsubcategory')->with('success', 'Data Inserted Successfully');

                 } else {
                     return view('addsubcategory')->with($data); 
                 }


    }

    public function deletesubcategory($id,Request $request){
   $subcategorysid = subcategorys::find($id);
        if (is_null($subcategorysid)) {
            Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('fail', 'No Record Found');
        } else {
            $subcategorysid->delete();
            Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('success', 'Delete Successfully');
        }
    }


    public function table(Request $request)
    {




    $data = subcategorys::join('categorys', 'subcategorys.category', '=', 'categorys.id')
        ->join('brands', 'subcategorys.brand', '=', 'brands.id')
        ->select('subcategorys.*', 'categorys.name as category_name', 'brands.name as brand_name')
        ->get();
        return response()->json($data);

    }
} 