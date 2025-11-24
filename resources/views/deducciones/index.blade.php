@extends('layouts.dashboard')

@section('title', 'Deducciones')
@section('header', 'Administración de Deducciones')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
        <div class="py-4 px-6 bg-gray-50 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">
                Lista de Deducciones
            </h2>
            <a href="{{ route('deducciones.create', ['tipo' => 'deduccion']) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-1"></i> 
                Nueva Deducción
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Nombre</th>
                        <th class="py-3 px-6 text-left">Tipo</th>
                        <th class="py-3 px-6 text-left">Valor</th>
                        <th class="py-3 px-6 text-left">Estado</th>
                        <th class="py-3 px-6 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @forelse ($deducciones as $deduccion)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6">
                            <div class="font-medium text-gray-900">{{ $deduccion->nombre }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($deduccion->descripcion, 50) }}</div>
                        </td>
                        <td class="py-3 px-6">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $deduccion->es_fijo ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $deduccion->es_fijo ? 'Monto Fijo' : 'Porcentaje' }}
                            </span>
                        </td>
                        <td class="py-3 px-6">
                            @if ($deduccion->es_fijo)
                                <div class="text-sm text-gray-900">{{ number_format($deduccion->monto_fijo, 2) }}</div>
                            @else
                                <div class="text-sm text-gray-900">{{ number_format($deduccion->porcentaje, 2) }}%</div>
                            @endif
                        </td>
                        <td class="py-3 px-6">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $deduccion->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $deduccion->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('deducciones.edit', $deduccion) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('deducciones.destroy', $deduccion) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Está seguro de que desea eliminar esta deducción?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-3 px-6 text-center text-gray-500">
                            No hay deducciones registradas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4">
            {{ $deducciones->links() }}
        </div>
    </div>
</div>
@endsection
