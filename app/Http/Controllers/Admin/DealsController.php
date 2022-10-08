<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddonGroup;
use App\Models\AddonItem;
use App\Models\Deal;
use App\Models\DealAddon;
use App\Models\DealItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DealsController extends Controller
{
    public function index(){
        try {
            if (request()->ajax()) {
                return datatables()->of(Deal::orderBy('id', 'desc')->get())
                    ->addColumn('image', function ($data) {
                        return '<img class="cell-image" src="'.$this->getImageWithTransformation($data->image, 40, 40).'">';
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="View" href="deals/show/' . $data->id . '" class="btn btn-dark btn-sm"><i class="fas fa-eye"></i></a>&nbsp;<a title="edit" href="deals/edit/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['image','action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/dashboard')->with('error', $ex->getMessage());
        }
        return view('admin.deals.index');
    }

    public function create(Request $request){
        if ($request->method() == 'POST'){
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'price' => 'required',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            $deal = Deal::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'slug' => $this->createSlug($request->input('name')),
            ]);
            if ($request->has('file')){
                $fileName = time() . '-' . $request->file('file')->getClientOriginalName();
                $filePath = $request->file('file')->path();
                $imageUrl = $this->uploadImageIK($fileName, $filePath, 'deals');
                $deal->update(['image' => $imageUrl]);
            }
            if (!empty($request->get('products'))) {
                foreach ($request->get('products') as $pKey => $productId) {
                    $product = Product::with('category')->where('id', $productId)->first();
                    if ($product != null) {
                        DealItem::create([
                            'deal_id' => $deal->id,
                            'product_id' => $productId,
                            'quantity' => $request->input('prod_quantity')[$pKey],
                            'size' => $request->input('prod_size')[$pKey] ?? null,
                            'product_name' => $product->name,
                            'category_name'  => $product->category->name,
                            'category_id'  => $product->category->id
                        ]);
                    }
                }
            }
            if (!empty($request->get('addons'))) {
                foreach ($request->get('addons') as $aKey => $addonId) {
                    $addonItem = AddonItem::with('addonGroup')->where('id', $request->input('addon_items')[$aKey])->first();
                    if ($addonItem != null) {
                        DealAddon::create([
                            'deal_id' => $deal->id,
                            'addon_group_id' => $addonId,
                            'addon_item_id' => $request->input('addon_items')[$aKey],
                            'quantity' => $request->input('addon_quantity')[$aKey],
                            'addon_group_name' => $addonItem->addonGroup->name ?? '',
                            'addon_item_name' => $addonItem->name ?? '',
                        ]);
                    }
                }
            }
            return redirect()->back()->with('success', 'Deal Created Successfully');
        }
        $products = Product::where('status', 1)->get();
        $addons = AddonGroup::with('addonItems')->get();
        $productSizes = $this->itemSizes();
        return view('admin.deals.create', compact('products', 'addons', 'productSizes'));
    }

    public function edit(Request $request, $id){
        $content = Deal::with('dealItems', 'dealAddons')->findOrFail($id);

        if ($request->method() == "POST"){
            $content->name = $request->input('name');
            $content->description = $request->input('description');
            $content->price = $request->input('price');
            if ($request->has('file')){
                $fileName = time() . '-' . $request->file('file')->getClientOriginalName();
                $filePath = $request->file('file')->path();
                $imageUrl = $this->uploadImageIK($fileName, $filePath, 'products');
                $content->image = $imageUrl;
            }
            $content->save();

            DealItem::where('deal_id', $id)->delete();
            if (!empty($request->get('products'))) {
                foreach ($request->get('products') as $pKey => $productId) {
                    $product = Product::with('category')->where('id', $productId)->first();
                    if ($product != null) {
                        DealItem::create([
                            'deal_id' => $id,
                            'product_id' => $productId,
                            'quantity' => $request->input('prod_quantity')[$pKey],
                            'size' => $request->input('prod_size')[$pKey] ?? null,
                            'product_name' => $product->name,
                            'category_name'  => $product->category->name,
                            'category_id'  => $product->category->id
                        ]);
                    }
                }
            }

            DealAddon::where('deal_id', $id)->delete();
            if (!empty($request->get('addons'))) {
                foreach ($request->get('addons') as $aKey => $addonId) {
                    $addonItem = AddonItem::with('addonGroup')->where('id', $request->input('addon_items')[$aKey])->first();
                    if ($addonItem != null) {
                        DealAddon::create([
                            'deal_id' => $id,
                            'addon_group_id' => $addonId,
                            'addon_item_id' => $request->input('addon_items')[$aKey],
                            'quantity' => $request->input('addon_quantity')[$aKey],
                            'addon_group_name' => $addonItem->addonGroup->name ?? '',
                            'addon_item_name' => $addonItem->name ?? '',
                        ]);
                    }
                }
            }
            return redirect()->back()->with('success', 'Deal Updated Successfully');
        }
        $products   = Product::where('status', 1)->get();
        $addons     = AddonGroup::with('addonItems')->get();
        $productSizes = $this->itemSizes();
        return view('admin.deals.edit', compact('content','products', 'addons','productSizes'));
    }

    public function show($id)
    {
        $deal = Deal::with('dealItems', 'dealAddons')->findOrFail($id);

        return view('admin.deals.show', compact('deal'));
    }

    public function destroy($id){
        $content = Deal::find($id);
        $content->delete();
        echo 1;
    }

    private function createSlug($str){
        $slug = Str::slug($str, '-');
        if (Deal::whereSlug($slug)->exists()) {
            $original = $slug;
            $count = 1;

            while (Deal::whereSlug($slug)->exists()) {
                $slug = "$original-" . $count++;
            }
        }
        return $slug;
    }
}
