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
        
        'USER' => 'Usuarios',
        
        'DATOS_CERTIFICADO' => 'Certificado'
    ];

    const TYPE_COMPANY = 'COMPANY';
    const TYPE_USER = 'USER';
    const DATOS_CERTIFICADO = 'DATOS_CERTIFICADO';

}