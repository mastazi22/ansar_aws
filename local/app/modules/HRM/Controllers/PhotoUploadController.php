<?php

namespace App\modules\HRM\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;

class PhotoUploadController extends Controller
{

    //
    public function uploadPhotoSignature(){

        return View::make('HRM::EntryForm.upload_photo_signature');

    }
    public function uploadOriginalInfo(){


        return View::make('HRM::EntryForm.upload_original_info');
    }

    public function storePhoto(Request $request){
        $rules = [
            'file'=>'mimes:jpeg,jpg'
        ];
        $this->validate($request,$rules);
        $path = storage_path('data/photo');
        $file = $request->file('file');
        if(File::exists($path.'/'.$file->getClientOriginalName())){
            File::delete($path.'/'.$file->getClientOriginalName());
        }
        try {
            Image::make($file)->save($path . '/' . $file->getClientOriginalName());
        }catch (\Exception $e){

        }
    }
    public function storeSignature(Request $request){
        $rules = [
            'file'=>'mimes:jpeg,jpg'
        ];
        $this->validate($request,$rules);
        $path = storage_path('data/signature');
        $file = $request->file('file');
        if(File::exists($path.'/'.$file->getClientOriginalName())){
            File::delete($path.'/'.$file->getClientOriginalName());
        }
        try {
            Image::make($file)->save($path . '/' . $file->getClientOriginalName());
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }

    public function storeOriginalFrontInfo(Request $request){
        $rules = [
            'file'=>'mimes:jpeg,jpg'
        ];
        $this->validate($request,$rules);
        $path = storage_path('data/orginalinfo/frontside');
        $file = $request->file('file');
        if(File::exists($path.'/'.$file->getClientOriginalName())){
            File::delete($path.'/'.$file->getClientOriginalName());
        }
        try {
            Image::make($file)->save($path . '/' . $file->getClientOriginalName());
        }catch (\Exception $e){

        }
    }
    public function storeOriginalBackInfo(Request $request){
        $rules = [
            'file'=>'mimes:jpeg,jpg'
        ];
        $this->validate($request,$rules);
        $path = storage_path('data/orginalinfo/backside');
        $file = $request->file('file');
        if(File::exists($path.'/'.$file->getClientOriginalName())){
            File::delete($path.'/'.$file->getClientOriginalName());
        }
        try {
            Image::make($file)->save($path . '/' . $file->getClientOriginalName());
        }catch (\Exception $e){

        }
    }
}
