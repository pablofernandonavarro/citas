<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Turno</title>
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
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
        }
        .appointment-details {
            background-color: white;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #4CAF50;
        }
        .detail-row {
            margin: 10px 0;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Confirmación de Turno</h1>
    </div>
    
    <div class="content">
        <p>Estimado/a <strong>{{ $appointment->patient->user->name }}</strong>,</p>
        
        <p>Su turno ha sido confirmado exitosamente. A continuación encontrará los detalles:</p>
        
        <div class="appointment-details">
            <div class="detail-row">
                <span class="detail-label">Fecha:</span>
                {{ $appointment->date->format('d/m/Y') }}
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Horario:</span>
                {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Duración:</span>
                {{ $appointment->duration }} minutos
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Doctor/a:</span>
                {{ $appointment->doctor->user->name }}
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Especialidad:</span>
                {{ $appointment->doctor->speciality->name }}
            </div>
            
            @if($appointment->reason)
            <div class="detail-row">
                <span class="detail-label">Motivo:</span>
                {{ $appointment->reason }}
            </div>
            @endif
        </div>
        
        <p><strong>Importante:</strong> Por favor, llegue 10 minutos antes de su hora programada.</p>
        
        <p>Si necesita cancelar o reprogramar su cita, comuníquese con nosotros con al menos 24 horas de anticipación.</p>
    </div>
    
    <div class="footer">
        <p>Este es un email automático, por favor no responda a este mensaje.</p>
    </div>
</body>
</html>
