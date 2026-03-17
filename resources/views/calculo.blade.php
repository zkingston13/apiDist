@extends('layouts.app')

@section('title', 'Cálculos y Reportes')
@section('page-title', 'Cálculos y Reportes')
@section('page-description', 'Realiza cálculos y genera reportes del sistema')

@section('contenido')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Calculadora de Comisiones -->
    <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
        <h3 class="text-lg font-semibold mb-4">Calculadora de Comisiones</h3>
        <form id="commissionCalculator" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vendedor</label>
                    <select id="calculatorSeller" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Seleccionar vendedor</option>
                        @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">Vendedor {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">% Comisión</label>
                    <input type="number" id="commissionPercentage" step="0.1" min="0" max="100" required
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                           placeholder="Ej: 7.5">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Monto de Ventas</label>
                <input type="number" id="salesAmount" step="0.01" required
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                       placeholder="0.00">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Periodo</label>
                <div class="grid grid-cols-2 gap-4">
                    <input type="date" id="startDate" required
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <input type="date" id="endDate" required
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>
            </div>
            
            <div class="pt-4">
                <button type="button" onclick="calculateCommission()"
                        class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3">
                    Calcular Comisión
                </button>
            </div>
        </form>
        
        <!-- Resultado -->
        <div id="calculationResult" class="mt-6 p-4 bg-gray-50 rounded-lg hidden">
            <h4 class="font-semibold text-lg mb-3">Resultado del Cálculo</h4>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Ventas Totales:</span>
                    <span class="font-semibold" id="resultSales">$0.00</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Porcentaje de Comisión:</span>
                    <span class="font-semibold" id="resultPercentage">0%</span>
                </div>
                <div class="flex justify-between text-lg font-bold border-t pt-2">
                    <span class="text-gray-700">Comisión Total:</span>
                    <span class="text-green-600" id="resultCommission">$0.00</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Herramientas Rápidas -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Herramientas Rápidas</h3>
        <div class="space-y-4">
            <button onclick="openSalesReport()" 
                    class="w-full flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="font-medium">Reporte de Ventas</span>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            
            <button onclick="openInventoryReport()" 
                    class="w-full flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <span class="font-medium">Reporte de Inventario</span>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            
            <button onclick="openCommissionsReport()" 
                    class="w-full flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-purple-100 text-purple-600 mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="font-medium">Reporte de Comisiones</span>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            
            <button onclick="openExportTool()" 
                    class="w-full flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-yellow-100 text-yellow-600 mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="font-medium">Exportar Datos</span>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Gráficos de Análisis -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Tendencia de Ventas</h3>
        <canvas id="salesTrendChart"></canvas>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Distribución por Categoría</h3>
        <canvas id="categoryDistributionChart"></canvas>
    </div>
</div>

<!-- Reportes Generados -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b">
        <h3 class="text-lg font-semibold">Reportes Generados Recientemente</h3>
    </div>
    <div class="divide-y divide-gray-200">
        @for ($i = 1; $i <= 5; $i++)
        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
            <div class="flex items-center">
                <div class="p-2 rounded-lg {{ $i % 3 == 0 ? 'bg-blue-100' : ($i % 3 == 1 ? 'bg-green-100' : 'bg-purple-100') }} mr-4">
                    <svg class="w-6 h-6 {{ $i % 3 == 0 ? 'text-blue-600' : ($i % 3 == 1 ? 'text-green-600' : 'text-purple-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Reporte de Ventas {{ now()->subDays($i)->format('d/m/Y') }}</h4>
                    <p class="text-sm text-gray-500">Generado por: Administrador</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="viewReport({{ $i }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Ver</button>
                <button onclick="downloadReport({{ $i }})" class="text-green-600 hover:text-green-800 text-sm font-medium">Descargar</button>
            </div>
        </div>
        @endfor
    </div>
</div>

@section('scripts')
<script>
    // Configurar fechas por defecto
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        
        document.getElementById('startDate').value = firstDay.toISOString().split('T')[0];
        document.getElementById('endDate').value = today.toISOString().split('T')[0];
        
        // Inicializar gráficos
        initializeCharts();
    });
    
    function calculateCommission() {
        const sales = parseFloat(document.getElementById('salesAmount').value);
        const percentage = parseFloat(document.getElementById('commissionPercentage').value);
        
        if (!sales || !percentage) {
            alert('Por favor completa todos los campos');
            return;
        }
        
        const commission = (sales * percentage) / 100;
        
        document.getElementById('resultSales').textContent = '$' + sales.toFixed(2);
        document.getElementById('resultPercentage').textContent = percentage + '%';
        document.getElementById('resultCommission').textContent = '$' + commission.toFixed(2);
        document.getElementById('calculationResult').classList.remove('hidden');
    }
    
    function openSalesReport() {
        alert('Generando reporte de ventas...');
    }
    
    function openInventoryReport() {
        alert('Generando reporte de inventario...');
    }
    
    function openCommissionsReport() {
        alert('Generando reporte de comisiones...');
    }
    
    function openExportTool() {
        alert('Abriendo herramienta de exportación...');
    }
    
    function viewReport(id) {
        alert('Viendo reporte ' + id);
    }
    
    function downloadReport(id) {
        alert('Descargando reporte ' + id);
    }
    
    function initializeCharts() {
        // Gráfico de tendencia de ventas
        const salesCtx = document.getElementById('salesTrendChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Ventas',
                    data: [12000, 19000, 15000, 22000, 18000, 25000, 20000],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
        
        // Gráfico de distribución por categoría
        const categoryCtx = document.getElementById('categoryDistributionChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'pie',
            data: {
                labels: ['Electrónica', 'Alimentos', 'Bebidas', 'Limpieza', 'Otros'],
                datasets: [{
                    data: [30, 25, 20, 15, 10],
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#8b5cf6',
                        '#ef4444'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
</script>
@endsection
@endsection