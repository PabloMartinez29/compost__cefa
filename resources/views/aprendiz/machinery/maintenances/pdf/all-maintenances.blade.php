<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Mantenimientos - Sistema Compost CEFA</title>
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
        .date-info {
            background: #f5f5f5;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #10b981;
        }
        .maintenances-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }
        .maintenances-table th {
            background-color: #10b981;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .maintenances-table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }
        .maintenances-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .type-badge {
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 3px;
        }
        .type-mantenimiento {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .type-operacion {
            background-color: #d1fae5;
            color: #065f46;
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
        <h1>Reporte de Control de Actividades</h1>
        <p>Sistema de Gestión de Compostaje CEFA</p>
    </div>

    <div class="date-info">
        <strong>Fecha de generación:</strong> {{ date('d/m/Y H:i:s') }}
    </div>

    <table class="maintenances-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Maquinaria</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Responsable</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($maintenances as $index => $maintenance)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $maintenance->machinery->name ?? 'N/A' }}</td>
                <td>{{ $maintenance->date->format('d/m/Y') }}</td>
                <td>
                    <span class="type-badge type-{{ strtolower($maintenance->type_name) }}">
                        {{ $maintenance->type_name }}
                    </span>
                </td>
                <td>{{ $maintenance->responsible }}</td>
                <td>{{ Str::limit($maintenance->description, 50) }}</td>
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

