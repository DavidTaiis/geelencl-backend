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
class TestEnd extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'test_end';

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
        'id_test',
        'id_proveedor',
        'comunication',
        'date_end',
        'email',
        'observation'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_test' => 'integer',
        'id_proveedor' => 'integer',
        'comunication' => 'string',
        'date_end' => 'string',
        'email' => 'string',
        'observation'=> 'string'
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
}