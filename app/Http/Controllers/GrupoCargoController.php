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
        return view('grupos-cargos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        
        GrupoCargo::create([
            'descripcion' => $request->descripcion,
            'estado' => $request->has('estado') ? 1 : 0,
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
        return view('grupos-cargos.edit', compact('grupo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        
        $grupo = GrupoCargo::findOrFail($id);
        $grupo->update([
            'descripcion' => $request->descripcion,
            'estado' => $request->has('estado') ? 1 : 0,
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
}
