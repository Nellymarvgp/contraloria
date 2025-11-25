@extends('layouts.dashboard')

@section('title', 'Crear Beneficio por Cargo')
@section('header', 'Crear Beneficio por Cargo')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Nuevo Beneficio por Cargo</h2>

                <form method="POST" action="{{ route('beneficios-cargo.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="beneficio_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Beneficio</label>
                        <select name="beneficio_id" id="beneficio_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Seleccione un beneficio</option>
                            @foreach($beneficios as $beneficio)
                                <option value="{{ $beneficio->id }}" {{ old('beneficio_id') == $beneficio->id ? 'selected' : '' }}>
                                    {{ $beneficio->beneficio }}
                                </option>
                            @endforeach
                        </select>
                        @error('beneficio_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="cargo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de cargo</label>
                        <select name="cargo" id="cargo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Seleccione un tipo de cargo</option>
                            <option value="Todos" {{ old('cargo') == 'Todos' ? 'selected' : '' }}>Todos</option>
                            <option value="Alto funcionario" {{ old('cargo') == 'Alto funcionario' ? 'selected' : '' }}>Alto funcionario</option>
                            <option value="Alto Nivel" {{ old('cargo') == 'Alto Nivel' ? 'selected' : '' }}>Alto Nivel</option>
                            <option value="Empleado" {{ old('cargo') == 'Empleado' ? 'selected' : '' }}>Empleado</option>
                            <option value="Obrero" {{ old('cargo') == 'Obrero' ? 'selected' : '' }}>Obrero</option>
                        </select>
                        @error('cargo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{ valorTipo: '{{ old('valor') !== null && old('valor') !== '' ? 'valor' : 'porcentaje' }}' }" class="mb-4">
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de valor</span>
                            <div class="flex space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" value="porcentaje" x-model="valorTipo" class="form-radio text-blue-600">
                                    <span class="ml-2">Porcentaje</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" value="valor" x-model="valorTipo" class="form-radio text-blue-600">
                                    <span class="ml-2">Valor fijo</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div x-show="valorTipo === 'porcentaje'" x-cloak>
                                <label for="porcentaje" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Porcentaje (%)</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    name="porcentaje"
                                    id="porcentaje"
                                    x-bind:disabled="valorTipo !== 'porcentaje'"
                                    value="{{ old('porcentaje') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                @error('porcentaje')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-show="valorTipo === 'valor'" x-cloak>
                                <label for="valor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor fijo</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    name="valor"
                                    id="valor"
                                    x-bind:disabled="valorTipo !== 'valor'"
                                    value="{{ old('valor') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                @error('valor')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <a href="{{ route('beneficios-cargo.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
