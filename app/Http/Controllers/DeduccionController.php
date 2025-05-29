<?php

namespace App\Http\Controllers;

use App\Models\Deduccion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeduccionController extends Controller
{
    /**
     * Display a listing of the deducciones.
     */
    public function index()
    {
        $deducciones = Deduccion::orderBy('nombre')->paginate(10);
        return view('deducciones.index', compact('deducciones'));
    }

    /**
     * Show the form for creating a new deduccion.
     */
    public function create()
    {
        return view('deducciones.create');
    }

    /**
     * Store a newly created deduccion in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:deducciones',
            'descripcion' => 'nullable|string',
            'porcentaje' => 'nullable|numeric|min:0|max:100',
            'es_fijo' => 'boolean',
            'monto_fijo' => 'nullable|numeric|min:0',
            'activo' => 'boolean',
        ]);

        Deduccion::create($validated);

        return redirect()->route('deducciones.index')
            ->with('success', 'Deducción creada exitosamente.');
    }

    /**
     * Show the form for editing the specified deduccion.
     */
    public function edit(Deduccion $deduccione)
    {
        return view('deducciones.edit', ['deduccion' => $deduccione]);
    }

    /**
     * Update the specified deduccion in storage.
     */
    public function update(Request $request, Deduccion $deduccione)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('deducciones')->ignore($deduccione->id),
            ],
            'descripcion' => 'nullable|string',
            'porcentaje' => 'nullable|numeric|min:0|max:100',
            'es_fijo' => 'boolean',
            'monto_fijo' => 'nullable|numeric|min:0',
            'activo' => 'boolean',
        ]);

        $deduccione->update($validated);

        return redirect()->route('deducciones.index')
            ->with('success', 'Deducción actualizada exitosamente.');
    }

    /**
     * Remove the specified deduccion from storage.
     */
    public function destroy(Deduccion $deduccione)
    {
        $deduccione->delete();

        return redirect()->route('deducciones.index')
            ->with('success', 'Deducción eliminada exitosamente.');
    }
}
