<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Pila de Compostaje - {{ $composting->formatted_pile_num }}</title>
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
            font-size: 20px;
            font-weight: bold;
        }
        .header p {
            margin: 8px 0 0 0;
            font-size: 14px;
        }
        .date-info {
            background: #f5f5f5;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #10b981;
            text-align: center;
        }
        .composting-card {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
        }
        .image-section {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
        }
        .composting-image {
            max-width: 250px;
            max-height: 180px;
            width: auto;
            height: auto;
            object-fit: contain;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .details-grid {
            display: table;
            width: 100%;
            margin-top: 20px;
        }
        .detail-section {
            display: table-cell;
            width: 50%;
            background: #f9fafb;
            padding: 15px;
            border-left: 4px solid #10b981;
            vertical-align: top;
        }
        .detail-section h3 {
            color: #10b981;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .detail-item {
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            color: #6b7280;
            font-size: 12px;
            display: block;
            margin-bottom: 4px;
        }
        .detail-value {
            color: #1f2937;
            font-size: 12px;
        }
        .ingredients-section {
            background: #f5f5f5;
            padding: 15px;
            margin-top: 20px;
            border: 1px solid #cccccc;
        }
        .ingredients-section h3 {
            color: #10b981;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: bold;
        }
        .ingredients-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .ingredients-table th {
            background-color: #10b981;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }
        .ingredients-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }
        .ingredients-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .status-section {
            background: #f5f5f5;
            padding: 15px;
            margin-top: 20px;
            border: 1px solid #cccccc;
        }
        .status-section h3 {
            color: #10b981;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: bold;
        }
        .status-item {
            margin-bottom: 8px;
        }
        .status-label {
            font-weight: 500;
            color: #374151;
            font-size: 12px;
        }
        .status-value {
            font-weight: bold;
            color: #10b981;
            font-size: 12px;
        }
        .status-badge {
            padding: 4px 8px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 4px;
            display: inline-block;
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
        <h1>Detalles de Pila de Compostaje</h1>
        <p>Sistema de Gestión de Compostaje CEFA</p>
    </div>

    <div class="date-info">
        <strong>Fecha de generación:</strong> {{ date('d/m/Y H:i:s') }}
    </div>

    <div class="composting-card">
        @if($composting->image && isset($imageBase64))
        <div class="image-section">
            <h3 style="color: #10b981; margin-bottom: 15px; font-size: 14px; font-weight: bold; text-transform: uppercase;">Imagen de la Pila</h3>
            <img src="{{ $imageBase64 }}" 
                 alt="{{ $composting->formatted_pile_num }}" 
                 class="composting-image">
        </div>
        @endif

        <div class="details-grid">
            <div class="detail-section">
                <h3>Información General</h3>
                <div class="detail-item">
                    <span class="detail-label">Número de Pila:</span>
                    <span class="detail-value">{{ $composting->formatted_pile_num }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Fecha de Inicio:</span>
                    <span class="detail-value">{{ $composting->formatted_start_date }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Fecha de Fin:</span>
                    <span class="detail-value">{{ $composting->formatted_end_date ?? 'En proceso' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Total Kilogramos:</span>
                    <span class="detail-value">{{ $composting->formatted_total_kg }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Eficiencia:</span>
                    <span class="detail-value">{{ $composting->formatted_efficiency }}</span>
                </div>
            </div>

            <div class="detail-section">
                <h3>Estadísticas</h3>
                <div class="detail-item">
                    <span class="detail-label">Total de Ingredientes:</span>
                    <span class="detail-value">{{ $composting->formatted_total_ingredients }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Total Kg Ingredientes:</span>
                    <span class="detail-value">{{ number_format($composting->ingredients->sum('amount'), 2) }} Kg</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Total de Seguimientos:</span>
                    <span class="detail-value">{{ $composting->trackings->count() }} seguimiento(s)</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Días Transcurridos:</span>
                    <span class="detail-value">{{ $composting->days_elapsed }} días</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Días Restantes:</span>
                    <span class="detail-value">{{ $composting->days_remaining }} días</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Fase Actual:</span>
                    <span class="detail-value">{{ $composting->current_phase }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Creado por:</span>
                    <span class="detail-value">{{ $composting->creator->name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        @if($composting->ingredients->count() > 0)
        <div class="ingredients-section">
            <h3>Ingredientes Utilizados ({{ $composting->ingredients->count() }})</h3>
            <table class="ingredients-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Cantidad (Kg)</th>
                        <th>Origen</th>
                        <th>Notas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($composting->ingredients as $index => $ingredient)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $ingredient->ingredient_name }}</td>
                        <td>{{ $ingredient->formatted_amount }}</td>
                        <td>{{ $ingredient->organic->origin ?? 'N/A' }}</td>
                        <td>{{ $ingredient->notes ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top: 15px; padding: 10px; background: #f0f9ff; border-left: 4px solid #10b981;">
                <strong style="color: #10b981; font-size: 11px;">Total Kg Ingredientes:</strong>
                <span style="color: #1f2937; font-size: 11px; font-weight: bold;">{{ number_format($composting->ingredients->sum('amount'), 2) }} Kg</span>
            </div>
        </div>
        @endif

        @if($composting->trackings->count() > 0)
        <div class="ingredients-section" style="margin-top: 20px;">
            <h3>Seguimientos ({{ $composting->trackings->count() }})</h3>
            <table class="ingredients-table">
                <thead>
                    <tr>
                        <th>Día</th>
                        <th>Fecha</th>
                        <th>Actividad</th>
                        <th>Temp. Interna</th>
                        <th>Temp. Ambiente</th>
                        <th>Humedad</th>
                        <th>pH</th>
                        <th>Agua (L)</th>
                        <th>Cal (Kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($composting->trackings as $tracking)
                    <tr>
                        <td style="font-family: monospace; font-weight: bold;">Día {{ $tracking->day }}</td>
                        <td>{{ $tracking->date->format('d/m/Y') }}</td>
                        <td>{{ Str::limit($tracking->activity, 30) }}</td>
                        <td>{{ $tracking->temp_internal }}°C</td>
                        <td>{{ $tracking->temp_env }}°C</td>
                        <td>{{ $tracking->hum_pile }}%</td>
                        <td>{{ $tracking->ph }}</td>
                        <td>{{ $tracking->water }}L</td>
                        <td>{{ $tracking->lime }}Kg</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="status-section">
            <h3>Estado de la Pila</h3>
            <div class="status-item">
                <span class="status-label">Estado actual:</span>
                <span class="status-value">
                    @php
                        $status = $composting->status;
                        $statusClass = match($status) {
                            'Completada' => 'status-completada',
                            default => 'status-en-proceso'
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $status }}</span>
                </span>
            </div>
            <div class="status-item">
                <span class="status-label">Progreso del proceso:</span>
                <span class="status-value">{{ number_format($composting->process_progress, 1) }}%</span>
            </div>
            <div class="status-item">
                <span class="status-label">Fecha de registro:</span>
                <span class="status-value">{{ $composting->created_at->format('d/m/Y H:i:s') }}</span>
            </div>
            <div class="status-item">
                <span class="status-label">Última actualización:</span>
                <span class="status-value">{{ $composting->updated_at->format('d/m/Y H:i:s') }}</span>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Generado automáticamente por el Sistema de Gestión de Compostaje CEFA</p>
        <p>Comprometidos con el medio ambiente y la educación</p>
    </div>
</body>
</html>

