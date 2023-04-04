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
class Provider extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'proveedor';

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
        'id',
        'users_id',
        'comercial_name',
        'legal_name',
        'email',
        'direction',
        'phone_number',
        'status',
        'qualification'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'comercial_name' => 'string',
        'email' => 'string',
        'direction' => 'string',
        'phone_number'=> 'string',
        'status' => 'string',
        'qualification' => 'string'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];   
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}