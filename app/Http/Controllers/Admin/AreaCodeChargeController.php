<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AreaCodeCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreaCodeChargeController extends Controller
{
    public function index(){
        try {
            if (request()->ajax()) {
                return datatables()->of(AreaCodeCharge::all())
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        return '<a title="edit" href="edit/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/dashboard')->with('error', $ex->getMessage());
        }
        return view('admin.area-codes.index');
    }

    public function show(Request $request, $id){
        $areaCode = AreaCodeCharge::findOrFail($id);
        return view('admin.area-codes.show',compact('areaCode'));
    }

    public function create(Request $request){
        if ($request->method() == 'POST'){
            $validator = Validator::make($request->all(), [
                'address' => 'required',
                'delivery_charge' => 'required|numeric',
                'min_amount' => 'required|numeric',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            AreaCodeCharge::create([
                'area_code' => $request->input('area_code'),
                'address' => $request->input('address'),
                'delivery_charge' => $request->input('delivery_charge'),
                'min_amount' => $request->input('min_amount'),
            ]);

            return redirect()->back()->with('success', 'Recorded Added Successfully');
        }
        return view('admin.area-codes.create');
    }

    public function edit(Request $request, $id){
        $content = AreaCodeCharge::findOrFail($id);
        if ($request->method() == 'POST'){
            $validator = Validator::make($request->all(), [
                'address' => 'required',
                'delivery_charge' => 'required|numeric',
                'min_amount' => 'required|numeric',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            $content->area_code = $request->input('area_code');
            $content->address = $request->input('address');
            $content->delivery_charge = $request->input('delivery_charge');
            $content->min_amount = $request->input('min_amount');
            $content->save();

            return redirect()->back()->with('success', 'Recorded Updated Successfully');
        }
        return view('admin.area-codes.create', compact('content'));
    }

    public function destroy(Request $request, $id){
        $content = AreaCodeCharge::find($id);
        if ($content != null){
            $content->delete();
            return true;
        }
        return false;
    }
}
