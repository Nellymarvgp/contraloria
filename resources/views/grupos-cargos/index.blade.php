@extends('layouts.dashboard')

@section('title', 'Grupos o Clases de Cargo')

@section('header', 'Grupos o Clases de Cargo')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="py-4 px-6 bg-gray-100 border-b flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Listado de Grupos o Clases de Cargo</h2>
        <a href="{{ route('grupos-cargos.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
            <i class="fas fa-plus mr-2"></i>Nuevo Grupo
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase">ID</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase">Descripción</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase">Categoría</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase">Estado</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($grupos as $grupo)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-6 text-sm">{{ $grupo->id }}</td>
                    <td class="py-2 px-6 text-sm">{{ $grupo->descripcion }}</td>
                    <td class="py-2 px-6 text-sm">
                        @php
                            $catLabels = [
                                'administrativo_bachiller' => 'Personal administrativo o bachilleres',
                                'tecnico_superior' => 'Personal técnico superior universitario',
                                'profesional_universitario' => 'Personal profesional universitario',
                            ];
                        @endphp
                        {{ $catLabels[$grupo->categoria] ?? '-' }}
                    </td>
                    <td class="py-2 px-6 text-sm">
                        <span class="px-2 py-1 text-xs rounded {{ $grupo->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $grupo->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="py-2 px-6 text-sm flex">
                        <a href="{{ route('grupos-cargos.edit', $grupo->id) }}" class="text-blue-500 hover:text-blue-700 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('grupos-cargos.destroy', $grupo->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este grupo?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-6 px-6 text-center text-gray-500">No hay grupos registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
