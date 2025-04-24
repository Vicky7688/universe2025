<?php
namespace App\Http\Controllers;
use App\Models\brands;
use App\Models\purchase_order;
use Illuminate\Http\Request;
use Session;
use Toastr;
class brandcontroller extends Controller
{
    public function purchaseorder(){
        $brands=brands::orderby('sort')->get();
        $data=compact('brands');
        return view('main.purchaseorder')->with($data);
    }
    public function ptable(Request $request)
    {
            $data = purchase_order::join('items', 'purchase_order.itemid', '=', 'items.itemcode')
            ->leftJoin('login', function ($join) {
            $join->on('purchase_order.updatedby', '=', 'login.id')
            ->where('purchase_order.updatedbytype', '=', 'supeadmin');
            })
            ->leftJoin('admin_master', function ($join) {
            $join->on('purchase_order.updatedby', '=', 'admin_master.id')
            ->where('purchase_order.updatedbytype', '=', 'admin');
            })
            ->leftJoin('retails', function ($join) {
            $join->on('purchase_order.updatedby', '=', 'retails.id')
            ->where('purchase_order.updatedbytype', '=', 'retailer');
            })
            ->leftJoin('employe_master', function ($join) {
            $join->on('purchase_order.updatedby', '=', 'employe_master.id')
            ->where('purchase_order.updatedbytype', '=', 'user');
            })
            ->select('purchase_order.*', 'items.name as itemsname',
            'login.name as updatedbyname', 'admin_master.name as adminname',
            'retails.name as retailername', 'employe_master.name as employeename')
            ->get();
        return response()->json($data);
    }

    public function addbrand(Request $request){
        $pagetitle="Brand Master";
        $pageto=url('addbrand');
        $brand=brands::all();
        $formurl=url('addbrand');
        $data=compact('formurl','brand','pagetitle','pageto');
                if (!empty($request->all())) {
                    $request->validate(
                                [
                                    'name' => 'required',
                                    'status' => 'required',
                                ]
                            );
                            $brandsin = new brands();
                            $brandsin->name = $request->name;
                            $brandsin->status = $request->status;
                            $brandsin->updatedby = Session::get('adminloginid');
                            $brandsin->updatedbytype = Session::get('logintype');
                            $brandsin->session = Session::get('sessionof');
                            $brandsin->save();
                            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                    return redirect('addbrand')->with('success', 'Data Inserted Successfully');
        } else {
            return view('addbrand')->with($data);
        }
    }
    public function editbrand($id,Request $request){
        $pagetitle="Brand Master";
         $pageto=url('addbrand');
        $brand=brands::all();
            $formurl=url('editbrand/'.$id);
            $brandsid=brands::find($id);
             $data=compact('brandsid','formurl','brand','pagetitle','pageto');
                if (!empty($request->all())) {
                            $request->validate(
                                    [
                                        'name' => 'required',
                                        'status' => 'required',
                                    ]
                                );
                                $brandsup =brands::find($id);
                                $brandsup->name = $request->name;
                                $brandsup->status = $request->status;
                                $brandsup->updatedby = Session::get('adminloginid');
                                $brandsup->updatedbytype = Session::get('logintype');
                                $brandsup->session = Session::get('sessionof');
                                $brandsup->save();
                                Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                            return redirect('addbrand')->with('success', 'Data Inserted Successfully');
                 } else {
                     return view('addbrand')->with($data);
                 }
    }
    public function deletebrand($id,Request $request){
   $brandsid = brands::find($id);
        if (is_null($brandsid)) {
            Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('fail', 'No Record Found');
        } else {
            $brandsid->delete();
            Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('success', 'Delete Successfully');
        }

    }
    public function table(Request $request)
    {
        $data = brands::all(); // Retrieve data from the database using your model
        return response()->json($data);
    }
}
