<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Vacaciones Rechazada</title>
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
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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
            border-left: 4px solid #ef4444;
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
        .reject-badge {
            background: #fee2e2;
            color: #991b1b;
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-block;
            font-weight: bold;
            margin: 10px 0;
        }
        .reason-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: #ef4444;
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
        <h1>❌ Solicitud de Vacaciones Rechazada</h1>
    </div>
    
    <div class="content">
        <p>Estimado/a <strong>{{ $vacacion->empleado->user->nombre ?? '' }} {{ $vacacion->empleado->user->apellido ?? '' }}</strong>,</p>
        
        <p>Lamentamos informarle que su solicitud de vacaciones ha sido <span class="reject-badge">RECHAZADA</span></p>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #ef4444;">📅 Detalles de la Solicitud</h3>
            
            <div class="info-row">
                <span class="label">Fecha de Inicio Solicitada:</span>
                <span class="value">{{ $vacacion->fecha_inicio->format('d/m/Y') }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Fecha de Fin Solicitada:</span>
                <span class="value">{{ $vacacion->fecha_fin->format('d/m/Y') }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Total de Días Solicitados:</span>
                <span class="value"><strong>{{ $vacacion->dias_solicitados }} días</strong></span>
            </div>
            
            <div class="info-row">
                <span class="label">Rechazado por:</span>
                <span class="value">{{ $vacacion->aprobador->nombre ?? '' }} {{ $vacacion->aprobador->apellido ?? '' }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Fecha de Rechazo:</span>
                <span class="value">{{ $vacacion->fecha_aprobacion->format('d/m/Y H:i') }}</span>
            </div>
        </div>
        
        @if($vacacion->comentario_admin)
        <div class="reason-box">
            <h4 style="margin-top: 0; color: #991b1b;">📝 Motivo del Rechazo:</h4>
            <p style="margin: 0;">{{ $vacacion->comentario_admin }}</p>
        </div>
        @endif
        
        <p>Si tiene alguna pregunta o desea discutir esta decisión, por favor contacte con su supervisor o el departamento de recursos humanos.</p>
        
        <p>Puede realizar una nueva solicitud de vacaciones con fechas diferentes si lo desea.</p>
        
        <p style="text-align: center;">
            <a href="{{ url('/vacaciones/' . $vacacion->id) }}" class="button">Ver Detalles de la Solicitud</a>
        </p>
    </div>
    
    <div class="footer">
        <p>Este es un correo automático, por favor no responda a este mensaje.</p>
        <p>&copy; {{ date('Y') }} Contraloría del Municipio Independencia - Sistema de Nómina</p>
    </div>
</body>
</html>
