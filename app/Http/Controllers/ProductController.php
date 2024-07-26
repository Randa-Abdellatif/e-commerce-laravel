<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
//use Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Traits\UploadImageTrait;


class ProductController extends Controller
{
    use ApiResponseTrait;
    use UploadImageTrait;

     //get all products
    public function index(){
        try{

        $product = Product::paginate(10);
        return $this->apiResponse($product,"ok", 200);
     }catch(\Exception $e){
            return response('Exception');
     }catch(\Error $e){
        return response('error');
    }

    }

     //get product by id
    public function show($id){
        try{
        $product = Product::find($id);
        if(!$product){
            return $this->apiResponse(null,'there is no product',404);
        } else return $this->apiResponse($product,"ok", 200);
    }
    catch(\Exception $e){return response('Exception');}
    catch(\Error $e){return response('Error');}
    }

     //store product
    public function store(Request $request){
        try{
        $validator = Validator::make($request->all(),[
            'title'=>'required',
            'description'=>'required',
            'image'=>'required',
            'price'=>'required|numeric',
            'quantity'=>'required|numeric',
            'cat_id'=>'required|numeric',
        ]);

        if ($validator->fails()){
            return $this->apiResponse(null,$validator->errors(), 400);
        }

        $product = new Product();
        $product->title=$request->title;
        $product->description=$request->description;
        $product->price=$request->price;
        $product->quantity=$request->quantity;
        $product->cat_id=$request->cat_id;

        if($request->hasFile('image')){
            $path = 'assets/uploads/product/'. $product->image;
            if(File::exists($path)){
                File::delete($path);
            }
            $filename = $this->UploadImage($request,'assets/uploads/product/');
            $product->image = $filename;
        }

        $product->save();
        return $this->apiResponse($product,'added',201);
    }
    catch(\Exception $e){return response('Exception');}
    catch(\Error $e){return response('Error');}

    }

    //update update
    public function update($id, Request $request){
        try{
        Validator::make($request->all(),[
            'title'=>'required',
            'description'=>'required',
            'image'=>'file',
            'price'=>'numeric',
            'quantity'=>'numeric',
            'cat_id'=>'numeric',
        ]);

        $product =Product::find($id);
        if(!$product){
            return $this->apiResponse(null,'not found',404);
        }
        $product->title=$request->title;
        $product->description=$request->description;
        if($request->price)$product->price=$request->price;
        if($request->quantity)$product->quantity=$request->quantity;
        if($request->cat_id)$product->cat_id=$request->cat_id;

        if($request->hasFile('image')){
            $path = 'assets/uploads/product/'. $product->image;
            if(File::exists($path)){
                File::delete($path);
            }
            $filename = $this->UploadImage($request,'assets/uploads/product/');
            $product->image = $filename;
        }

        $product->save();
        return $this->apiResponse($product,"updated", 201);
    }
    catch(\Exception $e){return response('Exception');}
    catch(\Error $e){return response('Error');}
    }

     //delete category
    public function destroy($id){
        try{
        $product = Product::find($id);
        if($product){
            $product->delete();
            return $this->apiResponse(null,"deleted", 200);
        } else return $this->apiResponse(null,"not found", 404);
    }
    catch(\Exception $e){return response('Exception');}
    catch(\Error $e){return response('Error');}
    }




}
