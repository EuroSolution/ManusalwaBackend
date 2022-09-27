<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class CustomersController extends Controller
{
    public function index(){
        try {
            if (request()->ajax()) {
                return datatables()->of(User::all()->where('role_id', 2))
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        return '<a title="View" href="customer/show/' . $data->id . '" class="btn btn-dark btn-sm"><i class="fas fa-eye"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/dashboard')->with('error', $ex->getMessage());
        }
        return view('admin.customers.index');
    }

    public function show(Request $request, $id){

        $customer = User::findOrFail($id);

        if(empty($customer)){
            abort(404);
        }
        return view('admin.customers.show', compact('customer'));
    }

    public function destroy($id){
        $customer = User::find($id);
        $customer->delete();
        echo 1;
    }
}
