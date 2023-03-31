<?php

namespace App\Http\Controllers\Multimedia;

use App\Http\Controllers\MyBaseController;
use App\Models\Image;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\ServerFactory;


class ImageController extends MyBaseController
{

    public function postSaveImages($images)
    {
        foreach ($images as $image) {
            $this->postSaveImage($image['image'], $image['params']);
        }
    }

    public function postSaveImage($image, $params)
    {
        $file = $image;
        $image = new Image();
        $image->weight = isset($params['weight']) ? $params['weight'] : 0;
        $image->product_id = isset($params['product_id']) && $params['product_id'] ? $params['product_id'] : null;
        $image->category_id = isset($params['category_id']) && $params['category_id'] ? $params['category_id'] : null;
        $image->provider_id = isset($params['provider_id']) && $params['provider_id'] ? $params['provider_id'] : null;
        $image->publicity_id = isset($params['publicity_id']) && $params['publicity_id'] ? $params['publicity_id'] : null;
        $image->coupon_id = isset($params['coupon_id']) && $params['coupon_id'] ? $params['coupon_id'] : null;
        $image->image_parameter_id = $params['imageParameterId'];
        $image->created_by = Auth::user()->id;
//        $img = InterventionImage::make($file->getRealPath());
//        $height = round($img->height() * 0.1);
//        $width = round($img->width() * 0.1);
//        $img->resize($height, $width, function ($constraint) {
//            $constraint->aspectRatio();
//        });
        $image->base64 = '';
        $folder = "/";
        switch (true) {
            case isset($params['product_id']) && $params['product_id']:
                $folder = $folder . 'products';
                break;
            case isset($params['category_id']) && $params['category_id']:
                $folder = $folder . 'categories';
                break;
            case (isset($params['provider_id']) && $params['provider_id']):
                $folder = $folder . 'providers';
                break;
            case (isset($params['publicity_id']) && $params['publicity_id']):
                $folder = $folder . 'publicity';
                break;
            case (isset($params['coupon_id']) && $params['coupon_id']):
                $folder = $folder . 'coupons';
                break;
        }
        $image->file_name = $this->saveImageAwsS3($file, $folder);
        $image->save();
        return $image;
    }

    public function saveImage($file, $directory)
    {
        $destinationPath = public_path() . $directory;
        $fileOriginalName = $file->getClientOriginalName();
        $extension = pathinfo($fileOriginalName, PATHINFO_EXTENSION);
        $fileName = uniqid() . '_' . $fileOriginalName;
        if ($file->move($destinationPath, $fileName)) {
            return $fileName;
        }
        return '';
    }

    /**
     * @param $file
     * @param $directory
     * @return string
     * @throws \Exception
     */
    public function saveFileAwsS3($file, $directory)
    {
        try {
            $fileOriginalName = $file->getClientOriginalName();
            $extension = pathinfo($fileOriginalName, PATHINFO_EXTENSION);
            $fileName = "_" . uniqid() . uniqid() . '.' . $extension;
            if(config('constants.logicFileSystem') == 's3'){
                Storage::disk(config('constants.logicFileSystem'))->put("$directory/$fileName", file_get_contents($file), 'public');
            }else{
            Storage::disk(config('constants.logicFileSystem'))->put("uploads/$directory/$fileName", file_get_contents($file), 'public');
            }
            return $fileName;
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [$file->getClientOriginalName(),]);
            throw $e;
        }
    }

    public function deleteImage($id)
    {
        try {
            $image = Image::find($id);
            $folder = "/" . $image->folder;
            $path = $folder . '/' . $image->file_name;

            if (Storage::disk(config('constants.logicFileSystem'))->exists($path)) {
                Storage::disk(config('constants.logicFileSystem'))->delete($path);
            }

            if ($image->delete()) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function show($path)
    {
//        $server = ServerFactory::create([
//            'response' => new LaravelResponseFactory(app('request')),
//            'source' => $filesystem->getDriver(),
//            'cache' => $filesystem->getDriver(),
//            'cache_path_prefix' => '.cache',
//            'base_url' => 'img',
//        ]);

        $server = ServerFactory::create([
            // Cache filesystem
            'cache' => Storage::disk(config('constants.logicFileSystem'))->getDriver(),
            // Cache filesystem path prefix
            'cache_path_prefix' => '.cache',
            // Source filesystem
            'source' => Storage::disk(config('constants.logicFileSystem'))->getDriver(),
            // Response
            'response' => new LaravelResponseFactory(),
//            'base_url' => 'img',
            // Default image manipulations
            'defaults' => [
                'q' => 80,
            ]
        ]);

        return $server->getImageResponse($path, request()->all());
    }
}
