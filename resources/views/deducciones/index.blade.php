@extends('layouts.dashboard')

@section('title', $tipo === 'deduccion' ? 'Deducciones' : ($tipo === 'beneficio' ? 'Beneficios' : 'Parámetros'))
@section('header', $tipo === 'deduccion' ? 'Administración de Deducciones' : ($tipo === 'beneficio' ? 'Administración de Beneficios' : 'Administración de Parámetros'))

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="py-4 px-6 bg-gray-50 border-b">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">
                Lista de {{ $tipo === 'deduccion' ? 'Deducciones' : ($tipo === 'beneficio' ? 'Beneficios' : 'Parámetros') }}
            </h2>
            <a href="{{ route('deducciones.create', ['tipo' => $tipo]) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-1"></i> 
                {{ $tipo === 'deduccion' ? 'Nueva Deducción' : ($tipo === 'beneficio' ? 'Nuevo Beneficio' : 'Nuevo Parámetro') }}
            </a>
        </div>
        
        <!-- Pestañas para filtrar por tipo -->
        <div class="flex border-b mb-4">
            <a href="{{ route('deducciones.index', ['tipo' => 'deduccion']) }}" class="py-2 px-4 {{ $tipo === 'deduccion' ? 'border-b-2 border-blue-500 text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-800' }}">
                Deducciones
            </a>
            <a href="{{ route('deducciones.index', ['tipo' => 'beneficio']) }}" class="py-2 px-4 {{ $tipo === 'beneficio' ? 'border-b-2 border-blue-500 text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-800' }}">
                Beneficios
            </a>
            <a href="{{ route('deducciones.index', ['tipo' => 'parametro']) }}" class="py-2 px-4 {{ $tipo === 'parametro' ? 'border-b-2 border-blue-500 text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-800' }}">
                Parámetros de Nómina
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    @if($tipo === 'parametro')
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campo</th>
                    @endif
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($deducciones as $deduccion)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $deduccion->nombre }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($deduccion->descripcion, 50) }}</div>
                    </td>
                    @if($tipo === 'parametro')
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            {{ $deduccion->campo }}
                        </span>
                    </td>
                    @endif
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $deduccion->es_fijo ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $deduccion->es_fijo ? 'Monto Fijo' : 'Porcentaje' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($deduccion->es_fijo)
                            <div class="text-sm text-gray-900">{{ number_format($deduccion->monto_fijo, 2) }}</div>
                        @else
                            <div class="text-sm text-gray-900">{{ number_format($deduccion->porcentaje, 2) }}%</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $deduccion->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $deduccion->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
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
                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
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
@endsection
