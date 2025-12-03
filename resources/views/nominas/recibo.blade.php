<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo de Nómina</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        .titulo { font-weight: bold; text-align: center; font-size: 16px; }
        .tabla { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .tabla th, .tabla td { border: 1px solid #333; padding: 4px; }
        .firma { margin-top: 30px; }
        .firma-linea { border-top: 1px solid #333; width: 200px; margin: 0 auto; text-align: center; }
        .datos { margin-bottom: 10px; }
        .page-break { page-break-after: always; }
        .logo { width: 70px; float: right; margin-bottom: 10px; }
    </style>
</head>
<body>
    @foreach($recibos as $recibo)
        <img src="{{ public_path('images/logo.jpeg') }}" class="logo" alt="Logo">
        <div class="titulo">CONTRALORÍA DEL MUNICIPIO INDEPENDENCIA<br>RECIBO DE NÓMINA</div>
        <div class="datos">
            <b>Nómina:</b> {{ $recibo['nomina']->descripcion }}<br>
            <b>Periodo:</b> {{ $recibo['nomina']->fecha_inicio->format('d/m/Y') }} al {{ $recibo['nomina']->fecha_fin->format('d/m/Y') }}<br>
            <b>Despacho:</b> {{ $recibo['empleado']->departamento->nombre ?? '' }}<br>
            <b>Empleado:</b> {{ $recibo['empleado']->user->nombre }} {{ $recibo['empleado']->user->apellido }}<br>
            <b>Cédula:</b> {{ $recibo['empleado']->cedula }}<br>
            <b>Fecha de Ingreso:</b> {{ $recibo['empleado']->fecha_ingreso ? $recibo['empleado']->fecha_ingreso->format('d/m/Y') : '' }}<br>
            <b>Cargo:</b> {{ $recibo['empleado']->cargo->nombre ?? '' }}
        </div>
        <table class="tabla">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Concepto</th>
                    <th>Asignación</th>
                    <th>Deducción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recibo['detalle']->conceptos()->get() as $concepto)
                    <tr>
                        <td></td>
                        <td>{{ $concepto->descripcion }}</td>
                        <td>{{ $concepto->tipo == 'asignacion' ? number_format($concepto->monto, 2) . ' Bs' : '' }}</td>
                        <td>{{ $concepto->tipo == 'deduccion' ? number_format($concepto->monto, 2) . ' Bs' : '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        @php(
            $totalAsignaciones = $recibo['detalle']->conceptos()
                ->where('tipo','asignacion')
                ->sum('monto')
        )
        @php(
            $totalDeducciones = $recibo['detalle']->conceptos()
                ->where('tipo','deduccion')
                ->sum('monto')
        )
        <b>Total Asignaciones:</b> {{ number_format($totalAsignaciones, 2) }} Bs<br>
        <b>Total Deducciones:</b> {{ number_format($totalDeducciones, 2) }} Bs<br>
        <b>Neto a Cobrar:</b> {{ number_format($totalAsignaciones - $totalDeducciones, 2) }} Bs
        <div class="firma">
            <br><br>
            <div class="firma-linea">{{ $recibo['empleado']->user->nombre }} {{ $recibo['empleado']->user->apellido }}</div>
            
        </div>
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
