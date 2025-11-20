@extends('layouts.masteraprendiz')

@section('content')
@vite(['resources/css/waste.css'])

@php
use Illuminate\Support\Facades\Storage;
@endphp

<!-- Notification Alerts -->
@if(isset($recentNotifications) && $recentNotifications->count() > 0)
    @foreach($recentNotifications as $notification)
        <div id="notification-alert-{{ $notification->id }}" class="fixed top-4 right-4 z-50 max-w-sm bg-white rounded-lg shadow-lg border-l-4 {{ $notification->status === 'approved' ? 'border-green-500' : 'border-red-500' }} animate-slide-in-right">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas {{ $notification->status === 'approved' ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' }} text-xl"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium text-gray-900">
                            {{ $notification->status === 'approved' ? 'Solicitud Aprobada' : 'Solicitud Rechazada' }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $notification->message }}</p>
                        <div class="mt-2">
                            <button onclick="closeNotificationAlert({{ $notification->id }})" 
                                class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1 rounded">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="waste-title">
                    <i class="fas fa-recycle waste-icon"></i>
                    Gestión de Residuos Orgánicos
                </h1>
                <p class="waste-subtitle">
                    <i class="fas fa-user-graduate text-green-400 mr-2"></i>
                    {{ Auth::user()->name }} - Panel de Aprendiz
                </p>
            </div>
            <div class="text-right">
                <div class="text-green-400 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Weight -->
        <div class="waste-card waste-card-primary animate-fade-in-up animate-delay-1">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Peso Total</div>
                    <div class="text-3xl font-bold text-gray-800">{{ number_format($totalWeight, 2) }} Kg</div>
                </div>
                <div class="waste-card-icon text-blue-600">
                    <i class="fas fa-weight"></i>
                </div>
            </div>
        </div>

        <!-- Total Records -->
        <div class="waste-card waste-card-success animate-fade-in-up animate-delay-2">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Registros</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalRecords }}</div>
                </div>
                <div class="waste-card-icon text-green-600">
                    <i class="fas fa-list"></i>
                </div>
            </div>
        </div>

        <!-- Today Records -->
        <div class="waste-card waste-card-warning animate-fade-in-up animate-delay-3">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Registros Hoy</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $todayRecords }}</div>
                </div>
                <div class="waste-card-icon text-yellow-600">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>

        <!-- Today Weight -->
        <div class="waste-card waste-card-info animate-fade-in-up animate-delay-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Peso Hoy</div>
                    <div class="text-3xl font-bold text-gray-800">{{ number_format($todayWeight, 2) }} Kg</div>
                </div>
                <div class="waste-card-icon text-cyan-600">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Table Header -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <!-- Primera fila: Título y botones -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-recycle text-green-600 mr-2"></i>
                    Registros de Residuos Orgánicos
                </h2>
                <div class="flex items-center space-x-4">
                    @if($organics->count() > 0)
                        <a href="{{ route('aprendiz.organic.download.all-pdf') }}" class="bg-red-500 text-white border border-red-600 hover:bg-red-600 px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                    @endif
                    <a href="{{ route('aprendiz.organic.create') }}" class="bg-green-400 text-green-800 border border-green-500 hover:bg-green-500 px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm">
                        <i class="fas fa-plus mr-2"></i>
                        Nuevo Registro
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded m-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('permission_required'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded m-6">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ session('permission_required') }}
            </div>
        @endif

        @if($organics->count() > 0)
            <!-- Tabla de residuos -->
            <div class="overflow-x-auto">
                <!-- DataTables agregará los controles y la tabla aquí -->
                <div id="organicsTable_wrapper" class="p-6">
                    <!-- Contenedor para controles superiores -->
                    <div style="width: 100%; overflow: hidden; margin-bottom: 1rem;">
                        <div id="dt-length-container" style="float: left;"></div>
                        <div id="dt-filter-container" style="float: right;"></div>
                    </div>
                    <table id="organicsTable" class="waste-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Imagen</th>
                                <th>Tipo</th>
                                <th>Peso (Kg)</th>
                                <th>Entregado Por</th>
                                <th>Recibido Por</th>
                                <th>Creado por</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($organics as $organic)
                        <tr>
                            <td class="font-mono">#{{ str_pad($organic->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $organic->formatted_date }}</td>
                            <td>
                                @if($organic->img)
                                    @php
                                        $imageUrl = asset($organic->img);
                                    @endphp
                                    <img src="{{ $imageUrl }}?v={{ $organic->updated_at->timestamp }}" 
                                         alt="Imagen del residuo" 
                                         class="w-12 h-12 object-cover rounded-full cursor-pointer hover:opacity-80 transition-opacity"
                                         onclick="openImageModal('{{ $imageUrl }}?v={{ $organic->updated_at->timestamp }}')"
                                         onerror="console.log('Error loading image:', this.src); this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center" style="display: none;">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="waste-badge 
                                    @if($organic->type == 'Kitchen') waste-badge-success
                                    @elseif($organic->type == 'Beds') waste-badge-info
                                    @elseif($organic->type == 'Leaves') waste-badge-warning
                                    @else waste-badge-primary
                                    @endif">
                                    {{ $organic->type_in_spanish }}
                                </span>
                            </td>
                            <td class="font-semibold">{{ $organic->formatted_weight }}</td>
                            <td>{{ $organic->delivered_by }}</td>
                            <td>{{ $organic->received_by }}</td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-800 font-medium mb-1">{{ $organic->creator ? $organic->creator->name : 'Usuario no disponible' }}</span>
                                    @if($organic->creator && $organic->creator->role === 'admin')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 w-fit">
                                            <i class="fas fa-user-shield mr-1"></i>
                                            Administrador
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 w-fit">
                                            <i class="fas fa-user-graduate mr-1"></i>
                                            Aprendiz
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="flex space-x-2 items-center">
                                    <button onclick="openViewModal({{ $organic->id }})" 
                                       class="inline-flex items-center text-blue-400 hover:text-blue-500" title="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($organic->created_by == auth()->id())
                                        <button onclick="confirmEdit({{ $organic->id }})" 
                                           class="inline-flex items-center text-green-500 hover:text-green-700" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        @php
                                            $isApproved = isset($approvedOrganicIds) && in_array($organic->id, $approvedOrganicIds);
                                            $isPending = isset($pendingOrganicIds) && in_array($organic->id, $pendingOrganicIds);
                                            $isRejected = isset($rejectedOrganicIds) && in_array($organic->id, $rejectedOrganicIds);
                                        @endphp

                                        @if($isRejected)
                                            <button type="button" class="inline-flex items-center text-red-600 hover:text-red-800" title="Solicitud rechazada"
                                                onclick="showRejectedAlert({{ $organic->id }})">
                                                <i class="fas fa-ban text-lg"></i>
                                            </button>
                                        @elseif($isApproved)
                                            <form id="delete-form-{{ $organic->id }}" action="{{ route('aprendiz.organic.destroy', $organic) }}" method="POST" class="inline-flex items-center" style="margin: 0; padding: 0; margin-left: 0.5rem;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="inline-flex items-center text-red-500 hover:text-red-700" title="Eliminar"
                                                    onclick="confirmDelete('delete-form-{{ $organic->id }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @elseif($isPending)
                                            <button type="button" class="inline-flex items-center text-yellow-500 cursor-default" title="Permiso pendiente de aprobación">
                                                <i class="fas fa-hourglass-half"></i>
                                            </button>
                                        @else
                                            <form id="request-delete-form-{{ $organic->id }}" action="{{ route('aprendiz.organic.request-delete', $organic) }}" method="POST" class="inline-flex items-center" style="margin: 0; padding: 0; margin-left: 0.5rem;">
                                                @csrf
                                                <button type="button" 
                                                   class="inline-flex items-center text-red-500 hover:text-red-700" title="Solicitar Eliminación"
                                                   onclick="confirmRequestPermission('request-delete-form-{{ $organic->id }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <button onclick="showPermissionAlert()" 
                                           class="inline-flex items-center text-gray-400 cursor-not-allowed" title="Sin permisos">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                        <button type="button" 
                                           class="inline-flex items-center text-gray-400 cursor-not-allowed" title="Sin permisos">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                    <a href="{{ route('aprendiz.organic.download.pdf', $organic) }}" 
                                       class="inline-flex items-center text-red-800 hover:text-red-900" 
                                       title="Descargar PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- Estado vacío -->
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <i class="fas fa-recycle text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay registros de residuos orgánicos</h3>
                <p class="text-gray-600">Comienza registrando tu primer residuo orgánico en el sistema.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal para Crear Registro -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 modal-backdrop-blur overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-plus text-white"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Registrar Residuo Orgánico</h3>
                    <p class="text-sm text-gray-600">{{ Auth::user()->name }} - Nuevo registro</p>
                </div>
            </div>
            <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="py-6">
            <form id="createForm" action="{{ route('aprendiz.organic.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Fecha -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-green-500 mr-2"></i>
                            Fecha del Registro *
                        </label>
                        <input type="date" name="date" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('date') border-red-500 @enderror" 
                               value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo de Residuo -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-recycle text-green-500 mr-2"></i>
                            Tipo de Residuo *
                        </label>
                        <select name="type" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('type') border-red-500 @enderror" 
                                required>
                            <option value="">Seleccionar tipo de residuo</option>
                            <option value="Kitchen" {{ old('type') == 'Kitchen' ? 'selected' : '' }}>🍽️ Cocina</option>
                            <option value="Beds" {{ old('type') == 'Beds' ? 'selected' : '' }}>🛏️ Camas</option>
                            <option value="Leaves" {{ old('type') == 'Leaves' ? 'selected' : '' }}>🍃 Hojas</option>
                            <option value="CowDung" {{ old('type') == 'CowDung' ? 'selected' : '' }}>🐄 Estiércol de Vaca</option>
                            <option value="ChickenManure" {{ old('type') == 'ChickenManure' ? 'selected' : '' }}>🐔 Estiércol de Pollo</option>
                            <option value="PigManure" {{ old('type') == 'PigManure' ? 'selected' : '' }}>🐷 Estiércol de Cerdo</option>
                            <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>📦 Otro</option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Peso -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-weight text-green-500 mr-2"></i>
                            Peso (Kilogramos) *
                        </label>
                        <input type="number" name="weight" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('weight') border-red-500 @enderror" 
                               placeholder="0.00" step="0.01" min="0.01" 
                               value="{{ old('weight') }}" required>
                        @error('weight')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Entregado Por -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user text-green-500 mr-2"></i>
                            Entregado Por *
                        </label>
                        <input type="text" name="delivered_by" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('delivered_by') border-red-500 @enderror" 
                               placeholder="Nombre del entregador" 
                               value="{{ old('delivered_by') }}" required>
                        @error('delivered_by')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Recibido Por -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user-check text-green-500 mr-2"></i>
                            Recibido Por *
                        </label>
                        <input type="text" name="received_by" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('received_by') border-red-500 @enderror" 
                               placeholder="Nombre del receptor" 
                               value="{{ old('received_by', Auth::user()->name) }}" required>
                        @error('received_by')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Imagen -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-image text-green-500 mr-2"></i>
                            Imagen (Requerido)
                        </label>
                        <div class="relative">
                            <input type="file" name="img" id="imageInput" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('img') border-red-500 @enderror" 
                                   accept="image/*" onchange="previewImage(this)" required>
                            <div id="imagePreview" class="mt-3 hidden">
                                <img id="previewImg" class="w-32 h-32 object-cover rounded-lg border border-gray-200" alt="Preview">
                            </div>
                        </div>
                        @error('img')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Tamaño máximo: 2MB. Formatos: JPEG, PNG, JPG, GIF</p>
                    </div>

                    <!-- Notas Adicionales -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sticky-note text-green-500 mr-2"></i>
                            Notas Adicionales
                        </label>
                        <textarea name="notes" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 resize-none @error('notes') border-red-500 @enderror" 
                                  rows="4" placeholder="Ingrese notas adicionales sobre el residuo orgánico...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
            <button onclick="closeCreateModal()" 
                    class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                <i class="fas fa-times mr-2"></i>
                Cancelar
            </button>
            <button onclick="submitForm()" 
                    class="px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg">
                <i class="fas fa-save mr-2"></i>
                Guardar Registro
            </button>
        </div>
    </div>
</div>

<!-- Modal para visualizar imagen -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 modal-backdrop-blur hidden z-50 flex items-center justify-center p-4">
    <div class="relative max-w-6xl max-h-[90vh] w-full flex items-center justify-center">
        <!-- Botón de cerrar -->
        <button onclick="closeImageModal()" class="absolute top-4 right-4 z-10 bg-black bg-opacity-50 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-75 transition-all">
            <i class="fas fa-times text-xl"></i>
        </button>
        
        <!-- Imagen -->
        <img id="modalImage" src="" alt="Imagen del residuo orgánico" 
             class="max-w-4xl max-h-[80vh] w-auto h-auto object-contain rounded-lg shadow-2xl mx-auto">
    </div>
</div>

<!-- Modal para ver detalles del residuo -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="waste-header">
            <div class="text-center">
                <h3 class="waste-title text-xl justify-center">
                    <i class="fas fa-eye waste-icon"></i>
                    Detalles del Residuo Orgánico
                </h3>
                <p class="waste-subtitle">
                    <i class="fas fa-user-graduate text-green-400 mr-2"></i>
                    <span id="viewUserInfo">{{ Auth::user()->name }} - Registro #<span id="viewRecordId"></span></span>
                </p>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="space-y-6">
                <!-- Imagen del residuo -->
                <div id="viewImageContainer" class="text-center">
                    <img id="viewImage" src="" alt="Imagen del residuo orgánico" 
                         class="max-w-full h-64 object-cover rounded-lg shadow-md mx-auto cursor-pointer"
                         onclick="openImageModal(this.src)">
                </div>

                <!-- Información del residuo -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha</label>
                        <div class="waste-form-input bg-gray-50" id="viewDate"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Tipo de Residuo</label>
                        <div class="waste-form-input bg-gray-50" id="viewType"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Peso (Kg)</label>
                        <div class="waste-form-input bg-gray-50" id="viewWeight"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Entregado Por</label>
                        <div class="waste-form-input bg-gray-50" id="viewDeliveredBy"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Recibido Por</label>
                        <div class="waste-form-input bg-gray-50" id="viewReceivedBy"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha de Creación</label>
                        <div class="waste-form-input bg-gray-50" id="viewCreatedAt"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Creado Por</label>
                        <div class="waste-form-input bg-gray-50" id="viewCreatedBy"></div>
                    </div>
                </div>

                <!-- Notas -->
                <div class="waste-form-group">
                    <label class="waste-form-label">Notas</label>
                    <div class="waste-form-textarea bg-gray-50" id="viewNotes" style="min-height: 100px;"></div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end pt-6 border-t border-gray-200">
                    <button onclick="closeViewModal()" class="waste-btn-secondary">
                        <i class="fas fa-times mr-2"></i>
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Editar -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="waste-header">
            <div class="text-center">
                <h3 class="waste-title text-xl justify-center">
                    <i class="fas fa-edit waste-icon"></i>
                    Editar Registro de Residuo Orgánico
                </h3>
                <p class="waste-subtitle">
                    <i class="fas fa-user-graduate text-green-400 mr-2"></i>
                    <span id="editUserInfo">{{ Auth::user()->name }} - Registro #<span id="editRecordId"></span></span>
                </p>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Date -->
                <div class="waste-form-group">
                    <label class="waste-form-label">Fecha *</label>
                    <input type="date" name="date" id="editDate" class="waste-form-input @error('date') border-red-500 @enderror" required>
                    @error('date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div class="waste-form-group">
                    <label class="waste-form-label">Tipo de Residuo *</label>
                    <div class="relative">
                        <select name="type" id="editType" class="waste-form-select @error('type') border-red-500 @enderror" required>
                            <option value="">Seleccionar tipo de residuo</option>
                            <option value="Kitchen">Cocina</option>
                            <option value="Beds">Camas</option>
                            <option value="Leaves">Hojas</option>
                            <option value="CowDung">Estiércol de Vaca</option>
                            <option value="ChickenManure">Gallinaza</option>
                            <option value="PigManure">Estiércol de Cerdo</option>
                            <option value="Other">Otro</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Weight -->
                <div class="waste-form-group">
                    <label class="waste-form-label">Peso (Kg) *</label>
                    <input type="number" name="weight" id="editWeight" class="waste-form-input @error('weight') border-red-500 @enderror" 
                           placeholder="Ingrese el peso en kilogramos" step="0.01" min="0.01" required>
                    @error('weight')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Delivered By -->
                <div class="waste-form-group">
                    <label class="waste-form-label">Entregado Por *</label>
                    <input type="text" name="delivered_by" id="editDeliveredBy" class="waste-form-input @error('delivered_by') border-red-500 @enderror" 
                           placeholder="Ingrese el nombre del entregador" required>
                    @error('delivered_by')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Received By -->
                <div class="waste-form-group">
                    <label class="waste-form-label">Recibido Por *</label>
                    <input type="text" name="received_by" id="editReceivedBy" class="waste-form-input @error('received_by') border-red-500 @enderror" 
                           placeholder="Ingrese el nombre del receptor" required>
                    @error('received_by')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Image -->
                <div id="currentImageContainer" class="waste-form-group hidden">
                    <label class="waste-form-label">Imagen Actual</label>
                    <div class="relative">
                        <img id="currentImage" src="" alt="Imagen actual del residuo orgánico" 
                             class="w-full h-32 object-cover rounded-lg border border-gray-200">
                    </div>
                </div>

                <!-- New Image Upload -->
                <div class="waste-form-group">
                    <label class="waste-form-label">Nueva Imagen (Opcional)</label>
                    <input type="file" name="img" class="waste-form-input @error('img') border-red-500 @enderror" 
                           accept="image/*">
                    @error('img')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Tamaño máximo: 2MB. Formatos soportados: JPEG, PNG, JPG, GIF</p>
                    <p id="imageReplaceWarning" class="text-yellow-600 text-sm mt-1 hidden">Subir una nueva imagen reemplazará la actual.</p>
                </div>

                <!-- Notes -->
                <div class="waste-form-group">
                    <label class="waste-form-label">Notas</label>
                    <textarea name="notes" id="editNotes" class="waste-form-textarea @error('notes') border-red-500 @enderror" 
                              rows="4" placeholder="Ingrese notas adicionales sobre el residuo orgánico..."></textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeEditModal()" class="waste-btn-secondary">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="waste-btn">
                        <i class="fas fa-save mr-2"></i>
                        Actualizar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    // Limpiar formulario
    document.getElementById('createForm').reset();
    document.getElementById('imagePreview').classList.add('hidden');
}

function submitForm() {
    document.getElementById('createForm').submit();
}

function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.classList.add('hidden');
    }
}

