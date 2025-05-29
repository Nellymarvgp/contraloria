<?php

namespace App\Http\Controllers;

use App\Models\DeductionConfig;
use Illuminate\Http\Request;

class DeductionConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deductions = DeductionConfig::all();
        return view('config.deductions.index', compact('deductions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('config.deductions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:20|unique:deduction_configs',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'porcentaje' => 'required|numeric|min:0|max:100',
            'activo' => 'boolean',
        ]);

        DeductionConfig::create($validated);

        return redirect()->route('deduction-configs.index')
            ->with('success', 'Configuración de deducción creada exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DeductionConfig $deductionConfig)
    {
        return view('config.deductions.edit', compact('deductionConfig'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DeductionConfig $deductionConfig)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:20|unique:deduction_configs,codigo,' . $deductionConfig->id,
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'porcentaje' => 'required|numeric|min:0|max:100',
            'activo' => 'boolean',
        ]);

        $deductionConfig->update($validated);

        return redirect()->route('deduction-configs.index')
            ->with('success', 'Configuración de deducción actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeductionConfig $deductionConfig)
    {
        $deductionConfig->delete();

        return redirect()->route('deduction-configs.index')
            ->with('success', 'Configuración de deducción eliminada exitosamente.');
    }
}
