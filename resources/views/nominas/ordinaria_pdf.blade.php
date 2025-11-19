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
    <div class="title">NÓMINA ORDINARIA</div>
    <div class="subtitle">
        {{ $nomina->despacho }}<br>
        Periodo: {{ $nomina->fecha_inicio->format('d/m/Y') }} - {{ $nomina->fecha_fin->format('d/m/Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>NOMBRE(S) Y APELLIDO(S)</th>
                <th>CEDULA</th>
                <th>SUELDO BASE</th>
                <th>PRIMA DE PROFESIONALIZACION</th>
                <th>PRIMA DE ANTIGUEDAD</th>
                <th>PRIMA POR HIJO</th>
                <th>COMIDA</th>
                <th>OTRAS PRIMAS</th>
                <th>RET IVSS</th>
                <th>RET PIE</th>
                <th>RET LPH</th>
                <th>RET FPJ</th>
                <th>ORDINARIA</th>
                <th>INCENTIVO</th>
                <th>FERIADO</th>
                <th>GASTOS DE REPRESENTACION</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nomina->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->empleado->user->nombre ?? 'N/A' }} {{ $detalle->empleado->user->apellido ?? 'N/A' }}</td>
                    <td class="text-center">{{ $detalle->empleado->cedula }}</td>
                    <td class="text-right">{{ number_format($detalle->sueldo_basico, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detalle->prima_profesionalizacion, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detalle->prima_antiguedad, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detalle->prima_por_hijo, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detalle->comida, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detalle->otras_primas, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detalle->ret_ivss, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detalle->ret_pie, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detalle->ret_lph, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detalle->ret_fpj, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detalle->ordinaria, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detalle->incentivo, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detalle->feriado, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detalle->gastos_representacion, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detalle->total, 2, ',', '.') }}</td>
                </tr>
            @endforeach

            <tr class="totals-row">
                <td colspan="2" class="text-right">TOTALES:</td>
                <td class="text-right">{{ number_format($nomina->detalles->sum('sueldo_basico'), 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($nomina->detalles->sum('prima_profesionalizacion'), 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($nomina->detalles->sum('prima_antiguedad'), 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($nomina->detalles->sum('prima_por_hijo'), 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($nomina->detalles->sum('comida'), 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($nomina->detalles->sum('otras_primas'), 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($nomina->detalles->sum('ret_ivss'), 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($nomina->detalles->sum('ret_pie'), 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($nomina->detalles->sum('ret_lph'), 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($nomina->detalles->sum('ret_fpj'), 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($nomina->detalles->sum('ordinaria'), 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($nomina->detalles->sum('incentivo'), 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($nomina->detalles->sum('feriado'), 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($nomina->detalles->sum('gastos_representacion'), 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($nomina->detalles->sum('total'), 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
