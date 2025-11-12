<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Turno Asignado</title>
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
            background-color: #2196F3;
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
            border-left: 4px solid #2196F3;
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
        <h1>Nuevo Turno Asignado</h1>
    </div>
    
    <div class="content">
        <p>Dr./Dra. <strong>{{ $appointment->doctor->user->name }}</strong>,</p>
        
        <p>Se ha programado un nuevo turno en su agenda:</p>
        
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
                <span class="detail-label">Paciente:</span>
                {{ $appointment->patient->user->name }}
            </div>
            
            @if($appointment->patient->date_of_birth)
            <div class="detail-row">
                <span class="detail-label">Edad:</span>
                {{ \Carbon\Carbon::parse($appointment->patient->date_of_birth)->age }} años
            </div>
            @endif
            
            @if($appointment->patient->socialWork)
            <div class="detail-row">
                <span class="detail-label">Obra Social:</span>
                {{ $appointment->patient->socialWork->name }}
            </div>
            @endif
            
            @if($appointment->reason)
            <div class="detail-row">
                <span class="detail-label">Motivo de consulta:</span>
                {{ $appointment->reason }}
            </div>
            @endif
        </div>
        
        <p>Puede revisar los detalles completos de la cita en el sistema.</p>
    </div>
    
    <div class="footer">
        <p>Este es un email automático, por favor no responda a este mensaje.</p>
    </div>
</body>
</html>
