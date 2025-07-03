<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'tipo_cargo'];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}
