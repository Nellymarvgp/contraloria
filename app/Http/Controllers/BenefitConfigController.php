<?php

namespace App\Http\Controllers;

use App\Models\BenefitConfig;
use Illuminate\Http\Request;

class BenefitConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $benefits = BenefitConfig::all();
        return view('config.benefits.index', compact('benefits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('config.benefits.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:20|unique:benefit_configs',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'valor' => 'required|numeric|min:0',
            'tipo' => 'required|string|in:fijo,porcentaje',
            'activo' => 'boolean',
        ]);

        BenefitConfig::create($validated);

        return redirect()->route('benefit-configs.index')
            ->with('success', 'Configuración de beneficio creada exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BenefitConfig $benefitConfig)
    {
        return view('config.benefits.edit', compact('benefitConfig'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BenefitConfig $benefitConfig)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:20|unique:benefit_configs,codigo,' . $benefitConfig->id,
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'valor' => 'required|numeric|min:0',
            'tipo' => 'required|string|in:fijo,porcentaje',
            'activo' => 'boolean',
        ]);

        $benefitConfig->update($validated);

        return redirect()->route('benefit-configs.index')
            ->with('success', 'Configuración de beneficio actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BenefitConfig $benefitConfig)
    {
        $benefitConfig->delete();

        return redirect()->route('benefit-configs.index')
            ->with('success', 'Configuración de beneficio eliminada exitosamente.');
    }
}
