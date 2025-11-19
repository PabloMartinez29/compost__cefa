<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Maquinarias - Sistema Compost CEFA</title>
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
        .machineries-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }
        .machineries-table th {
            background-color: #10b981;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .machineries-table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }
        .machineries-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .status-badge {
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 3px;
        }
        .status-operacion {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-mantenimiento {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-requerido {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .status-sin-actividad {
            background-color: #f3f4f6;
            color: #374151;
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
        <h1>Reporte de Maquinarias del Sistema</h1>
        <p>Sistema de Gestión de Compostaje CEFA</p>
    </div>

    <div class="date-info">
        <strong>Fecha de generación:</strong> {{ date('d/m/Y H:i:s') }}
    </div>

    <table class="machineries-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Serie</th>
                <th>Estado</th>
                <th>Frecuencia Mant.</th>
                <th>Fecha Inicio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($machineries as $index => $machinery)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $machinery->name }}</td>
                <td>{{ $machinery->location }}</td>
                <td>{{ $machinery->brand }}</td>
                <td>{{ $machinery->model }}</td>
                <td>{{ $machinery->serial }}</td>
                <td>
                    @php
                        $status = $machinery->status;
                        $statusClass = match($status) {
                            'Operación' => 'status-operacion',
                            'En mantenimiento' => 'status-mantenimiento',
                            'Mantenimiento requerido' => 'status-requerido',
                            'Sin actividad' => 'status-sin-actividad',
                            default => 'status-sin-actividad'
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $status }}</span>
                </td>
                <td>{{ $machinery->maint_freq }}</td>
                <td>{{ $machinery->start_func->format('d/m/Y') }}</td>
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

