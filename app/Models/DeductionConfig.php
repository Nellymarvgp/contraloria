<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeductionConfig extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'porcentaje',
        'activo'
    ];

    protected $casts = [
        'porcentaje' => 'decimal:4',
        'activo' => 'boolean'
    ];

    public static function getActive($codigo)
    {
        return self::where('codigo', $codigo)
            ->where('activo', true)
            ->first();
    }

    public static function getActivePercentage($codigo)
    {
        $config = self::getActive($codigo);
        return $config ? $config->porcentaje : 0;
    }
}
