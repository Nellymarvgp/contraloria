<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficio extends Model
{
    use HasFactory;

    protected $table = 'beneficios';

    protected $fillable = [
        'beneficio',
        'fecha_beneficio',
    ];

    protected $casts = [
        'fecha_beneficio' => 'date',
    ];

    public function cargos()
    {
        return $this->hasMany(BeneficioCargo::class, 'beneficio_id');
    }
}
