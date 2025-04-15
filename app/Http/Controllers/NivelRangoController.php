<?php

namespace App\Http\Controllers;

use App\Models\NivelRango;
use Illuminate\Http\Request;

class NivelRangoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $niveles = NivelRango::all();
        return view('niveles-rangos.index', compact('niveles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('niveles-rangos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        
        NivelRango::create([
            'descripcion' => $request->descripcion,
            'estado' => $request->has('estado') ? 1 : 0,
        ]);
        
        return redirect()->route('niveles-rangos.index')
            ->with('success', 'Nivel de rango creado correctamente.');
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
        $nivel = NivelRango::findOrFail($id);
        return view('niveles-rangos.edit', compact('nivel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        
        $nivel = NivelRango::findOrFail($id);
        $nivel->update([
            'descripcion' => $request->descripcion,
            'estado' => $request->has('estado') ? 1 : 0,
        ]);
        
        return redirect()->route('niveles-rangos.index')
            ->with('success', 'Nivel de rango actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $nivel = NivelRango::findOrFail($id);
        $nivel->delete();
        
        return redirect()->route('niveles-rangos.index')
            ->with('success', 'Nivel de rango eliminado correctamente.');
    }
}
