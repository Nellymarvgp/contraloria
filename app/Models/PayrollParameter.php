<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollParameter extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'campo',
        'valor_defecto',
        'activo'
    ];

    protected $casts = [
        'valor_defecto' => 'decimal:2',
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
        return $config ? $config->valor_defecto : 0;
    }

    public static function getActiveByField($campo)
    {
        return self::where('campo', $campo)
            ->where('activo', true)
            ->first();
    }

    public static function getValueByField($campo)
    {
        $config = self::getActiveByField($campo);
        return $config ? $config->valor_defecto : 0;
    }
}
