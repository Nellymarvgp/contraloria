<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\User;
use App\Models\Cargo;
use App\Models\Departamento;
use App\Models\Horario;
use App\Models\Estado;
use App\Models\PrimaAntiguedad;
use App\Models\PrimaProfesionalizacion;
use App\Models\NivelRango;
use App\Models\GrupoCargo;
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
        $primasAntiguedad = PrimaAntiguedad::where('estado', 1)->get();
        $primasProfesionalizacion = PrimaProfesionalizacion::where('estado', 1)->get();
        $nivelesRangos = NivelRango::where('estado', 1)->get();
        $gruposCargos = GrupoCargo::where('estado', 1)->get();
        $tiposCargo = [
            'administrativo' => 'Administrativo',
            'tecnico_superior' => 'Técnico Superior Universitario',
            'profesional_universitario' => 'Profesional Universitario'
        ];

        return view('empleados.create', compact(
            'users', 'cargos', 'departamentos', 'horarios', 'estados',
            'primasAntiguedad', 'primasProfesionalizacion', 'nivelesRangos',
            'gruposCargos', 'tiposCargo'
        ));
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
            'observaciones' => 'nullable|string',
            'prima_antiguedad_id' => 'nullable|exists:prima_antiguedads,id',
            'prima_profesionalizacion_id' => 'nullable|exists:prima_profesionalizacions,id',
            'nivel_rango_id' => 'nullable|exists:nivel_rangos,id',
            'grupo_cargo_id' => 'nullable|exists:grupo_cargos,id',
            'tipo_cargo' => 'nullable|in:administrativo,tecnico_superior,profesional_universitario'
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
        $primasAntiguedad = PrimaAntiguedad::where('estado', 1)->get();
        $primasProfesionalizacion = PrimaProfesionalizacion::where('estado', 1)->get();
        $nivelesRangos = NivelRango::where('estado', 1)->get();
        $gruposCargos = GrupoCargo::where('estado', 1)->get();
        $tiposCargo = [
            'administrativo' => 'Administrativo',
            'tecnico_superior' => 'Técnico Superior Universitario',
            'profesional_universitario' => 'Profesional Universitario'
        ];

        return view('empleados.edit', compact(
            'empleado', 'cargos', 'departamentos', 'horarios', 'estados',
            'primasAntiguedad', 'primasProfesionalizacion', 'nivelesRangos',
            'gruposCargos', 'tiposCargo'
        ));
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
            'observaciones' => 'nullable|string',
            'prima_antiguedad_id' => 'nullable|exists:prima_antiguedads,id',
            'prima_profesionalizacion_id' => 'nullable|exists:prima_profesionalizacions,id',
            'nivel_rango_id' => 'nullable|exists:nivel_rangos,id',
            'grupo_cargo_id' => 'nullable|exists:grupo_cargos,id',
            'tipo_cargo' => 'nullable|in:administrativo,tecnico_superior,profesional_universitario'
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
