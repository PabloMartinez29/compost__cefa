<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Control de Uso - {{ $usageControl->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #ffffff;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #10b981;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }
        .header p {
            margin: 8px 0 0 0;
            font-size: 14px;
        }
        .date-info {
            background: #f5f5f5;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #10b981;
            text-align: center;
        }
        .usage-control-card {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
        }
        .image-section {
            text-align: center;
            margin-bottom: 30px;
            background: #f5f5f5;
            padding: 15px;
            border: 1px solid #cccccc;
        }
        .machinery-image {
            max-width: 400px;
            max-height: 300px;
            object-fit: contain;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .details-grid {
            display: table;
            width: 100%;
            margin-top: 20px;
        }
        .detail-section {
            display: table-cell;
            width: 50%;
            background: #f9fafb;
            padding: 15px;
            border-left: 4px solid #10b981;
            vertical-align: top;
        }
        .detail-section h3 {
            color: #10b981;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .detail-item {
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            color: #6b7280;
            font-size: 12px;
            display: block;
            margin-bottom: 4px;
        }
        .detail-value {
            color: #1f2937;
            font-size: 12px;
        }
        .description-section {
            background: #f9fafb;
            padding: 15px;
            margin-top: 20px;
            border-left: 4px solid #10b981;
        }
        .description-section h3 {
            color: #10b981;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .description-text {
            color: #1f2937;
            font-size: 12px;
            line-height: 1.6;
        }
        .status-section {
            background: #f5f5f5;
            padding: 15px;
            margin-top: 20px;
            border: 1px solid #cccccc;
        }
        .status-section h3 {
            color: #10b981;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: bold;
        }
        .status-item {
            margin-bottom: 8px;
        }
        .status-label {
            font-weight: 500;
            color: #374151;
            font-size: 12px;
        }
        .status-value {
            font-weight: bold;
            color: #10b981;
            font-size: 12px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Detalles de Control de Uso del Equipo</h1>
        <p>Sistema de Gestión de Compostaje CEFA</p>
    </div>

    <div class="date-info">
        <strong>Fecha de generación:</strong> {{ date('d/m/Y H:i:s') }}
    </div>

    <div class="usage-control-card">
        @if($usageControl->machinery && $usageControl->machinery->image && isset($imageBase64))
        <div class="image-section">
            <h3 style="color: #10b981; margin-bottom: 15px; font-size: 14px; font-weight: bold; text-transform: uppercase;">Imagen de la Maquinaria</h3>
            <img src="{{ $imageBase64 }}" 
                 alt="{{ $usageControl->machinery->name }}" 
                 class="machinery-image">
        </div>
        @endif

        <div class="details-grid">
            <div class="detail-section">
                <h3>Información General</h3>
                <div class="detail-item">
                    <span class="detail-label">Maquinaria:</span>
                    <span class="detail-value">{{ $usageControl->machinery->name ?? 'N/A' }}</span>
                    @if($usageControl->machinery)
                        <div style="font-size: 11px; color: #6b7280; margin-top: 2px;">
                            {{ $usageControl->machinery->brand }} {{ $usageControl->machinery->model }}
                        </div>
                    @endif
                </div>
                <div class="detail-item">
                    <span class="detail-label">Fecha/Hora Inicio:</span>
                    <span class="detail-value">{{ $usageControl->start_date ? $usageControl->start_date->setTimezone('America/Bogota')->format('d/m/Y h:i A') : 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Fecha/Hora Fin:</span>
                    <span class="detail-value">{{ $usageControl->end_date ? $usageControl->end_date->setTimezone('America/Bogota')->format('d/m/Y h:i A') : 'En uso' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Total Horas de Uso:</span>
                    <span class="detail-value" style="font-weight: bold;">{{ $usageControl->hours ?? 0 }} horas</span>
                </div>
            </div>

            <div class="detail-section">
                <h3>Información del Registro</h3>
                <div class="detail-item">
                    <span class="detail-label">Responsable:</span>
                    <span class="detail-value">{{ $usageControl->responsible }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">ID del Registro:</span>
                    <span class="detail-value">#{{ $usageControl->id }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Fecha de registro:</span>
                    <span class="detail-value">{{ $usageControl->created_at->format('d/m/Y H:i:s') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Última actualización:</span>
                    <span class="detail-value">{{ $usageControl->updated_at->format('d/m/Y H:i:s') }}</span>
                </div>
            </div>
        </div>

        @if($usageControl->description)
        <div class="description-section">
            <h3>Observaciones</h3>
            <div class="description-text">{{ $usageControl->description }}</div>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Generado automáticamente por el Sistema de Gestión de Compostaje CEFA</p>
        <p>Comprometidos con el medio ambiente y la educación</p>
    </div>
</body>
</html>
