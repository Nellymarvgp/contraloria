@extends('layouts.dashboard')

@section('title', $tipo === 'deduccion' ? 'Editar Deducción' : ($tipo === 'beneficio' ? 'Editar Beneficio' : 'Editar Parámetro'))
@section('header', $tipo === 'deduccion' ? 'Editar Deducción' : ($tipo === 'beneficio' ? 'Editar Beneficio' : 'Editar Parámetro'))

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="py-4 px-6 bg-gray-50 border-b">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ $tipo === 'deduccion' ? 'Editar Deducción' : ($tipo === 'beneficio' ? 'Editar Beneficio' : 'Editar Parámetro') }}: {{ $deduccion->nombre }}
        </h2>
    </div>
    
    <form action="{{ route('deducciones.update', $deduccion) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nombre -->
            <div class="col-span-2">
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $deduccion->nombre) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                @error('nombre')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Descripción -->
            <div class="col-span-2">
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('descripcion', $deduccion->descripcion) }}</textarea>
                @error('descripcion')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            @if($tipo !== 'parametro')
            <!-- Tipo de Valor (Radio Buttons) -->
            <div class="col-span-2">
                <div class="block text-sm font-medium text-gray-700 mb-2">Tipo de Valor <span class="text-red-500">*</span></div>
                <div class="flex space-x-4" x-data="{ valorTipo: '{{ old('es_fijo', $deduccion->es_fijo) ? 'fijo' : 'porcentaje' }}' }">
                    <label class="inline-flex items-center">
                        <input type="radio" name="es_fijo" value="0" class="form-radio text-indigo-600" x-model="valorTipo" x-on:change="valorTipo = 'porcentaje'" {{ old('es_fijo', $deduccion->es_fijo) == '0' || old('es_fijo', $deduccion->es_fijo) === 0 ? 'checked' : '' }}>
                        <span class="ml-2">Porcentaje</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="es_fijo" value="1" class="form-radio text-indigo-600" x-model="valorTipo" x-on:change="valorTipo = 'fijo'" {{ old('es_fijo', $deduccion->es_fijo) == '1' || old('es_fijo', $deduccion->es_fijo) === 1 ? 'checked' : '' }}>
                        <span class="ml-2">Monto Fijo</span>
                    </label>
                </div>
                @error('es_fijo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @else
            <!-- Campo para parámetros -->
            <div>
                <label for="campo" class="block text-sm font-medium text-gray-700 mb-1">Campo <span class="text-red-500">*</span></label>
                <input type="text" name="campo" id="campo" value="{{ old('campo', $deduccion->campo) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                <p class="text-xs text-gray-500 mt-1">Nombre del campo en el sistema (TXT, etc.)</p>
                @error('campo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <!-- Valor para parámetros (siempre es fijo) -->
            <div>
                <label for="monto_fijo" class="block text-sm font-medium text-gray-700 mb-1">Valor <span class="text-red-500">*</span></label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="number" name="monto_fijo" id="monto_fijo" value="{{ old('monto_fijo', $deduccion->monto_fijo) }}" step="0.01" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <input type="hidden" name="es_fijo" value="1">
                @error('monto_fijo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif
            
            @if($tipo !== 'parametro')
            <!-- Porcentaje (visible si tipo es porcentaje) -->
            <div x-data="{ valorTipo: '{{ old('es_fijo', $deduccion->es_fijo) ? 'fijo' : 'porcentaje' }}' }" x-show="valorTipo === 'porcentaje'">
                <label for="porcentaje" class="block text-sm font-medium text-gray-700 mb-1">Porcentaje (%)</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="number" name="porcentaje" id="porcentaje" value="{{ old('porcentaje', $deduccion->porcentaje) }}" step="0.01" min="0" {{ $tipo === 'deduccion' ? 'max="100"' : '' }} class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">%</span>
                    </div>
                </div>
                @error('porcentaje')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Monto Fijo (visible si tipo es fijo) -->
            <div x-data="{ valorTipo: '{{ old('es_fijo', $deduccion->es_fijo) ? 'fijo' : 'porcentaje' }}' }" x-show="valorTipo === 'fijo'">
                <label for="monto_fijo" class="block text-sm font-medium text-gray-700 mb-1">Monto Fijo</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <input type="number" name="monto_fijo" id="monto_fijo" value="{{ old('monto_fijo', $deduccion->monto_fijo) }}" step="0.01" min="0" class="pl-7 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                @error('monto_fijo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif
            
            <!-- Estado -->
            <div>
                <label for="activo" class="flex items-center">
                    <input type="checkbox" name="activo" id="activo" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ old('activo', $deduccion->activo) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Activo</span>
                </label>
                @error('activo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mt-6 flex items-center justify-end">
            <a href="{{ route('deducciones.index', ['tipo' => $tipo]) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Actualizar
            </button>
        </div>
    </form>
</div>
@endsection
