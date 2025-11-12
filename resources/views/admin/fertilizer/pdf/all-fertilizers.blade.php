<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Abonos - Sistema Compost CEFA</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
        }
        
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
            font-size: 24px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .info-section {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
        }
        .info-section h3 {
            color: #10b981;
            margin-bottom: 15px;
            font-size: 16px;
            border-bottom: 2px solid #10b981;
            padding-bottom: 5px;
        }
        .fertilizers-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }
        .fertilizers-table th {
            background-color: #10b981;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .fertilizers-table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
        }
        .fertilizers-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .type-badge {
            padding: 2px 6px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 3px;
        }
        .type-liquid {
            background-color: #dbeafe;
            color: #2563eb;
        }
        .type-solid {
            background-color: #fef3c7;
            color: #d97706;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
        .date-info {
            background: #f5f5f5;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #10b981;
        }
        .statistics {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 10px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }
        .stat-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
        }
        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #10b981;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Abonos Terminados</h1>
        <p>Sistema de Gestión de Compostaje CEFA</p>
    </div>

    <div class="date-info">
        <strong>Fecha de generación:</strong> {{ date('d/m/Y H:i:s') }}
    </div>

    <div class="statistics">
        <div class="stat-item">
            <div class="stat-label">Total Registros</div>
            <div class="stat-value">{{ $fertilizers->count() }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Cantidad Total</div>
            <div class="stat-value">{{ number_format($fertilizers->sum('amount'), 2) }} Kg/L</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Registros Hoy</div>
            <div class="stat-value">{{ $fertilizers->where('date', today())->count() }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Cantidad Hoy</div>
            <div class="stat-value">{{ number_format($fertilizers->where('date', today())->sum('amount'), 2) }} Kg/L</div>
        </div>
    </div>

    <div class="info-section">
        <h3>Lista de Abonos Terminados</h3>
        <table class="fertilizers-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Pila</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Solicitante</th>
                    <th>Destino</th>
                    <th>Recibido Por</th>
                    <th>Entregado Por</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fertilizers as $index => $fertilizer)
                <tr>
                    <td>{{ str_pad($fertilizer->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $fertilizer->formatted_date }}</td>
                    <td>{{ $fertilizer->time }}</td>
                    <td>{{ $fertilizer->composting ? $fertilizer->composting->formatted_pile_num : 'N/A' }}</td>
                    <td>
                        <span class="type-badge type-{{ strtolower($fertilizer->type) }}">
                            {{ $fertilizer->type_in_spanish }}
                        </span>
                    </td>
                    <td>{{ $fertilizer->formatted_amount }}</td>
                    <td>{{ $fertilizer->requester }}</td>
                    <td>{{ $fertilizer->destination }}</td>
                    <td>{{ $fertilizer->received_by }}</td>
                    <td>{{ $fertilizer->delivered_by }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Generado automáticamente por el Sistema de Gestión de Compostaje CEFA</p>
        <p>Comprometidos con el medio ambiente y la educación</p>
    </div>
</body>
</html>

