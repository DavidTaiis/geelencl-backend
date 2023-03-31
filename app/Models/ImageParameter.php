<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageParameter extends Model
{

    protected $table = 'image_parameter'; //Database table used by the model


    public static $extensions = [
        '.jpg' => 'JPG',
        '.png' => 'PNG',
        '.gif' => 'GIF',
        '.svg' => 'SVG'
    ];
    public static $entities = [
        
        'COMPANY' => 'Empresa',
        'LEVEL' => 'Niveles',
        'USER' => 'Usuarios',
        'PRODUCT' => 'Productos',
        'UNIT' => 'Unidades',
        'CATEGORY' => 'Categoría',
        'MISSION' => 'Misiones',
        'OBJECTIVE' => 'Objetivos'
    ];

    const TYPE_COMPANY = 'COMPANY';
    const TYPE_LEVEL = 'LEVEL';
    const TYPE_USER = 'USER';
    const TYPE_PRODUCT = 'PRODUCT';
    const TYPE_UNIT = 'UNIT';
    const TYPE_CATEGORY = 'CATEGORY';
    const TYPE_MISSION = 'MISSION';
    const TYPE_OBJECTIVE = 'OBJECTIVE';
}