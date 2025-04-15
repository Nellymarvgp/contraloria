<?php

namespace App\Http\Controllers;

use App\Models\Remuneracion;
use App\Models\NivelRango;
use App\Models\GrupoCargo;
use Illuminate\Http\Request;

class RemuneracionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $remuneraciones = Remuneracion::with(['nivelRango', 'grupoCargo'])->get();
        return view('remuneraciones.index', compact('remuneraciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nivelesRangos = NivelRango::where('estado', 1)->get();
        $gruposCargos = GrupoCargo::where('estado', 1)->get();
        $tiposCargo = [
            'administrativo' => 'Administrativo',
            'tecnico_superior' => 'Técnico Superior Universitario',
            'profesional_universitario' => 'Profesional Universitario'
        ];
        
        return view('remuneraciones.create', compact('nivelesRangos', 'gruposCargos', 'tiposCargo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nivel_rango_id' => 'required|exists:nivel_rangos,id',
            'grupo_cargo_id' => 'required|exists:grupo_cargos,id',
            'tipo_cargo' => 'required|in:administrativo,tecnico_superior,profesional_universitario',
            'valor' => 'required|numeric|min:0',
        ]);
        
        Remuneracion::create([
            'nivel_rango_id' => $request->nivel_rango_id,
            'grupo_cargo_id' => $request->grupo_cargo_id,
            'tipo_cargo' => $request->tipo_cargo,
            'valor' => $request->valor,
            'estado' => $request->has('estado') ? 1 : 0,
        ]);
        
        return redirect()->route('remuneraciones.index')
            ->with('success', 'Remuneración creada correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $remuneracion = Remuneracion::findOrFail($id);
        $nivelesRangos = NivelRango::where('estado', 1)->get();
        $gruposCargos = GrupoCargo::where('estado', 1)->get();
        $tiposCargo = [
            'administrativo' => 'Administrativo',
            'tecnico_superior' => 'Técnico Superior Universitario',
            'profesional_universitario' => 'Profesional Universitario'
        ];
        
        return view('remuneraciones.edit', compact('remuneracion', 'nivelesRangos', 'gruposCargos', 'tiposCargo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nivel_rango_id' => 'required|exists:nivel_rangos,id',
            'grupo_cargo_id' => 'required|exists:grupo_cargos,id',
            'tipo_cargo' => 'required|in:administrativo,tecnico_superior,profesional_universitario',
            'valor' => 'required|numeric|min:0',
        ]);
        
        $remuneracion = Remuneracion::findOrFail($id);
        $remuneracion->update([
            'nivel_rango_id' => $request->nivel_rango_id,
            'grupo_cargo_id' => $request->grupo_cargo_id,
            'tipo_cargo' => $request->tipo_cargo,
            'valor' => $request->valor,
            'estado' => $request->has('estado') ? 1 : 0,
        ]);
        
        return redirect()->route('remuneraciones.index')
            ->with('success', 'Remuneración actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $remuneracion = Remuneracion::findOrFail($id);
        $remuneracion->delete();
        
        return redirect()->route('remuneraciones.index')
            ->with('success', 'Remuneración eliminada correctamente.');
    }
}
