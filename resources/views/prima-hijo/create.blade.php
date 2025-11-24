@extends('layouts.dashboard')

@section('title', 'Crear Prima por Hijo')

@section('header', 'Crear Prima por Hijo')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="py-4 px-6 bg-gray-100 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Nueva Prima por Hijo</h2>
    </div>

    <div class="py-4 px-6">
        <form action="{{ route('prima-hijo.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="hijos" class="block text-gray-700 font-medium mb-2">Número de hijos</label>
                <input type="number" name="hijos" id="hijos" min="1" value="{{ old('hijos') }}"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('hijos') border-red-500 @enderror">
                @error('hijos')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="porcentaje" class="block text-gray-700 font-medium mb-2">Porcentaje (%)</label>
                <input type="number" step="0.01" name="porcentaje" id="porcentaje" min="0" max="100" value="{{ old('porcentaje') }}"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('porcentaje') border-red-500 @enderror">
                @error('porcentaje')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="estado" id="estado" checked
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-gray-700">Activo</span>
                </label>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('prima-hijo.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded mr-2">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
