<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduccion extends Model
{
    use HasFactory;

    protected $table = 'deducciones';
    protected $fillable = ['nombre', 'tipo', 'descripcion', 'campo', 'porcentaje', 'es_fijo', 'monto_fijo', 'activo'];

    protected $casts = [
        'porcentaje' => 'float',
        'monto_fijo' => 'float',
        'es_fijo' => 'boolean',
        'activo' => 'boolean',
    ];
    
    // Métodos para facilitar la búsqueda de conceptos específicos
    
    // Obtener una deducción activa por nombre
    public static function findActiveDeduction($nombre)
    {
        return self::where('nombre', $nombre)
            ->where('tipo', 'deduccion')
            ->where('activo', true)
            ->first();
    }
    
    // Obtener un beneficio activo por código
    public static function findActiveBenefit($codigo)
    {
        return self::where('nombre', $codigo)
            ->where('tipo', 'beneficio')
            ->where('activo', true)
            ->first();
    }
    
    // Obtener un parámetro activo por campo
    public static function findActiveParameter($campo)
    {
        return self::where('campo', $campo)
            ->where('tipo', 'parametro')
            ->where('activo', true)
            ->first();
    }
    
    // Obtener el valor de un concepto (sea porcentaje o monto fijo)
    public function getValor()
    {
        if ($this->es_fijo) {
            return $this->monto_fijo;
        } else {
            return $this->porcentaje;
        }
    }
}
