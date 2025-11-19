<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos los Seguimientos - Sistema de Gestión de Compostaje CEFA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #ffffff;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #10b981;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
        }
        .date-info {
            background: #f5f5f5;
            padding: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #10b981;
            text-align: center;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background-color: #10b981;
            color: white;
        }
        th {
            padding: 8px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            border: 1px solid #0d9968;
        }
        td {
            padding: 6px;
            border: 1px solid #e5e7eb;
            font-size: 8px;
        }
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        tbody tr:hover {
            background-color: #f3f4f6;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Todos los Seguimientos de Compostaje</h1>
        <p>Sistema de Gestión de Compostaje CEFA</p>
    </div>

    <div class="date-info">
        <strong>Fecha de generación:</strong> {{ date('d/m/Y H:i:s') }} | <strong>Total de seguimientos:</strong> {{ $trackings->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Pila</th>
                <th>Día</th>
                <th>Fecha</th>
                <th>Actividad</th>
                <th>Temp. Interna</th>
                <th>Temp. Ambiente</th>
                <th>Humedad Pila</th>
                <th>Humedad Ambiente</th>
                <th>pH</th>
                <th>Agua (L)</th>
                <th>Cal (Kg)</th>
                <th>Horas Trabajo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($trackings as $tracking)
                <tr>
                    <td class="text-center">{{ $tracking->id }}</td>
                    <td>{{ $tracking->composting->formatted_pile_num ?? 'N/A' }}</td>
                    <td class="text-center">Día {{ $tracking->day }}</td>
                    <td>{{ $tracking->date->format('d/m/Y') }}</td>
                    <td>{{ Str::limit($tracking->activity, 30) }}</td>
                    <td class="text-center">{{ $tracking->temp_internal }}°C</td>
                    <td class="text-center">{{ $tracking->temp_env }}°C</td>
                    <td class="text-center">{{ $tracking->hum_pile }}%</td>
                    <td class="text-center">{{ $tracking->hum_env }}%</td>
                    <td class="text-center">{{ $tracking->ph }}</td>
                    <td class="text-center">{{ $tracking->water }}</td>
                    <td class="text-center">{{ $tracking->lime }}</td>
                    <td class="text-center">{{ $tracking->work_hours }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center">No hay seguimientos registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generado automáticamente por el Sistema de Gestión de Compostaje CEFA</p>
        <p>Comprometidos con el medio ambiente y la educación</p>
    </div>
</body>
</html>


