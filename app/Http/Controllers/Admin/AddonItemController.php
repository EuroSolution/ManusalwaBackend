<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddonGroup;
use App\Models\AddonItem;
use App\Models\AddonSize;
use Illuminate\Http\Request;

class AddonItemController extends Controller
{
    public function index(){
        try {
            if (request()->ajax()) {
                return datatables()->of(AddonItem::with('addonGroup')->get())
                    ->addColumn('image', function ($data) {
                        return '<img class="cell-image" src="'.$this->getImageWithTransformation($data->image, 40, 40).'" width="40px" height="40px">';
                    })
                    ->addColumn('addon_group', function ($data) {
                        return $data->addonGroup->name ?? '';
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="edit" href="addon-item/edit/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['image', 'addon_group', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/dashboard')->with('error', $ex->getMessage());
        }
        return view('admin.addon-item.index');
    }

    public function add(Request $request){
        if ($request->method() == 'POST'){
            $this->validate($request, [
                'name' => 'required',
                'addon_group' => 'required',
            ]);
            $item = AddonItem::create([
                'addon_group_id' => $request->input('addon_group'),
                'name' => $request->input('name'),
                'price' => $request->input('price') ?? 0,
                'discounted_price' => $request->input('discounted_price') ?? 0,
                'description' => $request->input('description'),
            ]);

            if (!empty($request->get('sizes'))){
                foreach ($request->get('sizes') as $sKey => $size){
                    if ($size != null){
                        AddonSize::create([
                            'addon_item_id' => $item->id,
                            'size' => $size,
                            'price' => $request->get('prices')[$sKey] ?? 0,
                            'discounted_price' => 0,
                        ]);
                    }
                }
            }

            if ($request->has('file')){
                $fileName = time() . '-' . $request->file('file')->getClientOriginalName();
                $filePath = $request->file('file')->path();
                $imageUrl = $this->uploadImageIK($fileName, $filePath, 'products');
                $item->update(['image' => $imageUrl]);
            }
            return redirect()->back()->with('success', 'Addon Item Added Successfully');
        }
        $addonGroups = AddonGroup::get();
        $sizes = array('small', 'medium', 'large');
        $addonSizes = $this->itemSizes();
        return view('admin.addon-item.create', compact('addonGroups', 'sizes', 'addonSizes'));
    }

    public function edit(Request $request, $id){
        $content = AddonItem::with('addonSizes')->findOrFail($id);
        if ($request->method() == 'POST'){
            $this->validate($request, [
                'name' => 'required',
            ]);
            $content->addon_group_id = $request->input('addon_group');
            $content->name = $request->input('name');
            $content->description = $request->input('description');
            $content->price = $request->input('price') ?? 0;
            $content->discounted_price = $request->input('discounted_price') ?? 0;
            $content->save();

            AddonSize::where('addon_item_id', $content->id)->delete();
            if (!empty($request->get('sizes'))){
                foreach ($request->get('sizes') as $sKey => $size){
                    if ($size != null){
                        AddonSize::create([
                            'addon_item_id' => $content->id,
                            'size' => $size,
                            'price' => $request->get('prices')[$sKey] ?? 0,
                            'discounted_price' =>  $request->get('discount_prices')[$sKey] ?? 0,
                        ]);
                    }
                }
            }
            if ($request->has('file')){
                $fileName = time() . '-' . $request->file('file')->getClientOriginalName();
                $filePath = $request->file('file')->path();
                $imageUrl = $this->uploadImageIK($fileName, $filePath, 'products');
                $content->image = $imageUrl;
                $content->save();
            }

            return redirect()->back()->with('success', 'Addon Item Updated Successfully');
        }
        $addonGroups = AddonGroup::get();
        // $sizes = array('small', 'medium', 'large');
        $sizes = $this->itemSizes();
        return view('admin.addon-item.edit', compact('content', 'addonGroups', 'sizes'));
    }

    public function destroy(Request $request, $id){
        $content = AddonItem::find($id);
        if ($content != null){
            $content->delete();
            return true;
        } else {
            return false;
        }
    }

    public function getAddonItemsById(Request $request){
        if (isset($request->addon_id) && $request->addon_id > 0){
            return AddonItem::select('id', 'name')->where('addon_group_id', $request->addon_id)->get();
        }
        return array();
    }
}
