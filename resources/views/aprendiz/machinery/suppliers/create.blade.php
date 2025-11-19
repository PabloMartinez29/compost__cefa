@extends('layouts.masteraprendiz')

@section('content')
@vite(['resources/css/waste.css'])

<div class="container mx-auto px-6 py-8">
    <!-- Header con colores suaves como la vista de lista -->
    <div class="waste-header animate-fade-in-up">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-plus text-green-500 mr-3"></i>
                Registrar Proveedor
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
                    <i class="fas fa-truck"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Información del Proveedor</h2>
            </div>
        </div>

        <!-- Cuerpo del formulario -->
        <div class="p-8">
            <form action="{{ route('aprendiz.machinery.supplier.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Primera fila -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Maquinaria -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                            <i class="fas fa-cogs text-soft-green-500 mr-2"></i>
                            Maquinaria *
                        </label>
                        <div class="relative">
                            <select name="machinery_id" required
                                    class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('machinery_id') border-red-500 @enderror appearance-none bg-white">
                                <option value="">Seleccionar maquinaria</option>
                                @foreach($machineries as $machinery)
                                    <option value="{{ $machinery->id }}" {{ old('machinery_id') == $machinery->id ? 'selected' : '' }}>
                                        {{ $machinery->name }} - {{ $machinery->brand }} {{ $machinery->model }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        @error('machinery_id')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Fabricante -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                            <i class="fas fa-industry text-soft-green-500 mr-2"></i>
                            Fabricante *
                        </label>
                        <input type="text" name="maker" maxlength="150" required
                               value="{{ old('maker') }}"
                               placeholder="Ej: John Deere"
                               class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('maker') border-red-500 @enderror">
                        @error('maker')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Origen -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                            <i class="fas fa-globe text-soft-green-500 mr-2"></i>
                            Origen *
                        </label>
                        <input type="text" name="origin" maxlength="150" required
                               value="{{ old('origin') }}"
                               placeholder="Ej: Estados Unidos"
                               class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('origin') border-red-500 @enderror">
                        @error('origin')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Segunda fila -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Fecha de Compra -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                            <i class="fas fa-calendar-alt text-soft-green-500 mr-2"></i>
                            Fecha de Compra *
                        </label>
                        <input type="date" name="purchase_date" required
                               value="{{ old('purchase_date') }}"
                               max="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('purchase_date') border-red-500 @enderror">
                        @error('purchase_date')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Nombre del Proveedor -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                            <i class="fas fa-store text-soft-green-500 mr-2"></i>
                            Nombre del Proveedor *
                        </label>
                        <input type="text" name="supplier" maxlength="150" required
                               value="{{ old('supplier') }}"
                               placeholder="Ej: Distribuidora Agrícola S.A."
                               class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('supplier') border-red-500 @enderror">
                        @error('supplier')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Tercera fila -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Teléfono -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                            <i class="fas fa-phone text-soft-green-500 mr-2"></i>
                            Teléfono *
                        </label>
                        <input type="text" name="phone" maxlength="50" required
                               value="{{ old('phone') }}"
                               placeholder="Ej: +57 300 123 4567"
                               class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Correo Electrónico -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-soft-gray-700">
                            <i class="fas fa-envelope text-soft-green-500 mr-2"></i>
                            Correo Electrónico *
                        </label>
                        <input type="email" name="email" maxlength="150" required
                               value="{{ old('email') }}"
                               placeholder="Ej: contacto@proveedor.com"
                               class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-gray-300">
                    <a href="{{ route('aprendiz.machinery.supplier.index') }}" 
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
@endsection
