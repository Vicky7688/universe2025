<?php

namespace App\Http\Controllers;

use App\Models\units;
use Illuminate\Http\Request;
use Session;
use Toastr;
class unitcontroller extends Controller
{

    public function unitlist(){
        $units=units::orderby('sort')->get();
        $data=compact('units');
        return view('main.unitlist')->with($data);
    }
    // public function getdata(){


    //     $items = units::all();
    //     return response()->json($items);
    // }

    public function addunit(Request $request){
        $pagetitle="Unit Master"; 
        $pageto=url('addunit');
        $formurl=url('addunit');
        $units=units::all();
        $data=compact('formurl','pagetitle','pageto','units');
   if (!empty($request->all())) {
       $request->validate(
                [
               'name' => 'required',

               'status' => 'required',
                ]
            );

            $unitsin = new units();
            $unitsin->name = $request->name;
            $unitsin->status = $request->status;
            $unitsin->updatedby = Session::get('adminloginid');
            $unitsin->updatedbytype = Session::get('logintype');
            $unitsin->session = Session::get('sessionof');
            $unitsin->save();
            Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
       return redirect('addunit')->with('success', 'Data Inserted Successfully');

        } else {
            return view('addunit')->with($data);
        }
    }



    public function editunit($id,Request $request){
            $formurl=url('editunit/'.$id);
            $unitsid=units::find($id); 
             $pagetitle="Unit Master"; 
            $pageto=url('addunit');
            $units=units::all();
             $data=compact('unitsid','formurl','pagetitle','pageto','units');
       if (!empty($request->all())) {
                $request->validate(
                         [
                            'name' => 'required', 
                            'status' => 'required',
                         ]
                     );

                     $unitsup =units::find($id);
                     $unitsup->name = $request->name; 
                     $unitsup->status = $request->status;
                     $unitsup->updatedby = Session::get('adminloginid');
                     $unitsup->updatedbytype = Session::get('logintype');
                     $unitsup->session = Session::get('sessionof');
                     $unitsup->save();
                     Toastr::success('Data Updated Successfully', 'Hurray...!!!', ["positionClass" => "toast-top-center"]);
                return redirect('addunit')->with('success', 'Data Inserted Successfully');

                 } else {
                     return view('addunit')->with($data);
                 }


    }

    public function deleteunit($id,Request $request){


   $unitsid = units::find($id);
        if (is_null($unitsid)) {
            Toastr::error('No Record Found', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('fail', 'No Record Found');
        } else {
            $unitsid->delete();
            Toastr::error('Data Deleted Successfully', 'Snap..!!!!', ["positionClass" => "toast-top-center"]);
            return back()->with('success', 'Delete Successfully');
        }




        // use Illuminate\Database\QueryException;
        // $unitsid = units::find($id);

        // try {
        //     if (is_null($unitsid)) {
        //         return back()->with('fail', 'No Record Found');
        //     } else {
        //         $unitsid->delete();
        //         return back()->with('success', 'Delete Successfully');
        //     }
        // } catch (QueryException $e) {
        //     if (strpos($e->getMessage(), 'foreign key constraint') !== false) {
        //         return back()->with('fail', 'This record cannot be deleted because it is connected to other records.');
        //     } else {
        //         return back()->with('fail', 'An error occurred. Please try again later.');
        //     }
        // }



    }




    public function table(Request $request)
    {
        $data = units::all(); // Retrieve data from the database using your model
        return response()->json($data);
    }




}
