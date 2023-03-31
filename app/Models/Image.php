<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Class Image
 * @package App\Models
 *
 * @property string file_name
 * @property int weight
 * @property int image_parameter_id
 * @property int entity_id
 * @property string entity_type
 */
class Image extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'image';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_name',
        'weight',
        'image_parameter_id',
        'entity_id',
        'entity_type',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'file_name' => 'string',
        'weight' => 'integer',
        'entity_id' => 'integer',
        'entity_type' => 'string',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'delete_at'
    ];


    public function getFolderAttribute()
    {
        $folder = '';
        switch (true) {
            case ($this->product_id != null):
                $folder = $folder . 'products';
                break;
            case ($this->category_id != null):
                $folder = $folder . 'categories';
                break;
            case ($this->provider_id != null):
                $folder = $folder . 'providers';
                break;
            case ($this->publicity_id != null):
                $folder = $folder . 'publicity';
                break;
            case ($this->coupon_id != null):
                $folder = $folder . 'coupons';
            case ($this->coverage_id != null):
                $folder = $folder . 'coverages';
                break;
        }
        return $folder;
    }


    /**
     * Generate url for image
     */
    public function getUrlAttribute()
    {
        $folderName = $this->entity()->getModel()->table;
        $folder = "$folderName/{$this->entity_id}";
        $fileName = $this->file_name;
        if(config('constants.logicFileSystem') == 's3'){
            $url = Storage::disk('s3')->url("{$folder}/{$fileName}");
        }else{
            $url = asset("uploads/{$folder}/{$fileName}");
        }
        return $url;
    }

    public function getThumbnailUrlAttribute()
    {
        $folder = $this->folder.'-multimedia';
        $fileName = $this->file_name;
        if(config('constants.logicFileSystem') == 's3'){
            $url = Storage::disk('s3')->url("{$folder}/{$fileName}");
        }else{
            $url = asset("uploads/{$folder}/{$fileName}");
        }
       
        return $url;
    }

    /**
     * Get all of the owning models.
     */
    public function entity()
    {
        return $this->morphTo('entity');
    }


    public function getFilesFormatDropzone($product_id, $image_parameter_id)
    {
        $images = Image::query()
            ->where('product_id', '=', $product_id)
            ->where('image_parameter_id', '=', $image_parameter_id)
            ->orderBy('weight', 'asc')
            ->get();
        $aux_images = [];
        foreach ($images as $image) {
            $aux_images[] = [
                'id' => $image->id,
                'file_name' => $image->file_name,
                'url' => $image->url,
                'weight' => $image->weight
            ];
        }
        return $aux_images;
    }

    public function imageParameter()
    {
        return $this->belongsTo(ImageParameter::class, 'image_parameter_id');
    }
}