<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function addToCart(Request $request){
        if(Auth::guest()){
           return  response()->json("You need to login first", 200);
        } 
        $id = Auth::user()->id;

        $cart = Cart::where('user_id', '=', $id)->first();

        if(!$cart){
            $cart = Cart::create(['user_id' =>$id]);            
        }

        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity');

        $CartItem = CartItem::where('cart_id','=',$cart->id)->where('product_id','=',$product_id)->first();
        
        if(!$CartItem){
            $data = [
                'cart_id' => $cart->id,
                'product_id' => $request->input('product_id'),
                'quantity' => $quantity,
            ];
    
            CartItem::create($data);    
        }
      
        $cart = Cart::where('user_id','=', $id)->first();
        
        return  response()->json("Successfully add to cart", 200); 
    }

    public function checkout(Request $request){
        if(Auth::guest()){
           return  response()->json("You need to login first", 200);
        } 
        $id = Auth::user()->id;

        DB::beginTransaction();
        $cartObject = Cart::where('user_id', '=', $id);
        $cart = $cartObject->first();
        $cartItems = CartItem::where('cart_id', '=', $cart->id)->get();
        $order = Order::create(['user_id' => $id]);
     
        foreach ($cartItems as $cartItem) {
            OrderDetail::create([
                'order_id' => $order->id,
                'quantity' => $cartItem->quantity,
                'product_id' => $cartItem->product_id,
            ]);
            $inventoryLock = DB::table('inventories')->where('product_id', '=', $cartItem->product_id)->lockForUpdate();
            $inventory = $inventoryLock->first();
            
            OrderDetail::create([
                'order_id' => $order->id,
                'quantity' => $cartItem->quantity,
                'product_id' => $cartItem->product_id,
            ]);
            if (($inventory->stock - $cartItem->quantity) < 0) {
                DB::rollback();
                return  response()->json("Product out of stock", 200);
            }
            $inventoryLock->update(['stock' => $inventory->stock -  $cartItem->quantity]); 
        }
        $cart->delete();

        DB::commit();
        
        return  response()->json("Successfully create an order", 200); 
    }
}
