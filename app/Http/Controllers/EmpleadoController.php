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
use App\Models\VacacionesPorDisfrute;
use App\Models\Beneficio;
use App\Models\BeneficioCargo;
use Illuminate\Http\Request;
use App\Imports\EmpleadosImport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

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

        // Detectar si existen empleados con antigüedad pendiente de actualizar
        $pendientes = $this->getEmpleadosConAntiguedadPendiente();
        $tienePendientes = $pendientes->isNotEmpty();

        return view('empleados.index', compact('empleados', 'tienePendientes'));
    }

    // Listar empleados con año de servicio cumplido pero antigüedad sin actualizar
    public function antiguedadPendiente()
    {
        $pendientes = $this->getEmpleadosConAntiguedadPendiente();
        return view('empleados.antiguedad_pendiente', compact('pendientes'));
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

        // Para el formulario, las deducciones que se pueden asignar son todas las de tipo 'deduccion' activas
        $deducciones = \App\Models\Deduccion::where('tipo', 'deduccion')->where('activo', 1)->get();

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
            'beneficios.*' => 'exists:beneficios,id',
            'deducciones' => 'nullable|array',
            'deducciones.*' => 'exists:deducciones,id',
        ]);

        $data = $request->all();
        $data['tiene_hijos'] = $request->has('tiene_hijos') ? 1 : 0;
        if(!$data['tiene_hijos']) {
            $data['cantidad_hijos'] = null;
        }
        // Calcular tiempo de antigüedad en años basado en la fecha de ingreso
        if (!empty($data['fecha_ingreso'])) {
            $fechaIngreso = Carbon::parse($data['fecha_ingreso']);
            $tiempoAntiguedad = $fechaIngreso->diffInYears(Carbon::now());
            $data['tiempo_antiguedad'] = $tiempoAntiguedad;

            // Asignar automáticamente la prima de antigüedad correspondiente
            $prima = PrimaAntiguedad::where('estado', 1)
                ->where('anios', '<=', $tiempoAntiguedad)
                ->orderByDesc('anios')
                ->first();
            $data['prima_antiguedad_id'] = $prima ? $prima->id : null;
        }

        // Al registrar un empleado, por defecto PVacaciones debe ser true
        $data['pvacaciones'] = true;

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

        $beneficios = \App\Models\Beneficio::all();
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
            'beneficios.*' => 'exists:beneficios,id',
            'deducciones' => 'nullable|array',
            'deducciones.*' => 'exists:deducciones,id',
        ]);

        $data = $request->all();
        $data['tiene_hijos'] = $request->has('tiene_hijos') ? 1 : 0;
        if(!$data['tiene_hijos']) {
            $data['cantidad_hijos'] = null;
        }
        // Calcular tiempo de antigüedad en años basado en la fecha de ingreso
        if (!empty($data['fecha_ingreso'])) {
            $fechaIngreso = Carbon::parse($data['fecha_ingreso']);
            $tiempoAntiguedad = $fechaIngreso->diffInYears(Carbon::now());
            $data['tiempo_antiguedad'] = $tiempoAntiguedad;

            // Asignar automáticamente la prima de antigüedad correspondiente
            $prima = PrimaAntiguedad::where('estado', 1)
                ->where('anios', '<=', $tiempoAntiguedad)
                ->orderByDesc('anios')
                ->first();
            $data['prima_antiguedad_id'] = $prima ? $prima->id : null;
        }
        $empleado->update($data);

        // Asociar beneficios: sincronizar siempre con lo que venga en el request.
        // Si no viene nada, se interpreta como "ningún beneficio seleccionado" y se eliminan todos.
        $empleado->beneficios()->sync($request->input('beneficios', []));
        // Asociar deducciones
        if ($request->filled('deducciones')) {
            $empleado->deducciones()->sync($request->input('deducciones'));
        } else {
            $empleado->deducciones()->detach();
        }

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado exitosamente.');
    }

    /**
     * Obtener beneficios disponibles para un cargo concreto (más los de "Todos").
     */
    public function beneficiosPorCargo(Cargo $cargo)
    {
        $tipoCargo = $cargo->tipo_cargo;

        // Buscar configuraciones de beneficios por tipo de cargo y por 'Todos'
        $beneficiosCargo = BeneficioCargo::with('beneficio')
            ->whereIn('cargo', [$tipoCargo, 'Todos'])
            ->get();

        // Devolver solo la lista de beneficios únicos (id + nombre)
        $beneficios = $beneficiosCargo
            ->pluck('beneficio')
            ->filter() // eliminar nulos por si acaso
            ->unique('id')
            ->values()
            ->map(function (Beneficio $beneficio) {
                return [
                    'id' => $beneficio->id,
                    'beneficio' => $beneficio->beneficio,
                ];
            });

        return response()->json($beneficios);
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

    // Actualizar antigüedad, prima de antigüedad y registrar días por disfrute para un empleado
    public function actualizarAntiguedad(Empleado $empleado)
    {
        if (!$empleado->fecha_ingreso) {
            return redirect()->back()->with('error', 'El empleado no tiene fecha de ingreso registrada.');
        }

        $hoy = Carbon::now();
        $fechaIngreso = Carbon::parse($empleado->fecha_ingreso);
        $aniosReales = $fechaIngreso->diffInYears($hoy);

        if ($aniosReales <= 0) {
            return redirect()->back()->with('error', 'El empleado aún no cumple un año de servicio.');
        }

        $registrado = $empleado->tiempo_antiguedad ?? 0;
        if ($aniosReales <= $registrado) {
            return redirect()->back()->with('error', 'La antigüedad del empleado ya está actualizada.');
        }

        // Actualizar tiempo de antigüedad, prima de antigüedad y marcar PVacaciones en false
        $empleado->tiempo_antiguedad = $aniosReales;
        $prima = PrimaAntiguedad::where('estado', 1)
            ->where('anios', '<=', $aniosReales)
            ->orderByDesc('anios')
            ->first();
        $empleado->prima_antiguedad_id = $prima ? $prima->id : null;
        $empleado->pvacaciones = false;
        $empleado->save();

        // Calcular días por disfrute según los años de servicio
        if ($aniosReales >= 1 && $aniosReales <= 5) {
            $diasPorDisfrute = 15;
        } elseif ($aniosReales > 5) {
            $diasPorDisfrute = (($aniosReales - 1) / 5) + 15;
        } else {
            $diasPorDisfrute = 0;
        }

        if ($diasPorDisfrute > 0) {
            VacacionesPorDisfrute::create([
                'empleado_id' => $empleado->id,
                'dias_por_disfrute' => $diasPorDisfrute,
            ]);
        }

        return redirect()->back()->with('success', 'Antigüedad y días por disfrute actualizados correctamente.');
    }

    // Obtener colección de empleados con antigüedad pendiente (años reales > tiempo_antiguedad almacenado)
    protected function getEmpleadosConAntiguedadPendiente()
    {
        $hoy = Carbon::now();
        $empleados = Empleado::whereNotNull('fecha_ingreso')->get();

        $pendientes = $empleados->filter(function ($empleado) use ($hoy) {
            $fechaIngreso = Carbon::parse($empleado->fecha_ingreso);
            // diffInYears ya devuelve un entero (años completos), sin decimales
            $aniosReales = $fechaIngreso->diffInYears($hoy);
            $registrado = $empleado->tiempo_antiguedad ?? 0;

            // Solo considerar empleados con al menos 1 año completo de diferencia
            $diferencia = $aniosReales - $registrado;
            return $diferencia >= 1;
        })->values();

        // Adjuntar años reales calculados para mostrar en la vista
        $pendientes->transform(function ($empleado) use ($hoy) {
            $fechaIngreso = Carbon::parse($empleado->fecha_ingreso);
            $empleado->anios_reales = $fechaIngreso->diffInYears($hoy);
            return $empleado;
        });

        return $pendientes;
    }
}
