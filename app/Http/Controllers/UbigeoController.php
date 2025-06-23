<?php

namespace App\Http\Controllers;

use App\Models\Distrito;
use App\Models\Provincia;

class UbigeoController extends Controller
{
    public function provincias($departamento)
    {
        return response()->json(Provincia::where('departamento_codigo', $departamento)->get());
    }

    public function distritos($departamento, $provincia)
    {
        return response()->json(Distrito::where([
            'departamento_codigo' => $departamento,
            'provincia_codigo' => $provincia
        ])->get());
    }
}