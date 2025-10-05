@extends('layouts.dashboard')

@section('title', 'Solicitudes de Vacaciones')
@section('header', 'Solicitudes de Vacaciones')

@section('content')
@if($vacaciones->isEmpty())
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">No hay solicitudes de vacaciones registradas.</p>
            </div>
        </div>
    </div>
@endif
<div class="mb-6 flex justify-end items-center">
    @if(!auth()->user()->isAdmin())
    <a href="{{ route('vacaciones.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center transition duration-150">
        <i class="fas fa-plus mr-2"></i>
        Nueva Solicitud
    </a>
    @endif
</div>

    <!-- Filtros por estado -->
    <div class="mb-6 flex gap-4" x-data="{ estado: 'todas' }">
        <button @click="estado = 'todas'" :class="estado === 'todas' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700'" class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-blue-50 transition">
            <i class="fas fa-list mr-2"></i>Todas
        </button>
        <button @click="estado = 'pendiente'" :class="estado === 'pendiente' ? 'bg-yellow-600 text-white' : 'bg-white text-gray-700'" class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-yellow-50 transition">
            <i class="fas fa-clock mr-2"></i>Pendientes
        </button>
        <button @click="estado = 'aprobada'" :class="estado === 'aprobada' ? 'bg-green-600 text-white' : 'bg-white text-gray-700'" class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-green-50 transition">
            <i class="fas fa-check-circle mr-2"></i>Aprobadas
        </button>
        <button @click="estado = 'rechazada'" :class="estado === 'rechazada' ? 'bg-red-600 text-white' : 'bg-white text-gray-700'" class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-red-50 transition">
            <i class="fas fa-times-circle mr-2"></i>Rechazadas
        </button>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200" id="vacacionesTable">
            <thead class="bg-gray-50">
                <tr>
                    @if(auth()->user()->isAdmin())
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empleado</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Inicio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Fin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Días</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Solicitud</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($vacaciones as $vacacion)
                <tr class="hover:bg-gray-50 transition">
                    @if(auth()->user()->isAdmin())
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    @if($vacacion->empleado && $vacacion->empleado->user)
                                        {{ $vacacion->empleado->user->nombre }} {{ $vacacion->empleado->user->apellido }}
                                    @elseif($vacacion->empleado)
                                        Empleado CI: {{ $vacacion->empleado->cedula }}
                                    @else
                                        Empleado no encontrado
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">{{ $vacacion->empleado->cedula ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    @endif
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <i class="far fa-calendar text-blue-500 mr-1"></i>
                        {{ $vacacion->fecha_inicio ? $vacacion->fecha_inicio->format('d/m/Y') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <i class="far fa-calendar text-blue-500 mr-1"></i>
                        {{ $vacacion->fecha_fin ? $vacacion->fecha_fin->format('d/m/Y') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">
                            {{ $vacacion->dias_solicitados }} días
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($vacacion->estado === 'pendiente')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i> Pendiente
                            </span>
                        @elseif($vacacion->estado === 'aprobada')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Aprobada
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Rechazada
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $vacacion->created_at ? $vacacion->created_at->format('d/m/Y H:i') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex gap-2">
                            <a href="{{ route('vacaciones.show', $vacacion) }}" class="text-blue-600 hover:text-blue-900" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            @if($vacacion->estado === 'pendiente')
                                @if(auth()->user()->isAdmin())
                                    <button onclick="openApproveModal({{ $vacacion->id }})" class="text-green-600 hover:text-green-900" title="Aprobar">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button onclick="openRejectModal({{ $vacacion->id }})" class="text-red-600 hover:text-red-900" title="Rechazar">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @else
                                    <a href="{{ route('vacaciones.edit', $vacacion) }}" class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('vacaciones.destroy', $vacacion) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar esta solicitud?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de Aprobación -->
<div id="approveModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Aprobar Solicitud</h3>
                <button onclick="closeApproveModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comentario (opcional)</label>
                    <textarea name="comentario_admin" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-check mr-2"></i>Aprobar
                    </button>
                    <button type="button" onclick="closeApproveModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Rechazo -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Rechazar Solicitud</h3>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Motivo del rechazo *</label>
                    <textarea name="comentario_admin" rows="3" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500"></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-times mr-2"></i>Rechazar
                    </button>
                    <button type="button" onclick="closeRejectModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('datatable-scripts')
<script>
$(document).ready(function() {
    $('#vacacionesTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        responsive: true,
        order: [[{{ auth()->user()->isAdmin() ? '5' : '4' }}, 'desc']]
    });
});

const routes = {
    aprobar: @json(route('vacaciones.aprobar', ['vacacion' => '__ID__'])),
    rechazar: @json(route('vacaciones.rechazar', ['vacacion' => '__ID__']))
};

function openApproveModal(id) {
    document.getElementById('approveForm').action = routes.aprobar.replace('__ID__', id);
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
}

function openRejectModal(id) {
    document.getElementById('rejectForm').action = routes.rechazar.replace('__ID__', id);
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endpush
@endsection
