<?php

namespace App\Http\Controllers;

use App\Models\Nomina;
use App\Models\NominaDetalle;
use App\Models\NominaDetalleConcepto;
use App\Models\Empleado;
use App\Models\Estado;
use App\Services\NominaCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NominaController extends Controller
{
    protected $nominaCalculator;

    public function __construct(NominaCalculator $nominaCalculator)
    {
        $this->nominaCalculator = $nominaCalculator;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nominas = Nomina::orderBy('created_at', 'desc')->paginate(10);
        return view('nominas.index', compact('nominas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('nominas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'descripcion' => 'required|string|max:255',
            'despacho' => 'nullable|string|max:255',
        ]);

        $nomina = Nomina::create([
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'descripcion' => $request->descripcion,
            'despacho' => $request->despacho,
            'estado' => 'borrador',
            'total_monto' => 0,
        ]);

        return redirect()->route('nominas.show', $nomina)
            ->with('success', 'Nómina creada. Ahora puede generar los cálculos.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Nomina $nomina)
    {
        $nomina->load('detalles.empleado', 'detalles.conceptos');
        return view('nominas.show', compact('nomina'));
    }

    /**
     * Generate payroll calculations for all active employees.
     */
    public function generate(Nomina $nomina)
    {
        // Check if calculations already exist
        if ($nomina->detalles()->count() > 0) {
            return redirect()->route('nominas.show', $nomina)
                ->with('warning', 'Esta nómina ya tiene cálculos generados.');
        }

        try {
            // Get all active employees - try multiple approaches to ensure we get employees
            // First approach: using the estado relationship
            $empleados = Empleado::whereHas('estado', function($query) {
                $query->where('nombre', 'Activo');
            })->get();
            
            // If no employees found, try direct estado_id approach
            if ($empleados->isEmpty()) {
                // Get the active estado ID
                $estadoActivo = Estado::where('nombre', 'Activo')->first();
                
                if ($estadoActivo) {
                    $empleados = Empleado::where('estado_id', $estadoActivo->id)->get();
                }
            }
            
            // If still no employees, get all employees as fallback
            if ($empleados->isEmpty()) {
                $empleados = Empleado::all();
            }
            
            // If still no employees, show error
            if ($empleados->isEmpty()) {
                return redirect()->route('nominas.show', $nomina)
                    ->with('error', 'No se encontraron empleados para generar la nómina. Por favor, verifique que existan empleados registrados.');
            }
            
            $totalMonto = 0;
            
            foreach ($empleados as $empleado) {
                // Calculate payroll for employee
                $calculationResult = $this->nominaCalculator->calculate($empleado);
                
                // Create payroll detail
                $detalle = NominaDetalle::create([
                    'nomina_id' => $nomina->id,
                    'empleado_id' => $empleado->id,
                    'sueldo_basico' => $calculationResult['sueldo_basico'],
                    'prima_profesionalizacion' => $calculationResult['prima_profesionalizacion'],
                    'prima_antiguedad' => $calculationResult['prima_antiguedad'],
                    'prima_por_hijo' => $calculationResult['prima_por_hijo'],
                    'comida' => $calculationResult['comida'],
                    'otras_primas' => $calculationResult['otras_primas'],
                    'ret_ivss' => $calculationResult['ret_ivss'],
                    'ret_pie' => $calculationResult['ret_pie'],
                    'ret_lph' => $calculationResult['ret_lph'],
                    'ret_fpj' => $calculationResult['ret_fpj'],
                    'ordinaria' => $calculationResult['ordinaria'],
                    'incentivo' => $calculationResult['incentivo'],
                    'feriado' => $calculationResult['feriado'],
                    'gastos_representacion' => $calculationResult['gastos_representacion'],
                    'total' => $calculationResult['total'],
                    'txt_1' => $calculationResult['txt_1'],
                    'txt_2' => $calculationResult['txt_2'],
                    'txt_3' => $calculationResult['txt_3'],
                    'txt_4' => $calculationResult['txt_4'],
                    'txt_5' => $calculationResult['txt_5'],
                    'txt_6' => $calculationResult['txt_6'],
                    'txt_7' => $calculationResult['txt_7'],
                    'txt_8' => $calculationResult['txt_8'],
                    'txt_9' => $calculationResult['txt_9'],
                    'txt_10' => $calculationResult['txt_10']
                ]);

                // Create concepts
                foreach ($calculationResult['conceptos'] as $concepto) {
                    NominaDetalleConcepto::create([
                        'nomina_detalle_id' => $detalle->id,
                        'tipo' => $concepto['tipo'],
                        'descripcion' => $concepto['descripcion'],
                        'monto' => $concepto['monto'],
                        'porcentaje' => $concepto['porcentaje'] ?? null,
                        'es_fijo' => $concepto['es_fijo']
                    ]);
                }

                $totalMonto += $calculationResult['total'];
            }

            // Update total amount
            $nomina->update([
                'total_monto' => $totalMonto
            ]);

            return redirect()->route('nominas.show', $nomina)
                ->with('success', 'Cálculos de nómina generados correctamente.');
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error al generar nómina: ' . $e->getMessage());
            
            return redirect()->route('nominas.show', $nomina)
                ->with('error', 'Error al generar la nómina: ' . $e->getMessage());
        }
    }

    /**
     * Change the status of a payroll.
     */
    public function changeStatus(Nomina $nomina, Request $request)
    {
        $request->validate([
            'estado' => 'required|in:borrador,aprobada,pagada'
        ]);

        $nomina->update([
            'estado' => $request->estado
        ]);

        return redirect()->route('nominas.show', $nomina)
            ->with('success', 'Estado de la nómina actualizado correctamente.');
    }

    /**
     * Export payroll to PDF.
     */
    public function exportPdf(Nomina $nomina)
    {
        $nomina->load('detalles.empleado.cargo', 'detalles.empleado.departamento', 'detalles.conceptos');
        
        // Implementation of PDF generation using a library like DomPDF would go here
        // For now, just redirect back with a message
        return redirect()->route('nominas.show', $nomina)
            ->with('info', 'Funcionalidad de exportación a PDF en desarrollo.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nomina $nomina)
    {
        if ($nomina->estado !== 'borrador') {
            return redirect()->route('nominas.index')
                ->with('error', 'Solo se pueden eliminar nóminas en estado borrador.');
        }

        $nomina->delete();
        
        return redirect()->route('nominas.index')
            ->with('success', 'Nómina eliminada correctamente.');
    }
}
