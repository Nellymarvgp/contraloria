<?php

namespace App\Http\Controllers;

use App\Models\NominaDetalle;
use App\Models\Empleado;
use App\Models\PagoVacaciones;
use Illuminate\Http\Request;

class ReciboApiController extends Controller
{
    public function nomina(Request $request)
    {
        $user = $request->user();

        $empleado = Empleado::where('cedula', $user->cedula)->first();

        if (!$empleado) {
            return response()->json([
                'data' => [],
            ]);
        }

        $detalles = NominaDetalle::with('nomina')
            ->where('empleado_id', $empleado->id)
            ->whereHas('nomina')
            ->orderByDesc('created_at')
            ->get();

        $data = $detalles->map(function ($detalle) {
    return [
        'nomina_id'   => $detalle->nomina_id,
        'descripcion' => optional($detalle->nomina)->descripcion,
        'monto'       => $detalle->total,
    ];
});

        return response()->json([
            'data' => $data,
        ]);
    }

    public function recibo(Request $request, $nominaId)
    {
        $user = $request->user();

        $empleado = Empleado::where('cedula', $user->cedula)->first();

        if (!$empleado) {
            return response()->json([
                'data' => null,
            ]);
        }

        $detalle = NominaDetalle::with(['nomina', 'empleado.user', 'conceptos'])
            ->where('empleado_id', $empleado->id)
            ->where('nomina_id', $nominaId)
            ->first();

        if (!$detalle) {
            return response()->json([
                'data' => null,
            ]);
        }

        $conceptos = $detalle->conceptos->map(function ($concepto) {
            return [
                'descripcion' => $concepto->descripcion,
                'tipo' => $concepto->tipo,
                'monto' => $concepto->monto,
            ];
        });

        $totalAsignaciones = $detalle->conceptos()
            ->where('tipo', 'asignacion')
            ->sum('monto');

        $totalDeducciones = $detalle->conceptos()
            ->where('tipo', 'deduccion')
            ->sum('monto');

        $data = [
            'empleado' => [
                'nombre' => optional($detalle->empleado->user)->nombre,
                'apellido' => optional($detalle->empleado->user)->apellido,
                'cedula' => optional($detalle->empleado->user)->cedula,
                'cargo' => $detalle->empleado->cargo,
                'unidad' => $detalle->empleado->unidad,
            ],
            'nomina' => [
                'id' => $detalle->nomina->id,
                'descripcion' => $detalle->nomina->descripcion,
                'desde' => $detalle->nomina->desde,
                'hasta' => $detalle->nomina->hasta,
            ],
            'conceptos' => $conceptos,
            'totales' => [
                'total_asignaciones' => $totalAsignaciones,
                'total_deducciones' => $totalDeducciones,
                'neto_cobrar' => $totalAsignaciones - $totalDeducciones,
            ],
        ];

        return response()->json([
            'data' => $data,
        ]);
    }

    public function vacaciones(Request $request)
    {
        $user = $request->user();

        $empleado = Empleado::where('cedula', $user->cedula)->first();

        if (!$empleado) {
            return response()->json([
                'data' => [],
            ]);
        }

        $pagos = PagoVacaciones::where('empleado_id', $empleado->id)
            ->orderByDesc('created_at')
            ->get();

        $data = $pagos->map(function ($pago) {
            return [
                'id'         => $pago->id,
                'periodo'    => $pago->periodo,
                'monto'      => $pago->monto,
                'year'       => $pago->year,
                'created_at' => $pago->created_at,
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
    }
    public function vacacionesDetalle(Request $request, $id)
    {
        // 1. Obtenemos el usuario autenticado
        $user = $request->user();
        
        // 2. Buscamos el empleado correspondiente
        $empleado = Empleado::where('cedula', $user->cedula)->first();

        if (!$empleado) {
            return response()->json(['data' => null], 404);
        }

        // 3. Buscamos el pago de vacaciones específico para ese empleado
        $pago = PagoVacaciones::where('empleado_id', $empleado->id)
                              ->where('id', $id)
                              ->first();

        // Si no se encuentra el pago, devolvemos un 404
        if (!$pago) {
            return response()->json(['data' => null], 404);
        }

        // 4. Devolvemos los datos del pago
        return response()->json([
            'data' => $pago,
        ]);
    }
}
