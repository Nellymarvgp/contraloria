<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GrupoCargo;

class GrupoCargoApiController extends Controller
{
    /**
     * Obtener grupos de cargo filtrados por tipo de cargo
     */
    public function obtenerPorTipo($tipo_cargo)
    {   
        // Mapear al valor de categoría
        $categoria = $this->mapearTipoACategoria($tipo_cargo);
        if (!$categoria) {
            return response()->json([], 200);
        }
        
        // Consultar grupos activos
        $grupos = $this->obtenerGruposPorCategoria($categoria);
        return response()->json($grupos, 200);
    }
    
    /**
     * Obtener grupos de cargo por tipo para el select
     */
    public function getGruposPorTipo(Request $request)
    {   
        // Obtener el tipo de cargo desde el query parameter
        $tipo = $request->query('tipo');
        if (empty($tipo)) {
            return response()->json(['error' => 'Se requiere el parámetro tipo'], 400);
        }
        
        // Mapear al valor de categoría
        $categoria = $this->mapearTipoACategoria($tipo);
        if (!$categoria) {
            return response()->json([], 200);
        }
        
        // Consultar grupos activos
        $grupos = $this->obtenerGruposPorCategoria($categoria);
        return response()->json($grupos, 200);
    }
    
    /**
     * Mapea el tipo de cargo del formulario a la categoría en la base de datos
     */
    private function mapearTipoACategoria($tipo)
    {
        $mapeoCategoria = [
            'bachiller' => 'administrativo_bachiller',
            'tecnico_superior' => 'tecnico_superior',
            'profesional_universitario' => 'profesional_universitario'
        ];
        
        return $mapeoCategoria[$tipo] ?? null;
    }
    
    /**
     * Obtiene los grupos de cargo activos por categoría
     */
    private function obtenerGruposPorCategoria($categoria)
    {
        return GrupoCargo::where('categoria', $categoria)
            ->where('estado', 1)
            ->select('id', 'descripcion')
            ->get();
    }
}
