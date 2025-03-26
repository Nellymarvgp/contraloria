<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function index()
    {
        $horarios = Horario::paginate(10);
        return view('horarios.index', compact('horarios'));
    }

    public function create()
    {
        return view('horarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:horarios',
            'hora_entrada' => 'required|date_format:H:i',
            'hora_salida' => 'required|date_format:H:i|after:hora_entrada',
            'descripcion' => 'nullable|string'
        ]);

        Horario::create($request->all());

        return redirect()->route('horarios.index')->with('success', 'Horario creado exitosamente.');
    }

    public function edit(Horario $horario)
    {
        return view('horarios.edit', compact('horario'));
    }

    public function update(Request $request, Horario $horario)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:horarios,nombre,' . $horario->id,
            'hora_entrada' => 'required|date_format:H:i',
            'hora_salida' => 'required|date_format:H:i|after:hora_entrada',
            'descripcion' => 'nullable|string'
        ]);

        $horario->update($request->all());

        return redirect()->route('horarios.index')->with('success', 'Horario actualizado exitosamente.');
    }

    public function destroy(Horario $horario)
    {
        if($horario->empleados()->exists()) {
            return redirect()->route('horarios.index')->with('error', 'No se puede eliminar el horario porque tiene empleados asociados.');
        }

        $horario->delete();
        return redirect()->route('horarios.index')->with('success', 'Horario eliminado exitosamente.');
    }
}
