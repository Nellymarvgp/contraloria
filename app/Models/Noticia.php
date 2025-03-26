<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'slug',
        'contenido',
        'imagen',
        'user_id',
        'publicado'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
