<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    use ApiResponseTrait;

    //get all orders
    public function index(){
        try{
        $orders = Order::paginate(20);
            foreach($orders as $order){
                foreach($order->items as $order_items){
                    $product = Product::where('id', $order_items->product_id)->pluck('title');
                    $order_items->product_name = $product['0'];
                }
            }
            return $this->apiResponse($orders,"ok", 200);
    }
   catch(\Exception $e){return response('Exception');}
    catch(\Error $e){return response('Error');}

    }


    //get order id
    public function show($id){
        try{
        $order = Order::find($id);
        if(!$order){
            return $this->apiResponse(null,"not found", 404);
            }
        return $this->apiResponse($order,"ok", 200);
    }
    catch(\Exception $e){return response('Exception');}
     catch(\Error $e){return response('Error');}
    }

    //create order
    public function store(Request $request){
        try{
        $request->validate([
            'order_items'=>'required',
            'phone'=>'required',
            'address' => 'required',
        ]);

        $total_price = 0;

        foreach($request->order_items as $order_items){
            $product = Product::where('id', $order_items['product_id'])->first();
            if(!$product){
                return $this->apiResponse(null,"not found", 404);
            }
            if($product->quantity < $order_items['quantity']){
                return $this->apiResponse(null,"out of stock", 404);
            }
            $total_price += ( $order_items['quantity']*$product->price );
        }

        $order = new Order();
        $order->user_id = Auth::id();
        $order->address = $request->address;
        $order->total_price = $total_price;
        $order->phone = $request->phone;
        $order->save();

        foreach($request->order_items as $order_items){
            $items = new OrderItems();
            $items->order_id = $order->id;
            $items->price = ($product->price*$order_items['quantity']);
            $items->product_id = $order_items['product_id'];
            $items->quantity = $order_items['quantity'];
            $items->save();

            $product = Product::where('id', $order_items['product_id'])->first();
            $product->quantity -= $order_items['quantity'];
            $product->save();
        }
        return $this->apiResponse($order,'order is added', 201);
   // }
}
catch(\Exception $e){return response('Exception');}
catch(\Error $e){return response('Error');}
    }


    //get_user_orders
    public function get_user_orders($id){
        try{
           if(Auth::id() == $id || Auth::user()->is_admin == 1){

        $orders = Order::with('items')
        ->where('user_id', $id)
        ->get();

        if($orders){
            foreach($orders as $order){
                foreach($order->items as $order_items){
                $product = Product::where('id', $order_items->product_id)->pluck('title');
                $order_items->product_name = $product['0'];
            }
            }
            return $this->apiResponse($orders,"ok",200);
        } else return $this->apiResponse(null,'no orders found for this user',404);
    }else{return response("not accepted");}
    }
    catch(\Exception $e){return response('Exception');}
     catch(\Error $e){return response('Error');}
    }

    //change_order_status
    public function change_order_status($id, Request $request){
        try{
        $order = Order::find($id);
        if($order){
            $order->update(['status'=>$request->status]);
            return $this->apiResponse($order,'status changed',200);
        } else return $this->apiResponse(null,'order no found',404);
    }
    catch(\Exception $e){return response('Exception');}
     catch(\Error $e){return response('Error');}
    }

}
