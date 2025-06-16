<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Remuneracion;

class RemuneracionApiController extends Controller
{
    // Endpoint para obtener el sueldo según los parámetros seleccionados
    public function obtener(Request $request)
    {
        $tipo_personal = $request->input('tipo_personal');
        if ($tipo_personal === 'administracion_publica') {
            $rem = Remuneracion::where('tipo_personal', 'administracion_publica')
                ->where('nivel_rango_id', $request->input('nivel_rango_id'))
                ->where('grupo_cargo_id', $request->input('grupo_cargo_id'))
                ->where('tipo_cargo', $request->input('tipo_cargo'))
                ->first();
        } elseif ($tipo_personal === 'obreros') {
            $rem = Remuneracion::where('tipo_personal', 'obreros')
                ->where('clasificacion', $request->input('clasificacion'))
                ->where('grado', $request->input('grado'))
                ->first();
        } else {
            return response()->json(['error' => 'Tipo de personal inválido'], 400);
        }

        if ($rem) {
            return response()->json(['valor' => $rem->valor]);
        } else {
            return response()->json(['error' => 'No se encontró remuneración'], 404);
        }
    }
}
