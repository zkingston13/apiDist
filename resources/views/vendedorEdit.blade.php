{{-- resources/views/vendedores/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Agregar Vendedor - Distribuidora')

@section('page-title', 'Editar Empleado')

@section('page-description', 'Ingresa los datos nuevos del empleado')

@section('contenido')
<div class="max-w-3xl mx-auto">
    <form action="{{ route('vendedores.update', $usuario->id_empleado ) }}" method="POST" class="space-y-6">
        @csrf
    @method('PUT')
        
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
                        <label for="nombres" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombres <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nombres" 
                               id="nombres" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombres') border-red-500 @enderror"
                               placeholder="Ej: Juan Carlos"
                               value="{{ $usuario->nombre }}"
                               required>
                        @error('nombres')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-2">
                            Apellido Materno <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="apellidos" 
                               id="apellidos" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('apellidos') border-red-500 @enderror"
                               placeholder="Ej: Pérez González"
                               value="{{ $usuario->apellidoP }}"
                               required>
                        @error('apellidos')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-2">
                            Apellido Paterno <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="apellidos" 
                               id="apellidos" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('apellidos') border-red-500 @enderror"
                               placeholder="Ej: Pérez González"
                               value="{{ $usuario->apellidoM }}"
                               required>
                        @error('apellidos')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                {{-- Email y Teléfono --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Correo Electrónico <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                               placeholder="ejemplo@correo.com"
                               value="{{ $usuario->correo }}"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                            Teléfono
                        </label>
                        <input type="tel" 
                               name="telefono" 
                               id="telefono" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('telefono') border-red-500 @enderror"
                               placeholder="Ej: 1234-5678"
                               value="{{ $usuario->telefono }}">
                        @error('telefono')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                {{-- Turno --}}
                <div>
                    <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                       Turno
                    </label>
                    <select name="turno" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-60 p-2.5">
                            <option value="{{ $usuario->turno }}">
                                Matutino
                            </option>
                            <option value="{{ $usuario->turno }}">
                                Vespertio
                            </option>
                        </select>
                    @error('direccion')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                       Rol
                    </label>
                    <select name="turno" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-60 p-2.5">
                            <option value="{{ $usuario->rol }}">
                                Vendedor
                            </option>
                            <option value="{{ $usuario->rol }}">
                                Administrador
                            </option>
                        </select>
                    @error('direccion')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
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
                                   value="{{ $usuario->sueldo_base }}"
                                   required>
                        </div>
                        @error('sueldo_base')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
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
                            <input type="hidden" name="activo" value="0">
                            <input type="checkbox" 
                                   name="activo" 
                                   id="activo" 
                                   class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition duration-150 ease-in-out"
                                   value="1"
                                    {{ $usuario->activo ? 'checked' : '' }}>
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
        <div class="flex justify-end space-x-4">
            <a href="/vendedores" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Guardar Vendedor
            </button>
        </div>
    </form>
</div>

@endsection