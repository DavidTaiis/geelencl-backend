<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Image;
use App\Models\ImageParameter;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UploadImagesProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $url;
    private $productId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url , $productId)
    {
        $this->url = $url;
        $this->productId = $productId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $product = Product::find($this->productId);
        $imageParameter = ImageParameter::query()
            ->where('entity', ImageParameter::TYPE_PRODUCT)
            ->where('name', config('constants.nameImageParameterProduct'))->first();
        $imageModel = Image::query()
            ->where('entity_id', $this->productId)
            ->where('image_parameter_id', $imageParameter->id)->first();

        $folderName = "{$product->getTable()}/{$product->id}";
        $fileName = $this->saveFile($this->url, $folderName);
        if ($fileName != false) {
            if ($imageModel != null) {
                if (config('constants.logicFileSystem') === 's3') {
                    $path = "{$product->getTable()}/{$product->id}/{$imageModel->file_name}";
                } else {
                    $path = "uploads/{$product->getTable()}/{$product->id}/{$imageModel->file_name}";
                }
               /*  if (Storage::disk(config('constants.logicFileSystem'))->exists($path)) {
                    Storage::disk(config('constants.logicFileSystem'))->delete($path);
                } */
            } else {
                $imageModel = new Image;
            }
            $imageModel->file_name = $fileName;
            $imageModel->image_parameter_id = $imageParameter->id;
            $product->images()->save($imageModel);
        }
    }
    public function saveFile($url, $directory)
    {
        try {
            $fileOriginalName = basename($url);
            $extension = pathinfo($fileOriginalName, PATHINFO_EXTENSION);
            $fileName = "_" . uniqid() . uniqid() . '.' . $extension;
            if (config('constants.logicFileSystem') === 's3') {
                Storage::disk(config('constants.logicFileSystem'))->put("$directory/$fileName", file_get_contents($url), 'public');
            } else {
                Storage::disk(config('constants.logicFileSystem'))->put("uploads/$directory/$fileName", file_get_contents($url), 'public');
            }
            return $fileName;
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return false;
        }
    }

}