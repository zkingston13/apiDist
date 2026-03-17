@extends('layouts.app')

@section('title', 'Gestión de Comisiones')
@section('page-title', 'Gestión de Comisiones')
@section('page-description', 'Administra y calcula comisiones de vendedores')

@section('contenido')
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <button type="button" onclick="calculateCommissions()" 
                class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Calcular Comisiones
        </button>
    </div>
    <div class="flex items-center space-x-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Periodo</label>
            <select id="periodFilter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option value="current">Mes Actual</option>
                <option value="last">Mes Anterior</option>
                <option value="quarter">Trimestre</option>
                <option value="year">Año</option>
            </select>
        </div>
        <div class="flex items-end">
            <button onclick="applyPeriodFilter()" 
                    class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                Aplicar
            </button>
        </div>
    </div>
</div>

<!-- Resumen de Comisiones -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Comisiones</p>
                <h3 class="text-2xl font-bold">$24,580</h3>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Pagadas</p>
                <h3 class="text-2xl font-bold">$18,420</h3>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Pendientes</p>
                <h3 class="text-2xl font-bold">$6,160</h3>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Vendedores</p>
                <h3 class="text-2xl font-bold">15</h3>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Comisiones -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h3 class="text-lg font-semibold">Comisiones por Vendedor</h3>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-600">Mostrar:</span>
            <select id="statusCommissions" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2">
                <option value="all">Todas</option>
                <option value="paid">Pagadas</option>
                <option value="pending">Pendientes</option>
            </select>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendedor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ventas Totales</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">% Comisión</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comisión</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Pago</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @for ($i = 1; $i <= 10; $i++)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-600 text-sm font-medium">{{ substr('ABCDEFGHIJ', $i-1, 1) }}</span>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">Vendedor {{ $i }}</div>
                                <div class="text-sm text-gray-500">ID: VEN-00{{ $i }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                        ${{ number_format(50000 + ($i * 10000), 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="text-sm font-medium mr-2">{{ 5 + ($i % 3) }}%</span>
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ 5 + ($i % 3) * 10 }}%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-green-600">
                        ${{ number_format((50000 + ($i * 10000)) * (0.05 + (($i % 3) * 0.01)), 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($i % 3 == 0)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                        @elseif ($i % 3 == 1)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Pagada</span>
                        @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Procesando</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if ($i % 3 != 0)
                        {{ now()->subDays($i)->format('d/m/Y') }}
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @if ($i % 3 == 0)
                        <button onclick="payCommission({{ $i }})" class="text-green-600 hover:text-green-900 mr-3">Pagar</button>
                        @endif
                        <button onclick="viewDetails({{ $i }})" class="text-blue-600 hover:text-blue-900">Detalles</button>
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>

<!-- Gráfico de Comisiones -->
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Distribución de Comisiones</h3>
    <canvas id="commissionsChart"></canvas>
</div>

<!-- Modal de Detalles -->
<div id="detailsModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50" onclick="closeDetailsModal()"></div>
        <div class="relative bg-white rounded-lg shadow-lg w-full max-w-2xl">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Detalles de Comisión</h3>
                <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Información del Vendedor</h4>
                            <div class="space-y-2">
                                <p><span class="text-gray-600">Nombre:</span> <span class="font-medium">Juan Pérez</span></p>
                                <p><span class="text-gray-600">ID:</span> <span class="font-medium">VEN-001</span></p>
                                <p><span class="text-gray-600">% Comisión:</span> <span class="font-medium">7%</span></p>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Resumen de Ventas</h4>
                            <div class="space-y-2">
                                <p><span class="text-gray-600">Ventas Totales:</span> <span class="font-medium text-green-600">$65,000.00</span></p>
                                <p><span class="text-gray-600">Comisión:</span> <span class="font-bold text-green-600">$4,550.00</span></p>
                                <p><span class="text-gray-600">Periodo:</span> <span class="font-medium">Enero 2024</span></p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-700 mb-3">Ventas Detalladas</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">ID Venta</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Fecha</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Producto</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Monto</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @for ($j = 1; $j <= 5; $j++)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">#00{{ $j }}</td>
                                        <td class="px-4 py-2 text-sm">{{ now()->subDays($j)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2 text-sm">Producto {{ $j }}</td>
                                        <td class="px-4 py-2 text-sm font-medium">${{ number_format(1000 * $j, 2) }}</td>
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                    <button onclick="closeDetailsModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">
                        Cerrar
                    </button>
                    <button onclick="generateReport()"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                        Generar Reporte
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function calculateCommissions() {
        if (confirm('¿Deseas calcular las comisiones para este periodo?')) {
            // Simular cálculo
            alert('Comisiones calculadas exitosamente');
        }
    }

    function applyPeriodFilter() {
        const period = document.getElementById('periodFilter').value;
        alert(`Filtro aplicado para: ${period}`);
    }

    function payCommission(id) {
        if (confirm('¿Marcar esta comisión como pagada?')) {
            alert(`Comisión ${id} marcada como pagada`);
        }
    }

    function viewDetails(id) {
        document.getElementById('detailsModal').classList.remove('hidden');
    }

    function closeDetailsModal() {
        document.getElementById('detailsModal').classList.add('hidden');
    }

    function generateReport() {
        alert('Reporte generado exitosamente');
        closeDetailsModal();
    }

    // Gráfico de comisiones
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('commissionsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Vend 1', 'Vend 2', 'Vend 3', 'Vend 4', 'Vend 5'],
                datasets: [{
                    label: 'Comisiones ($)',
                    data: [4550, 3200, 5100, 2800, 3900],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ]
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
    });
</script>
@endsection
@endsection