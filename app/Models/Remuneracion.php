<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Remuneracion extends Model
{
    protected $fillable = [
        'nivel_rango_id',
        'grupo_cargo_id',
        'tipo_cargo',
        'valor',
        'estado'
    ];

    /**
     * Get the nivel rango associated with the remuneracion.
     */
    public function nivelRango()
    {
        return $this->belongsTo(NivelRango::class);
    }

    /**
     * Get the grupo cargo associated with the remuneracion.
     */
    public function grupoCargo()
    {
        return $this->belongsTo(GrupoCargo::class);
    }
}
