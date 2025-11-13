<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Vacaciones Aprobada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
        }
        .info-box {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            border-left: 4px solid #10b981;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #6b7280;
        }
        .value {
            color: #111827;
        }
        .success-badge {
            background: #d1fae5;
            color: #065f46;
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-block;
            font-weight: bold;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>✅ Solicitud de Vacaciones Aprobada</h1>
    </div>
    
    <div class="content">
        <p>Estimado/a <strong>{{ $vacacion->empleado->user->nombre ?? '' }} {{ $vacacion->empleado->user->apellido ?? '' }}</strong>,</p>
        
        <p>Nos complace informarle que su solicitud de vacaciones ha sido <span class="success-badge">APROBADA</span></p>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #10b981;">📅 Detalles de las Vacaciones</h3>
            
            <div class="info-row">
                <span class="label">Fecha de Inicio:</span>
                <span class="value">{{ $vacacion->fecha_inicio->format('d/m/Y') }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Fecha de Fin:</span>
                <span class="value">{{ $vacacion->fecha_fin->format('d/m/Y') }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Total de Días:</span>
                <span class="value"><strong>{{ $vacacion->dias_solicitados }} días</strong></span>
            </div>
            
            @if($vacacion->comentario_admin)
            <div class="info-row">
                <span class="label">Comentario:</span>
                <span class="value">{{ $vacacion->comentario_admin }}</span>
            </div>
            @endif
            
            <div class="info-row">
                <span class="label">Aprobado por:</span>
                <span class="value">{{ $vacacion->aprobador->nombre ?? '' }} {{ $vacacion->aprobador->apellido ?? '' }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Fecha de Aprobación:</span>
                <span class="value">{{ $vacacion->fecha_aprobacion->format('d/m/Y H:i') }}</span>
            </div>
        </div>
        
        <p>Por favor, asegúrese de coordinar con su supervisor directo cualquier detalle pendiente antes de su período de vacaciones.</p>
        
        <p style="text-align: center;">
            <a href="{{ url('/vacaciones/' . $vacacion->id) }}" class="button">Ver Detalles de la Solicitud</a>
        </p>
        
        <p>¡Que disfrute sus vacaciones!</p>
    </div>
    
    <div class="footer">
        <p>Este es un correo automático, por favor no responda a este mensaje.</p>
        <p>&copy; {{ date('Y') }} Contraloría del Municipio Independencia - Sistema de Nómina</p>
    </div>
</body>
</html>
