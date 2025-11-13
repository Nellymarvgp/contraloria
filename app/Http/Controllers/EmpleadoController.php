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
use App\Imports\EmpleadosImport;
use Maatwebsite\Excel\Facades\Excel;

class EmpleadoController extends Controller
{
    public function show(Empleado $empleado)
    {
        // Cargar relaciones necesarias
        $empleado->load(['user', 'cargo', 'departamento', 'horario', 'estado', 'beneficios', 'deducciones']);
        return view('empleados.show', compact('empleado'));
    }
    public function index()
    {
        $empleados = Empleado::with(['user', 'cargo', 'departamento', 'horario', 'estado'])->paginate(10);
        return view('empleados.index', compact('empleados'));
    }

    public function create(Request $request)
    {
        $users = User::whereNotIn('cedula', Empleado::pluck('cedula'))->get();
        $cargos = Cargo::all();
        $departamentos = Departamento::all();
        $horarios = Horario::all();
        $estados = Estado::all();
        $primasAntiguedad = PrimaAntiguedad::where('estado', 1)->get();
        $primasProfesionalizacion = PrimaProfesionalizacion::where('estado', 1)->get();
        $nivelesRangos = NivelRango::where('estado', 1)->get();

        $tiposCargo = [
            'administrativo' => 'Administrativo',
            'tecnico_superior' => 'Técnico Superior Universitario',
            'profesional_universitario' => 'Profesional Universitario'
        ];

        $tipoCargoSeleccionado = $request->get('tipo_cargo');
        if ($tipoCargoSeleccionado) {
            $gruposCargos = GrupoCargo::where('estado', 1)
                ->where('categoria', $tipoCargoSeleccionado)
                ->get();
        } else {
            $gruposCargos = collect(); // Vacío hasta que seleccione tipo_cargo
        }

        $deducciones = \App\Models\Deduccion::whereIn('tipo', ['beneficio', 'parametro'])->where('activo', 1)->get();

        return view('empleados.create', compact(
            'users', 'cargos', 'departamentos', 'horarios', 'estados',
            'primasAntiguedad', 'primasProfesionalizacion', 'nivelesRangos',
            'gruposCargos', 'tiposCargo', 'deducciones', 'tipoCargoSeleccionado'
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
            'tipo_cargo' => 'nullable|in:administrativo,tecnico_superior,profesional_universitario',
            'tiene_hijos' => 'nullable|boolean',
            'cantidad_hijos' => 'nullable|integer|min:1|required_if:tiene_hijos,1',
            'beneficios' => 'nullable|array',
            'beneficios.*' => 'exists:deducciones,id',
            'deducciones' => 'nullable|array',
            'deducciones.*' => 'exists:deducciones,id',
        ]);

        $data = $request->all();
        $data['tiene_hijos'] = $request->has('tiene_hijos') ? 1 : 0;
        if(!$data['tiene_hijos']) {
            $data['cantidad_hijos'] = null;
        }
        $empleado = Empleado::create($data);

        // Asociar beneficios
        if ($request->filled('beneficios')) {
            $empleado->beneficios()->sync($request->input('beneficios'));
        }
        // Asociar deducciones
        if ($request->filled('deducciones')) {
            $empleado->deducciones()->sync($request->input('deducciones'));
        }

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

        $beneficios = \App\Models\Deduccion::where('tipo', 'beneficio')->where('activo', 1)->get();
        $deducciones = \App\Models\Deduccion::where('tipo', 'deduccion')->where('activo', 1)->get();

        return view('empleados.edit', compact(
            'empleado', 'cargos', 'departamentos', 'horarios', 'estados',
            'primasAntiguedad', 'primasProfesionalizacion', 'nivelesRangos',
            'gruposCargos', 'tiposCargo', 'beneficios', 'deducciones'
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
            'tipo_cargo' => 'nullable|in:administrativo,tecnico_superior,profesional_universitario',
            'tiene_hijos' => 'nullable|boolean',
            'cantidad_hijos' => 'nullable|integer|min:1|required_if:tiene_hijos,1',
            'beneficios' => 'nullable|array',
            'beneficios.*' => 'exists:deducciones,id',
            'deducciones' => 'nullable|array',
            'deducciones.*' => 'exists:deducciones,id',
        ]);

        $data = $request->all();
        $data['tiene_hijos'] = $request->has('tiene_hijos') ? 1 : 0;
        if(!$data['tiene_hijos']) {
            $data['cantidad_hijos'] = null;
        }
        $empleado->update($data);

        // Asociar beneficios
        if ($request->filled('beneficios')) {
            $empleado->beneficios()->sync($request->input('beneficios'));
        } else {
            $empleado->beneficios()->detach();
        }
        // Asociar deducciones
        if ($request->filled('deducciones')) {
            $empleado->deducciones()->sync($request->input('deducciones'));
        } else {
            $empleado->deducciones()->detach();
        }

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado exitosamente.');
    }

    public function destroy(Empleado $empleado)
    {
        $empleado->delete();
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado exitosamente.');
    }

    /**
     * Show the form for importing empleados.
     */
    public function importForm()
    {
        return view('empleados.import');
    }

    /**
     * Import empleados from Excel/CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:csv,xlsx,xls|max:2048',
        ], [
            'archivo.required' => 'Debes seleccionar un archivo para importar.',
            'archivo.mimes' => 'El archivo debe ser CSV o Excel (.csv, .xlsx, .xls).',
        ]);
        try {
            Excel::import(new EmpleadosImport, $request->file('archivo'));
            return redirect()->route('empleados.index')->with('success', 'Empleados importados correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }
}
