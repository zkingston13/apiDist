@extends('layouts.app')

@section('title', 'Registrar Venta')
@section('page-title', 'Registrar Venta')
@section('page-description', 'Registra las ventas realizadas por los vendedores')

@section('contenido')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Columna izquierda: Productos (ocupa 8 columnas de 12) -->
    
    
    <!-- Columna derecha: Carrito (ocupa 4 columnas de 12) -->
    <div class="lg:col-span-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-6">
            <!-- Header del carrito -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Carrito de Ventas</h3>
                    <span id="contadorItems" class="bg-blue-100 text-blue-600 text-sm font-medium px-3 py-1 rounded-full">0</span>
                </div>
            </div>
            
            <!-- Lista de productos - MÁS ALTA -->
            <div class="p-4 min-h-[400px] max-h-[500px] overflow-y-auto" id="carrito-items">
                <div class="text-center text-gray-400 py-12">
                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <p class="text-base">Carrito vacío</p>
                    <p class="text-sm mt-1">Agrega productos desde la galería</p>
                </div>
            </div>
            
            <!-- Resumen - MÁS ESPACIADO -->
            <div class="border-t border-gray-200 p-6">
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-base">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium text-gray-800" id="subtotal">$0.00</span>
                    </div>
                    <div class="flex justify-between text-base">
                        <span class="text-gray-600">IVA (16%):</span>
                        <span class="font-medium text-gray-800" id="iva">$0.00</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-3 border-t border-gray-200">
                        <span>Total:</span>
                        <span class="text-blue-600" id="total">$0.00</span>
                    </div>
                </div>
                
                <!-- Botones - MÁS GRANDES -->
                <div class="space-y-3">
                    <button onclick="registrarVenta()" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors text-base">
                        Registrar Venta
                    </button>
                    <button onclick="limpiarCarrito()" 
                            class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-lg transition-colors text-base">
                        Limpiar Carrito
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('scripts')
<script>
let carrito = [];

function aumentarCantidad(btn) {
    const card = btn.closest('.producto-card');
    const input = card.querySelector('.producto-cantidad');
    const max = parseInt(input.max);
    let valor = parseInt(input.value) || 0;
    if (valor < max) input.value = valor + 1;
}

function disminuirCantidad(btn) {
    const card = btn.closest('.producto-card');
    const input = card.querySelector('.producto-cantidad');
    let valor = parseInt(input.value) || 0;
    if (valor > 0) input.value = valor - 1;
}

function agregarAlCarrito(btn) {
    const card = btn.closest('.producto-card');
    const cantidad = parseInt(card.querySelector('.producto-cantidad').value);
    
    if (cantidad <= 0) {
        alert('Selecciona una cantidad mayor a 0');
        return;
    }
    
    const producto = {
        id: card.dataset.id,
        nombre: card.dataset.nombre,
        precio: parseFloat(card.dataset.precio),
        cantidad: cantidad,
        subtotal: parseFloat(card.dataset.precio) * cantidad
    };
    
    const existeIndex = carrito.findIndex(item => item.id === producto.id);
    
    if (existeIndex >= 0) {
        carrito[existeIndex].cantidad += cantidad;
        carrito[existeIndex].subtotal = carrito[existeIndex].precio * carrito[existeIndex].cantidad;
    } else {
        carrito.push(producto);
    }
    
    card.querySelector('.producto-cantidad').value = 0;
    actualizarCarrito();
}

function actualizarCarrito() {
    const contenedor = document.getElementById('carrito-items');
    const contador = document.getElementById('contadorItems');
    
    if (carrito.length === 0) {
        contenedor.innerHTML = `
            <div class="text-center text-gray-400 py-12">
                <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <p class="text-base">Carrito vacío</p>
                <p class="text-sm mt-1">Agrega productos desde la galería</p>
            </div>
        `;
        contador.textContent = '0';
        document.getElementById('subtotal').textContent = '$0.00';
        document.getElementById('iva').textContent = '$0.00';
        document.getElementById('total').textContent = '$0.00';
        return;
    }
    
    let html = '';
    let subtotal = 0;
    let totalItems = 0;
    
    carrito.forEach((item, index) => {
        subtotal += item.subtotal;
        totalItems += item.cantidad;
        html += `
            <div class="flex justify-between items-start bg-gray-50 p-4 rounded-lg mb-3">
                <div class="flex-1">
                    <p class="font-medium text-gray-800">${item.nombre}</p>
                    <p class="text-sm text-gray-500 mt-1">${item.cantidad} x $${item.precio.toFixed(2)}</p>
                </div>
                <div class="text-right ml-3">
                    <p class="font-semibold text-gray-800">$${item.subtotal.toFixed(2)}</p>
                    <button onclick="eliminarDelCarrito(${index})" class="text-xs text-red-600 hover:text-red-800 mt-1">
                        Eliminar
                    </button>
                </div>
            </div>
        `;
    });
    
    const iva = subtotal * 0.16;
    const total = subtotal + iva;
    
    contenedor.innerHTML = html;
    contador.textContent = totalItems;
    document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('iva').textContent = `$${iva.toFixed(2)}`;
    document.getElementById('total').textContent = `$${total.toFixed(2)}`;
}

function eliminarDelCarrito(index) {
    carrito.splice(index, 1);
    actualizarCarrito();
}

function limpiarCarrito() {
    if (carrito.length > 0 && confirm('¿Limpiar carrito?')) {
        carrito = [];
        actualizarCarrito();
    }
}

function registrarVenta() {
    const vendedorId = document.getElementById('vendedor_id').value;
    
    if (!vendedorId) {
        alert('Selecciona un vendedor');
        document.getElementById('vendedor_id').focus();
        return;
    }
    
    if (carrito.length === 0) {
        alert('Agrega productos al carrito');
        return;
    }
    
    alert('Venta registrada exitosamente');
    carrito = [];
    actualizarCarrito();
    document.getElementById('vendedor_id').value = '';
}

// Filtro por categoría
document.getElementById('filtroCategoria')?.addEventListener('change', function(e) {
    const categoria = e.target.value;
    const productos = document.querySelectorAll('.producto-card');
    
    productos.forEach(producto => {
        if (!categoria || producto.dataset.categoria === categoria) {
            producto.style.display = 'block';
        } else {
            producto.style.display = 'none';
        }
    });
});

// Validar inputs
document.querySelectorAll('.producto-cantidad').forEach(input => {
    input.addEventListener('change', function() {
        let valor = parseInt(this.value) || 0;
        const max = parseInt(this.max);
        if (valor < 0) this.value = 0;
        if (valor > max) this.value = max;
    });
});
</script>

<style>
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0; 
}
input[type=number] {
    -moz-appearance: textfield;
}
button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection