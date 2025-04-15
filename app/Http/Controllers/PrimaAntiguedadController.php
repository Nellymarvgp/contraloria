<?php

namespace App\Http\Controllers;

use App\Models\PrimaAntiguedad;
use Illuminate\Http\Request;

class PrimaAntiguedadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $primas = PrimaAntiguedad::all();
        return view('prima-antiguedad.index', compact('primas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('prima-antiguedad.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'anios' => 'required|integer|min:1',
            'porcentaje' => 'required|numeric|min:0|max:100',
        ]);
        
        PrimaAntiguedad::create([
            'anios' => $request->anios,
            'porcentaje' => $request->porcentaje,
            'estado' => $request->has('estado') ? 1 : 0,
        ]);
        
        return redirect()->route('prima-antiguedad.index')
            ->with('success', 'Prima de antigüedad creada correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $prima = PrimaAntiguedad::findOrFail($id);
        return view('prima-antiguedad.edit', compact('prima'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'anios' => 'required|integer|min:1',
            'porcentaje' => 'required|numeric|min:0|max:100',
        ]);
        
        $prima = PrimaAntiguedad::findOrFail($id);
        $prima->update([
            'anios' => $request->anios,
            'porcentaje' => $request->porcentaje,
            'estado' => $request->has('estado') ? 1 : 0,
        ]);
        
        return redirect()->route('prima-antiguedad.index')
            ->with('success', 'Prima de antigüedad actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $prima = PrimaAntiguedad::findOrFail($id);
        $prima->delete();
        
        return redirect()->route('prima-antiguedad.index')
            ->with('success', 'Prima de antigüedad eliminada correctamente.');
    }
}
