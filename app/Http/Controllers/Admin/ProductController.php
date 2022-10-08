<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddonItem;
use App\Models\Attribute;
use App\Models\ProductAddon;
use App\Models\ProductAttribute;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;



class ProductController extends Controller
{
    public function index(){
        try {
            if (request()->ajax()) {
                return datatables()->of(Product::with('category')->get())
                    ->addColumn('image', function ($data) {
                        return '<img class="cell-image" src="'.$this->getImageWithTransformation($data->image, 40, 40).'" width="40px" height="40px">';
                    })
                    ->addColumn('category_id', function ($data) {
                        return $data->category->name ?? '';
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="View" href="product/show/' . $data->id . '" class="btn btn-dark btn-sm"><i class="fas fa-eye"></i></a>&nbsp;<a title="edit" href="product/edit/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['image','action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/dashboard')->with('error', $ex->getMessage());
        }
        return view('admin.product.index');
    }

    public function add(Request $request){
        if ($request->method() == 'POST'){
            $this->validate($request, array(
                'name' => 'required',
                'category' => 'required',
                'price'		=>	'numeric'
            ));

            $slugStr = Str::of($request->input('name'))->slug('-');

            $product = Product::create([
                'name' => $request->input('name'),
                'category_id' => $request->input('category'),
                'description' => $request->input('description'),
                'price' => $request->input('price') ?? 0.00,
                'slug' => $this->createSlug($slugStr),
            ]);

            if (!empty($request->get('sizes'))){
                foreach ($request->get('sizes') as $sKey => $size){
                    if ($size != null){
                        ProductSize::create([
                            'product_id' => $product->id,
                            'size' => $size,
                            'price' => $request->get('size_prices')[$sKey] ?? 0,
                            'discounted_price' => 0.00,
                        ]);
                    }
                }
            }

            if (!empty($request->get('attributes'))){
                foreach ($request->get('attributes') as $aKey => $attribute){
                    if ($attribute != null){
                        ProductAttribute::create([
                            'product_id' => $product->id,
                            'attribute_id' => $attribute,
                            'attribute_item_id' => $request->get('attribute_items')[$aKey]
                        ]);
                    }
                }
            }

            if (!empty($request->get('addons'))){
                foreach ($request->get('addons') as $aKey => $addon){
                    if ($addon != null){
                        ProductAddon::create([
                            'product_id' => $product->id,
                            'addon_item_id' => $addon,
                            'price' => $request->get('addon_prices')[$aKey] ?? 0,
                            'discounted_price' => 0.00,
                        ]);
                    }
                }
            }

            if ($request->has('file')){
                $fileName = time() . '-' . $request->file('file')->getClientOriginalName();
                $filePath = $request->file('file')->path();
                $imageUrl = $this->uploadImageIK($fileName, $filePath, 'products');
                $product->update(['image' => $imageUrl]);
            }
            return redirect('/products')->with(['success' => 'Product Added Successfully']);
        }

        $categories = Category::get();
        $addonItems = AddonItem::get();
        $attributes = Attribute::get();
        $productSizes = $this->itemSizes();
        return view('admin.product.add-product', compact('categories', 'addonItems', 'attributes','productSizes'));
    }


    public function show($id)
    {
        $product = Product::where('id', $id)->firstOrFail();
        return view('admin.product.view', compact('product'));
    }


    public function edit(Request $request, $id)
    {
        $content = Product::with('sizes', 'addons', 'productAttributes')->findOrFail($id);
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

            ProductSize::where('product_id', $id)->delete();
            if (!empty($request->get('sizes'))){
                foreach ($request->get('sizes') as $sKey => $size){
                    ProductSize::create([
                        'product_id' => $id,
                        'size' => $size,
                        'price' => $request->get('size_prices')[$sKey] ?? 0,
                        'discounted_price' => 0.00,
                    ]);
                }
            }
            ProductAttribute::where('product_id', $id)->delete();
            if (!empty($request->get('attributes'))){
                foreach ($request->get('attributes') as $aKey => $attribute){
                    if ($attribute != null){
                        ProductAttribute::create([
                            'product_id' => $id,
                            'attribute_id' => $attribute,
                            'attribute_item_id' => $request->get('attribute_items')[$aKey]
                        ]);
                    }
                }
            }
            ProductAddon::where('product_id', $id)->delete();
            if (!empty($request->get('addons'))){
                foreach ($request->get('addons') as $aKey => $addon){
                    ProductAddon::create([
                        'product_id' => $id,
                        'addon_item_id' => $addon,
                        'price' => $request->get('addon_prices')[$aKey] ?? 0,
                        'discounted_price' => 0.00,
                    ]);
                }
            }
            return redirect()->back()->with('success', 'Product Updated successfully');
        }

        $categories = Category::get();
        $addonItems = AddonItem::get();
        $attributes = Attribute::with('attributeItems')->get();
        $productSizes = $this->itemSizes();
        return view('admin.product.update-product', compact('content','categories', 'addonItems', 'attributes', 'productSizes'));
    }

    public function destroy($id)
    {
        $content = Product::find($id);
        $content->delete();
        echo 1;
    }

    private function createSlug($str){
        $slug = Str::slug($str, '-');
        if (Product::whereSlug($slug)->exists()) {
            $original = $slug;
            $count = 1;

            while (Product::whereSlug($slug)->exists()) {
                $slug = "$original-" . $count++;
            }
        }
        return $slug;
    }
}
