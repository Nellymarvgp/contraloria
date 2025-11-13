<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BenefitConfig extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'valor',
        'tipo',
        'activo'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'activo' => 'boolean'
    ];

    public static function getActive($codigo)
    {
        return self::where('codigo', $codigo)
            ->where('activo', true)
            ->first();
    }

    public static function getActiveValue($codigo)
    {
        $config = self::getActive($codigo);
        return $config ? $config->valor : 0;
    }
}
