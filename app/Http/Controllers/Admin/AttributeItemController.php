<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttributeItemController extends Controller
{
    public function index(Request $request){
        try {
            if (request()->ajax()) {
                return datatables()->of(AttributeItem::get())
                    ->editColumn('image', function ($data) {
                        return '<img class="cell-image" src="'.$this->getImageWithTransformation($data->image, 40, 40).'">';
                    })
                    ->addColumn('attribute_group', function ($data) {
                        return $data->attribute->name ?? '';
                    })->addColumn('action', function ($data) {
                        return '<a title="edit" href="attribute-item/edit/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['attribute_group', 'action', 'image'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/dashboard')->with('error', $ex->getMessage());
        }
        return view('admin.attribute-items.index');
    }

    public function create(Request $request){
        if ($request->method() == 'POST'){
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'attribute' => 'required',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            $item = AttributeItem::create([
                'attribute_id' => $request->input('attribute'),
                'name' => $request->input('name'),
            ]);

            if ($request->has('file')){
                $fileName = time() . '-' . $request->file('file')->getClientOriginalName();
                $filePath = $request->file('file')->path();
                $imageUrl = $this->uploadImageIK($fileName, $filePath, 'attributes');
                $item->update(['image' => $imageUrl]);
            }

            return redirect()->back()->with('success', 'Attribute Added Successfully');
        }
        $attributes = Attribute::get();
        return view('admin.attribute-items.create', compact('attributes'));
    }

    public function edit(Request $request, $id){
        $content = AttributeItem::findOrFail($id);
        if ($request->method() == 'POST'){
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'attribute' => 'required',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            $content->attribute_id = $request->input('attribute');
            $content->name = $request->input('name');
            if ($request->has('file')){
                $fileName = time() . '-' . $request->file('file')->getClientOriginalName();
                $filePath = $request->file('file')->path();
                $imageUrl = $this->uploadImageIK($fileName, $filePath, 'attributes');
                $content->image = $imageUrl;
            }
            $content->save();
            return redirect()->back()->with('success', 'Attribute Updated Successfully');
        }
        $attributes = Attribute::get();
        return view('admin.attribute-items.create', compact('attributes', 'content'));
    }

    public function destroy(Request $request, $id){
        $content = AttributeItem::find($id);
        if ($content != null){
            $content->delete();
            return true;
        }
        return false;
    }
}
