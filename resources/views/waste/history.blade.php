@extends('layouts.masteraprendiz')

@section('content')
@vite(['resources/css/waste.css'])

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="waste-header animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="waste-title">
                    <i class="fas fa-history waste-icon"></i>
                    Waste History
                </h1>
                <p class="waste-subtitle">
                    <i class="fas fa-user text-green-400 mr-2"></i>
                    {{ Auth::user()->name }} - Complete waste management history
                </p>
            </div>
            <div class="text-right">
                <div class="text-green-400 font-bold text-lg">{{ \Carbon\Carbon::now()->setTimezone('America/Bogota')->format('d/m/Y') }}</div>    
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="waste-container mb-6 animate-fade-in-up animate-delay-1">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Filters</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="waste-form-label">Date From</label>
                <input type="date" class="waste-form-input">
            </div>
            <div>
                <label class="waste-form-label">Date To</label>
                <input type="date" class="waste-form-input">
            </div>
            <div>
                <label class="waste-form-label">Waste Type</label>
                <select class="waste-form-select">
                    <option value="">All Types</option>
                    <option value="organic">Organic Waste</option>
                    <option value="vegetable">Vegetable Waste</option>
                    <option value="fruit">Fruit Waste</option>
                    <option value="coffee">Coffee Grounds</option>
                </select>
            </div>
            <div>
                <label class="waste-form-label">Status</label>
                <select class="waste-form-select">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end mt-4">
            <button class="waste-btn-secondary mr-2">
                <i class="fas fa-filter mr-2"></i>
                Apply Filters
            </button>
            <button class="waste-btn">
                <i class="fas fa-download mr-2"></i>
                Export Data
            </button>
        </div>
    </div>

    <!-- Statistics Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="waste-card waste-card-primary animate-fade-in-up animate-delay-2">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Records</div>
                    <div class="text-3xl font-bold text-gray-800">0</div>
                </div>
                <div class="waste-card-icon text-blue-300">
                    <i class="fas fa-list"></i>
                </div>
            </div>
        </div>

        <div class="waste-card waste-card-success animate-fade-in-up animate-delay-3">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Weight</div>
                    <div class="text-3xl font-bold text-gray-800">0 Kg</div>
                </div>
                <div class="waste-card-icon text-green-300">
                    <i class="fas fa-weight"></i>
                </div>
            </div>
        </div>

        <div class="waste-card waste-card-warning animate-fade-in-up animate-delay-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Processing</div>
                    <div class="text-3xl font-bold text-gray-800">0</div>
                </div>
                <div class="waste-card-icon text-yellow-300">
                    <i class="fas fa-cogs"></i>
                </div>
            </div>
        </div>

        <div class="waste-card waste-card-info animate-fade-in-up animate-delay-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Completed</div>
                    <div class="text-3xl font-bold text-gray-800">0</div>
                </div>
                <div class="waste-card-icon text-cyan-300">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Waste History Table -->
    <div class="waste-container animate-fade-in-up animate-delay-3">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-table text-green-400 mr-2"></i>
                Waste Records
            </h2>
            <div class="flex space-x-2">
                <button class="waste-btn-secondary">
                    <i class="fas fa-refresh mr-2"></i>
                    Refresh
                </button>
                <button class="waste-btn">
                    <i class="fas fa-plus mr-2"></i>
                    Add New
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="waste-table">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Weight (Kg)</th>
                        <th>Source</th>
                        <th>Quality</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sample data row -->
                    <tr>
                        <td>#001</td>
                        <td>29/08/2025</td>
                        <td>Organic Waste</td>
                        <td>15.5</td>
                        <td>Kitchen</td>
                        <td>
                            <span class="waste-badge waste-badge-success">Good</span>
                        </td>
                        <td>
                            <span class="waste-badge waste-badge-warning">Processing</span>
                        </td>
                        <td>
                            <div class="flex space-x-2">
                                <button class="text-blue-500 hover:text-blue-700" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-green-500 hover:text-green-700" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-500 hover:text-red-700" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Empty state -->
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-4 block"></i>
                            No waste records found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
            <div class="text-sm text-gray-700">
                Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">0</span> results
            </div>
            <div class="flex space-x-2">
                <button class="waste-btn-secondary" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="waste-btn-secondary bg-green-200 text-green-800">1</button>
                <button class="waste-btn-secondary" disabled>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Set default date range (last 30 days)
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    const thirtyDaysAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
    
    const dateToInput = document.querySelector('input[type="date"]');
    const dateFromInput = document.querySelectorAll('input[type="date"]')[0];
    
    if (dateToInput) {
        dateToInput.value = today.toISOString().split('T')[0];
    }
    if (dateFromInput) {
        dateFromInput.value = thirtyDaysAgo.toISOString().split('T')[0];
    }
});

// Filter functionality
function applyFilters() {
    // Here you would implement the filter logic
    console.log('Applying filters...');
}

// Export functionality
function exportData() {
    // Here you would implement the export logic
    console.log('Exporting data...');
}
</script>
@endsection
