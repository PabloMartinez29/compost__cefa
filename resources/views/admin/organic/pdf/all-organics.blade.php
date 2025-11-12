<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Residuos Orgánicos - Sistema Compost CEFA</title>
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
        .organics-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }
        .organics-table th {
            background-color: #10b981;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .organics-table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }
        .organics-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .type-badge {
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 3px;
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
        <h1>Reporte de Residuos Orgánicos</h1>
        <p>Sistema de Gestión de Compostaje CEFA</p>
    </div>

    <div class="date-info">
        <strong>Fecha de generación:</strong> {{ date('d/m/Y H:i:s') }}
    </div>

    <div class="statistics">
        <div class="stat-item">
            <div class="stat-label">Total Registros</div>
            <div class="stat-value">{{ $organics->count() }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Peso Total</div>
            <div class="stat-value">{{ number_format($organics->sum('weight'), 2) }} Kg</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Registros Hoy</div>
            <div class="stat-value">{{ $organics->where('date', today())->count() }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Peso Hoy</div>
            <div class="stat-value">{{ number_format($organics->where('date', today())->sum('weight'), 2) }} Kg</div>
        </div>
    </div>

    <div class="info-section">
        <h3>Lista de Residuos Orgánicos</h3>
        <table class="organics-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Peso (Kg)</th>
                    <th>Entregado Por</th>
                    <th>Recibido Por</th>
                    <th>Creado Por</th>
                </tr>
            </thead>
            <tbody>
                @foreach($organics as $index => $organic)
                <tr>
                    <td>{{ str_pad($organic->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $organic->formatted_date }}</td>
                    <td>
                        <span class="type-badge" style="background-color: #dbeafe; color: #2563eb;">
                            {{ $organic->type_in_spanish }}
                        </span>
                    </td>
                    <td>{{ number_format($organic->weight, 2) }}</td>
                    <td>{{ $organic->delivered_by }}</td>
                    <td>{{ $organic->received_by }}</td>
                    <td>{{ $organic->creator ? $organic->creator->name : 'N/A' }}</td>
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

