<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function index()
    {
        $departamentos = Departamento::paginate(10);
        return view('departamentos.index', compact('departamentos'));
    }

    public function create()
    {
        return view('departamentos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:departamentos',
            'descripcion' => 'nullable|string'
        ]);

        Departamento::create($request->all());

        return redirect()->route('departamentos.index')->with('success', 'Departamento creado exitosamente.');
    }

    public function edit(Departamento $departamento)
    {
        return view('departamentos.edit', compact('departamento'));
    }

    public function update(Request $request, Departamento $departamento)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:departamentos,nombre,' . $departamento->id,
            'descripcion' => 'nullable|string'
        ]);

        $departamento->update($request->all());

        return redirect()->route('departamentos.index')->with('success', 'Departamento actualizado exitosamente.');
    }

    public function destroy(Departamento $departamento)
    {
        if($departamento->empleados()->exists()) {
            return redirect()->route('departamentos.index')->with('error', 'No se puede eliminar el departamento porque tiene empleados asociados.');
        }

        $departamento->delete();
        return redirect()->route('departamentos.index')->with('success', 'Departamento eliminado exitosamente.');
    }
}
