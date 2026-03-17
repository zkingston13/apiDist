@extends('layouts.app')

@section('title', 'Gestión de Artículos')
@section('page-title', 'Editar artículo')
@section('page-description', 'Edita el producto seleccionado')

@section('contenido')

           <form action="{{ route('productos.update',$producto->id_producto) }}" method="POST">
            @csrf
            @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Producto</label>
                        <input type="text" name="nombre_producto" required
                        value="{{ $producto->nombre_producto }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Existencia</label>
                            <input type="number" name="existencia" step="1" min="1"
                            value="{{ $producto->existencia }}"
                            required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio</label>
                            <input type="number" name="precio" step="0.01" min="0"
                            value="{{ $producto->precio }}"
                            required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                       <select name="categoria_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                            @foreach ($categorias as $c)
                            <option value="{{ $c->id_categoria }}"
                            {{ $producto->categoria_id == $c->id_categoria ? 'selected' : '' }}>
                            {{ $c->nombre_categoria }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                </div>
                        <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Activo</label>
                        <input type="checkbox" 
                                name="activo"
                                value="1"
                                {{ $producto->activo ? 'checked' : '' }}
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block">
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

@endsection