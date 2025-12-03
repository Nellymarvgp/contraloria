<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pago de Vacaciones</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .container { width: 100%; }
        .titulo { text-align: center; font-weight: bold; font-size: 18px; margin-bottom: 4px; }
        .title { text-align: center; font-weight: bold; font-size: 16px; margin-bottom: 6px; }
        .subtitle { text-align: center; font-size: 12px; margin-bottom: 20px; }
        .section-title { font-weight: bold; margin-top: 10px; margin-bottom: 5px; }
        .row { margin-bottom: 4px; }
        .label { display: inline-block; width: 140px; font-weight: bold; }
        .value { display: inline-block; }
        .text-right { text-align: right; }
        .mt-20 { margin-top: 60px; }
        .signature-box { margin-top: 40px; text-align: center; }
        .signature-line { border-top: 1px solid #000; width: 220px; margin: 0 auto; margin-top: 40px; }
    </style>
</head>
<body>
    <div class="container">
        <div style="position: relative;">
            <img src="{{ public_path('images/logo.jpeg') }}" alt="Logo" style="position: absolute; top: 0; right: 0; height: 40px;">
            <div class="titulo">CONTRALORÍA DEL MUNICIPIO INDEPENDENCIA</div>
        </div>
        <div class="title">PAGO DE VACACIONES</div>
        <div class="subtitle">
            Periodo a pagar: {{ $periodo }}
        </div>

        <div class="section-title">Datos del Empleado</div>
        <div class="row">
            <span class="label">Nombre:</span>
            <span class="value">{{ optional($empleado->user ?? $empleadoModel->user ?? null)->nombre ?? '' }} {{ optional($empleado->user ?? $empleadoModel->user ?? null)->apellido ?? '' }}</span>
        </div>
        <div class="row">
            <span class="label">Cédula:</span>
            <span class="value">{{ ($empleado->cedula ?? $empleadoModel->cedula) }}</span>
        </div>
        <div class="row">
            <span class="label">Departamento:</span>
            <span class="value">{{ optional(($empleado->departamento ?? $empleadoModel->departamento ?? null))->nombre ?? 'N/A' }}</span>
        </div>
        <div class="row">
            <span class="label">Cargo:</span>
            <span class="value">{{ optional(($empleado->cargo ?? $empleadoModel->cargo ?? null))->nombre ?? 'N/A' }}</span>
        </div>

        <div class="section-title" style="margin-top: 15px;">Detalle del Pago</div>
        <div class="row">
            <span class="label">Período de pago:</span>
            <span class="value">{{ $periodo ?? 'N/A' }}</span>
        </div>
        <div class="row">
            <span class="label">Días base (15):</span>
            <span class="value">{{ $dias_base ?? 15 }} días</span>
        </div>
        <div class="row">
            <span class="label">Días adicionales:</span>
            <span class="value">{{ $dias_adicionales ?? 0 }} días</span>
        </div>
        <div class="row">
            <span class="label">Total días a pagar:</span>
            <span class="value"><strong>{{ $dias_pagados ?? 0 }} días</strong></span>
        </div>
        <div class="row">
            <span class="label">Salario base:</span>
            <span class="value">{{ number_format($empleado->salario ?? 0, 2, ',', '.') }} Bs</span>
        </div>
        <div class="row">
            <span class="label">Monto a pagar:</span>
            <span class="value"><strong>{{ number_format($monto ?? 0, 2, ',', '.') }} Bs</strong></span>
        </div>
       
        <div class="signature-box mt-20">
            <div class="signature-line"></div>
            <div>Firma del empleador</div>
        </div>
    </div>
</body>
</html>
