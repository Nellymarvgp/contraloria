<?php

namespace App\Http\Controllers;

use App\Models\Vacacion;
use App\Models\Empleado;
use App\Mail\VacacionAprobada;
use App\Mail\VacacionRechazada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
            $vacaciones = Vacacion::with(['empleado.user', 'aprobador'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $empleado = Empleado::where('cedula', $user->cedula)->first();
            if (!$empleado) {
                return redirect()->route('dashboard')->with('error', 'No se encontró registro de empleado');
            }
            $vacaciones = Vacacion::where('empleado_id', $empleado->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('vacaciones.index', compact('vacaciones'));
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
        $diasSolicitados = $fechaInicio->diffInDays($fechaFin) + 1;

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
        $vacacion->load(['empleado.user', 'aprobador']);
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
        $diasSolicitados = $fechaInicio->diffInDays($fechaFin) + 1;

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
}
