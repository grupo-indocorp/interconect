<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'facilidad_tecnica',
        'departamento_codigo',
        'provincia_codigo',
        'distrito_codigo',
        'cliente_id'
    ];
}
