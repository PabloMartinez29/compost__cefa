<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Usuario - {{ $user->name }}</title>
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
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #10b981;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #059669;
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
        .user-card {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
        }
        .user-avatar {
            width: 60px;
            height: 60px;
            background-color: #10b981;
            border-radius: 50%;
            display: inline-block;
            text-align: center;
            line-height: 60px;
            margin: 0 auto 20px;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        .user-info {
            text-align: center;
            margin-bottom: 20px;
        }
        .user-name {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 8px;
        }
        .user-role {
            display: inline-block;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .role-admin {
            background-color: #fef3c7;
            color: #d97706;
        }
        .role-aprendiz {
            background-color: #dbeafe;
            color: #2563eb;
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
        }
        .detail-value {
            color: #1f2937;
            font-size: 12px;
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
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Detalles del Usuario</h1>
        <p>Sistema de Gestión de Compostaje CEFA</p>
    </div>

    <div class="date-info">
        <strong>Fecha de generación:</strong> {{ date('d/m/Y H:i:s') }}
    </div>

    <div class="user-card">
        <div class="user-info">
            <div class="user-avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="user-name">{{ $user->name }}</div>
            <div class="user-role role-{{ $user->role }}">
                {{ $user->role === 'admin' ? 'Administrador' : 'Aprendiz' }}
            </div>
        </div>

        <div class="details-grid">
            <div class="detail-section">
                <h3>Información Personal</h3>
                <div class="detail-item">
                    <span class="detail-label">Identificación:</span>
                    <span class="detail-value">{{ $user->identification ?? 'No especificada' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Nombre completo:</span>
                    <span class="detail-value">{{ $user->name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Correo electrónico:</span>
                    <span class="detail-value">{{ $user->email }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Rol en el sistema:</span>
                    <span class="detail-value">{{ $user->role === 'admin' ? 'Administrador' : 'Aprendiz' }}</span>
                </div>
            </div>

            <div class="detail-section">
                <h3>Información del Sistema</h3>
                <div class="detail-item">
                    <span class="detail-label">Fecha de registro:</span>
                    <span class="detail-value">{{ $user->created_at->format('d/m/Y H:i:s') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Última actualización:</span>
                    <span class="detail-value">{{ $user->updated_at->format('d/m/Y H:i:s') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email verificado:</span>
                    <span class="detail-value">{{ $user->email_verified_at ? 'Sí' : 'No' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">ID del usuario:</span>
                    <span class="detail-value">#{{ $user->id }}</span>
                </div>
            </div>
        </div>

        <div class="status-section">
            <h3>Estado de la Cuenta</h3>
            <div class="status-item">
                <span class="status-label">Estado de la cuenta:</span>
                <span class="status-value">Activa</span>
            </div>
            <div class="status-item">
                <span class="status-label">Permisos en el sistema:</span>
                <span class="status-value">{{ $user->role === 'admin' ? 'Administrador completo' : 'Aprendiz' }}</span>
            </div>
            <div class="status-item">
                <span class="status-label">Días en el sistema:</span>
                <span class="status-value">{{ $user->created_at->diffInDays(now()) }} días</span>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Generado automáticamente por el Sistema de Gestión de Compostaje CEFA</p>
        <p>Comprometidos con el medio ambiente y la educación</p>
    </div>
</body>
</html>
