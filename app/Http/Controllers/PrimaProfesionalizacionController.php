<?php

namespace App\Http\Controllers;

use App\Models\PrimaProfesionalizacion;
use Illuminate\Http\Request;

class PrimaProfesionalizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $primas = PrimaProfesionalizacion::all();
        return view('prima-profesionalizacion.index', compact('primas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('prima-profesionalizacion.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'porcentaje' => 'required|numeric|min:0|max:100',
        ]);
        
        PrimaProfesionalizacion::create([
            'descripcion' => $request->descripcion,
            'porcentaje' => $request->porcentaje,
            'estado' => $request->has('estado') ? 1 : 0,
        ]);
        
        return redirect()->route('prima-profesionalizacion.index')
            ->with('success', 'Prima de profesionalización creada correctamente.');
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
        $prima = PrimaProfesionalizacion::findOrFail($id);
        return view('prima-profesionalizacion.edit', compact('prima'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'porcentaje' => 'required|numeric|min:0|max:100',
        ]);
        
        $prima = PrimaProfesionalizacion::findOrFail($id);
        $prima->update([
            'descripcion' => $request->descripcion,
            'porcentaje' => $request->porcentaje,
            'estado' => $request->has('estado') ? 1 : 0,
        ]);
        
        return redirect()->route('prima-profesionalizacion.index')
            ->with('success', 'Prima de profesionalización actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $prima = PrimaProfesionalizacion::findOrFail($id);
        $prima->delete();
        
        return redirect()->route('prima-profesionalizacion.index')
            ->with('success', 'Prima de profesionalización eliminada correctamente.');
    }
}
