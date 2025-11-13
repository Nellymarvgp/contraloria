@extends('layouts.dashboard')

@section('title', 'Remuneraciones')

@section('header', 'Remuneraciones')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="py-4 px-6 bg-gray-100 border-b flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Listado de Remuneraciones</h2>
        <a href="{{ route('remuneraciones.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
            <i class="fas fa-plus mr-2"></i>Nueva Remuneración
        </a>
    </div>

    <div class="overflow-x-auto">
        <table id="datatable" class="min-w-full display nowrap" style="width:100%">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase">ID</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase">Nivel de Rango</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase">Grupo o Clase</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase">Tipo de Cargo</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase">Tipo de Personal</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase">Valor</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase">Estado</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($remuneraciones as $remuneracion)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-6 text-sm">{{ $remuneracion->id }}</td>
                    <td class="py-2 px-6 text-sm">{{ $remuneracion->nivelRango?->descripcion ?? '-' }}</td>
                    <td class="py-2 px-6 text-sm">{{ $remuneracion->grupoCargo?->descripcion ?? '-' }}</td>
                    <td class="py-2 px-6 text-sm">
                        @if($remuneracion->tipo_cargo == 'administrativo')
                            Administrativo
                        @elseif($remuneracion->tipo_cargo == 'tecnico_superior')
                            Técnico Superior Universitario
                        @elseif($remuneracion->tipo_cargo == 'profesional_universitario')
                            Profesional Universitario
                        @endif
                    </td>
                    <td class="py-2 px-6 text-sm">
                        @if($remuneracion->tipo_personal == 'obreros')
                            Obreros
                        @elseif($remuneracion->tipo_personal == 'administracion_publica')
                            Administración Pública
                        @endif
                    </td>
                    <td class="py-2 px-6 text-sm">{{ number_format($remuneracion->valor, 2) }}</td>
                    <td class="py-2 px-6 text-sm">
                        <span class="px-2 py-1 text-xs rounded {{ $remuneracion->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $remuneracion->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="py-2 px-6 text-sm flex">
                        <a href="{{ route('remuneraciones.edit', $remuneracion->id) }}" class="text-blue-500 hover:text-blue-700 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('remuneraciones.destroy', $remuneracion->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta remuneración?');">
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
                    <td colspan="8" class="py-6 px-6 text-center text-gray-500">No hay remuneraciones registradas</td>
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
