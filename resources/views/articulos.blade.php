@extends('layouts.app')

@section('title', 'Gestión de Artículos')
@section('page-title', 'Gestión de Artículos')
@section('page-description', 'Administra los productos y su inventario')

@section('contenido')
<div class="mb-6 flex justify-between items-center">
    <div>
        <button type="button" onclick="openAddModal()" 
                class="text-white bg-blue-900 hover:bg-gray-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nuevo Producto
        </button>
    </div>
    <div class="relative w-64">
        <input type="text" id="searchProduct" 
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" 
               placeholder="Buscar producto...">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="flex flex-wrap gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
            <select id="categoryFilter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @foreach ($categorias as $c)
                    <option value="">{{ $c->nombre_categoria }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
            <select id="statusFilter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option value="">Todos</option>
                <option value="stock">En stock</option>
                <option value="low">Stock bajo</option>
                <option value="out">Agotado</option>
            </select>
        </div>
        <div class="flex items-end">
            <button onclick="applyFilters()" class="text-white bg-blue-900 hover:bg-gray-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                Filtrar
            </button>
        </div>
    </div>
</div>

<!-- Tabla de Productos -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                    <!--<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>-->
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Existencia</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-gray-600" id="productsTable">
                @forelse ($productos as $p)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $p->id_producto }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $p->nombre_producto }}</td>
                    <!--<td class="px-6 py-4 whitespace-nowrap">{{ $p->nombre_categoria }}</td> -->
                    <td class="px-6 py-4 whitespace-nowrap">${{ number_format($p->precio, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $p->existencia }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($p->existencia > 10 && $p->activo==1)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                En stock
                            </span>
                        @elseif($p->existencia > 0 && $p->activo==1)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Stock bajo
                            </span>
                        @elseif($p->activo == 0)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                inactivo
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Agotado
                            </span>
                        @endif
                    </td>
                    @if($p->existencia <= 0)
                        <td>
                            <a href="/compra"
                           class="text-indigo-600 hover:text-indigo-900 mr-3">
                            Reabastecer
                        </a>
                        </td>
                    @else
                         <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('productos.show',$p->id_producto) }}"
                            class="text-blue-600">
                            Editar
                        </a>

                        <!-- Botón Eliminar -->
                        @if ($p->activo != 0)
                            <form action="{{ route('productos.destroy', $p->id_producto) }}"
                              method="POST"
                              class="inline-block"
                              onsubmit="return confirm('¿Estás segura que deseas eliminar este producto?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 hover:text-red-900">
                                Desactivar
                            </button>
                        </form>
                        @endif
                        
                    </td>
                    @endif
                   
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No hay productos disponibles
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
   <!-- Paginación -->
    <div class="px-6 py-4 border-t border-gray-200">
        <nav class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Mostrando <span class="font-medium">{{ $productos->firstItem() }}</span> 
                a <span class="font-medium">{{ $productos->lastItem() }}</span> 
                de <span class="font-medium">{{ $productos->total() }}</span> productos
            </div>
            <div class="flex space-x-2">
                @if ($productos->onFirstPage())
                    <span class="px-3 py-1 rounded-lg border border-gray-300 text-sm text-gray-400 cursor-not-allowed">Anterior</span>
                @else
                    <a href="{{ $productos->previousPageUrl() }}" class="px-3 py-1 rounded-lg border border-gray-300 text-sm hover:bg-gray-50">Anterior</a>
                @endif

                @foreach ($productos->getUrlRange(1, $productos->lastPage()) as $page => $url)
                    @if ($page == $productos->currentPage())
                        <span class="px-3 py-1 rounded-lg bg-blue-900 text-white text-sm">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 rounded-lg border border-gray-300 text-sm hover:bg-gray-50">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($productos->hasMorePages())
                    <a href="{{ $productos->nextPageUrl() }}" class="px-3 py-1 rounded-lg border border-gray-300 text-sm hover:bg-gray-50">Siguiente</a>
                @else
                    <span class="px-3 py-1 rounded-lg border border-gray-300 text-sm text-gray-400 cursor-not-allowed">Siguiente</span>
                @endif
            </div>
        </nav>
    </div>
</div>

<!-- Modal para Agregar/Editar Producto -->
<div id="productModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50" onclick="closeModal()"></div>
        <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Nuevo Producto</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
           <form id="productForm" action="{{ route('productos.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
                        <select name="id_proveedor" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Seleccione un proveedor</option>
                        @foreach ($proveedores as $p)
                            <option value="{{ $p->id_proveedor }}">{{ $p->nombre }}</option>
                        @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Producto</label>
                        <input type="text" name="nombre_producto" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad</label>
                            <input type="number" name="existencia" step="1" min="1" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio</label>
                            <input type="number" name="precio" step="0.01" min="0" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                        <select name="categoria_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Seleccione una categoría</option>
                        @foreach ($categorias as $c)
                            <option value="{{ $c->id_categoria }}">{{ $c->nombre_categoria }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                
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

@section('scripts')
<script>
    let editingProductId = null;

    function openAddModal() {
        document.getElementById('modalTitle').textContent = 'Nuevo Producto';
        document.getElementById('productForm').reset();
        editingProductId = null;
        document.getElementById('productModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('productModal').classList.add('hidden');
    }

   function applyFilters() {
        const category = document.getElementById('categoryFilter').value;
        const status = document.getElementById('statusFilter').value;
        
        // Construir URL con filtros
        let url = new URL(window.location.href);
        if (category) url.searchParams.set('categoria', category);
        else url.searchParams.delete('categoria');
        
        if (status) url.searchParams.set('estado', status);
        else url.searchParams.delete('estado');
        
        // Resetear a la primera página cuando se aplican filtros
        url.searchParams.delete('page');
        
        window.location.href = url.toString();
    }
    
    // Búsqueda en tiempo real (solo filtra los datos actualmente visibles)
    document.getElementById('searchProduct').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#productsTable tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
@endsection
@endsection