@extends('layouts.master')

@section('title', 'Registrar Maquinaria')

@section('content')
@vite(['resources/css/waste.css'])

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-6 py-8">
    <!-- Header con colores suaves como la vista de lista -->
    <div class="waste-header animate-fade-in-up">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-plus text-green-500 mr-3"></i>
                Registrar Maquinaria
            </h1>
            <p class="waste-subtitle">
                <i class="fas fa-user-shield text-green-400 mr-2"></i>
                {{ Auth::user()->name }} - Crear nuevo registro
            </p>
        </div>
    </div>

    <!-- Formulario con estilo de tarjeta como la vista de lista -->
    <div class="waste-card animate-fade-in-up animate-delay-1">
        <!-- Header del formulario -->
        <div class="waste-card-header">
            <div class="flex items-center space-x-3">
                <div class="waste-card-icon text-green-600">
                    <i class="fas fa-cogs"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Información de la Maquinaria</h2>
            </div>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded m-6">
                <h3 class="text-sm font-medium mb-2">Por favor corrige los siguientes errores:</h3>
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded m-6">
                {{ session('error') }}
            </div>
        @endif

            <!-- Cuerpo del formulario -->
            <div class="p-8">
                <form action="{{ route('admin.machinery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <!-- Primera fila -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Nombre -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-cogs text-soft-green-500 mr-2"></i>
                                Nombre de la maquinaria *
                            </label>
                            <input type="text" name="name" id="name" maxlength="150" required
                                   value="{{ old('name') }}"
                                   placeholder="Nombre"
                                   class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Ubicación -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-map-marker-alt text-soft-green-500 mr-2"></i>
                                Ubicación *
                            </label>
                            <input type="text" name="location" id="location" maxlength="150" required
                                   value="{{ old('location') }}"
                                   placeholder="Ej: Galpón A - Sector 1"
                                   class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('location') border-red-500 @enderror">
                            @error('location')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Fecha -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-calendar-alt text-soft-green-500 mr-2"></i>
                                Fecha de inicio de funcionamiento *
                            </label>
                            <input type="date" name="start_func" id="start_func" required
                                   value="{{ old('start_func') }}"
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('start_func') border-red-500 @enderror">
                            @error('start_func')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Segunda fila -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Marca -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-industry text-soft-green-500 mr-2"></i>
                                Marca *
                            </label>
                            <input type="text" name="brand" id="brand" maxlength="100" required
                                   value="{{ old('brand') }}"
                                   placeholder="Ej: jcb"
                                   class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('brand') border-red-500 @enderror">
                            @error('brand')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Modelo -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-tag text-soft-green-500 mr-2"></i>
                                Modelo *
                            </label>
                            <input type="text" name="model" id="model" maxlength="100" required
                                   value="{{ old('model') }}"
                                   placeholder="Ej: 5075E"
                                   class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('model') border-red-500 @enderror">
                            @error('model')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tercera fila -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Número de serie -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-barcode text-soft-green-500 mr-2"></i>
                                Número de serie *
                            </label>
                            <input type="text" name="serial" id="serial" maxlength="100" required
                                   value="{{ old('serial') }}"
                                   placeholder="Ej: JD5075E2023001"
                                   class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 font-mono @error('serial') border-red-500 @enderror">
                            @error('serial')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Frecuencia de mantenimiento -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-wrench text-soft-green-500 mr-2"></i>
                                Frecuencia de mantenimiento *
                            </label>
                            <div class="relative">
                                <select name="maint_freq" id="maint_freq" required
                                        class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 appearance-none bg-white @error('maint_freq') border-red-500 @enderror">
                                    <option value="">Seleccionar frecuencia...</option>
                                    <option value="Diario" {{ old('maint_freq') == 'Diario' ? 'selected' : '' }}>Diario</option>
                                    <option value="Semanal" {{ old('maint_freq') == 'Semanal' ? 'selected' : '' }}>Semanal</option>
                                    <option value="Quincenal" {{ old('maint_freq') == 'Quincenal' ? 'selected' : '' }}>Quincenal</option>
                                    <option value="Mensual" {{ old('maint_freq') == 'Mensual' ? 'selected' : '' }}>Mensual</option>
                                    <option value="Bimestral" {{ old('maint_freq') == 'Bimestral' ? 'selected' : '' }}>Bimestral</option>
                                    <option value="Trimestral" {{ old('maint_freq') == 'Trimestral' ? 'selected' : '' }}>Trimestral</option>
                                    <option value="Semestral" {{ old('maint_freq') == 'Semestral' ? 'selected' : '' }}>Semestral</option>
                                    <option value="Anual" {{ old('maint_freq') == 'Anual' ? 'selected' : '' }}>Anual</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('maint_freq')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Cuarta fila: Imagen -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Imagen -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                                <i class="fas fa-image text-soft-green-500 mr-2"></i>
                                Imagen (Opcional)
                            </label>
                            <div class="relative">
                                <input type="file" name="image" id="imageInput" 
                                       class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('image') border-red-500 @enderror" 
                                       accept="image/*" onchange="previewImage(this)">
                                <div id="imagePreview" class="mt-4 hidden">
                                    <div class="relative inline-block">
                                        <img id="previewImg" class="w-32 h-32 object-cover rounded-xl border-2 border-gray-300 shadow-lg" alt="Preview">
                                        <button type="button" onclick="removeImage()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('image')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Formatos permitidos: JPEG, PNG, JPG, GIF, WEBP
                            </p>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-gray-300">
                        <a href="{{ route('admin.machinery.index') }}" 
                           class="flex-1 sm:flex-none px-8 py-4 bg-soft-gray-100 text-soft-gray-700 rounded-xl hover:bg-soft-gray-200 transition-all duration-300 text-center font-semibold flex items-center justify-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver a la Lista
                        </a>
                        <button type="submit" 
                                class="flex-1 sm:flex-none px-8 py-4 bg-gradient-to-r from-soft-green-400 to-soft-green-500 text-white rounded-xl hover:from-soft-green-500 hover:to-soft-green-600 transition-all duration-300 shadow-lg hover:shadow-xl text-center font-semibold flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i>
                            Guardar Registro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Función para previsualizar imagen (debe ser global para onchange)
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
    
    function removeImage() {
        const input = document.getElementById('imageInput');
        const preview = document.getElementById('imagePreview');
        
        input.value = '';
        preview.classList.add('hidden');
    }
    
    // Validación en tiempo real del número de serie
    document.addEventListener('DOMContentLoaded', function() {
        const serialInput = document.getElementById('serial');
        
        if (serialInput) {
            serialInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            });
        }
        
        // Confirmación antes de enviar el formulario
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const requiredFields = ['name', 'location', 'brand', 'model', 'serial', 'start_func', 'maint_freq'];
                const emptyFields = [];
                
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input.value.trim()) {
                        emptyFields.push(field);
                    }
                });
                
                if (emptyFields.length > 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Campos incompletos',
                        text: 'Por favor completa todos los campos requeridos antes de continuar.',
                        icon: 'warning',
                        confirmButtonColor: '#16a34a',
                        confirmButtonText: 'Entendido',
                        customClass: {
                            popup: 'rounded-lg',
                            title: 'text-lg font-semibold',
                            content: 'text-sm text-gray-600',
                            confirmButton: 'px-4 py-2 rounded-lg font-medium'
                        }
                    });
                    return false;
                }
                
                e.preventDefault();
                Swal.fire({
                    title: '¿Confirmar registro?',
                    text: '¿Estás seguro de que deseas registrar esta maquinaria con los datos ingresados?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, registrar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        popup: 'rounded-lg',
                        title: 'text-lg font-semibold',
                        content: 'text-sm text-gray-600',
                        confirmButton: 'px-4 py-2 rounded-lg font-medium',
                        cancelButton: 'px-4 py-2 rounded-lg font-medium'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
                
                return false;
            });
        }
    });

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

    // Mostrar mensaje de error si existe
    @if(session('error'))
        Swal.fire({
            title: '¡Error!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Entendido',
            customClass: {
                popup: 'rounded-lg',
                title: 'text-lg font-semibold text-red-600',
                content: 'text-sm text-gray-600',
                confirmButton: 'px-4 py-2 rounded-lg font-medium'
            }
        });
    @endif
</script>
@endsection
