<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VacacionesPorDisfrute extends Model
{
    protected $table = 'vacaciones_por_disfrute';

    protected $fillable = [
        'empleado_id',
        'dias_por_disfrute',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
