@extends('layouts/master')

@section('content')
@vite(['resources/css/dashboard-admin.css'])

<div class="container mx-auto px-6 py-8">
    <!-- Header de Bienvenida -->
    <div class="dashboard-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="welcome-title">
                    Bienvenido Administrador
                </h1>
                <p class="welcome-subtitle">
                    <i class="fas fa-user-circle text-green-600 mr-2"></i>
                    {{ Auth::user()->name }} - Panel de Control del Sistema de Compostaje
                </p>
            </div>
            <div class="text-right">
                <div class="text-green-600 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Tarjetas de Estadísticas Básicas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mt-8">
        <!-- Pasantes -->
        <div class="stats-card stats-card-primary animate-fade-in-up animate-delay-1">
            <div class="flex items-center justify-between">
                <div>
                    <div class="stats-label">Total Aprendices</div>
                    <div class="stats-number">{{ $userStats['total_apprentices'] }}</div>
                </div>
                <div class="stats-icon text-blue-300">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
        </div>

        <!-- Pilas -->
        <div class="stats-card stats-card-success animate-fade-in-up animate-delay-2">
            <div class="flex items-center justify-between">
                <div>
                    <div class="stats-label">Pilas Activas</div>
                    <div class="stats-number">{{ $compostingStats['active'] }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $compostingStats['total'] }} registradas</div>
                </div>
                <div class="stats-icon text-green-300">
                    <i class="fas fa-mountain"></i>
                </div>
            </div>
        </div>

        <!-- Residuos -->
        <div class="stats-card stats-card-info animate-fade-in-up animate-delay-3">
            <div class="flex items-center justify-between">
                <div>
                    <div class="stats-label">Residuos (Kg)</div>
                    <div class="stats-number">{{ number_format($organicStats['total_weight'], 1) }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $organicStats['total_records'] }} registros</div>
                </div>
                <div class="stats-icon text-cyan-300">
                    <i class="fas fa-leaf"></i>
                </div>
            </div>
        </div>

        <!-- Maquinaria -->
        <div class="stats-card stats-card-orange animate-fade-in-up animate-delay-3">
            <div class="flex items-center justify-between">
                <div>
                    <div class="stats-label">Maquinaria</div>
                    <div class="stats-number">{{ $machineryStats['total'] }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $machineryStats['operational'] }} operativas</div>
                </div>
                <div class="stats-icon text-orange-300">
                    <i class="fas fa-cogs"></i>
                </div>
            </div>
        </div>

        <!-- Abono -->
        <div class="stats-card stats-card-warning animate-fade-in-up animate-delay-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="stats-label">Abono (Kg)</div>
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
        <!-- Información del Sistema -->
        <div class="info-card animate-fade-in-up animate-delay-2">
            <h2 class="section-title">
                <i class="fas fa-info-circle section-icon"></i>
                Información del Sistema
            </h2>
            <div class="space-y-4">
                <div class="info-item">
                    <div class="info-icon bg-green-100 text-green-600">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="info-text">
                        <div class="info-title">Sistema Listo</div>
                        <div class="info-subtitle">El sistema de compostaje está operativo</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon bg-blue-100 text-blue-400">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="info-text">
                        <div class="info-title">Gestión de Pasantes</div>
                        <div class="info-subtitle">Registra y gestiona los pasantes del programa</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon bg-yellow-100 text-yellow-400">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div class="info-text">
                        <div class="info-title">Control de Residuos</div>
                        <div class="info-subtitle">Monitorea la entrada y procesamiento de residuos</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon bg-orange-100 text-orange-400">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <div class="info-text">
                        <div class="info-title">Gestión de Maquinaria</div>
                        <div class="info-subtitle">Controla el inventario y mantenimiento de equipos</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon bg-purple-100 text-purple-400">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="info-text">
                        <div class="info-title">Reportes y Estadísticas</div>
                        <div class="info-subtitle">Genera reportes detallados del proceso</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="quick-actions animate-fade-in-up animate-delay-3">
            <h2 class="section-title">
                <i class="fas fa-bolt section-icon"></i>
                Acciones Rápidas
            </h2>
            <div class="grid grid-cols-1 gap-4">
                <a href="#" class="action-btn">
                    <i class="fas fa-user-plus mr-2"></i>
                    Registrar Pasante
                </a>
                <a href="#" class="action-btn-secondary">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Nueva Pila
                </a>
                <a href="{{ route('admin.organic.create') }}" class="action-btn-info">
                    <i class="fas fa-leaf mr-2"></i>
                    Entrada de Residuos
                </a>
                <a href="#" class="action-btn-orange">
                    <i class="fas fa-cogs mr-2"></i>
                    Gestionar Maquinaria
                </a>
                <a href="#" class="action-btn-warning">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Ver Reportes
                </a>
            </div>
        </div>
    </div>

    <!-- Mensaje de Bienvenida -->
    <div class="mt-8 animate-fade-in-up animate-delay-4">
        <div class="bg-gradient-to-r from-blue-50 via-green-50 to-cyan-50 rounded-lg p-8 border border-green-200">
            <div class="text-center">
                <i class="fas fa-seedling text-4xl text-green-400 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-800 mb-2">
                    ¡Bienvenido al Sistema de Compostaje!
                </h3>
                <p class="text-gray-600 mb-4">
                    Comienza a gestionar el proceso de compostaje del CEFA. 
                    Registra pasantes, crea pilas de compostaje, gestiona maquinaria y monitorea el progreso.
                </p>
                <div class="flex justify-center space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">
                        <i class="fas fa-check-circle mr-1"></i>
                        Sistema Operativo
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700">
                        <i class="fas fa-clock mr-1"></i>
                        Listo para Usar
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection