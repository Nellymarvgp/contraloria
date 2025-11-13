@extends('layouts.dashboard')

@section('title', 'Importar Empleados')

@section('header', 'Importar Empleados')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="py-4 px-6 bg-gray-100 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Importar Empleados</h2>
    </div>

    <div class="py-4 px-6">
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-700 mb-2">Instrucciones</h3>
            <ul class="list-disc pl-5 text-gray-600">
                <li class="mb-1">El archivo debe ser CSV, XLS o XLSX.</li>
                <li class="mb-1">La primera fila debe contener los encabezados.</li>
                <li class="mb-1">Las columnas requeridas son: nombre, apellido, cedula, email, nivel_rango, grupo_cargo, tipo_cargo.</li>
                <li class="mb-1">La columna estado es opcional (Activo/Inactivo).</li>
            </ul>
        </div>

        <form action="{{ route('empleados.import') }}" method="POST" enctype="multipart/form-data" class="border-t pt-6">
            @csrf
            <div class="mb-4">
                <label for="file" class="block text-gray-700 font-medium mb-2">Archivo</label>
                <input type="file" name="file" id="file" 
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('file') border-red-500 @enderror">
                @error('file')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end">
                <a href="{{ route('empleados.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded mr-2">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                    Importar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
