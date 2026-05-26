<?php

namespace App\Http\Controllers\Functions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Backend\User;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\DB;

use App\Models\Backend\GalleryModel;

class FunctionControl extends Controller
{
    // Image Function
    public function galleryDelete(Request $request)
    {
        $data = GalleryModel::find($request->id);
        if($data){
            try{
                Storage::disk('public')->delete($data->image);
            }catch (\Exception $e){

            }
            GalleryModel::destroy($data->id);
            $arr['status'] = '200';
            $arr['icon'] = 'success';
            $arr['title'] = 'Success';
            $arr['text'] = '';
        }else{
            $arr['status'] = '500';
            $arr['icon'] = 'error';
            $arr['title'] = 'Something, went wrong !';
            $arr['text'] = '';
        }
        echo json_encode($arr);
    }

    public static function upload_image($image_file,$folder,$x,$y)
    {
        $filename = "$folder" . date('dmY-His');
        $file = $image_file;
        if ($file) 
        {
            $lg = Image::make($file->getRealPath());
            $ext = explode("/", $lg->mime())[1];
            $lg->resize($x,$y)->stream();
            $newLG = 'upload/'.$folder.'/' . $filename . '.' . $ext;
            $store = Storage::disk('public')->put($newLG, $lg);
            if($store)
            {
                return $newLG;
            }
        }
    }


    public static function upload_image2($image_file,$folder)
    {
        $arr['status'] = 200;
        $filename = "$folder" . date('dmY-His');
        $file = $image_file;
        if ($file) 
        {
            $lg = Image::make($file->getRealPath());
            $ext = explode("/", $lg->mime())[1];
            $size = $lg->filesize();

            $height = Image::make($file)->height();
            $width = Image::make($file)->width();
            $lg->resize($width, $height)->stream();
            $newLG = 'upload/'.$folder.'/' . $filename . '.' . $ext;
            $store = Storage::disk('public')->put($newLG, $lg);
            if($store)
            {
                $arr['image'] = $newLG;
                $arr['ext'] = $ext;
                $arr['size'] = $size;
            }
        }
        return $arr;
    }

    public static function upload_gallery($image_file,$key,$folder)
    {

        $arr['status'] = 200;
        $filename = date('dmY-His').$key;
        $file = $image_file;
        if ($file) 
        {
            $lg = Image::make($file->getRealPath());
            $ext = explode("/", $lg->mime())[1];
            $size = $lg->filesize();

            $height = Image::make($file)->height();
            $width = Image::make($file)->width();
            $lg->resize($width, $height)->stream();
            $newLG = 'upload/'.$folder.'/' . $filename . '.' . $ext;
            $store = Storage::disk('public')->put($newLG, $lg);
            if($store)
            {
                $arr['image'] = $newLG;
                $arr['ext'] = $ext;
                $arr['size'] = $size;
            }
        }
        return $arr;
    }

    public static function galleryDeleteAll($id)
    {
        $data = GalleryModel::find($id);
        if($data){
            try{
                Storage::disk('public')->delete($data->image);
            }catch (\Exception $e){

            }
            GalleryModel::destroy($data->id);
        }else{
        }
    }


}
