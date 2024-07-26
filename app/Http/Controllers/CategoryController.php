<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Traits\UploadImageTrait;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    use ApiResponseTrait;
    use UploadImageTrait;

    //get all category
    public function index(){
        try{
        $categories = Category::paginate(10);
        return $this->apiResponse($categories,"ok", 200);
    }
    catch(\Exception $e){return response('Exception');}
    catch(\Error $e){return response('Error');}
     }


     //get category by id
     public function show($id){
        try{
        $category = Category::find($id);
        if($category){
        return $this->apiResponse($category,"ok", 200);
        }
        return $this->apiResponse(null,"not found", 404);
    }
    catch(\Exception $e){return response('Exception');}
    catch(\Error $e){return response('Error');}
     }


    //store category
    public function store(Request $request){
        try{

        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:categories|max:255',
            'image' => 'required',
        ]);
        if ($validator->fails()){
            return $this->apiResponse(null,$validator->errors(), 400);
        }
        $path = $this->UploadImage($request,'assets/uploads/category/');
        $category = Category::create([
            'image'=>$path,
            'title'=>$request->title
           ]);
         if($category){
             return $this->apiResponse($category,"ok", 201);
         }
         return $this->apiResponse(null,"not save", 400);
        }
        catch(\Exception $e){return response('Exception');}
        catch(\Error $e){return response('Error');}

    }

    //update category
    public function update($id,Request $request ){
        try{
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:categories|max:255',
            'image' => 'file',
        ]);
        if ($validator->fails()){
            return $this->apiResponse(null,$validator->errors(), 400);
        }

        $category = Category::find($id);
        if(!$category){
            return $this->apiResponse(null,"not found", 404);
        }
        $category->title=$request->title;
        if($request->hasFile('image')){
            $path = 'assets/uploads/category/'. $category->image;
            if(File::exists($path)){
                File::delete($path);
            }
        $filename = $this->UploadImage($request,'assets/uploads/category/');
        $category->image = $filename;

        }
        $category->save();
        return $this->apiResponse($category,"updated", 201);
    }
    catch(\Exception $e){return response('Exception');}
    catch(\Error $e){return response('Error');}

    }

    //delete category
    public function delete($id){
        try{
        $category = Category::find($id);
        if(!$category){
            return $this->apiResponse(null,"not found", 404);
        }
        $category->delete();
            return $this->apiResponse(null,"deleted", 200);
        }
        catch(\Exception $e){return response('Exception');}
        catch(\Error $e){return response('Error');}
    }

}
