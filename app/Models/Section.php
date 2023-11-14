<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class TypeProviders
 * @package App\Http\Models
 * @property MorphMany images
 * @property BelongsTo company
 * @property int id
 * @property string description
 * @property string level
 * @property string status
 */
class Section extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'secciones';

    protected $guarded = [];
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';
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
        'name',
        'status',
        'value',
        'total_points',
        'empresas_id',
        'proveedor_id',
        'estandar',
        'is_used'

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'status' => 'string',
        'value' => 'float',
        'total_points' => 'float',
        'empresas_id'=>'integer',
        'proveedor_id' => 'integer',
        'standar' => 'string',
        'is_used' => 'string'



    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'secciones_id')->where('status', 'ACTIVE');
    }

    public function sectionsTypeProvider()
    {
        return $this->belongsToMany(TypeProvider::class, 'secciones_tipo_proveedor',
            'secciones_id', 'tipo_proveedor_id');
    }
    public function proveedor()
    {
        return $this->belongsTo(Provider::class, 'proveedor_id');
    }
}
