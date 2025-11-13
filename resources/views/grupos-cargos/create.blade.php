@extends('layouts.dashboard')

@section('title', 'Crear Grupo o Clase de Cargo')

@section('header', 'Crear Grupo o Clase de Cargo')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="py-4 px-6 bg-gray-100 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Nuevo Grupo o Clase de Cargo</h2>
    </div>

    <div class="py-4 px-6">
        <form action="{{ route('grupos-cargos.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="descripcion" class="block text-gray-700 font-medium mb-2">Descripción</label>
                <input type="text" name="descripcion" id="descripcion" value="{{ old('descripcion') }}" 
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('descripcion') border-red-500 @enderror">
                @error('descripcion')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="categoria" class="block text-gray-700 font-medium mb-2">Categoría</label>
                <select name="categoria" id="categoria" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('categoria') border-red-500 @enderror">
                    <option value="">Seleccione una categoría</option>
                    @foreach($categorias as $key => $label)
                        <option value="{{ $key }}" {{ old('categoria') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('categoria')
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
                <a href="{{ route('grupos-cargos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded mr-2">
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
