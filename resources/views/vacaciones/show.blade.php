@extends('layouts.dashboard')

@section('title', 'Detalle de Solicitud')
@section('header', 'Detalle de Solicitud de Vacaciones')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('vacaciones.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver a Solicitudes
        </a>
    </div>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Header con estado -->
        <div class="px-6 py-4 {{ $vacacion->estado === 'aprobada' ? 'bg-gradient-to-r from-green-500 to-green-600' : ($vacacion->estado === 'rechazada' ? 'bg-gradient-to-r from-red-500 to-red-600' : 'bg-gradient-to-r from-yellow-500 to-yellow-600') }}">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-file-alt mr-3"></i>
                    Detalle de Solicitud de Vacaciones
                </h2>
                @if($vacacion->estado === 'pendiente')
                    <span class="px-4 py-2 bg-white text-yellow-600 rounded-full font-semibold">
                        <i class="fas fa-clock mr-1"></i> Pendiente
                    </span>
                @elseif($vacacion->estado === 'aprobada')
                    <span class="px-4 py-2 bg-white text-green-600 rounded-full font-semibold">
                        <i class="fas fa-check-circle mr-1"></i> Aprobada
                    </span>
                @else
                    <span class="px-4 py-2 bg-white text-red-600 rounded-full font-semibold">
                        <i class="fas fa-times-circle mr-1"></i> Rechazada
                    </span>
                @endif
            </div>
        </div>

        <div class="p-6">
            <!-- Información del Empleado -->
            @if($vacacion->empleado)
            <div class="mb-6 bg-blue-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user-circle text-blue-600 mr-2"></i>
                    Información del Empleado
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nombre Completo</p>
                        <p class="font-semibold text-gray-900">
                            @if($vacacion->empleado->user)
                                {{ $vacacion->empleado->user->nombre }} {{ $vacacion->empleado->user->apellido }}
                            @else
                                Empleado CI: {{ $vacacion->empleado->cedula }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Cédula</p>
                        <p class="font-semibold text-gray-900">{{ $vacacion->empleado->cedula }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Cargo</p>
                        <p class="font-semibold text-gray-900">{{ $vacacion->empleado->cargo->nombre ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Departamento</p>
                        <p class="font-semibold text-gray-900">{{ $vacacion->empleado->departamento->nombre ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            @else
            <div class="mb-6 bg-red-50 p-6 rounded-lg border border-red-200">
                <p class="text-red-600"><i class="fas fa-exclamation-triangle mr-2"></i>El empleado asociado a esta solicitud no existe.</p>
            </div>
            @endif

            <!-- Detalles de la Solicitud -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                    Detalles de las Vacaciones
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Fecha de Inicio</p>
                        <p class="text-xl font-bold text-gray-900">{{ $vacacion->fecha_inicio ? $vacacion->fecha_inicio->format('d/m/Y') : 'N/A' }}</p>
                        @if($vacacion->fecha_inicio)
                        <p class="text-xs text-gray-500 mt-1">{{ $vacacion->fecha_inicio->locale('es')->isoFormat('dddd') }}</p>
                        @endif
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Fecha de Fin</p>
                        <p class="text-xl font-bold text-gray-900">{{ $vacacion->fecha_fin ? $vacacion->fecha_fin->format('d/m/Y') : 'N/A' }}</p>
                        @if($vacacion->fecha_fin)
                        <p class="text-xs text-gray-500 mt-1">{{ $vacacion->fecha_fin->locale('es')->isoFormat('dddd') }}</p>
                        @endif
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Total de Días</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $vacacion->dias_solicitados }}</p>
                        <p class="text-xs text-gray-500 mt-1">días solicitados</p>
                    </div>
                </div>
            </div>

            <!-- Motivo -->
            @if($vacacion->motivo)
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-comment-dots text-blue-600 mr-2"></i>
                    Motivo de la Solicitud
                </h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-700">{{ $vacacion->motivo }}</p>
                </div>
            </div>
            @endif

            <!-- Información de Aprobación/Rechazo -->
            @if($vacacion->estado !== 'pendiente')
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-info-circle {{ $vacacion->estado === 'aprobada' ? 'text-green-600' : 'text-red-600' }} mr-2"></i>
                    Información de {{ $vacacion->estado === 'aprobada' ? 'Aprobación' : 'Rechazo' }}
                </h3>
                <div class="bg-{{ $vacacion->estado === 'aprobada' ? 'green' : 'red' }}-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        <div>
                            <p class="text-sm text-gray-600">{{ $vacacion->estado === 'aprobada' ? 'Aprobado' : 'Rechazado' }} por</p>
                            <p class="font-semibold text-gray-900">{{ $vacacion->aprobador->nombre ?? 'N/A' }} {{ $vacacion->aprobador->apellido ?? '' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Fecha de {{ $vacacion->estado === 'aprobada' ? 'Aprobación' : 'Rechazo' }}</p>
                            <p class="font-semibold text-gray-900">{{ $vacacion->fecha_aprobacion ? $vacacion->fecha_aprobacion->format('d/m/Y H:i') : 'N/A' }}</p>
                        </div>
                    </div>
                    @if($vacacion->comentario_admin)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Comentario del Administrador</p>
                        <p class="text-gray-700">{{ $vacacion->comentario_admin }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Información de la Solicitud -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-clock text-blue-600 mr-2"></i>
                    Información de la Solicitud
                </h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Fecha de Solicitud</p>
                            <p class="font-semibold text-gray-900">{{ $vacacion->created_at ? $vacacion->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Última Actualización</p>
                            <p class="font-semibold text-gray-900">{{ $vacacion->updated_at ? $vacacion->updated_at->format('d/m/Y H:i') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            @if($vacacion->estado === 'pendiente')
            <div class="flex gap-4 pt-4 border-t">
                @if(auth()->user()->isAdmin())
                    <button onclick="openApproveModal()" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 flex items-center justify-center">
                        <i class="fas fa-check mr-2"></i>
                        Aprobar Solicitud
                    </button>
                    <button onclick="openRejectModal()" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i>
                        Rechazar Solicitud
                    </button>
                @else
                    <a href="{{ route('vacaciones.edit', $vacacion) }}" class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 text-center">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Solicitud
                    </a>
                    <form action="{{ route('vacaciones.destroy', $vacacion) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Está seguro de eliminar esta solicitud?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150">
                            <i class="fas fa-trash mr-2"></i>
                            Eliminar Solicitud
{{ ... }}
                    </form>
                @endif
            </div>
            @endif
        </div>
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
            <form action="{{ route('vacaciones.aprobar', ['vacacion' => $vacacion->id]) }}" method="POST">
                @csrf
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
            <form action="{{ route('vacaciones.rechazar', ['vacacion' => $vacacion->id]) }}" method="POST">
                @csrf
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

<script>
function openApproveModal() {
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
}

function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection
