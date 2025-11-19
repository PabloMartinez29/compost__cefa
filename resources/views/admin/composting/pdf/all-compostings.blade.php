<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Pilas de Compostaje - Sistema Compost CEFA</title>
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
            font-size: 24px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .date-info {
            background: #f5f5f5;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #10b981;
        }
        .compostings-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }
        .compostings-table th {
            background-color: #10b981;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .compostings-table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
        }
        .compostings-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .status-badge {
            padding: 2px 6px;
            font-size: 8px;
            font-weight: bold;
            border-radius: 3px;
        }
        .status-completada {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-en-proceso {
            background-color: #fef3c7;
            color: #92400e;
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
        <h1>Reporte de Pilas de Compostaje del Sistema</h1>
        <p>Sistema de Gestión de Compostaje CEFA</p>
    </div>

    <div class="date-info">
        <strong>Fecha de generación:</strong> {{ date('d/m/Y H:i:s') }}
    </div>

    <table class="compostings-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Pila</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Kg Beneficiados</th>
                <th>Eficiencia</th>
                <th>Ingredientes</th>
                <th>Estado</th>
                <th>Creado por</th>
            </tr>
        </thead>
        <tbody>
            @foreach($compostings as $index => $composting)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $composting->formatted_pile_num }}</td>
                <td>{{ $composting->formatted_start_date }}</td>
                <td>{{ $composting->formatted_end_date ?? 'En proceso' }}</td>
                <td>{{ $composting->formatted_total_kg }}</td>
                <td>{{ $composting->formatted_efficiency }}</td>
                <td>{{ $composting->formatted_total_ingredients }}</td>
                <td>
                    @php
                        $status = $composting->status;
                        $statusClass = match($status) {
                            'Completada' => 'status-completada',
                            default => 'status-en-proceso'
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $status }}</span>
                </td>
                <td>{{ $composting->creator->name ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generado automáticamente por el Sistema de Gestión de Compostaje CEFA</p>
        <p>Comprometidos con el medio ambiente y la educación</p>
    </div>
</body>
</html>


