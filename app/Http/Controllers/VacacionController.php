<?php

namespace App\Http\Controllers;

use App\Models\Vacacion;
use App\Models\Empleado;
use App\Models\VacacionesPorDisfrute;
use App\Models\PagoVacaciones;
use App\Mail\VacacionAprobada;
use App\Mail\VacacionRechazada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class VacacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $vacaciones = Vacacion::with(['empleado.user', 'empleado.cargo', 'empleado.departamento', 'aprobador'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $empleado = Empleado::where('cedula', $user->cedula)->first();
            if (!$empleado) {
                return redirect()->route('dashboard')->with('error', 'No se encontró registro de empleado');
            }
            $vacaciones = Vacacion::with(['empleado.user', 'empleado.cargo', 'empleado.departamento'])
                ->where('empleado_id', $empleado->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('vacaciones.index', compact('vacaciones'));
    }

    /**
     * Listar empleados con vacaciones pendientes de pago (pvacaciones = false).
     * Solo accesible para administradores.
     */
    public function porPagar()
    {
        $user = auth()->user();
        if (!$user->isAdmin()) {
            return redirect()->route('vacaciones.index')->with('error', 'Acceso no autorizado.');
        }

        // Si la columna pvacaciones aún no existe (migración no ejecutada),
        // devolvemos una colección vacía para evitar errores SQL.
        if (!Schema::hasColumn('empleados', 'pvacaciones')) {
            $empleados = collect();
        } else {
            $empleados = Empleado::with(['user', 'cargo', 'departamento'])
                ->where('pvacaciones', false)
                ->get();
        }

        return view('vacaciones.por_pagar', compact('empleados'));
    }

    /**
     * Resumen de vacaciones por disfrute por empleado (solo administrador).
     */
    public function disfruteResumen()
    {
        $user = auth()->user();
        if (!$user->isAdmin()) {
            return redirect()->route('vacaciones.index')->with('error', 'Acceso no autorizado.');
        }

        // Cargar empleados con sus registros de disfrute y vacaciones aprobadas
        $empleados = Empleado::with([
            'user',
            'vacacionesPorDisfrute',
            'vacaciones' => function ($q) {
                $q->where('estado', 'aprobada');
            },
        ])->get();

        $resumen = $empleados->map(function ($empleado) {
            $diasAsignados = $empleado->vacacionesPorDisfrute->sum('dias_por_disfrute');
            $diasTomados = $empleado->vacaciones->sum('dias_solicitados');
            $diasRestantes = max($diasAsignados - $diasTomados, 0);

            return [
                'empleado' => $empleado,
                'dias_asignados' => $diasAsignados,
                'dias_tomados' => $diasTomados,
                'dias_restantes' => $diasRestantes,
            ];
        })->filter(function ($item) {
            // Mostrar solo empleados que tengan al menos algún día asignado o tomado
            return $item['dias_asignados'] > 0 || $item['dias_tomados'] > 0;
        })->values();

        return view('vacaciones.disfrute', compact('resumen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $empleado = Empleado::where('cedula', $user->cedula)->first();
        
        if (!$empleado) {
            return redirect()->route('dashboard')->with('error', 'No se encontró registro de empleado');
        }

        return view('vacaciones.create', compact('empleado'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'motivo' => 'nullable|string|max:500'
        ]);

        $fechaInicio = Carbon::parse($request->fecha_inicio);
        $fechaFin = Carbon::parse($request->fecha_fin);
        $diasSolicitados = $this->calcularDiasHabiles($fechaInicio, $fechaFin);

        $empleado = Empleado::findOrFail($request->empleado_id);
        $diasDisponibles = $this->calcularDiasDisponibles($empleado);

        if ($diasSolicitados > $diasDisponibles) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No puede solicitar más días de vacaciones que los disponibles. Días solicitados: ' . $diasSolicitados . ', días disponibles: ' . $diasDisponibles . '.');
        }

        Vacacion::create([
            'empleado_id' => $request->empleado_id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'dias_solicitados' => $diasSolicitados,
            'motivo' => $request->motivo,
            'estado' => 'pendiente'
        ]);

        return redirect()->route('vacaciones.index')
            ->with('success', 'Solicitud de vacaciones enviada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vacacion $vacacion)
    {
        $vacacion->load(['empleado.user', 'empleado.cargo', 'empleado.departamento', 'aprobador']);
        return view('vacaciones.show', compact('vacacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vacacion $vacacion)
    {
        // Solo se puede editar si está pendiente
        if ($vacacion->estado !== 'pendiente') {
            return redirect()->route('vacaciones.index')
                ->with('error', 'No se puede editar una solicitud ya procesada');
        }

        $empleado = $vacacion->empleado;
        return view('vacaciones.edit', compact('vacacion', 'empleado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vacacion $vacacion)
    {
        if ($vacacion->estado !== 'pendiente') {
            return redirect()->route('vacaciones.index')
                ->with('error', 'No se puede editar una solicitud ya procesada');
        }

        $request->validate([
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'motivo' => 'nullable|string|max:500'
        ]);

        $fechaInicio = Carbon::parse($request->fecha_inicio);
        $fechaFin = Carbon::parse($request->fecha_fin);
        $diasSolicitados = $this->calcularDiasHabiles($fechaInicio, $fechaFin);

        $empleado = $vacacion->empleado;
        if ($empleado) {
            $diasDisponibles = $this->calcularDiasDisponibles($empleado, $vacacion->id);
            if ($diasSolicitados > $diasDisponibles) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'No puede solicitar más días de vacaciones que los disponibles. Días solicitados: ' . $diasSolicitados . ', días disponibles: ' . $diasDisponibles . '.');
            }
        }

        $vacacion->update([
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'dias_solicitados' => $diasSolicitados,
            'motivo' => $request->motivo
        ]);

        return redirect()->route('vacaciones.index')
            ->with('success', 'Solicitud actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vacacion $vacacion)
    {
        if ($vacacion->estado !== 'pendiente') {
            return redirect()->route('vacaciones.index')
                ->with('error', 'No se puede eliminar una solicitud ya procesada');
        }

        $vacacion->delete();

        return redirect()->route('vacaciones.index')
            ->with('success', 'Solicitud eliminada correctamente');
    }

    /**
     * Aprobar solicitud de vacaciones
     */
    public function aprobar(Request $request, Vacacion $vacacion)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('vacaciones.index')
                ->with('error', 'No tiene permisos para aprobar solicitudes');
        }

        $request->validate([
            'comentario_admin' => 'nullable|string|max:500'
        ]);

        $vacacion->update([
            'estado' => 'aprobada',
            'comentario_admin' => $request->comentario_admin,
            'aprobado_por' => auth()->id(),
            'fecha_aprobacion' => now()
        ]);

        // Enviar correo de aprobación
        $empleadoUser = $vacacion->empleado->user;
        if ($empleadoUser && $empleadoUser->email) {
            Mail::to($empleadoUser->email)->send(new VacacionAprobada($vacacion));
        }

        return redirect()->route('vacaciones.index')
            ->with('success', 'Solicitud aprobada y notificación enviada');
    }

    /**
     * Rechazar solicitud de vacaciones
     */
    public function rechazar(Request $request, Vacacion $vacacion)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('vacaciones.index')
                ->with('error', 'No tiene permisos para rechazar solicitudes');
        }

        $request->validate([
            'comentario_admin' => 'required|string|max:500'
        ]);

        $vacacion->update([
            'estado' => 'rechazada',
            'comentario_admin' => $request->comentario_admin,
            'aprobado_por' => auth()->id(),
            'fecha_aprobacion' => now()
        ]);

        // Enviar correo de rechazo
        $empleadoUser = $vacacion->empleado->user;
        if ($empleadoUser && $empleadoUser->email) {
            Mail::to($empleadoUser->email)->send(new VacacionRechazada($vacacion));
        }

        return redirect()->route('vacaciones.index')
            ->with('success', 'Solicitud rechazada y notificación enviada');
    }

    /**
     * Marcar el pago de vacaciones pendientes para un empleado.
     * La lógica concreta de negocio se definirá posteriormente.
     */
    public function pagarPendiente(Empleado $empleado)
    {
        $user = auth()->user();
        if (!$user->isAdmin()) {
            return redirect()->route('vacaciones.por_pagar')->with('error', 'Acceso no autorizado.');
        }

        $year = now()->year;
        $periodo = ($year - 1) . ' - ' . $year;
        $monto = round(($empleado->salario / 30) * 15, 2);

        $empleado->pvacaciones = true;
        $empleado->save();

        PagoVacaciones::create([
            'empleado_id' => $empleado->id,
            'periodo' => $periodo,
            'monto' => $monto,
            'year' => $year,
        ]);

        $pdf = Pdf::loadView('vacaciones.pago_pdf', [
            'empleado' => $empleado,
            'periodo' => $periodo,
            'monto' => $monto,
            'year' => $year,
        ])->setPaper('A4', 'portrait');

        $filename = 'pago_vacaciones_' . $empleado->cedula . '_' . $year . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Calcular días hábiles (lunes a viernes) entre dos fechas inclusive.
     */
    protected function calcularDiasHabiles(Carbon $inicio, Carbon $fin): int
    {
        $dias = 0;
        $fecha = $inicio->copy();

        while ($fecha->lessThanOrEqualTo($fin)) {
            // 1 = lunes, 7 = domingo; contar solo 1-5
            if ($fecha->isWeekday()) {
                $dias++;
            }
            $fecha->addDay();
        }

        return $dias;
    }

    /**
     * Calcular días de vacaciones disponibles para un empleado
     * (días asignados por disfrute menos días ya tomados en solicitudes aprobadas).
     * Se puede excluir una vacación específica (por ejemplo, al editar).
     */
    protected function calcularDiasDisponibles(Empleado $empleado, $excluirVacacionId = null): int
    {
        // Días asignados por disfrute
        $diasAsignados = $empleado->vacacionesPorDisfrute()->sum('dias_por_disfrute');

        // Días ya tomados en vacaciones aprobadas
        $vacacionesAprobadas = $empleado->vacaciones()
            ->where('estado', 'aprobada');

        if ($excluirVacacionId) {
            $vacacionesAprobadas->where('id', '!=', $excluirVacacionId);
        }

        $diasTomados = $vacacionesAprobadas->sum('dias_solicitados');

        return max($diasAsignados - $diasTomados, 0);
    }
}
