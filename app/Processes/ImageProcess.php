<?php

namespace App\Processes;

use App\Http\Controllers\Multimedia\ImageController;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageProcess
{

    public function __construct()
    {
    }

    public function saveImage($user, $image, $imageModel, $imageParameterId)
    {
        $imageController = new ImageController();
        $folderName = "{$user->getTable()}/{$user->id}";
        $fileName = $imageController->saveFileAwsS3($image, $folderName);
        if ($imageModel != null) {
            if (config('constants.logicFileSystem') == 's3') {
                $path = "{$user->getTable()}/{$user->id}/{$imageModel->file_name}";
            } else {
                $path = "uploads/{$user->getTable()}/{$user->id}/{$imageModel->file_name}";
            }
            if (Storage::disk(config('constants.logicFileSystem'))->exists($path)) {
                Storage::disk(config('constants.logicFileSystem'))->delete($path);
            }
        } else {
            $imageModel = new Image;
        }
        $imageModel->file_name = $fileName;
        $imageModel->image_parameter_id = $imageParameterId;
        $user->images()->save($imageModel);

    }
}
