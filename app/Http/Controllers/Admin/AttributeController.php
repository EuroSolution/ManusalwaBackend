<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttributeController extends Controller
{
    public function index(Request $request){
        try {
            if (request()->ajax()) {
                return datatables()->of(Attribute::with('category')->get())
                    ->addColumn('category_id', function($data){
                        return $data->category->name ?? '';
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="edit" href="attributes/edit/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/dashboard')->with('error', $ex->getMessage());
        }
        return view('admin.attributes.index');
    }

    public function create(Request $request){
        if ($request->method() == 'POST'){
            $validator = Validator::make($request->all(), [
                'name' => 'required'
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            Attribute::create([
                'name' => $request->input('name'),
                'type' => $request->input('type'),
                'category_id' => $request->input('category')
            ]);
            return redirect()->back()->with('success', 'Attribute Added Successfully');
        }

        $categories = Category::all();
        return view('admin.attributes.create', compact('categories'));
    }

    public function edit(Request $request, $id){
        $content = Attribute::findOrFail($id);
        if ($request->method() == 'POST'){
            $validator = Validator::make($request->all(), [
                'name' => 'required'
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            $content->name = $request->input('name');
            $content->category_id = $request->input('category');
            $content->type = $request->input('type');
            $content->save();
            return redirect()->back()->with('success', 'Attribute Updated Successfully');
        }
        $categories = Category::all();
        return view('admin.attributes.create', compact('content','categories'));
    }

    public function destroy(Request $request, $id){
        $content = Attribute::find($id);
        if ($content != null){
            $content->delete();
            return true;
        }
        return false;
    }

    public function getAttributeItemsById(Request $request){
        if (isset($request->attribute_id) && $request->attribute_id > 0){
            $items = AttributeItem::select('id', 'name')->where('attribute_id', $request->attribute_id)->get();
            return $items;
        }
        return array();
    }
}
