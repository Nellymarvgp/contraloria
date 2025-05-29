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
    public function index(Request $request)
    {
        $tipo = $request->query('tipo', 'deduccion');
        
        // Validar que el tipo sea válido
        if (!in_array($tipo, ['deduccion', 'beneficio', 'parametro'])) {
            $tipo = 'deduccion';
        }
        
        $deducciones = Deduccion::where('tipo', $tipo)
            ->orderBy('nombre')
            ->paginate(10);
            
        return view('deducciones.index', compact('deducciones', 'tipo'));
    }

    /**
     * Show the form for creating a new deduccion.
     */
    public function create(Request $request)
    {
        $tipo = $request->query('tipo', 'deduccion');
        
        // Validar que el tipo sea válido
        if (!in_array($tipo, ['deduccion', 'beneficio', 'parametro'])) {
            $tipo = 'deduccion';
        }
        
        return view('deducciones.create', compact('tipo'));
    }

    /**
     * Store a newly created deduccion in storage.
     */
    public function store(Request $request)
    {
        $tipo = $request->input('tipo', 'deduccion');
        
        // Validar que el tipo sea válido
        if (!in_array($tipo, ['deduccion', 'beneficio', 'parametro'])) {
            $tipo = 'deduccion';
        }
        
        // Reglas básicas para todos los tipos
        $rules = [
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'es_fijo' => 'boolean',
            'activo' => 'boolean',
        ];
        
        // Reglas adicionales según el tipo
        if ($tipo === 'parametro') {
            $rules['campo'] = 'required|string|max:20';
            $rules['monto_fijo'] = 'required|numeric|min:0';
        } else {
            // Para deducciones y beneficios
            if ($request->input('es_fijo')) {
                $rules['monto_fijo'] = 'required|numeric|min:0';
                $rules['porcentaje'] = 'nullable|numeric|min:0';
            } else {
                $rules['porcentaje'] = 'required|numeric|min:0';
                if ($tipo === 'deduccion') {
                    $rules['porcentaje'] .= '|max:100'; // Para deducciones, máximo 100%
                }
                $rules['monto_fijo'] = 'nullable|numeric|min:0';
            }
        }
        
        $validated = $request->validate($rules);
        
        // Agregar el tipo al array validado
        $validated['tipo'] = $tipo;
        
        // Crear el registro
        Deduccion::create($validated);
        
        // Redirigir según el tipo
        $message = ($tipo === 'deduccion') ? 'Deducción creada exitosamente.' :
                  (($tipo === 'beneficio') ? 'Beneficio creado exitosamente.' :
                   'Parámetro creado exitosamente.');

        return redirect()->route('deducciones.index', ['tipo' => $tipo])
            ->with('success', $message);
    }

    /**
     * Show the form for editing the specified deduccion.
     */
    public function edit(Deduccion $deduccione)
    {
        $tipo = $deduccione->tipo ?? 'deduccion';
        return view('deducciones.edit', ['deduccion' => $deduccione, 'tipo' => $tipo]);
    }

    /**
     * Update the specified deduccion in storage.
     */
    public function update(Request $request, Deduccion $deduccione)
    {
        $tipo = $deduccione->tipo ?? 'deduccion';
        
        // Reglas básicas para todos los tipos
        $rules = [
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('deducciones')->ignore($deduccione->id),
            ],
            'descripcion' => 'nullable|string',
            'es_fijo' => 'boolean',
            'activo' => 'boolean',
        ];
        
        // Reglas adicionales según el tipo
        if ($tipo === 'parametro') {
            $rules['campo'] = 'required|string|max:20';
            $rules['monto_fijo'] = 'required|numeric|min:0';
        } else {
            // Para deducciones y beneficios
            if ($request->input('es_fijo')) {
                $rules['monto_fijo'] = 'required|numeric|min:0';
                $rules['porcentaje'] = 'nullable|numeric|min:0';
            } else {
                $rules['porcentaje'] = 'required|numeric|min:0';
                if ($tipo === 'deduccion') {
                    $rules['porcentaje'] .= '|max:100'; // Para deducciones, máximo 100%
                }
                $rules['monto_fijo'] = 'nullable|numeric|min:0';
            }
        }
        
        $validated = $request->validate($rules);
        
        // Actualizar el registro
        $deduccione->update($validated);
        
        // Redirigir según el tipo
        $message = ($tipo === 'deduccion') ? 'Deducción actualizada exitosamente.' :
                  (($tipo === 'beneficio') ? 'Beneficio actualizado exitosamente.' :
                   'Parámetro actualizado exitosamente.');

        return redirect()->route('deducciones.index', ['tipo' => $tipo])
            ->with('success', $message);
    }

    /**
     * Remove the specified deduccion from storage.
     */
    public function destroy(Deduccion $deduccione)
    {
        $tipo = $deduccione->tipo ?? 'deduccion';
        $deduccione->delete();
        
        // Mensaje según el tipo
        $message = ($tipo === 'deduccion') ? 'Deducción eliminada exitosamente.' :
                  (($tipo === 'beneficio') ? 'Beneficio eliminado exitosamente.' :
                   'Parámetro eliminado exitosamente.');

        return redirect()->route('deducciones.index', ['tipo' => $tipo])
            ->with('success', $message);
    }
}
