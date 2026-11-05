<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{

    protected $fillable = [
        'codigo',
        'titulo',
        'categoria',
        'estado',
        'descripcion',
        'observaciones',
        'fecha_registro',
    ];

    protected function casts(): array
    {
        return [
            'fecha_registro' => 'date',
        ];
    }
}