// Cerrar modal al hacer clic fuera
document.getElementById('createModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateModal();
    }
});

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCreateModal();
    }
});

// Funciones para el modal de imagen
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Cerrar modal de imagen al hacer clic fuera
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Cerrar modal de imagen con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Funciones para el modal de editar
function openEditModal(organicId) {
    // Obtener datos del registro
    fetch(`/aprendiz/organic/${organicId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            // Llenar el formulario
            document.getElementById('editRecordId').textContent = data.id.toString().padStart(3, '0');
            document.getElementById('editDate').value = data.date;
            document.getElementById('editType').value = data.type;
            document.getElementById('editWeight').value = data.weight;
            document.getElementById('editDeliveredBy').value = data.delivered_by;
            document.getElementById('editReceivedBy').value = data.received_by;
            document.getElementById('editNotes').value = data.notes || '';
            
            // Mostrar imagen actual si existe
            if (data.img && data.img_url) {
                document.getElementById('currentImage').src = data.img_url;
                document.getElementById('currentImageContainer').classList.remove('hidden');
                document.getElementById('imageReplaceWarning').classList.remove('hidden');
            } else {
                document.getElementById('currentImageContainer').classList.add('hidden');
                document.getElementById('imageReplaceWarning').classList.add('hidden');
            }
            
            // Configurar acción del formulario
            document.getElementById('editForm').action = `/aprendiz/organic/${organicId}`;
            
            // Mostrar modal
            document.getElementById('editModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos del registro');
        });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Cerrar modal de editar al hacer clic fuera
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Cerrar modal de editar con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
    }
});

// Funciones para el modal de vista
function openViewModal(organicId) {
    // Obtener datos del registro
    fetch(`/aprendiz/organic/${organicId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            // Llenar el formulario
            document.getElementById('viewRecordId').textContent = data.id.toString().padStart(3, '0');
            document.getElementById('viewDate').textContent = data.date_formatted || data.date;
            document.getElementById('viewType').textContent = data.type_in_spanish || data.type;
            document.getElementById('viewWeight').textContent = data.formatted_weight || data.weight + ' Kg';
            document.getElementById('viewDeliveredBy').textContent = data.delivered_by;
            document.getElementById('viewReceivedBy').textContent = data.received_by;
            document.getElementById('viewCreatedAt').textContent = data.created_at_formatted || data.created_at;
            document.getElementById('viewCreatedBy').textContent = data.created_by_info || 'Información no disponible';
            document.getElementById('viewNotes').textContent = data.notes || 'Sin notas';
            
            // Mostrar imagen si existe
            if (data.img && data.img_url) {
                document.getElementById('viewImage').src = data.img_url;
                document.getElementById('viewImageContainer').style.display = 'block';
            } else {
                document.getElementById('viewImageContainer').style.display = 'none';
            }
            
            // Mostrar modal
            document.getElementById('viewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos del registro');
        });
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Cerrar modal de vista al hacer clic fuera
document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeViewModal();
    }
});

