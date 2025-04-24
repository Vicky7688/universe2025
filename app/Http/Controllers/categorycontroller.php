<?php

namespace App\Http\Controllers;

use App\Models\categorys;
use App\Models\brands;
use Illuminate\Http\Request;
use Session;
use Toastr;
class categorycontroller extends Controller
{

    public function categorylist(){
        $categorys=categorys::orderby('sort')->get();
        $data=compact('categorys');
        return view('main.categorylist')->with($data);
    }

    public function addcategory(Request $request){

       


        $pagetitle="Category Master"; 
        $pageto=url('addcategory');
        $formurl=url('addcategory');
        $categorys = categorys::join('brands', 'categorys.brand', '=', 'brands.id')->select('categorys.*', 'brands.name as brand_name')->get();
        $loadbrands=brands::orderby('sort')->get(); 
        $data=compact('formurl','loadbrands','categorys','pagetitle','pageto');
            if (!empty($request->all())) {
                $request->validate(
                            [
                        'name' => 'required',
                        'brand' => 'required',
                        'status' => 'required',
                            ]
                        );

                        $categorysin = new categorys();
                        $categorysin->name = $request->name;
                        $categorysin->brand = $request->brand;
                        $categorysin->status = $request->status;
                        $categorysin->updatedby = Session::get('adminloginid');
                        $categorysin->updatedbytype = Session::get('logintype');
                        $categorysin->session = Session::get('sessionof');
                        $categorysin->save();
                        Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                return redirect('addcategory')->with('success', 'Data Inserted Successfully');

                    } else {
                        return view('addcategory')->with($data);
                    }
    }



    public function editcategory($id,Request $request){
        $pagetitle="Category Master"; 
        $pageto=url('addcategory');
            $formurl=url('editcategory/'.$id);
            $categorysid=categorys::find($id);
            $loadbrands=brands::orderby('sort')->get();
            $categorys = categorys::join('brands', 'categorys.brand', '=', 'brands.id')->select('categorys.*', 'brands.name as brand_name')->get();
             $data=compact('categorysid','formurl','loadbrands','categorys','pagetitle','pageto');
       if (!empty($request->all())) {
                $request->validate(
                         [
                            'name' => 'required',
                            'brand' => 'required',
                            'status' => 'required',
                         ]
                     );

                     $categorysup =categorys::find($id);
                     $categorysup->name = $request->name;
                     $categorysup->brand = $request->brand; 
                     $categorysup->status = $request->status;
                     $categorysup->updatedby = Session::get('adminloginid');
                     $categorysup->updatedbytype = Session::get('logintype');
                     $categorysup->session = Session::get('sessionof');
                     $categorysup->save();
                     Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                return redirect('addcategory')->with('success', 'Data Inserted Successfully');

                 } else {
                     return view('addcategory')->with($data);
                 }


    }

    public function deletecategory($id,Request $request){
   $categorysid = categorys::find($id);
        if (is_null($categorysid)) {
            Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('fail', 'No Record Found');
        } else {
            $categorysid->delete();
            Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('success', 'Delete Successfully');
        }
    }
    public function table(Request $request)
    {


        $data = categorys::join('brands', 'categorys.brand', '=', 'brands.id')
                        ->select('categorys.*', 'brands.name as brand_name')
                        ->get();
                        return response()->json($data);


    }

} 