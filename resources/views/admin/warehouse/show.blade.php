@extends('layouts.master')

@section('content')
<div class="min-h-screen bg-soft-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-soft-green-500 to-soft-green-600 rounded-xl flex items-center justify-center shadow-sm">
                        <i class="fas fa-eye text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-soft-gray-900">Detalle del Movimiento</h1>
                        <p class="text-soft-gray-600">Información completa del registro</p>
                    </div>
                </div>
                <a href="{{ route('admin.warehouse.index') }}" 
                   class="bg-soft-gray-200 hover:bg-soft-gray-300 text-soft-gray-700 px-4 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver</span>
                </a>
            </div>
        </div>

        <!-- Movement Details Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-soft-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-soft-green-100 to-soft-green-200 px-6 py-4 border-b border-soft-green-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-soft-gray-900">Movimiento #{{ str_pad($warehouse->id, 4, '0', STR_PAD_LEFT) }}</h2>
                        <p class="text-soft-gray-600">{{ $warehouse->formatted_date }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $warehouse->movement_type === 'entry' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            <i class="fas {{ $warehouse->movement_type === 'entry' ? 'fa-arrow-down' : 'fa-arrow-up' }} mr-1"></i>
                            {{ $warehouse->movement_type_in_spanish }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-soft-gray-900 border-b border-soft-gray-200 pb-2">
                            Información Básica
                        </h3>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-soft-gray-600">Tipo de Residuo</label>
                                <p class="text-soft-gray-900 font-medium">{{ $warehouse->type_in_spanish }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-soft-gray-600">Peso</label>
                                <p class="text-2xl font-bold text-soft-green-600">{{ $warehouse->formatted_weight }} kg</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-soft-gray-600">Procesado por</label>
                                <p class="text-soft-gray-900">{{ $warehouse->processed_by }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-soft-gray-600">Fecha de Registro</label>
                                <p class="text-soft-gray-900">{{ $warehouse->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-soft-gray-900 border-b border-soft-gray-200 pb-2">
                            Información Adicional
                        </h3>
                        
                        <div class="space-y-3">
                            @if($warehouse->notes)
                            <div>
                                <label class="block text-sm font-medium text-soft-gray-600">Notas</label>
                                <div class="bg-soft-gray-50 rounded-lg p-3 border border-soft-gray-200">
                                    <p class="text-soft-gray-900">{{ $warehouse->notes }}</p>
                                </div>
                            </div>
                            @endif
                            
                            @if($warehouse->img)
                            <div>
                                <label class="block text-sm font-medium text-soft-gray-600 mb-2">Imagen</label>
                                <div class="relative">
                                    <img src="{{ Storage::url($warehouse->img) }}" 
                                         alt="Imagen del movimiento" 
                                         class="w-full max-w-sm rounded-lg shadow-sm border border-soft-gray-200 cursor-pointer hover:shadow-md transition-shadow duration-200"
                                         onclick="openImageModal('{{ Storage::url($warehouse->img) }}')">
                                </div>
                            </div>
                            @else
                            <div>
                                <label class="block text-sm font-medium text-soft-gray-600">Imagen</label>
                                <div class="bg-soft-gray-50 rounded-lg p-4 border border-soft-gray-200 text-center">
                                    <i class="fas fa-image text-soft-gray-400 text-2xl mb-2"></i>
                                    <p class="text-soft-gray-500 text-sm">No hay imagen disponible</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-[90vh] w-auto h-auto">
        <button onclick="closeImageModal()" 
                class="absolute -top-4 -right-4 bg-white rounded-full w-10 h-10 flex items-center justify-center shadow-lg hover:bg-gray-100 transition-colors duration-200 z-10">
            <i class="fas fa-times text-gray-600"></i>
        </button>
        <img id="modalImage" src="" alt="Imagen ampliada" class="max-w-full max-h-full rounded-lg shadow-2xl">
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside the image
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endsection
