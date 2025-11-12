@extends('layouts/masteraprendiz')

@section('content')
@vite(['resources/css/dashboard-admin.css'])

<div class="container mx-auto px-6 py-8">
    <!-- Header de Bienvenida -->
    <div class="dashboard-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="welcome-title">
                    Bienvenido Aprendiz
                </h1>
                <p class="welcome-subtitle">
                    <i class="fas fa-user-graduate text-green-600 mr-2"></i>
                    {{ Auth::user()?->name ?? 'Usuario' }} - Panel de Aprendiz del Sistema de Compostaje
                </p>
            </div>
            <div class="text-right">
                <div class="text-green-600 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Tarjetas de Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
        <!-- Mis Residuos -->
        <div class="stats-card stats-card-primary animate-fade-in-up animate-delay-1">
            <div class="flex items-center justify-between">
                <div>
                    <div class="stats-label">Mis Residuos (Kg)</div>
                    <div class="stats-number">{{ number_format($organicStats['total_weight'], 1) }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $organicStats['total_records'] }} registros</div>
                </div>
                <div class="stats-icon text-blue-300">
                    <i class="fas fa-recycle"></i>
                </div>
            </div>
        </div>

        <!-- Mis Pilas -->
        <div class="stats-card stats-card-success animate-fade-in-up animate-delay-2">
            <div class="flex items-center justify-between">
                <div>
                    <div class="stats-label">Mis Pilas</div>
                    <div class="stats-number">{{ $compostingStats['total_piles'] }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $compostingStats['active_piles'] }} activas</div>
                </div>
                <div class="stats-icon text-green-300">
                    <i class="fas fa-mountain"></i>
                </div>
            </div>
        </div>

        <!-- Mis Seguimientos -->
        <div class="stats-card stats-card-info animate-fade-in-up animate-delay-3">
            <div class="flex items-center justify-between">
                <div>
                    <div class="stats-label">Mis Seguimientos</div>
                    <div class="stats-number">{{ $trackingStats['total_trackings'] }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $trackingStats['today_trackings'] }} hoy</div>
                </div>
                <div class="stats-icon text-cyan-300">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>

        <!-- Abonos -->
        <div class="stats-card stats-card-warning animate-fade-in-up animate-delay-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="stats-label">Abono Total (Kg/L)</div>
                    <div class="stats-number">{{ number_format($fertilizerStats['total_amount'], 1) }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $fertilizerStats['total_records'] }} registros</div>
                </div>
                <div class="stats-icon text-yellow-300">
                    <i class="fas fa-seedling"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Actividad Reciente -->
        <div class="info-card animate-fade-in-up animate-delay-2">
            <h2 class="section-title">
                <i class="fas fa-chart-bar section-icon"></i>
                Mi Actividad Hoy
            </h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg mr-3">
                            <i class="fas fa-recycle text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Residuos Registrados</div>
                            <div class="text-sm text-gray-600">{{ $organicStats['today_records'] }} registros ({{ number_format($organicStats['today_weight'], 1) }} Kg)</div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-100">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg mr-3">
                            <i class="fas fa-mountain text-green-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Pilas Creadas</div>
                            <div class="text-sm text-gray-600">{{ $compostingStats['total_piles'] }} pilas ({{ $compostingStats['active_piles'] }} activas)</div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-cyan-50 rounded-lg border border-cyan-100">
                    <div class="flex items-center">
                        <div class="p-2 bg-cyan-100 rounded-lg mr-3">
                            <i class="fas fa-chart-line text-cyan-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Seguimientos Registrados</div>
                            <div class="text-sm text-gray-600">{{ $trackingStats['today_trackings'] }} seguimientos hoy</div>
                        </div>
                    </div>
                </div>
                
                @if($pendingNotifications > 0)
                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                            <i class="fas fa-bell text-yellow-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Notificaciones Pendientes</div>
                            <div class="text-sm text-gray-600">{{ $pendingNotifications }} solicitudes en espera</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="quick-actions animate-fade-in-up animate-delay-3">
            <h2 class="section-title">
                <i class="fas fa-bolt section-icon"></i>
                Acciones Rápidas
            </h2>
            <div class="grid grid-cols-1 gap-4">
                <a href="{{ route('aprendiz.organic.create') }}" class="action-btn">
                    <i class="fas fa-plus mr-2"></i>
                    Registrar Residuo Orgánico
                </a>
                <a href="{{ route('aprendiz.composting.create') }}" class="action-btn-secondary">
                    <i class="fas fa-mountain mr-2"></i>
                    Crear Nueva Pila
                </a>
                <a href="{{ route('aprendiz.tracking.create') }}" class="action-btn-info">
                    <i class="fas fa-chart-line mr-2"></i>
                    Registrar Seguimiento
                </a>
                <a href="{{ route('aprendiz.fertilizer.create') }}" class="action-btn-warning">
                    <i class="fas fa-seedling mr-2"></i>
                    Registrar Abono
                </a>
            </div>
        </div>
    </div>

    <!-- Enlaces Rápidos a Módulos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
        <a href="{{ route('aprendiz.organic.index') }}" class="info-card hover:shadow-lg transition-all duration-200 animate-fade-in-up animate-delay-4">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <i class="fas fa-recycle text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Residuos Orgánicos</h3>
                    <p class="text-sm text-gray-600">Ver todos los registros</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('aprendiz.composting.index') }}" class="info-card hover:shadow-lg transition-all duration-200 animate-fade-in-up animate-delay-4">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg mr-4">
                    <i class="fas fa-mountain text-green-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Pilas de Compostaje</h3>
                    <p class="text-sm text-gray-600">Ver todas las pilas</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('aprendiz.tracking.index') }}" class="info-card hover:shadow-lg transition-all duration-200 animate-fade-in-up animate-delay-4">
            <div class="flex items-center">
                <div class="p-3 bg-cyan-100 rounded-lg mr-4">
                    <i class="fas fa-chart-line text-cyan-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Seguimientos</h3>
                    <p class="text-sm text-gray-600">Ver todos los seguimientos</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('aprendiz.fertilizer.index') }}" class="info-card hover:shadow-lg transition-all duration-200 animate-fade-in-up animate-delay-4">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg mr-4">
                    <i class="fas fa-seedling text-yellow-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Abonos</h3>
                    <p class="text-sm text-gray-600">Ver todos los abonos</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
