<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $fillable = ['nombre', 'color', 'descripcion'];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}
