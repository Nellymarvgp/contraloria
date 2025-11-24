@extends('layouts.dashboard')

@section('title', 'Editar Beneficio')
@section('header', 'Editar Beneficio')

@section('content')
<div class="py-6">
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Editar Beneficio</h2>

                <form method="POST" action="{{ route('beneficios.update', $beneficio) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="beneficio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre del Beneficio</label>
                        <input type="text" name="beneficio" id="beneficio" value="{{ old('beneficio', $beneficio->beneficio) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('beneficio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="fecha_beneficio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Día del beneficio (se usa solo el día del mes)</label>
                        @php
                            $diaActual = old('fecha_beneficio');
                            if ($diaActual === null && $beneficio->fecha_beneficio) {
                                $diaActual = $beneficio->fecha_beneficio->day;
                            }
                        @endphp
                        <select
                            name="fecha_beneficio"
                            id="fecha_beneficio"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">-- Cualquier día del mes --</option>
                            @for ($d = 1; $d <= 31; $d++)
                                <option value="{{ $d }}" {{ (string)$diaActual === (string)$d ? 'selected' : '' }}>{{ $d }}</option>
                            @endfor
                        </select>
                        @error('fecha_beneficio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <a href="{{ route('beneficios-cargo.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
