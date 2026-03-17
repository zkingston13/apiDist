{{-- resources/views/vendedores/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Administrar Vendedores - Distribuidora')

@section('page-title', 'Administración de Vendedores')

@section('page-description', 'Gestiona la información y comisiones de los vendedores')

@section('contenido')
<div class="space-y-6">
    {{-- Header con acciones --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex gap-2">
            <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Vendedor
            </button>
        </div>
        
        {{-- Buscador --}}
        <div class="relative w-full sm:w-64 gap-2">
            <input type="text" 
                   placeholder="Buscar vendedor..." 
                   class="w-70 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    {{-- Tabla de vendedores --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Vendedor
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ventas Realizadas
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Sueldo Base
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Comisión por Ventas
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Sueldo Neto
                        </th>
                         <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($vendedores as $vendedor)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $vendedor->id_empleado }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                       {{ $vendedor->nombre }} {{ $vendedor->apellidoP }} {{ $vendedor->apellidoM }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $vendedor->telefono }}
                                    </div>
                                </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $vendedor->ventas_realizadas }}</div>

                            <div class="text-xs text-gray-500">{{ $vendedor->total_ventas }}</div>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $vendedor->sueldo_base }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $vendedor->comision }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $vendedor->sueldo_neto }}</div>
                            <div class="text-xs text-gray-500">Sueldo base + comisión</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                        @if($vendedor->activo == 1)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-300 text-white">
                                activo
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                inactivo
                            </span>
                        @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                    <a href="{{ route('vendedores.show', $vendedor->id_empleado )}}" class="text-blue-600 hover:text-blue-900" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    </a>
                                @if($vendedor->activo == 1)
                                <form action="{{ route('vendedores.destroy', $vendedor->id_empleado) }}"
                                    method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                    class="text-red-600 hover:text-red-900" title="Eliminar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                                </form>
                                @endif
                                
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                
                {{-- Totales --}}
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">
                            Totales:
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Q7,500.00
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Q5,840.63
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Q13,340.63
                        </td>
                        <td class="px-6 py-4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
        
        {{-- Paginación --}}
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Mostrando <span class="font-medium">1</span> a <span class="font-medium">3</span> de <span class="font-medium">12</span> vendedores
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50" disabled>
                        Anterior
                    </button>
                    <button class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                        1
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        2
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        3
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Siguiente
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar vendedor -->
<div id="vendedorModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50" onclick="closeModal()"></div>
        <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Nuevo vendedor</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        <form id="vendedorForm" action="{{ route('vendedores.store') }}" method="POST" class="space-y-6">
            @csrf
        
        {{-- Información Personal --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Información Personal</h3>
                <p class="text-sm text-gray-500 mt-1">Datos básicos del vendedor</p>
            </div>
            
            <div class="p-6 space-y-6">
                {{-- Nombres y Apellidos --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre" 
                               class="w-full px-4 py-2 border border-gray-300 text-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombres') border-red-500 @enderror"
                               placeholder="Ej: Juan Carlos"
                               required>
                    </div>
                    
                    <div>
                        <label for="apellidoP" class="block text-sm font-medium text-gray-700 mb-2">
                            Apellido Paterno <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="apellidoP" 
                               id="apellidoP" 
                               class="w-full px-4 py-2 border border-gray-300 text-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('apellidos') border-red-500 @enderror"
                               placeholder="Ej: Pérez González"
                               required>
                    </div>
                    <div>
                        <label for="apellidoM" class="block text-sm font-medium text-gray-700 mb-2">
                            Apellido Materno <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="apellidoM" 
                               id="apellidoM" 
                               class="w-full px-4 py-2 border border-gray-300 text-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('apellidos') border-red-500 @enderror"
                               placeholder="Ej: Pérez González"
                               required>
                    </div>
                </div>
                
                {{-- Email y Teléfono --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Correo Electrónico <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="correo" 
                               id="email" 
                               class="w-full px-4 py-2 border border-gray-300 text-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                               placeholder="ejemplo@correo.com"
                               required>
                    </div>
                    
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                            Teléfono
                        </label>
                        <input type="tel" 
                               name="telefono" 
                               id="telefono" 
                               class="w-full px-4 py-2 border border-gray-300 text-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('telefono') border-red-500 @enderror"
                               placeholder="Ej: 1234-5678">
                    </div>
                </div>
                <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Contraseña Temporal <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="password" 
                               id="nombre" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombres') border-red-500 @enderror"
                               placeholder="● ● ● ● ● ● ●"
                               required>
                    </div>
                
                {{-- Turno --}}
                <div>
                    <label for="turno" class="block text-sm font-medium text-gray-700 mb-2">
                       Turno
                    </label>
                    <select name="turno" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-60 p-2.5">
                            <option value="Matutino">
                                Matutino
                            </option>
                            <option value="Vespertino">
                                Vespertino
                            </option>
                        </select>
                </div>
                <div>
                    <label for="rol" class="block text-sm font-medium text-gray-700 mb-2">
                       Rol
                    </label>
                    <select name="rol" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-60 p-2.5">
                            <option value="vendedor">
                                Vendedor
                            </option>
                            <option value="administrador">
                                Administrador
                            </option>
                        </select>
                </div>
                <div>
                        <label for="sueldo_base" class="block text-sm font-medium text-gray-700 mb-2">
                            Sueldo Base <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="sueldo_base" 
                                   id="sueldo_base" 
                                   step="0.01"
                                   min="0"
                                   class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sueldo_base') border-red-500 @enderror"
                                   placeholder="0.00"
                                   required>
                        </div>
                    </div>
            </div>
        </div>
        
        {{-- Estado del Vendedor --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Estado del Vendedor</h3>
            </div>
            
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        {{-- Checkbox de Activo --}}
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="activo" 
                                   id="activo" 
                                   value="1"
                                   class="h-5 w-5 text-blue-600 focus:ring-blue-500  rounded transition duration-150 ease-in-out">
                            <label for="activo" class="ml-2 block text-sm font-medium text-gray-700">
                                Vendedor Activo
                            </label>
                        </div>
                        
                    </div>
                </div>
                
                <p class="mt-2 text-xs text-gray-500">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Si el vendedor no está activo, no aparecerá en los reportes de ventas ni podrá realizar nuevas ventas.
                </p>
            </div>
        </div>
        
        {{-- Botones de acción --}}
        <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="closeModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-900 hover:bg-gray-700 rounded-lg">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
     function openAddModal() {
        document.getElementById('modalTitle').textContent = 'Nuevo Empleado';
        document.getElementById('vendedorForm').reset();
        editingProductId = null;
        document.getElementById('vendedorModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('vendedorModal').classList.add('hidden');
    }

    // Aquí puedes agregar JavaScript específico para esta vista
    // Por ejemplo, funcionalidad para el buscador, filtros, etc.
    document.addEventListener('DOMContentLoaded', function() {
        // Ejemplo: buscador simple
        const searchInput = document.querySelector('input[placeholder="Buscar vendedor..."]');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }
    });
</script>
@endsection