<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AddonGroup;
use App\Models\AreaCodeCharge;
use App\Models\Attribute;
use App\Models\Banner;
use App\Models\Category;
use App\Models\ContactQuery;
use App\Models\Deal;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function banners(){
        try{
            $banners = Banner::where('status', 1)->get();
            $products = Product::with('category', 'sizes', 'addons', 'productAttributes')
                ->orderBy('id', 'desc')->take(10)->get();
            return $this->success(array('banners' => $banners, 'popularProducts' => $products));
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }
    }

    public function menu(Request $request){
        try{
            $categories = Category::with('products', 'products.sizes', 'products.addons',
                'products.productAttributes');
            if($request->has('page')){
                $limit = 10;
                $page = $request->page ?? 1;
                $start = ($page > 1) ? $limit*($page-1) : 0;

                $categories = $categories->whereHas('products', function ($q) use ($start, $limit){
                    $q->skip($start)->take($limit);
                });
            }
            $categories = $categories->get();
            $sizes = array(
                "small" => "small",
                "medium" => "medium",
                "large" => "large",
            );
            return $this->success(array('categories' => $categories, 'sizes' => $sizes));
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }
    }

    public function categories(Request $request){
        try{

            $categories = Category::get();
            $sizes = array(
                "small" => "small",
                "medium" => "medium",
                "large" => "large",
            );
            return $this->success(array('categories' => $categories, 'sizes' => $sizes));
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }
    }
    
    public function deals(){
        try{
            $deals = Deal::with('dealItems', 'dealAddons')->where('status', 1)->orderBy('id', 'desc')->get();
            return $this->success($deals);
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }
    }

    public function dealById($id){
        try{
            $deal = Deal::with('dealItems.category.products', 'dealAddons')->where('status', 1)
                ->where('id', $id)->orderBy('id', 'desc')->first();
            return $this->success($deal);
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }
    }

    public function products(){
        try{
            $products = Product::with('category', 'sizes', 'addons', 'productAttributes')->get();
            return $this->success($products);
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }
    }

    public function searchProducts(Request $request){
        try{
            $products = Product::with('category')
                ->where('name', 'LIKE', '%'.$request->keyword.'%')->get();
            return $this->success($products);
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }
    }

    public function productById(Request $request, $id){
        try{
            $product = Product::with('category', 'sizes', 'addons.addon', 'productAttributes.attributeItem.attribute')->find($id);

            if(!empty($product->addons) && $product->addons != null && count($product->addons) > 0){
                $addons = AddonGroup::with('category', 'addonItems.addonSizes')->whereHas('addonItems', function ($q){
                    $q->where('id', '!=', null);
                })->where('category_id', $product->category_id)->get();
            }else{
                $addons = [];
            }
            if(!empty($product->productAttributes) && $product->productAttributes != null && count($product->productAttributes) > 0){
                $attributes = Attribute::with('category', 'attributeItems')->where('category_id', $product->category_id)->get();
            }else{
                $attributes = [];
            }

            if ($product != null){
                return $this->success(array(
                    'product' => $product,
                    'addons'  => $addons,
                    'attributes' => $attributes
                ));
            }
            return $this->error('Product Not Found', 400);
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }
    }
    public function areaCodeCharges(Request $request){
        try {
            $data = AreaCodeCharge::query();
            if ($request->has('area_code')){
                $data = $data->where('area_code', $request->area_code)->first();
            }else{
                $data = $data->get();
            }

            return $this->success($data);
        }catch (\Exception $ex){
            return $this->error($ex->getMessage());
        }

    }

    public function siteSetting(Request $request){
        try{
            $data = Setting::all();
            return $this->success($data);
        }catch(\Exception $ex){
            return $this->error($ex->getMessage());
        }
    }

    public function contactUs(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:199',
            'email' => 'required|email|max:199',
            'message' => 'required|string|max:1500',
        ]);
        if ($validator->fails()){
            return $this->error('Validation Errors', 200, [$validator->errors()]);
        }
        ContactQuery::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);
        $mailData = array(
            'name' => $request->name,
            'email' => $request->email,
            'text' => $request->message,
            'to' => 'contact@pizzaroma-schlangenbad.de'
        );
        try{
            Mail::send('emails.contact-us', $mailData, function ($message) use($mailData){
                $message->to($mailData['to'])->subject("Contact Query");
            });
        }catch (\Exception $ex){
            return $this->success([], "Thank You for your Query. Admin will be contact you soon", 200,
                ["exception" => $ex->getMessage()]);
        }
        return $this->success([],"Thank You for your Query. Admin will be contact you soon");
    }

    public function getProductsByCategory(Request $request, $id){
        try{
            
            $productObj = new Product();

            $page = 1;
            $limit = 10;

            if($request->has('page')){
                $limit = 10;
                $page = $request->page ?? 1;
                $start = ($page > 1) ? $limit*($page-1) : 0;
            }

            $products = $productObj->getProductionsByCategoryId($id ,$page, $limit);
            
            return $this->success(array('products' => $products, 'page' => $page));
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }
    }
}
