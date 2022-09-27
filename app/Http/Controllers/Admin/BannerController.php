<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;


class BannerController extends Controller
{
    public function index(){
        try {
            if (request()->ajax()) {
                return datatables()->of(Banner::all())
                    ->addColumn('image', function ($data) {
                        return '<img class="cell-image" src="'.$this->getImageWithTransformation($data->image, 40, 40).'">';
                    })
                    ->editColumn('status', function ($data){
                        return ($data->status == 1) ? "Active" : "In-Active";
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="edit" href="banner/edit/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['image','action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/dashboard')->with('error', $ex->getMessage());
        }
        return view('admin.banners.index');
    }

    public function add(Request $request){
        if ($request->method() == 'POST') {
            $this->validate($request, array(
                'image' => 'required'
            ));

            if ($request->file('image')) {
                $fileName = time() . '-' . $request->file('image')->getClientOriginalName();
                $filePath = $request->file('image')->path();
                $imageUrl = $this->uploadImageIK($fileName, $filePath, 'banners');
            }

            $banner = Banner::create([
                'image' => $imageUrl ?? null,
                'sort_order' => $request->input('sort_order')
            ]);

           if ($banner) {
               return redirect()->back()->with(['success' => 'Banner Added Successfully']);
           }
        }
        return view('admin.banners.create');
    }

    public function edit(Request $request, $id){
        if ($request->method() == 'POST') {
            $this->validate($request, array(
                'image' => ['required']
            ));

            $banner = Banner::find($id);
            if ($request->file('image')) {
                $fileName = time() . '-' . $request->file('image')->getClientOriginalName();
                $filePath = $request->file('image')->path();
                $imageUrl = $this->uploadImageIK($fileName, $filePath, 'banners');
                $banner->image = $imageUrl;
            }

            $banner->sort_order = $request->input('sort_order');

            if ($banner->save()) {
                return redirect()->back()->with(['success' => 'Banner Edit Successfully']);
            }
        }else {
            $content=Banner::findOrFail($id);
            return view('admin.banners.create', compact('content'));
        }
    }

    public function destroy($id){
        $banner = Banner::find($id);
        $banner->delete();
        echo 1;
    }
}
