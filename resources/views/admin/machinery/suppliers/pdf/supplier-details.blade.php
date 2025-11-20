<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Proveedor - {{ $supplier->supplier }}</title>
    <style>
        @page {
            margin: 10mm;
        }
        
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            .header { page-break-after: avoid; }
            .supplier-card { page-break-inside: avoid; }
            .details-grid { page-break-inside: avoid; }
            .image-section { page-break-inside: avoid; }
            .status-section { page-break-inside: avoid; }
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
        .date-info {
            background: #f5f5f5;
            padding: 6px;
            margin-bottom: 10px;
            border-left: 3px solid #10b981;
            text-align: center;
            font-size: 9px;
        }
        .supplier-card {
            background: white;
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #e5e7eb;
            page-break-inside: avoid;
        }
        .image-section {
            text-align: center;
            margin-bottom: 10px;
            background: #f5f5f5;
            padding: 8px;
            border: 1px solid #cccccc;
            page-break-inside: avoid;
        }
        .image-section h3 {
            color: #10b981;
            margin-bottom: 8px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .machinery-image {
            max-width: 120px;
            max-height: 90px;
            object-fit: contain;
            border: 2px solid #e5e7eb;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
            display: block;
            margin-bottom: 2px;
        }
        .detail-value {
            color: #1f2937;
            font-size: 10px;
        }
        .status-section {
            background: #f5f5f5;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #cccccc;
            page-break-inside: avoid;
        }
        .status-section h3 {
            color: #10b981;
            margin-bottom: 8px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-item {
            margin-bottom: 5px;
        }
        .status-label {
            font-weight: 500;
            color: #374151;
            font-size: 10px;
        }
        .status-value {
            font-weight: bold;
            color: #10b981;
            font-size: 10px;
        }
        .footer {
            margin-top: 10px;
            text-align: center;
            color: #6b7280;
            font-size: 9px;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Detalles de Proveedor</h1>
        <p>Sistema de Gestión de Compostaje CEFA</p>
    </div>

    <div class="date-info">
        <strong>Fecha de generación:</strong> {{ date('d/m/Y H:i:s') }}
    </div>

    <div class="supplier-card">
        @if($supplier->machinery && $supplier->machinery->image && isset($imageBase64))
        <div class="image-section">
            <h3>Imagen de la Maquinaria</h3>
            <img src="{{ $imageBase64 }}" 
                 alt="{{ $supplier->machinery->name }}" 
                 class="machinery-image">
        </div>
        @endif

        <div class="details-grid">
            <div class="detail-section">
                <h3>Información del Proveedor</h3>
                <div class="detail-item">
                    <span class="detail-label">Maquinaria:</span>
                    <span class="detail-value">{{ $supplier->machinery->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Fabricante:</span>
                    <span class="detail-value">{{ $supplier->maker }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Proveedor:</span>
                    <span class="detail-value">{{ $supplier->supplier }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Origen:</span>
                    <span class="detail-value">{{ $supplier->origin }}</span>
                </div>
            </div>

            <div class="detail-section">
                <h3>Información de Contacto</h3>
                <div class="detail-item">
                    <span class="detail-label">Fecha de Compra:</span>
                    <span class="detail-value">{{ $supplier->purchase_date->format('d/m/Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Teléfono:</span>
                    <span class="detail-value">{{ $supplier->phone }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Correo Electrónico:</span>
                    <span class="detail-value">{{ $supplier->email }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">ID del Registro:</span>
                    <span class="detail-value">#{{ $supplier->id }}</span>
                </div>
            </div>
        </div>

        <div class="status-section">
            <h3>Información del Sistema</h3>
            <div class="status-item">
                <span class="status-label">Fecha de registro:</span>
                <span class="status-value">{{ $supplier->created_at->format('d/m/Y H:i:s') }}</span>
            </div>
            <div class="status-item">
                <span class="status-label">Última actualización:</span>
                <span class="status-value">{{ $supplier->updated_at->format('d/m/Y H:i:s') }}</span>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Generado automáticamente por el Sistema de Gestión de Compostaje CEFA</p>
        <p>Comprometidos con el medio ambiente y la educación</p>
    </div>
</body>
</html>
