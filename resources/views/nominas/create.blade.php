@extends('layouts.dashboard')

@section('title', 'Nueva Nómina')
@section('header', 'Nueva Nómina')

@section('content')
<div class="p-4">
    <div class="flex justify-end mb-6">
        <a href="{{ route('nominas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden max-w-3xl mx-auto">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h2 class="text-lg font-medium text-gray-900">Crear Nueva Nómina</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('nominas.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('descripcion') border-red-300 @enderror" 
                        id="descripcion" name="descripcion" value="{{ old('descripcion') }}" 
                        placeholder="Ej: Nómina Quincena 1 - Abril 2025" required>
                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="despacho" class="block text-sm font-medium text-gray-700 mb-1">Despacho</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('despacho') border-red-300 @enderror" 
                        id="despacho" name="despacho" value="{{ old('despacho') }}" 
                        placeholder="Ej: Contraloría General">
                    @error('despacho')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                        <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('fecha_inicio') border-red-300 @enderror" 
                            id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
                        @error('fecha_inicio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                        <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('fecha_fin') border-red-300 @enderror" 
                            id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}" required>
                        @error('fecha_fin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Después de crear la nómina, podrá generar los cálculos automáticamente para todos los empleados activos.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center">
                        <i class="fas fa-save mr-2"></i> Crear Nómina
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