// Cerrar modal de vista con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeViewModal();
    }
});

// Función para cerrar alertas de notificación
function closeNotificationAlert(notificationId) {
    const alert = document.getElementById('notification-alert-' + notificationId);
    if (alert) {
        alert.style.animation = 'slideOutRight 0.3s ease-in-out';
        setTimeout(() => {
            alert.remove();
        }, 300);
    }
}

// Funciones para manejar permisos
function requestEditPermission(organicId) {
    Swal.fire({
        title: 'Solicitar permiso de edición',
        text: '¿Desea solicitar permiso al administrador para editar este registro?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, solicitar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Solicitud enviada',
                text: 'El administrador revisará su solicitud.',
                icon: 'success'
            });
        }
    });
}

function requestDeletePermission(organicId) {
    Swal.fire({
        title: 'Solicitar permiso de eliminación',
        text: '¿Desea solicitar permiso al administrador para eliminar este registro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, solicitar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Solicitud enviada',
                text: 'El administrador revisará su solicitud.',
                icon: 'success'
            });
        }
    });
}

function showPermissionAlert() {
    Swal.fire({
        title: 'Sin permisos',
        text: 'No tiene permisos para realizar esta acción. Solo puede editar o eliminar sus propios registros.',
        icon: 'info'
    });
}

