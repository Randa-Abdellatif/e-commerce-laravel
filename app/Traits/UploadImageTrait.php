<?php
namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
trait UploadImageTrait
{
    public function UploadImage(Request $request, $folderName){
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            try{
            $file->move($folderName, $filename);
            //$photoUrl =url('/',$filename);
           }catch(FileException $e){
            dd($e);
        }
        //return response()->json(['url' =>$photoUrl], 200);
            return $filename;

            /*$image = $request->file('image')->getClientOriginalName(); //asset('imgs/' .$image->path)
            $path = $request->file('image')->storeAs($folderName, $image,'img');
            return $path;*/
    }
}
