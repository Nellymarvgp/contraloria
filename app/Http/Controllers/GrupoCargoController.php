<?php

namespace App\Http\Controllers;

use App\Models\GrupoCargo;
use Illuminate\Http\Request;

class GrupoCargoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grupos = GrupoCargo::all();
        return view('grupos-cargos.index', compact('grupos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = [
            'administrativo_bachiller' => 'Personal administrativo o bachilleres',
            'tecnico_superior' => 'Personal técnico superior universitario',
            'profesional_universitario' => 'Personal profesional universitario',
        ];
        return view('grupos-cargos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'categoria' => 'required|in:administrativo_bachiller,tecnico_superior,profesional_universitario',
        ]);
        
        GrupoCargo::create([
            'descripcion' => $request->descripcion,
            'estado' => $request->has('estado') ? 1 : 0,
            'categoria' => $request->categoria,
        ]);
        
        return redirect()->route('grupos-cargos.index')
            ->with('success', 'Grupo de cargo creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $grupo = GrupoCargo::findOrFail($id);
        $categorias = [
            'administrativo_bachiller' => 'Personal administrativo o bachilleres',
            'tecnico_superior' => 'Personal técnico superior universitario',
            'profesional_universitario' => 'Personal profesional universitario',
        ];
        return view('grupos-cargos.edit', compact('grupo', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'categoria' => 'required|in:administrativo_bachiller,tecnico_superior,profesional_universitario',
        ]);
        
        $grupo = GrupoCargo::findOrFail($id);
        $grupo->update([
            'descripcion' => $request->descripcion,
            'estado' => $request->has('estado') ? 1 : 0,
            'categoria' => $request->categoria,
        ]);
        
        return redirect()->route('grupos-cargos.index')
            ->with('success', 'Grupo de cargo actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $grupo = GrupoCargo::findOrFail($id);
        $grupo->delete();
        
        return redirect()->route('grupos-cargos.index')
            ->with('success', 'Grupo de cargo eliminado correctamente.');
    }
    
    /**
     * Obtiene los grupos de cargo filtrados por tipo.
     */
    public function getGruposPorTipo(string $tipo)
    {
        $mapeoCategoria = [
            'bachiller' => 'administrativo_bachiller',
            'tecnico_superior' => 'tecnico_superior',
            'profesional_universitario' => 'profesional_universitario'
        ];
        
        $categoria = $mapeoCategoria[$tipo] ?? null;
        if (!$categoria) {
            return response()->json([], 200);
        }
        
        $grupos = GrupoCargo::where('categoria', $categoria)
            ->where('estado', 1)
            ->select('id', 'descripcion')
            ->get();
            
        return response()->json($grupos, 200);
    }
}
