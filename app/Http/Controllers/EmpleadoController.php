<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\User;
use App\Models\Cargo;
use App\Models\Departamento;
use App\Models\Horario;
use App\Models\Estado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::with(['user', 'cargo', 'departamento', 'horario', 'estado'])->paginate(10);
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        $users = User::whereNotIn('cedula', Empleado::pluck('cedula'))->get();
        $cargos = Cargo::all();
        $departamentos = Departamento::all();
        $horarios = Horario::all();
        $estados = Estado::all();

        return view('empleados.create', compact('users', 'cargos', 'departamentos', 'horarios', 'estados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cedula' => 'required|exists:users,cedula|unique:empleados,cedula',
            'cargo_id' => 'required|exists:cargos,id',
            'departamento_id' => 'required|exists:departamentos,id',
            'horario_id' => 'required|exists:horarios,id',
            'estado_id' => 'required|exists:estados,id',
            'salario' => 'required|numeric|min:0',
            'fecha_ingreso' => 'required|date',
            'observaciones' => 'nullable|string'
        ]);

        Empleado::create($request->all());

        return redirect()->route('empleados.index')->with('success', 'Empleado registrado exitosamente.');
    }

    public function edit(Empleado $empleado)
    {
        $cargos = Cargo::all();
        $departamentos = Departamento::all();
        $horarios = Horario::all();
        $estados = Estado::all();

        return view('empleados.edit', compact('empleado', 'cargos', 'departamentos', 'horarios', 'estados'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        $request->validate([
            'cargo_id' => 'required|exists:cargos,id',
            'departamento_id' => 'required|exists:departamentos,id',
            'horario_id' => 'required|exists:horarios,id',
            'estado_id' => 'required|exists:estados,id',
            'salario' => 'required|numeric|min:0',
            'fecha_ingreso' => 'required|date',
            'observaciones' => 'nullable|string'
        ]);

        $empleado->update($request->all());

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado exitosamente.');
    }

    public function destroy(Empleado $empleado)
    {
        $empleado->delete();
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado exitosamente.');
    }
}
