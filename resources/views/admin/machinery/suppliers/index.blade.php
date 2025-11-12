@extends('layouts.master')

@section('content')
@vite(['resources/css/waste.css'])

@php
    use Illuminate\Support\Facades\Storage;
@endphp

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="waste-title">
                    <i class="fas fa-truck waste-icon"></i>
                    Gestión de Proveedores
                </h1>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    {{ Auth::user()->name }} - Admin Panel
                </p>
            </div>
            <div class="text-right">
                <div class="text-green-400 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Proveedores -->
        <div class="waste-card waste-card-primary animate-fade-in-up animate-delay-1">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Proveedores</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalSuppliers }}</div>
                </div>
                <div class="waste-card-icon text-blue-600">
                    <i class="fas fa-truck"></i>
                </div>
            </div>
        </div>

        <!-- Total Registros -->
        <div class="waste-card waste-card-success animate-fade-in-up animate-delay-2">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Registros</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalSuppliers }}</div>
                </div>
                <div class="waste-card-icon text-green-600">
                    <i class="fas fa-list"></i>
                </div>
            </div>
        </div>

        <!-- Registros Hoy -->
        <div class="waste-card waste-card-warning animate-fade-in-up animate-delay-3">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Registros Hoy</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $todaySuppliers }}</div>
                </div>
                <div class="waste-card-icon text-yellow-600">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>

        <!-- Este Mes -->
        <div class="waste-card waste-card-info animate-fade-in-up animate-delay-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Este Mes</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $thisMonthSuppliers }}</div>
                </div>
                <div class="waste-card-icon text-cyan-600">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="waste-container animate-fade-in-up animate-delay-2">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-table text-green-600 mr-2"></i>
                Registros de Proveedores
            </h2>
            <a href="{{ route('admin.machinery.supplier.create') }}" class="bg-green-400 text-green-800 border border-green-500 hover:bg-green-500 px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-sm">
                <i class="fas fa-plus mr-2"></i>
                Nuevo Registro
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="waste-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Maquinaria</th>
                        <th>Fabricante</th>
                        <th>Proveedor</th>
                        <th>Origen</th>
                        <th>Fecha de Compra</th>
                        <th>Contacto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td class="font-mono">#{{ str_pad($supplier->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                @if($supplier->machinery && $supplier->machinery->image)
                                    <img src="{{ Storage::url($supplier->machinery->image) }}?v={{ $supplier->machinery->updated_at->timestamp }}" 
                                         alt="Imagen de maquinaria" 
                                         class="w-12 h-12 object-cover rounded-full cursor-pointer hover:opacity-80 transition-opacity"
                                         onclick="openImageModal('{{ Storage::url($supplier->machinery->image) }}?v={{ $supplier->machinery->updated_at->timestamp }}')"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center" style="display: none;">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-cogs text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="font-semibold">{{ $supplier->machinery->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $supplier->machinery->brand ?? '' }} {{ $supplier->machinery->model ?? '' }}</div>
                            </td>
                            <td>{{ $supplier->maker }}</td>
                            <td>{{ $supplier->supplier }}</td>
                            <td>{{ $supplier->origin }}</td>
                            <td>{{ $supplier->purchase_date->format('d/m/Y') }}</td>
                            <td>
                                <div class="text-sm">
                                    <div><i class="fas fa-phone text-green-600 mr-1"></i>{{ $supplier->phone }}</div>
                                    <div class="text-xs text-gray-500 truncate"><i class="fas fa-envelope text-green-600 mr-1"></i>{{ $supplier->email }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="flex space-x-2 items-center">
                                    <button onclick="openViewModal({{ $supplier->id }})" 
                                       class="inline-flex items-center text-blue-500 hover:text-blue-700" title="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="confirmEdit({{ $supplier->id }})" 
                                       class="inline-flex items-center text-green-500 hover:text-green-700" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.machinery.supplier.destroy', $supplier) }}" 
                                          method="POST" class="inline" 
                                          onsubmit="return confirmDelete(event, this)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center text-red-500 hover:text-red-700" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-4 block"></i>
                                No se encontraron registros de proveedores
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($suppliers->hasPages())
            <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-700">
                    Showing {{ $suppliers->firstItem() }} to {{ $suppliers->lastItem() }} of {{ $suppliers->total() }} results
                </div>
                <div class="flex space-x-2">
                    {{ $suppliers->links() }}
                </div>
            </div>
        @endif
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
        <img id="modalImage" src="" alt="Imagen de maquinaria" 
             class="max-w-4xl max-h-[80vh] w-auto h-auto object-contain rounded-lg shadow-2xl mx-auto">
    </div>
</div>

<!-- Modal para ver detalles del proveedor -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop-blur hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="waste-header">
            <div class="text-center">
                <h3 class="waste-title text-xl justify-center">
                    <i class="fas fa-eye waste-icon"></i>
                    Detalles del Proveedor
                </h3>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    <span id="viewUserInfo">{{ Auth::user()->name }} - Registro #<span id="viewRecordId"></span></span>
                </p>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="space-y-6">
                <!-- Imagen de la maquinaria -->
                <div id="viewImageContainer" class="text-center">
                    <img id="viewImage" src="" alt="Imagen de maquinaria" 
                         class="max-w-full h-64 object-cover rounded-lg shadow-md mx-auto cursor-pointer"
                         onclick="openImageModal(this.src)">
                </div>

                <!-- Información del proveedor -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="waste-form-group">
                        <label class="waste-form-label">Maquinaria</label>
                        <div class="waste-form-input bg-gray-50" id="viewMachinery"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Fabricante</label>
                        <div class="waste-form-input bg-gray-50" id="viewMaker"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Proveedor</label>
                        <div class="waste-form-input bg-gray-50" id="viewSupplier"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Origen</label>
                        <div class="waste-form-input bg-gray-50" id="viewOrigin"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha de Compra</label>
                        <div class="waste-form-input bg-gray-50" id="viewPurchaseDate"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Teléfono</label>
                        <div class="waste-form-input bg-gray-50" id="viewPhone"></div>
                    </div>

                    <div class="waste-form-group md:col-span-2">
                        <label class="waste-form-label">Correo Electrónico</label>
                        <div class="waste-form-input bg-gray-50" id="viewEmail"></div>
                    </div>

                    <div class="waste-form-group">
                        <label class="waste-form-label">Fecha de Creación</label>
                        <div class="waste-form-input bg-gray-50" id="viewCreatedAt"></div>
                    </div>
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

<script>
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

// Funciones para el modal de vista
function openViewModal(supplierId) {
    fetch(`/admin/machinery/supplier/${supplierId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            document.getElementById('viewRecordId').textContent = data.id.toString().padStart(3, '0');
            document.getElementById('viewMachinery').textContent = data.machinery_name || 'N/A';
            document.getElementById('viewMaker').textContent = data.maker || 'N/A';
            document.getElementById('viewSupplier').textContent = data.supplier || 'N/A';
            document.getElementById('viewOrigin').textContent = data.origin || 'N/A';
            document.getElementById('viewPurchaseDate').textContent = data.purchase_date_formatted || data.purchase_date;
            document.getElementById('viewPhone').textContent = data.phone || 'N/A';
            document.getElementById('viewEmail').textContent = data.email || 'N/A';
            document.getElementById('viewCreatedAt').textContent = data.created_at_formatted || data.created_at;
            
            // Mostrar imagen si existe
            if (data.machinery_image_url) {
                document.getElementById('viewImage').src = data.machinery_image_url;
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

// Confirmación antes de editar
function confirmEdit(supplierId) {
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
            window.location.href = `/admin/machinery/supplier/${supplierId}/edit`;
        }
    });
}

// Función para confirmar eliminación con SweetAlert2
function confirmDelete(event, form) {
    event.preventDefault();
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¿Quieres eliminar este registro de proveedor?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-lg',
            title: 'text-lg font-semibold',
            content: 'text-sm text-gray-600',
            confirmButton: 'px-4 py-2 rounded-lg font-medium',
            cancelButton: 'px-4 py-2 rounded-lg font-medium'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Eliminando...',
                text: 'Por favor espera',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Enviar formulario
            form.submit();
        }
    });
    
    return false;
}

// Mostrar mensaje de éxito si existe
@if(session('success'))
    Swal.fire({
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonColor: '#22c55e',
        confirmButtonText: 'Entendido',
        customClass: {
            popup: 'rounded-lg',
            title: 'text-lg font-semibold text-green-600',
            content: 'text-sm text-gray-600',
            confirmButton: 'px-4 py-2 rounded-lg font-medium'
        }
    });
@endif
</script>
@endsection
