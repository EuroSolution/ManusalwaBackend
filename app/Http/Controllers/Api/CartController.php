<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AddonItem;
use App\Models\AttributeItem;
use App\Models\Cart;
use App\Models\CartAttribute;
use App\Models\CartItem;
use App\Models\CartItemAddon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(Request $request){
        try{
            $data = Cart::with('cartItems', 'cartItems.cartItemAddons')->where('user_id', Auth::id()??0)->get();
            return $this->success($data);
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(), 500);
        }
    }

    public function add(Request $request){
        try{
            if (!empty($request->cart_items) || !empty($request->deals)){
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                    'deal_id' => $request->deal_id ?? null,
                    'cart_count' => $request->cart_count,
                    'subtotal' => $request->subtotal,
                    'tax' => $request->tax,
                    'discount' => $request->discount ?? 0,
                    'delivery_fee' => $request->delivery_fee,
                    'is_deal' => $request->is_deal ?? 0,
                    'voucher_code' => $request->voucher_code ?? null,
                    'total_amount' => $request->total_amount,
                    'notes' => $request->notes,
                    'source' => $request->source ?? null
                ]);
                if (!empty($request->cart_items)) {
                    foreach ($request->cart_items as $item) {
                        $product = Product::with('addons')->find($item['id']);
                        if ($product != null) {
                            $cartItem = CartItem::create([
                                'cart_id' => $cart->id,
                                'product_id' => $product->id,
                                'price' => $item['price'] ?? $product->price,
                                'size' => $item['size'] ?? "",
                                'quantity' => $item['quantity']
                            ]);
                            if ($product->addons != null || !empty($request->addons)) {
                                /*if ($product->addons != null && ! empty($product->addons)){
                                    foreach ($product->addons as $pAddonItem){
                                        $addon = AddonItem::with('addonGroup')->find($pAddonItem->addon_item_id);
                                        if ($addon != null){
                                            CartItemAddon::create([
                                                'cart_item_id' => $cartItem->id,
                                                'addon_item_id' => $addon->id,
                                                'addon_group' => $addon->addonGroup->name,
                                                'addon_item' => $addon->name,
                                                'price' => $pAddonItem->price,
                                                'quantity'  => 1
                                            ]);
                                        }
                                    }
                                }*/
                                if (isset($item['cart_item_addons']) && !empty($item['cart_item_addons'])) {
                                    foreach ($item['cart_item_addons'] as $addonItem) {
                                        $addon = AddonItem::with('addonGroup')->find($addonItem['id']);
                                        if ($addon != null) {
                                            CartItemAddon::create([
                                                'cart_item_id' => $cartItem->id,
                                                'addon_item_id' => $addon->id,
                                                'addon_group' => $addon->addonGroup->name,
                                                'addon_item' => $addon->name,
                                                'price' => $addonItem['price'] ?? 0,
                                                'quantity' => $addonItem['quantity'] ?? 1,
                                                'size' => $addonItem['size'] ?? ''
                                            ]);
                                        }
                                    }
                                }
                            }
                            if (isset($item['attributes']) && !empty($item['attributes'])) {
                                foreach ($item['attributes'] as $attribute) {
                                    $attributeItem = AttributeItem::with('attribute')->find($attribute['id']);
                                    if ($attributeItem != null) {
                                        CartAttribute::create([
                                            'cart_item_id' => $cartItem->id,
                                            'attribute_item_id' => $attributeItem->id,
                                            'attribute_name' => $attributeItem->attribute->name ?? '',
                                            'attribute_item' => $attributeItem->name,
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
                if (!empty($request->deals)){
                    foreach($request->deals as $deal){
                        foreach ($deal['deal_items'] as $deal_item){
                            $product = Product::with('addons')->find($deal_item['product_id'] ?? $deal_item['id']);
                            if ($product != null) {
                                $cartItem = CartItem::create([
                                    'cart_id' => $cart->id,
                                    'product_id' => $product->id,
                                    'price' => $deal_item['price'] ?? $product->price,
                                    'size' => $deal_item['size'] ?? "",
                                    'quantity' => $deal_item['quantity'] ?? 1,
                                    'deal_id' => $deal['id'],
                                    'deal_item_id' => $deal_item['deal_item_id']
                                ]);
                                if (isset($deal_item['deal_item_addons']) && !empty($deal_item['deal_item_addons'])) {
                                    foreach ($deal_item['deal_item_addons'] as $addonItem) {
                                        $addon = AddonItem::with('addonGroup')->find($addonItem['addon_item_id'] ?? $addonItem['id']);
                                        if ($addon != null) {
                                            CartItemAddon::create([
                                                'cart_item_id' => $cartItem->id,
                                                'addon_item_id' => $addon->id,
                                                'addon_group' => $addon->addonGroup->name,
                                                'addon_item' => $addon->name,
                                                'price' => $addonItem['price'] ?? 0,
                                                'quantity' => $addonItem['quantity'] ?? 1,
                                                'size' => $addonItem['size'] ?? ''
                                            ]);
                                        }
                                    }
                                }
                                if (isset($deal_item['attributes']) && !empty($deal_item['attributes'])) {
                                    foreach ($deal_item['attributes'] as $attribute) {
                                        $attributeItem = AttributeItem::with('attribute')->find($attribute['attribute_item_id']??$attribute['id']);
                                        if ($attributeItem != null) {
                                            CartAttribute::create([
                                                'cart_item_id' => $cartItem->id,
                                                'attribute_item_id' => $attributeItem->id,
                                                'attribute_name' => $attributeItem->attribute->name ?? '',
                                                'attribute_item' => $attributeItem->name,
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $data = Cart::with('cartItems', 'cartItems.cartItemAddons')->find($cart->id);
                return $this->success($data, "Cart Item Added");
            }
            return $this->error('Empty Cart Items');
        }catch (\Exception $exception){
            return $this->error($exception->getMessage().$exception->getLine());
        }
    }
}
