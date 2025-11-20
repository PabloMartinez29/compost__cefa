<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Residuo Orgánico #{{ str_pad($organic->id, 3, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            margin: 10mm;
        }
        
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            .header { page-break-after: avoid; }
            .organic-card { page-break-inside: avoid; }
            .details-grid { page-break-inside: avoid; }
            .image-section { page-break-inside: avoid; }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            background-color: #ffffff;
            font-size: 11px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 10px;
            padding: 12px;
            background-color: #10b981;
            color: white;
            page-break-after: avoid;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .header p {
            margin: 4px 0 0 0;
            font-size: 11px;
        }
        .organic-card {
            background: white;
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #e5e7eb;
            page-break-inside: avoid;
        }
        .organic-info {
            text-align: center;
            margin-bottom: 10px;
        }
        .organic-id {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .organic-type {
            display: inline-block;
            padding: 4px 8px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #dbeafe;
            color: #2563eb;
            border-radius: 4px;
        }
        .details-grid {
            display: table;
            width: 100%;
            margin-top: 10px;
            page-break-inside: avoid;
        }
        .detail-section {
            display: table-cell;
            width: 50%;
            background: #f9fafb;
            padding: 10px;
            border-left: 3px solid #10b981;
            vertical-align: top;
        }
        .detail-section h3 {
            color: #10b981;
            margin-bottom: 8px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .detail-item {
            margin-bottom: 6px;
        }
        .detail-label {
            font-weight: bold;
            color: #6b7280;
            font-size: 10px;
        }
        .detail-value {
            color: #1f2937;
            font-size: 10px;
        }
        .image-section {
            background: #f5f5f5;
            padding: 8px;
            margin-top: 10px;
            border: 1px solid #cccccc;
            text-align: center;
            page-break-inside: avoid;
        }
        .image-section h3 {
            color: #10b981;
            margin-bottom: 8px;
            font-size: 11px;
            font-weight: bold;
        }
        .image-section img {
            max-width: 120px;
            max-height: 90px;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
        .footer {
            margin-top: 10px;
            text-align: center;
            color: #6b7280;
            font-size: 9px;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
        }
        .date-info {
            background: #f5f5f5;
            padding: 6px;
            margin-bottom: 10px;
            border-left: 3px solid #10b981;
            text-align: center;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Detalles del Residuo Orgánico</h1>
        <p>Sistema de Gestión de Compostaje CEFA</p>
    </div>

    <div class="date-info">
        <strong>Fecha de generación:</strong> {{ date('d/m/Y H:i:s') }}
    </div>

    <div class="organic-card">
        <div class="organic-info">
            <div class="organic-id">Registro #{{ str_pad($organic->id, 3, '0', STR_PAD_LEFT) }}</div>
            <div class="organic-type">{{ $organic->type_in_spanish }}</div>
        </div>

        <div class="details-grid">
            <div class="detail-section">
                <h3>Información del Registro</h3>
                <div class="detail-item">
                    <span class="detail-label">Fecha:</span>
                    <span class="detail-value">{{ $organic->formatted_date }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tipo:</span>
                    <span class="detail-value">{{ $organic->type_in_spanish }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Peso:</span>
                    <span class="detail-value">{{ $organic->formatted_weight }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Entregado por:</span>
                    <span class="detail-value">{{ $organic->delivered_by }}</span>
                </div>
            </div>

            <div class="detail-section">
                <h3>Información Adicional</h3>
                <div class="detail-item">
                    <span class="detail-label">Recibido por:</span>
                    <span class="detail-value">{{ $organic->received_by }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Creado por:</span>
                    <span class="detail-value">{{ $organic->creator ? $organic->creator->name : 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Fecha de registro:</span>
                    <span class="detail-value">{{ $organic->created_at->format('d/m/Y H:i:s') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Última actualización:</span>
                    <span class="detail-value">{{ $organic->updated_at->format('d/m/Y H:i:s') }}</span>
                </div>
            </div>
        </div>

        @if($organic->notes)
        <div style="background: #f9fafb; padding: 8px; margin-top: 10px; border-left: 3px solid #10b981; width: 100%; display: block; page-break-inside: avoid;">
            <h3 style="color: #10b981; margin-bottom: 6px; font-size: 11px; font-weight: bold; text-transform: uppercase;">Notas</h3>
            <p style="color: #1f2937; font-size: 10px; margin: 0;">{{ $organic->notes }}</p>
        </div>
        @endif

        @if($organic->img && isset($imageBase64))
        <div class="image-section">
            <h3>Imagen del Residuo</h3>
            <img src="{{ $imageBase64 }}" alt="Imagen del residuo orgánico">
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Generado automáticamente por el Sistema de Gestión de Compostaje CEFA</p>
        <p>Comprometidos con el medio ambiente y la educación</p>
    </div>
</body>
</html>

