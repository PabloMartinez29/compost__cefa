<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #22c55e;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #22c55e;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section h2 {
            color: #333;
            font-size: 16px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #22c55e;
            color: white;
            font-weight: bold;
        }
        table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }
        .stat-box {
            text-align: center;
            padding: 15px;
            background-color: #f0fdf4;
            border: 1px solid #22c55e;
            border-radius: 5px;
            min-width: 150px;
        }
        .stat-box .number {
            font-size: 24px;
            font-weight: bold;
            color: #22c55e;
        }
        .stat-box .label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Período: {{ ucfirst($period) }} - {{ $startDate->format('d/m/Y') }} al {{ $endDate->format('d/m/Y') }}</p>
        <p>Generado el: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>

    @if($module === 'residuos')
        <div class="info-section">
            <h2>Resumen de Residuos</h2>
            <div class="stats">
                <div class="stat-box">
                    <div class="number">{{ count($data['by_type']) }}</div>
                    <div class="label">Tipos Diferentes</div>
                </div>
                <div class="stat-box">
                    <div class="number">{{ $data['total'] }}</div>
                    <div class="label">Total Registros</div>
                </div>
                <div class="stat-box">
                    <div class="number">{{ number_format($data['total_weight'], 1) }} Kg</div>
                    <div class="label">Peso Total</div>
                </div>
            </div>
        </div>

        <div class="info-section">
            <h2>Residuos por Tipo</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Cantidad de Registros</th>
                        <th>Peso Total (Kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['by_type'] as $type => $info)
                    <tr>
                        <td>{{ $type }}</td>
                        <td>{{ $info['count'] }}</td>
                        <td>{{ number_format($info['weight'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="info-section">
            <h2>Registros por Período</h2>
            <table>
                <thead>
                    <tr>
                        <th>Período</th>
                        <th>Cantidad de Registros</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['by_date'] as $date => $count)
                    <tr>
                        <td>{{ $date }}</td>
                        <td>{{ $count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif($module === 'pilas')
        <div class="info-section">
            <h2>Resumen de Pilas</h2>
            <div class="stats">
                <div class="stat-box">
                    <div class="number">{{ $data['by_status']['active'] }}</div>
                    <div class="label">Pilas Activas</div>
                </div>
                <div class="stat-box">
                    <div class="number">{{ $data['by_status']['completed'] }}</div>
                    <div class="label">Pilas Completadas</div>
                </div>
                <div class="stat-box">
                    <div class="number">{{ $data['total'] }}</div>
                    <div class="label">Total Pilas</div>
                </div>
            </div>
        </div>

        <div class="info-section">
            <h2>Pilas por Período</h2>
            <table>
                <thead>
                    <tr>
                        <th>Período</th>
                        <th>Cantidad de Pilas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['by_date'] as $date => $count)
                    <tr>
                        <td>{{ $date }}</td>
                        <td>{{ $count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif($module === 'abono')
        <div class="info-section">
            <h2>Resumen de Abonos</h2>
            <div class="stats">
                <div class="stat-box">
                    <div class="number">{{ count($data['by_type']) }}</div>
                    <div class="label">Tipos Diferentes</div>
                </div>
                <div class="stat-box">
                    <div class="number">{{ $data['total'] }}</div>
                    <div class="label">Total Registros</div>
                </div>
                <div class="stat-box">
                    <div class="number">{{ number_format($data['total_amount'], 1) }}</div>
                    <div class="label">Cantidad Total</div>
                </div>
            </div>
        </div>

        <div class="info-section">
            <h2>Abonos por Tipo</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Cantidad de Registros</th>
                        <th>Cantidad Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['by_type'] as $type => $info)
                    <tr>
                        <td>{{ $type === 'Liquid' ? 'Líquido' : 'Sólido' }}</td>
                        <td>{{ $info['count'] }}</td>
                        <td>{{ number_format($info['amount'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="info-section">
            <h2>Abonos por Período</h2>
            <table>
                <thead>
                    <tr>
                        <th>Período</th>
                        <th>Cantidad de Registros</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['by_date'] as $date => $count)
                    <tr>
                        <td>{{ $date }}</td>
                        <td>{{ $count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif($module === 'maquinaria')
        <div class="info-section">
            <h2>Resumen de Maquinaria</h2>
            <div class="stats">
                <div class="stat-box">
                    <div class="number">{{ $data['total'] }}</div>
                    <div class="label">Total Equipos</div>
                </div>
                <div class="stat-box">
                    <div class="number">{{ $data['by_status']['Operativa'] ?? 0 }}</div>
                    <div class="label">Operativa</div>
                </div>
                <div class="stat-box">
                    <div class="number">{{ $data['by_status']['Mantenimiento requerido'] ?? 0 }}</div>
                    <div class="label">Mantenimiento Requerido</div>
                </div>
            </div>
        </div>

        <div class="info-section">
            <h2>Estado de Maquinaria</h2>
            <table>
                <thead>
                    <tr>
                        <th>Estado</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['by_status'] as $status => $count)
                    <tr>
                        <td>{{ $status }}</td>
                        <td>{{ $count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="footer">
        <p>Sistema de Compostaje CEFA - Reporte de Monitoreo</p>
    </div>
</body>
</html>

