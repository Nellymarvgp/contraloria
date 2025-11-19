<?php

namespace App\Http\Controllers;

use App\Models\NominaDetalle;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReciboController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Buscar el empleado asociado al usuario por cédula
        $empleado = Empleado::where('cedula', $user->cedula)->first();

        if (!$empleado) {
            return view('recibos.index', [
                'detalles' => collect(),
            ]);
        }

        $detalles = NominaDetalle::with('nomina')
            ->where('empleado_id', $empleado->id)
            ->whereHas('nomina')
            ->orderByDesc('created_at')
            ->get();

        return view('recibos.index', compact('detalles'));
    }

    public function show(NominaDetalle $detalle)
    {
        $user = auth()->user();
        $empleado = Empleado::where('cedula', $user->cedula)->first();

        // Seguridad: asegurar que el recibo corresponde al empleado autenticado
        if (!$empleado || $detalle->empleado_id !== $empleado->id) {
            abort(403);
        }

        $nomina = $detalle->nomina;
        $empleadoModel = $detalle->empleado;

        $recibos = [
            [
                'nomina' => $nomina,
                'detalle' => $detalle,
                'empleado' => $empleadoModel,
            ],
        ];

        $pdf = Pdf::loadView('nominas.recibo', compact('recibos'))->setPaper('A4');
        $filename = 'recibo_nomina_' . $nomina->id . '_empleado_' . $empleadoModel->cedula . '.pdf';

        return $pdf->download($filename);
    }
}
