<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Remuneracion;

class RemuneracionApiController extends Controller
{
    // Endpoint para obtener el sueldo según los parámetros seleccionados
    public function obtener(Request $request)
    {
        // Consulta directa por grupo_cargo_id si se proporciona
        if ($request->has('grupo_cargo_id')) {
            $grupo_cargo_id = $request->input('grupo_cargo_id');
            $rem = Remuneracion::where('grupo_cargo_id', $grupo_cargo_id)
                ->where('tipo_personal', 'administracion_publica')
                ->first();
            
            if ($rem) {
                return response()->json(['valor' => $rem->valor]);
            } else {
                return response()->json(['error' => 'No se encontró remuneración para este grupo'], 404);
            }
        }
        
        // Consulta tradicional basada en todos los criterios
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
    
    /**
     * Obtener remuneración directamente por el ID de grupo de cargo
     */
    public function obtenerPorGrupo($grupo_cargo_id)
    {
        // Buscar remuneración para este grupo_cargo_id
        $rem = Remuneracion::where('grupo_cargo_id', $grupo_cargo_id)
            ->where('tipo_personal', 'administracion_publica')
            ->first();
            
        if ($rem) {
            return response()->json(['valor' => $rem->valor]);
        }
        
        // Si no encontramos una remuneración específica, intentamos obtener por el grupo de cargo
        $grupoCargo = \App\Models\GrupoCargo::find($grupo_cargo_id);
        if (!$grupoCargo) {
            return response()->json(['error' => 'Grupo de cargo no encontrado'], 404);
        }
        
        // Buscamos la remuneración relacionada con este grupo de cargo
        $rem = Remuneracion::where('grupo_cargo_id', $grupo_cargo_id)
            ->first();
            
        if ($rem) {
            return response()->json(['valor' => $rem->valor]);
        } else {
            return response()->json(['error' => 'No se encontró remuneración para este grupo de cargo'], 404);
        }
    }
}
