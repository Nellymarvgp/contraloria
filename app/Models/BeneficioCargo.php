<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeneficioCargo extends Model
{
    use HasFactory;

    protected $table = 'beneficios_cargo';

    protected $fillable = [
        'beneficio_id',
        'cargo',
        'porcentaje',
        'valor',
    ];

    protected $casts = [
        'porcentaje' => 'decimal:2',
        'valor' => 'decimal:2',
    ];

    public function beneficio()
    {
        return $this->belongsTo(Beneficio::class, 'beneficio_id');
    }
}
