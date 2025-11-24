@extends('layouts.dashboard')

@section('title', 'Beneficios por Cargo')
@section('header', 'Configuración de Beneficios por Cargo')

@section('content')
<div class="py-6" x-data="{ showBeneficiosModal: {{ ($errors->any() || session('error')) ? 'true' : 'false' }} }">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
            <div class="py-4 px-6 bg-gray-50 border-b">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <h2 class="text-xl font-semibold text-gray-800">
                        Beneficios asociados a cargos
                    </h2>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('beneficios-cargo.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Crear Beneficio por Cargo
                        </a>
                        <button @click="showBeneficiosModal = true" type="button" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <i class="fas fa-list mr-2"></i>
                            Gestionar Beneficios
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-200 text-gray-700 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">ID</th>
                                <th class="py-3 px-6 text-left">Beneficio</th>
                                <th class="py-3 px-6 text-left">Cargo</th>
                                <th class="py-3 px-6 text-left">Porcentaje</th>
                                <th class="py-3 px-6 text-left">Valor</th>
                                <th class="py-3 px-6 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm">
                            @forelse($beneficiosCargo as $item)
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-6 text-sm text-gray-900">{{ $item->id }}</td>
                                    <td class="py-3 px-6 text-sm text-gray-900">
                                        {{ optional($item->beneficio)->beneficio ?? '—' }}
                                    </td>
                                    <td class="py-3 px-6 text-sm text-gray-900">{{ $item->cargo }}</td>
                                    <td class="py-3 px-6 text-sm text-gray-900">
                                        {{ $item->porcentaje !== null ? number_format($item->porcentaje, 2) . ' %' : '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->valor !== null ? number_format($item->valor, 2) : '—' }}
                                    </td>
                                    <td class="py-3 px-6 text-sm text-gray-900">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('beneficios-cargo.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('beneficios-cargo.destroy', $item) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este beneficio por cargo?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-3 px-6 text-center text-gray-500">
                                        No hay beneficios asociados a cargos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de gestión de beneficios -->
    <div
        x-cloak
        x-show="showBeneficiosModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div @click.away="showBeneficiosModal = false" class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Beneficios registrados</h3>
                <button @click="showBeneficiosModal = false" class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
            </div>
            <div class="px-6 py-4 space-y-4 max-h-[60vh] overflow-y-auto">
                <div class="pb-4 border-b border-gray-200">
                    <h4 class="text-md font-semibold text-gray-800 mb-2">Registrar nuevo beneficio</h4>
                    <form method="POST" action="{{ route('beneficios.store') }}">
                        @csrf
                        <div class="space-y-3">
                            <div class="flex flex-col md:flex-row gap-3">
                                <input
                                    type="text"
                                    name="beneficio"
                                    placeholder="Nombre del beneficio"
                                    class="w-full md:flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                            </div>
                            <div class="flex flex-col md:flex-row gap-3">
                                <div class="w-full md:w-auto">
                                    <label for="fecha_beneficio" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Día del beneficio (solo día del mes)</label>
                                    <select
                                        name="fecha_beneficio"
                                        id="fecha_beneficio"
                                        class="w-full md:w-auto rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                        <option value="">-- Cualquier día del mes --</option>
                                        @for ($d = 1; $d <= 31; $d++)
                                            <option value="{{ $d }}">{{ $d }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="pt-2 flex justify-start">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-gray-600 border-b border-gray-200">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Beneficio</th>
                            <th class="px-4 py-2">Fecha</th>
                            <th class="px-4 py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($beneficios as $beneficio)
                            <tr>
                                <td class="px-4 py-2 text-gray-800">{{ $beneficio->id }}</td>
                                <td class="px-4 py-2 text-gray-800">{{ $beneficio->beneficio }}</td>
                                <td class="px-4 py-2 text-gray-800">
                                    {{ $beneficio->fecha_beneficio ? $beneficio->fecha_beneficio->format('d') : '—' }}
                                </td>
                                <td class="px-4 py-2 text-gray-800">
                                    <div class="flex space-x-2 text-xs">
                                        <a href="{{ route('beneficios.edit', $beneficio) }}" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('beneficios.destroy', $beneficio) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este beneficio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-center text-gray-500">
                                    No hay beneficios registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3 border-t border-gray-200 flex justify-end">
                <button @click="showBeneficiosModal = false" type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
