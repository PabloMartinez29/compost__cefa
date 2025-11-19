<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Control de Uso - Sistema Compost CEFA</title>
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
        .usage-controls-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }
        .usage-controls-table th {
            background-color: #10b981;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .usage-controls-table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }
        .usage-controls-table tr:nth-child(even) {
            background-color: #f9fafb;
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
        <h1>Reporte de Control de Uso del Equipo</h1>
        <p>Sistema de Gestión de Compostaje CEFA</p>
    </div>

    <div class="date-info">
        <strong>Fecha de generación:</strong> {{ date('d/m/Y H:i:s') }}
    </div>

    <table class="usage-controls-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Maquinaria</th>
                <th>Fecha/Hora Inicio</th>
                <th>Fecha/Hora Fin</th>
                <th>Total Horas</th>
                <th>Responsable</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usageControls as $index => $usageControl)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $usageControl->machinery->name ?? 'N/A' }}</td>
                <td>{{ $usageControl->start_date ? $usageControl->start_date->setTimezone('America/Bogota')->format('d/m/Y h:i A') : 'N/A' }}</td>
                <td>{{ $usageControl->end_date ? $usageControl->end_date->setTimezone('America/Bogota')->format('d/m/Y h:i A') : 'En uso' }}</td>
                <td>{{ $usageControl->hours ?? '0' }} horas</td>
                <td>{{ $usageControl->responsible }}</td>
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


