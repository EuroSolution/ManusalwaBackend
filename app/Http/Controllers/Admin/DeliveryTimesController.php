<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
Use App\Models\DeliveryTime;
use Illuminate\Support\Facades\Validator;
use Lcobucci\JWT\Validation\Constraint\ValidAt;

class DeliveryTimesController extends Controller
{
    public function index(){
        try {
            if (request()->ajax()) {
                return datatables()->of(DeliveryTime::all())
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        return '<a title="edit" href="edit/delivery-time/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/dashboard')->with('error', $ex->getMessage());
        }

        return view('admin.delivery-time.index');
    }

    public function add(Request $request, DeliveryTime $deliveryTime){
        
        if($request->method() == 'POST'){
            $validate = Validator::make($request->all(),[
                'day' => 'required',
                'time' => 'required'
            ]);

            if($validate->fails()){
                return redirect()->back()->withErrors($validate->errors())->withInput();
            }
            

            $data = $deliveryTime->create([
                'day' => $request->input('day'),
                'time' => $request->input('time')
            ]);
            
            if($data){
                return redirect()->back()->with('success', 'Delivery Time Added Success');
            }

            return redirect()->back()->with('error', 'record not added!');
            
        }

        return view('admin.delivery-time.add');
    }

    public function edit(Request $request, $id){
        $content = DeliveryTime::find($id);
        if($request->method() == "POST"){
            $validate = Validator::make($request->all(),[
                'day' => 'required',
                'time' => 'required'
            ]);

            if($validate->fails()){
                return redirect()->back()->withErrors($validate->errors())->withInput();
            }

            $content->day = $request->input('day');
            $content->time = $request->input('time');
            
            if($content->save()){
                return redirect()->back()->with(['success' => 'Updated Succefully']);
            }else{
                return redirect()->back()->with(['error' => 'OOPS! Data Not Updated']);

            }
        }
        
        return view('admin.delivery-time.add', compact('content'));
    }

    public function destroy($id){
        $data = DeliveryTime::find($id);
        $data->delete();
        echo 1;
    }
}
