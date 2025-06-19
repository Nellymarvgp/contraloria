@extends('layouts.dashboard')

@section('title', 'Prima de Profesionalización')

@section('header', 'Prima de Profesionalización')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="py-4 px-6 bg-gray-100 border-b flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Listado de Primas por Profesionalización</h2>
        <a href="{{ route('prima-profesionalizacion.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
            <i class="fas fa-plus mr-2"></i>Nueva Prima
        </a>
    </div>

    <div class="overflow-x-auto">
        <table id="datatable" class="min-w-full display nowrap" style="width:100%">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase">ID</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase">Descripción</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase">Porcentaje</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase">Estado</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($primas as $prima)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-6 text-sm">{{ $prima->id }}</td>
                    <td class="py-2 px-6 text-sm">{{ $prima->descripcion }}</td>
                    <td class="py-2 px-6 text-sm">{{ $prima->porcentaje }}%</td>
                    <td class="py-2 px-6 text-sm">
                        <span class="px-2 py-1 text-xs rounded {{ $prima->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $prima->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="py-2 px-6 text-sm flex">
                        <a href="{{ route('prima-profesionalizacion.edit', $prima->id) }}" class="text-blue-500 hover:text-blue-700 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('prima-profesionalizacion.destroy', $prima->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta prima?');">
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
                    <td colspan="5" class="py-6 px-6 text-center text-gray-500">No hay primas registradas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('datatable-scripts')
<script>
$(document).ready(function() {
    $('#datatable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        dom: 'Bfrtip',
        buttons: [
            'excel', 'pdf', 'print'
        ]
    });
});
</script>
@endpush
