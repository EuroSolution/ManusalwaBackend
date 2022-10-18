<?php

use App\Http\Controllers\Admin\AddonGroupController;
use App\Http\Controllers\Admin\AddonItemController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AttributeItemController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\Admin\DealsController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AreaCodeChargeController;
use App\Http\Controllers\Api\HomeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\DeliveryTimesController;
use App\Http\Controllers\Staff\OrdersController AS staffOrdersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});
Route::get('logout', function (){
    auth()->logout();
    return redirect('login');
})->name('admin.logout');
Route::match(['get', 'post'], 'login', [AdminController::class, 'login'])->name('admin.login');
Route::middleware('admin')->name('admin.')->group(function (){
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::match(['get', 'post'],'/setting', [AdminController::class, 'setting'])->name('setting');
    Route::get('/notifications', [AdminController::class, 'showNotification'])->name('notification');
    Route::post('update-notification', [AdminController::class, 'updateNotification'])->name('updateNotification');

    Route::get('categories', [CategoryController::class, 'index'])->name('categories');
    Route::match(['get', 'post'],'/category/add', [CategoryController::class, 'add'])->name('addCategory');
    Route::match(['get', 'post'],'/category/edit/{id}', [CategoryController::class, 'edit'])->name('editCategory');
    Route::get('/category/show/{id}', [CategoryController::class, 'show'])->name('showCategory');
    Route::delete('categories/destroy/{id}', [CategoryController::class, 'destroy'])->name('destroyCategory');

    Route::get('products', [ProductController::class, 'index'])->name('products');
    Route::match(['get', 'post'],'/product/add', [ProductController::class, 'add'])->name('addProduct');
    Route::match(['get', 'post'],'/product/edit/{id}', [ProductController::class, 'edit'])->name('editProduct');
    Route::get('/product/show/{id}', [ProductController::class, 'show'])->name('showProduct');
    Route::delete('products/destroy/{id}', [ProductController::class, 'destroy'])->name('destroyProduct');

    Route::get('addon-groups', [AddonGroupController::class, 'index'])->name('addonGroups');
    Route::match(['get', 'post'],'/addon-group/add', [AddonGroupController::class, 'add'])->name('addAddonGroups');
    Route::match(['get', 'post'],'/addon-group/edit/{id}', [AddonGroupController::class, 'edit'])->name('editAddonGroups');
    Route::delete('addon-groups/destroy/{id}', [AddonGroupController::class, 'destroy'])->name('destroyAddonGroups');

    Route::get('addon-items', [AddonItemController::class, 'index'])->name('addonItems');
    Route::match(['get', 'post'],'/addon-item/add', [AddonItemController::class, 'add'])->name('addAddonItems');
    Route::match(['get', 'post'],'/addon-item/edit/{id}', [AddonItemController::class, 'edit'])->name('editAddonItems');
    Route::delete('addon-items/destroy/{id}', [AddonItemController::class, 'destroy'])->name('destroyAddonItems');

    Route::get('coupons', [CouponController::class, 'index'])->name('coupons');
    Route::match(['get', 'post'],'/coupon/add', [CouponController::class, 'add'])->name('addCoupon');
    Route::get('/coupon/show/{id}', [CouponController::class, 'show'])->name('showCoupon');
    Route::match(['get', 'post'],'/coupon/edit/{id}', [CouponController::class, 'edit'])->name('editCoupon');
    Route::delete('coupons/destroy/{id}', [CouponController::class, 'destroy'])->name('destroyCoupon');

    Route::get('customers',[CustomersController::class, 'index'])->name('customers');
    Route::get('customer/show/{id}', [CustomersController::class, 'show'])->name('showCustomer');
    Route::delete('customers/destroy/{id}', [CustomersController::class, 'destroy'])->name('destroyCustomer');

    Route::get('banners', [BannerController::class, 'index'])->name('banners');
    Route::match(['get', 'post'],'/banner/add', [BannerController::class, 'add'])->name('addBanner');
    Route::match(['get', 'post'],'/banner/edit/{id}', [BannerController::class, 'edit'])->name('editBanner');
    Route::delete('banners/destroy/{id}', [BannerController::class, 'destroy'])->name('destroyBanner');

    Route::get('orders', [OrdersController::class, 'index'])->name('orders');
    Route::get('order/show/{id}', [OrdersController::class, 'show'])->name('showOrder');
    Route::delete('orders/destroy/{id}', [OrdersController::class, 'destroy'])->name('destroyOrder');
    Route::post('order/changeOrderStatus/{id}', [OrdersController::class, 'changeOrderStatus'])->name('changeOrderStatus');

    Route::get('attributes', [AttributeController::class, 'index'])->name('attributes');
    Route::get('getAttributeItemsById', [AttributeController::class, 'getAttributeItemsById'])->name('getAttributeItemsById');
    Route::match(['get', 'post'],'/attributes/add', [AttributeController::class, 'create'])->name('addAttributes');
    Route::match(['get', 'post'],'/attributes/edit/{id}', [AttributeController::class, 'edit'])->name('editAttributes');
    Route::delete('attributes/destroy/{id}', [AttributeController::class, 'destroy'])->name('destroyAttributes');

    Route::get('attribute-items', [AttributeItemController::class, 'index'])->name('attributeItems');
    Route::match(['get', 'post'],'/attribute-item/add', [AttributeItemController::class, 'create'])->name('addAttributeItems');
    Route::match(['get', 'post'],'/attribute-item/edit/{id}', [AttributeItemController::class, 'edit'])->name('editAttributeItems');
    Route::delete('attribute-items/destroy/{id}', [AttributeItemController::class, 'destroy'])->name('destroyAttributeItems');

    Route::get('area-code/charge', [AreaCodeChargeController::class, 'index'])->name('areaCodeCharge');
    Route::match(['get','post'],'/area-code/add', [AreaCodeChargeController::class, 'create'])->name('areaCodeAdd');
    Route::match(['get','post'],'/area-code/edit/{id}', [AreaCodeChargeController::class, 'edit'])->name('areaCodeEdit');
    Route::get('area-code/show/{id}', [AreaCodeChargeController::class, 'show'])->name('areaCodeShow');
    Route::delete('area-code/destroy/{id}', [AreaCodeChargeController::class, 'destroy'])->name('areaCodeDestory');

    Route::get('deals', [DealsController::class, 'index'])->name('deals');
    Route::get('getAddonItemsById', [AddonItemController::class, 'getAddonItemsById'])->name('getAddonItemsById');
    Route::match(['get', 'post'],'deals/add', [DealsController::class, 'create'])->name('addDeals');
    Route::match(['get', 'post'],'/deals/edit/{id}', [DealsController::class, 'edit'])->name('editDeals');
    Route::delete('deals/destroy/{id}', [DealsController::class, 'destroy'])->name('destroyDeals');
    Route::get('deals/show/{id}', [DealsController::class, 'show'])->name('dealsShow');

    Route::get('staff/member', [StaffController::class, 'index'])->name('staffMember');
    Route::match(['get','post'],'add/staff',[StaffController::class, 'add'])->name('addStaff');
    Route::match(['get','post'],'/staff/edit/{id}',[StaffController::class, 'edit'])->name('staffEdit');
    Route::delete('staff/destroy/{id}', [StaffController::class, 'destroy'])->name('staffDestroy');

    Route::get('delivery-time', [DeliveryTimesController::class, 'index'])->name('deliveryTime');
    Route::match(['get','post'],'add/delivery-time', [DeliveryTimesController::class, 'add'])->name('addDeliveryTime');
    Route::match(['get','post'], 'edit/delivery-time/{id}',[DeliveryTimesController::class, 'edit'])->name('editDeliveryTime');
    Route::delete('destroy/delivery-time/{id}',[DeliveryTimesController::class, 'destroy'])->name('destroyDeliveryTime');

    Route::get('getAddonsAttributesByCategoryId/{id}', [CategoryController::class, 'getAddonsAttributesByCategoryId'])->name('getAddonsAttributesByCategoryId');

});
Route::patch('/fcm-token', [AdminController::class, 'updateToken'])->name('fcmToken');
Route::get('/send-notification',[AdminController::class,'sendNotification']);

Route::middleware('staff')->name('staff.')->group(function(){
    Route::get('/staff/dashboard', [staffOrdersController::class, 'dashboard'])->name('staffDashboard');
    Route::get('/staff/orders', [staffOrdersController::class, 'index'])->name('staffOrders');
    Route::delete('/staff/order-destroy/{id}', [staffOrdersController::class, 'destroy'])->name('staffOrderDestroy');
    Route::get('staff/order-show/{id}', [staffOrdersController::class, 'show'])->name('staffOrderShow');
    Route::post('staff/changeOrderStatus/{id}', [staffOrdersController::class, 'changeOrderStatus'])->name('stafChangeOrderStatus');
    Route::get('staff/notifications', [staffOrdersController::class, 'showNotification'])->name('staffNotifications');
    // Route::post('update-notification', [AdminController::class, 'updateNotification'])->name('updateNotification');
});

Route::get('/cache-clear', function(){
    
    $q = request()->get('query');

    try{
        Artisan::call("$q:clear");
    }catch(\Exception $x){
        return $x->getMessage();
    }

    return "Cache Cleared....";

});