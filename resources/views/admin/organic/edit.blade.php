@extends('layouts.master')

@section('content')
@vite(['resources/css/waste.css'])

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="waste-title">
                    <i class="fas fa-edit waste-icon"></i>
                    Editar Registro de Residuo Orgánico
                </h1>
                <p class="waste-subtitle">
                    <i class="fas fa-user-shield text-green-400 mr-2"></i>
                    {{ Auth::user()->name }} - Registro #{{ str_pad($organic->id, 3, '0', STR_PAD_LEFT) }}
                </p>
            </div>
            <div class="text-right">
                <div class="text-green-400 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="max-w-2xl mx-auto">
        <div class="waste-form animate-fade-in-up animate-delay-1">
            <form action="{{ route('admin.organic.update', $organic) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Date -->
                <div class="waste-form-group">
                    <label class="waste-form-label">Date *</label>
                    <input type="date" name="date" class="waste-form-input @error('date') border-red-500 @enderror" 
                           value="{{ old('date', $organic->date->format('Y-m-d')) }}" required>
                    @error('date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div class="waste-form-group">
                    <label class="waste-form-label">Tipo de Residuo *</label>
                    <select name="type" class="waste-form-select @error('type') border-red-500 @enderror" required>
                        <option value="">Seleccionar tipo de residuo</option>
                        <option value="Kitchen" {{ old('type', $organic->type) == 'Kitchen' ? 'selected' : '' }}>Cocina</option>
                        <option value="Beds" {{ old('type', $organic->type) == 'Beds' ? 'selected' : '' }}>Camas</option>
                        <option value="Leaves" {{ old('type', $organic->type) == 'Leaves' ? 'selected' : '' }}>Hojas</option>
                        <option value="CowDung" {{ old('type', $organic->type) == 'CowDung' ? 'selected' : '' }}>Estiércol de Vaca</option>
                        <option value="ChickenManure" {{ old('type', $organic->type) == 'ChickenManure' ? 'selected' : '' }}>Estiércol de Pollo</option>
                        <option value="PigManure" {{ old('type', $organic->type) == 'PigManure' ? 'selected' : '' }}>Estiércol de Cerdo</option>
                        <option value="Other" {{ old('type', $organic->type) == 'Other' ? 'selected' : '' }}>Otro</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Weight -->
                <div class="waste-form-group">
                    <label class="waste-form-label">Weight (Kg) *</label>
                    <input type="number" name="weight" class="waste-form-input @error('weight') border-red-500 @enderror" 
                           placeholder="Enter weight in kilograms" step="0.01" min="0.01" 
                           value="{{ old('weight', $organic->weight) }}" required>
                    @error('weight')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Delivered By -->
                <div class="waste-form-group">
                    <label class="waste-form-label">Delivered By *</label>
                    <input type="text" name="delivered_by" class="waste-form-input @error('delivered_by') border-red-500 @enderror" 
                           placeholder="Enter deliverer name" value="{{ old('delivered_by', $organic->delivered_by) }}" required>
                    @error('delivered_by')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Received By -->
                <div class="waste-form-group">
                    <label class="waste-form-label">Received By *</label>
                    <input type="text" name="received_by" class="waste-form-input @error('received_by') border-red-500 @enderror" 
                           placeholder="Enter receiver name" value="{{ old('received_by', $organic->received_by) }}" required>
                    @error('received_by')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Image -->
                @if($organic->img)
                    <div class="waste-form-group">
                        <label class="waste-form-label">Current Image</label>
                        <div class="relative">
                            <img src="{{ asset($organic->img) }}" 
                                 alt="Current organic waste image" 
                                 class="w-full h-32 object-cover rounded-lg border border-gray-200">
                        </div>
                    </div>
                @endif

                <!-- New Image Upload -->
                <div class="waste-form-group">
                    <label class="waste-form-label">New Image (Optional)</label>
                    <input type="file" name="img" class="waste-form-input @error('img') border-red-500 @enderror" 
                           accept="image/*">
                    @error('img')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Max file size: 2MB. Supported formats: JPEG, PNG, JPG, GIF</p>
                    @if($organic->img)
                        <p class="text-yellow-600 text-sm mt-1">Uploading a new image will replace the current one.</p>
                    @endif
                </div>

                <!-- Notes -->
                <div class="waste-form-group">
                    <label class="waste-form-label">Notes</label>
                    <textarea name="notes" class="waste-form-textarea @error('notes') border-red-500 @enderror" 
                              rows="4" placeholder="Enter additional notes about the organic waste...">{{ old('notes', $organic->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-300">
                    <a href="{{ route('admin.organic.index') }}" 
                       class="flex-1 sm:flex-none px-4 py-2 bg-soft-gray-100 text-soft-gray-700 rounded-lg hover:bg-soft-gray-200 transition-all duration-200 text-center font-medium flex items-center justify-center">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="flex-1 sm:flex-none px-4 py-2 bg-soft-green-400 text-white rounded-lg hover:bg-soft-green-500 transition-all duration-200 shadow-md hover:shadow-lg text-center font-medium flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Record History -->
    <div class="max-w-4xl mx-auto mt-8">
        <div class="waste-container animate-fade-in-up animate-delay-2">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-history text-green-400 mr-2"></i>
                Record Information
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="waste-form-label">Created At</label>
                    <div class="waste-form-input bg-gray-50">{{ $organic->created_at->format('d/m/Y H:i:s') }}</div>
                </div>
                <div>
                    <label class="waste-form-label">Last Updated</label>
                    <div class="waste-form-input bg-gray-50">{{ $organic->updated_at->format('d/m/Y H:i:s') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
