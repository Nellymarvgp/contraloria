@extends('layouts.dashboard')

@section('title', 'Editar Remuneración')

@section('header', 'Editar Remuneración')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="py-4 px-6 bg-gray-100 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Editar Remuneración</h2>
    </div>

    <div class="py-4 px-6">
        <form action="{{ route('remuneraciones.update', $remuneracion->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="nivel_rango_id" class="block text-gray-700 font-medium mb-2">Nivel de Rango</label>
                <select name="nivel_rango_id" id="nivel_rango_id" 
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('nivel_rango_id') border-red-500 @enderror">
                    <option value="">Seleccione un nivel</option>
                    @foreach($nivelesRangos as $nivel)
                        <option value="{{ $nivel->id }}" {{ (old('nivel_rango_id', $remuneracion->nivel_rango_id) == $nivel->id) ? 'selected' : '' }}>
                            {{ $nivel->descripcion }}
                        </option>
                    @endforeach
                </select>
                @error('nivel_rango_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="grupo_cargo_id" class="block text-gray-700 font-medium mb-2">Grupo o Clase de Cargo</label>
                <select name="grupo_cargo_id" id="grupo_cargo_id" 
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('grupo_cargo_id') border-red-500 @enderror">
                    <option value="">Seleccione un grupo</option>
                    @foreach($gruposCargos as $grupo)
                        <option value="{{ $grupo->id }}" {{ (old('grupo_cargo_id', $remuneracion->grupo_cargo_id) == $grupo->id) ? 'selected' : '' }}>
                            {{ $grupo->descripcion }}
                        </option>
                    @endforeach
                </select>
                @error('grupo_cargo_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="tipo_cargo" class="block text-gray-700 font-medium mb-2">Tipo de Cargo</label>
                <select name="tipo_cargo" id="tipo_cargo" 
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('tipo_cargo') border-red-500 @enderror">
                    <option value="">Seleccione un tipo</option>
                    @foreach($tiposCargo as $value => $label)
                        <option value="{{ $value }}" {{ (old('tipo_cargo', $remuneracion->tipo_cargo) == $value) ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('tipo_cargo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="valor" class="block text-gray-700 font-medium mb-2">Valor</label>
                <input type="number" step="0.01" name="valor" id="valor" value="{{ old('valor', $remuneracion->valor) }}" 
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('valor') border-red-500 @enderror">
                @error('valor')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="estado" id="estado" {{ $remuneracion->estado ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-gray-700">Activo</span>
                </label>
            </div>
            
            <div class="flex justify-end">
                <a href="{{ route('remuneraciones.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded mr-2">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