function showRejectedAlert(organicId) {
    Swal.fire({
        title: 'Solicitud rechazada',
        text: 'Esta solicitud de eliminación ha sido rechazada por el administrador. No puede eliminar este registro.',
        icon: 'error'
    });
}

function confirmEdit(organicId) {
    Swal.fire({
        title: 'Confirmar edición',
        text: '¿Está seguro de que desea editar este registro?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, editar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            openEditModal(organicId);
        }
    });
}

function confirmDelete(formId) {
    Swal.fire({
        title: '¿Eliminar registro?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

function confirmRequestPermission(formId) {
    Swal.fire({
        title: 'Solicitar permiso',
        text: '¿Desea solicitar permiso al administrador para eliminar este registro?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, solicitar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

// Inicializar DataTables
document.addEventListener('DOMContentLoaded', function() {
    // Verificar que DataTable esté disponible
    if (typeof DataTable === 'undefined') {
        console.error('DataTable no está cargado. Verifica que el script de DataTables esté incluido.');
        return;
    }
    
    // Verificar que la tabla exista y que haya registros
    const tableElement = document.querySelector('#organicsTable');
    if (!tableElement) {
        console.log('No hay tabla para inicializar DataTables (no hay registros)');
        return;
    }
    
    // Verificar que haya filas de datos (no solo el thead)
    const tbody = tableElement.querySelector('tbody');
    if (!tbody || tbody.children.length === 0) {
        console.log('No hay registros para mostrar en DataTables');
        return;
    }
    
    console.log('Inicializando DataTables...');
    
    let table = new DataTable('#organicsTable', {
        language: {
            search: 'Buscar:',
            lengthMenu: 'Mostrar _MENU_ registros',
            info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
            infoEmpty: 'Mostrando 0 a 0 de 0 registros',
            infoFiltered: '(filtrado de _MAX_ registros totales)',
            zeroRecords: 'No se encontraron registros',
            emptyTable: 'No hay datos disponibles',
            paginate: {
                first: '«',
                previous: '<',
                next: '>',
                last: '»'
            }
        },
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        order: [[1, 'desc']], // Ordenar por fecha descendente
        processing: false,
        serverSide: false,
        dom: 'rtip', // Sin length y filter, los moveremos manualmente
        initComplete: function() {
            const wrapper = this.api().table().container();
            
            // Crear controles manualmente
            const lengthContainer = document.createElement('div');
            lengthContainer.className = 'dataTables_length';
            lengthContainer.innerHTML = `
                <label>
                    Mostrar
                    <select name="organicsTable_length" aria-controls="organicsTable" class="px-3 py-2 border border-gray-300 rounded-lg ml-2">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="-1">Todos</option>
                    </select>
                    registros
                </label>
            `;
            
            const filterContainer = document.createElement('div');
            filterContainer.className = 'dataTables_filter';
            filterContainer.innerHTML = `
                <label>
                    Buscar:
                    <input type="search" class="px-3 py-2 border border-gray-300 rounded-lg ml-2" placeholder="Buscar..." aria-controls="organicsTable" style="width: 250px; outline: none; transition: none;">
                </label>
            `;
            
            // Agregar a los contenedores
            const lengthTarget = document.getElementById('dt-length-container');
            const filterTarget = document.getElementById('dt-filter-container');
            
            if (lengthTarget) {
                lengthTarget.appendChild(lengthContainer);
            }
            
            if (filterTarget) {
                filterTarget.appendChild(filterContainer);
            }
            
            // Conectar eventos
            const lengthSelect = lengthContainer.querySelector('select');
            const searchInput = filterContainer.querySelector('input');
            
            if (lengthSelect) {
                lengthSelect.addEventListener('change', function() {
                    table.page.len(parseInt(this.value)).draw();
                });
            }
            
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    table.search(this.value).draw();
                });
            }
        }
    });
    
    console.log('DataTables configurado:', table);
});
</script>
@endsection

<style>
/* Estilos para DataTables */
.dataTables_wrapper {
    position: relative;
    clear: both;
    width: 100%;
}

/* Contenedor superior: Mostrar (izquierda) y Buscar (derecha) - MISMA LÍNEA */
.dataTables_wrapper .dataTables_length {
    float: left !important;
    margin-bottom: 1rem;
    padding: 0.5rem 0;
    clear: none !important;
    width: auto !important;
}

.dataTables_wrapper .dataTables_filter {
    float: right !important;
    margin-bottom: 1rem;
    padding: 0.5rem 0;
    text-align: right !important;
    clear: none !important;
    width: auto !important;
}

.dataTables_wrapper .dataTables_length label,
.dataTables_wrapper .dataTables_filter label {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    color: #374151;
    margin: 0;
    white-space: nowrap;
}

.dataTables_wrapper .dataTables_length select {
    margin-left: 0.5rem;
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    min-width: 60px;
}

.dataTables_wrapper .dataTables_filter input {
    margin-left: 0.5rem;
    padding: 0.5rem;
    border: 1px solid #d1d5db !important;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    width: 250px;
    outline: none !important;
    transition: none;
    background-color: white;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #d1d5db !important;
    box-shadow: none !important;
    outline: none !important;
    background-color: white !important;
}

.dataTables_wrapper .dataTables_filter input:hover {
    border-color: #9ca3af !important;
    box-shadow: none !important;
    background-color: white !important;
}

.dataTables_wrapper .dataTables_filter input:active {
    border-color: #d1d5db !important;
    outline: none !important;
}

/* Información y paginación inferior */
.dataTables_wrapper .dataTables_info {
    float: left;
    padding: 0.75rem 0;
    margin-top: 1.5rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.dataTables_wrapper .dataTables_paginate {
    float: right;
    text-align: right;
    padding: 0.75rem 0;
    margin-top: 1.5rem;
}

/* Paginación más pequeña */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.375rem 0.625rem;
    margin: 0 0.125rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    background: white;
    color: #374151;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-block;
    text-decoration: none;
    font-size: 0.875rem;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f3f4f6 !important;
    border-color: #d1d5db !important;
    color: #374151 !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #22c55e;
    color: white;
    border-color: #22c55e;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

/* Limpiar floats */
.dataTables_wrapper::after {
    content: "";
    display: table;
    clear: both;
}
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.animate-slide-in-right {
    animation: slideInRight 0.3s ease-in-out;
}
</style>
