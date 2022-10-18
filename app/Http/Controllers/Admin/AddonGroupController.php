<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddonGroup;
use App\Models\Category;
use Illuminate\Http\Request;

class AddonGroupController extends Controller
{
    public function index(){
        try {
            if (request()->ajax()) {
                return datatables()->of(AddonGroup::with('category')->get())
                    ->addColumn('category_id', function($data){
                        return $data->category->name ?? '';
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="edit" href="addon-group/edit/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/dashboard')->with('error', $ex->getMessage());
        }
        return view('admin.addon-group.index');
    }

    public function add(Request $request){
        if ($request->method() == 'POST'){
            $this->validate($request, [
                'name' => 'required'
            ]);
            AddonGroup::create([
                'name' => $request->input('name'),
                'category_id' => $request->input('category'),
                'type' => $request->input('type')
            ]);
            return redirect()->back()->with('success', 'Addon Group Added Successfully');
        }
        $categories = Category::all();
        return view('admin.addon-group.create', compact('categories'));
    }

    public function edit(Request $request, $id){
        $content = AddonGroup::findOrFail($id);
        if ($request->method() == 'POST'){
           /*  print_r($_POST);
            exit; */
            $this->validate($request, [
                'name' => 'required'
            ]);
            $content->name = $request->input('name');
            $content->category_id = $request->input('category');
            $content->type = $request->input('type');
            $content->save();

            return redirect()->back()->with('success', 'Addon Group Updated Successfully');
        }

        $categories = Category::all();
        return view('admin.addon-group.create', compact('content', 'categories'));
    }

    public function destroy(Request $request, $id){
        $content = AddonGroup::find($id);
        if ($content != null){
            $content->delete();
            return true;
        } else {
          return false;
        }
    }
}
