<?php

namespace App\Http\Controllers;

use App\Models\Remuneracion;
use App\Models\NivelRango;
use App\Models\GrupoCargo;
use Illuminate\Http\Request;
use App\Imports\RemuneracionesImport;
use Maatwebsite\Excel\Facades\Excel;

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
            'bachiller' => 'Bachiller',
            'tecnico_superior' => 'Técnico Superior Universitario',
            'profesional_universitario' => 'Profesional Universitario'
        ];
        
        $tiposPersonal = [
            'obreros' => 'Obreros',
            'administracion_publica' => 'Administración Pública'
        ];
        
        return view('remuneraciones.create', compact('nivelesRangos', 'gruposCargos', 'tiposCargo', 'tiposPersonal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación común para todos los tipos de personal
        $request->validate([
            'tipo_personal' => 'required|in:administracion_publica,obreros',
        ]);
        
        // Validación específica según el tipo de personal
        if ($request->tipo_personal === 'administracion_publica') {
            $request->validate([
                'nivel_rango_id' => 'required|exists:nivel_rangos,id',
                'grupo_cargo_id' => 'required|exists:grupo_cargos,id',
                'tipo_cargo' => 'required|in:bachiller,tecnico_superior,profesional_universitario',
                'valor' => 'required|numeric|min:0',
            ]);
            
            $data = [
                'nivel_rango_id' => $request->nivel_rango_id,
                'grupo_cargo_id' => $request->grupo_cargo_id,
                'tipo_cargo' => $request->tipo_cargo,
                'valor' => $request->valor,
            ];
        } else { // obreros
            $request->validate([
                'clasificacion' => 'required|in:no_calificados,calificados,supervisor',
                'grado' => 'required|integer|min:1|max:10',
                'valor' => 'required|numeric|min:0',
            ]);
            
            $data = [
                'clasificacion' => $request->clasificacion,
                'grado' => $request->grado,
                'valor' => $request->valor,
            ];
        }
        
        // Datos comunes para ambos tipos
        $data['tipo_personal'] = $request->tipo_personal;
        $data['estado'] = $request->has('estado') ? 1 : 0;
        
        Remuneracion::create($data);
        
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
            'bachiller' => 'Bachiller',
            'tecnico_superior' => 'Técnico Superior Universitario',
            'profesional_universitario' => 'Profesional Universitario'
        ];
        
        $tiposPersonal = [
            'obreros' => 'Obreros',
            'administracion_publica' => 'Administración Pública'
        ];
        
        return view('remuneraciones.edit', compact('remuneracion', 'nivelesRangos', 'gruposCargos', 'tiposCargo', 'tiposPersonal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validación común para todos los tipos de personal
        $request->validate([
            'tipo_personal' => 'required|in:administracion_publica,obreros',
        ]);
        
        // Validación específica según el tipo de personal
        if ($request->tipo_personal === 'administracion_publica') {
            $request->validate([
                'nivel_rango_id' => 'required|exists:nivel_rangos,id',
                'grupo_cargo_id' => 'required|exists:grupo_cargos,id',
                'tipo_cargo' => 'required|in:bachiller,tecnico_superior,profesional_universitario',
                'valor' => 'required|numeric|min:0',
            ]);
            
            $data = [
                'nivel_rango_id' => $request->nivel_rango_id,
                'grupo_cargo_id' => $request->grupo_cargo_id,
                'tipo_cargo' => $request->tipo_cargo,
                'valor' => $request->valor,
                // Limpiar campos de obreros si cambia de tipo
                'clasificacion' => null,
                'grado' => null,
            ];
        } else { // obreros
            $request->validate([
                'clasificacion' => 'required|in:no_calificados,calificados,supervisor',
                'grado' => 'required|integer|min:1|max:10',
                'valor' => 'required|numeric|min:0',
            ]);
            
            $data = [
                'clasificacion' => $request->clasificacion,
                'grado' => $request->grado,
                'valor' => $request->valor,
                // Limpiar campos de funcionarios si cambia de tipo
                'nivel_rango_id' => null,
                'grupo_cargo_id' => null,
                'tipo_cargo' => null,
            ];
        }
        
        // Datos comunes para ambos tipos
        $data['tipo_personal'] = $request->tipo_personal;
        $data['estado'] = $request->has('estado') ? 1 : 0;
        
        $remuneracion = Remuneracion::findOrFail($id);
        $remuneracion->update($data);
        
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
    
    /**
     * Show the form for importing remuneraciones.
     */
    public function importForm()
    {
        return view('remuneraciones.import');
    }
    
    /**
     * Import remuneraciones from Excel/CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls|max:2048',
        ]);
        
        try {
            Excel::import(new RemuneracionesImport, $request->file('file'));
            
            return redirect()->route('remuneraciones.index')
                ->with('success', 'Remuneraciones importadas correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }
}
