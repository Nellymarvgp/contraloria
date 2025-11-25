<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nómina Ordinaria</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 3px; }
        th { background-color: #e5f5e0; font-size: 9px; }
        .title { text-align: center; font-weight: bold; font-size: 14px; margin-bottom: 5px; }
        .subtitle { text-align: center; font-size: 11px; margin-bottom: 10px; }
        .totals-row { background-color: #f0f0f0; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div style="position: relative;">
        <img src="{{ public_path('images/logo.jpeg') }}" alt="Logo" style="position: absolute; top: 0; right: 0; height: 40px;">
        <div class="title">NÓMINA ORDINARIA</div>
    </div>
    <div class="subtitle">
        {{ $nomina->despacho }}<br>
        Periodo: {{ $nomina->fecha_inicio->format('d/m/Y') }} - {{ $nomina->fecha_fin->format('d/m/Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>CEDULA</th>
                <th>NOMBRE(S) Y APELLIDO(S)</th>
                <th>SUELDO BÁSICO</th>
                <th>PRIMA PROF.</th>
                <th>PRIMA ANTIG.</th>
                <th>PRIMA POR HIJO</th>
                @foreach($beneficios as $beneficio)
                    <th>{{ strtoupper($beneficio->beneficio) }}</th>
                @endforeach
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nomina->detalles as $detalle)
                <tr>
                    <td class="text-center">{{ $detalle->empleado->cedula }}</td>
                    <td>{{ $detalle->empleado->user->nombre ?? 'N/A' }} {{ $detalle->empleado->user->apellido ?? 'N/A' }}</td>
                    <td class="text-right">{{ number_format($detalle->sueldo_basico, 2, ',', '.') }} Bs</td>
                    <td class="text-right">{{ number_format($detalle->prima_profesionalizacion, 2, ',', '.') }} Bs</td>
                    <td class="text-right">{{ number_format($detalle->prima_antiguedad, 2, ',', '.') }} Bs</td>
                    <td class="text-right">{{ number_format($detalle->prima_por_hijo, 2, ',', '.') }} Bs</td>
                    @php
                        $totalAsignacionesFilaPdf = $detalle->sueldo_basico
                            + $detalle->prima_profesionalizacion
                            + $detalle->prima_antiguedad
                            + $detalle->prima_por_hijo;
                    @endphp
                    @foreach($beneficios as $beneficio)
                        @php
                            $concepto = $detalle->conceptos
                                ->where('tipo', 'asignacion')
                                ->firstWhere('descripcion', $beneficio->beneficio);
                            $monto = $concepto ? $concepto->monto : 0;
                            $totalAsignacionesFilaPdf += $monto;
                        @endphp
                        <td class="text-right">{{ number_format($monto, 2, ',', '.') }} Bs</td>
                    @endforeach
                    <td class="text-right">{{ number_format($totalAsignacionesFilaPdf, 2, ',', '.') }} Bs</td>
                </tr>
            @endforeach

            @php
                $totalSueldoBasicoPdf = $nomina->detalles->sum('sueldo_basico');
                $totalPrimaProfPdf = $nomina->detalles->sum('prima_profesionalizacion');
                $totalPrimaAntigPdf = $nomina->detalles->sum('prima_antiguedad');
                $totalPrimaHijoPdf = $nomina->detalles->sum('prima_por_hijo');

                $totalBeneficiosDinamicosPdf = 0;
                foreach ($beneficios as $beneficioColPdf) {
                    foreach ($nomina->detalles as $detalleTotalPdf) {
                        $conceptoPdf = $detalleTotalPdf->conceptos
                            ->where('tipo', 'asignacion')
                            ->firstWhere('descripcion', $beneficioColPdf->beneficio);
                        if ($conceptoPdf) {
                            $totalBeneficiosDinamicosPdf += $conceptoPdf->monto;
                        }
                    }
                }

                $totalGeneralNominaOrdinariaPdf = $totalSueldoBasicoPdf
                    + $totalPrimaProfPdf
                    + $totalPrimaAntigPdf
                    + $totalPrimaHijoPdf
                    + $totalBeneficiosDinamicosPdf;
            @endphp

            <tr class="totals-row">
                <td></td>
                <td class="text-right">TOTALES:</td>
                <td class="text-right">{{ number_format($totalSueldoBasicoPdf, 2, ',', '.') }} Bs</td>
                <td class="text-right">{{ number_format($totalPrimaProfPdf, 2, ',', '.') }} Bs</td>
                <td class="text-right">{{ number_format($totalPrimaAntigPdf, 2, ',', '.') }} Bs</td>
                <td class="text-right">{{ number_format($totalPrimaHijoPdf, 2, ',', '.') }} Bs</td>
                @foreach($beneficios as $beneficio)
                    @php
                        $totalBeneficioPdf = 0;
                        foreach ($nomina->detalles as $detalleTotalBeneficioPdf) {
                            $concepto = $detalleTotalBeneficioPdf->conceptos
                                ->where('tipo', 'asignacion')
                                ->firstWhere('descripcion', $beneficio->beneficio);
                            if ($concepto) {
                                $totalBeneficioPdf += $concepto->monto;
                            }
                        }
                    @endphp
                    <td class="text-right">{{ number_format($totalBeneficioPdf, 2, ',', '.') }} Bs</td>
                @endforeach
                <td class="text-right">{{ number_format($totalGeneralNominaOrdinariaPdf, 2, ',', '.') }} Bs</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
