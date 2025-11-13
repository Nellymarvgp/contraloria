<?php

namespace App\Http\Controllers;

use App\Models\PayrollParameter;
use Illuminate\Http\Request;

class PayrollParameterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parameters = PayrollParameter::all();
        return view('config.parameters.index', compact('parameters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('config.parameters.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:20|unique:payroll_parameters',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'campo' => 'required|string|max:20',
            'valor_defecto' => 'required|numeric|min:0',
            'activo' => 'boolean',
        ]);

        PayrollParameter::create($validated);

        return redirect()->route('payroll-parameters.index')
            ->with('success', 'Parámetro de nómina creado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PayrollParameter $payrollParameter)
    {
        return view('config.parameters.edit', compact('payrollParameter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PayrollParameter $payrollParameter)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:20|unique:payroll_parameters,codigo,' . $payrollParameter->id,
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'campo' => 'required|string|max:20',
            'valor_defecto' => 'required|numeric|min:0',
            'activo' => 'boolean',
        ]);

        $payrollParameter->update($validated);

        return redirect()->route('payroll-parameters.index')
            ->with('success', 'Parámetro de nómina actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PayrollParameter $payrollParameter)
    {
        $payrollParameter->delete();

        return redirect()->route('payroll-parameters.index')
            ->with('success', 'Parámetro de nómina eliminado exitosamente.');
    }
}
