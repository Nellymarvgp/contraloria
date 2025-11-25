@extends('layouts.dashboard')

@section('title', 'Editar Solicitud de Vacaciones')
@section('header', 'Editar Solicitud de Vacaciones')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('vacaciones.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver a Solicitudes
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
            <h2 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-edit mr-3"></i>
                Editar Solicitud de Vacaciones
            </h2>
        </div>

        <form action="{{ route('vacaciones.update', $vacacion) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <!-- Información del Empleado -->
            <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-user-circle text-blue-600 mr-2"></i>
                    Información del Empleado
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nombre Completo</p>
                        <p class="font-semibold text-gray-900">
                            @if($empleado->user)
                                {{ $empleado->user->nombre }} {{ $empleado->user->apellido }}
                            @else
                                Empleado CI: {{ $empleado->cedula }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Cédula</p>
                        <p class="font-semibold text-gray-900">{{ $empleado->cedula }}</p>
                    </div>
                </div>
            </div>

            <!-- Selección de Fechas -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                    Modificar Fechas de Vacaciones
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Inicio *
                        </label>
                        <input type="date" 
                               name="fecha_inicio" 
                               id="fecha_inicio" 
                               required
                               min="{{ date('Y-m-d') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('fecha_inicio') border-red-500 @enderror"
                               value="{{ old('fecha_inicio', $vacacion->fecha_inicio ? $vacacion->fecha_inicio->format('Y-m-d') : '') }}">
                        @error('fecha_inicio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Fin *
                        </label>
                        <input type="date" 
                               name="fecha_fin" 
                               id="fecha_fin" 
                               required
                               min="{{ date('Y-m-d') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('fecha_fin') border-red-500 @enderror"
                               value="{{ old('fecha_fin', $vacacion->fecha_fin ? $vacacion->fecha_fin->format('Y-m-d') : '') }}">
                        @error('fecha_fin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Contador de días -->
                <div id="diasCounter" class="mt-4 p-4 bg-green-50 rounded-lg">
                    <p class="text-sm text-gray-600">Días solicitados:</p>
                    <p class="text-2xl font-bold text-green-600" id="diasCount">{{ $vacacion->dias_solicitados }}</p>
                </div>
            </div>

            <!-- Motivo -->
            <div class="mb-6">
                <label for="motivo" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-comment-dots text-blue-600 mr-1"></i>
                    Motivo (opcional)
                </label>
                <textarea name="motivo" 
                          id="motivo" 
                          rows="4" 
                          maxlength="500"
                          class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('motivo') border-red-500 @enderror"
                          placeholder="Describa el motivo de su solicitud de vacaciones...">{{ old('motivo', $vacacion->motivo) }}</textarea>
                @error('motivo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Máximo 500 caracteres</p>
            </div>

            <!-- Botones -->
            <div class="flex gap-4 pt-4 border-t">
                <button type="submit" class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Cambios
                </button>
                <a href="{{ route('vacaciones.index') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg transition duration-150 text-center">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');
    const diasCounter = document.getElementById('diasCounter');
    const diasCount = document.getElementById('diasCount');

    function calcularDias() {
        if (fechaInicio.value && fechaFin.value) {
            const inicio = new Date(fechaInicio.value);
            const fin = new Date(fechaFin.value);

            if (fin >= inicio) {
                let diasHabiles = 0;
                let cursor = new Date(inicio.getTime());

                while (cursor <= fin) {
                    const diaSemana = cursor.getDay(); // 0=Dom, 1=Lun, ..., 6=Sab
                    if (diaSemana >= 1 && diaSemana <= 5) { // Lunes a viernes
                        diasHabiles++;
                    }
                    cursor.setDate(cursor.getDate() + 1);
                }

                if (diasHabiles > 0) {
                    diasCount.textContent = diasHabiles;
                    diasCounter.classList.remove('hidden');
                } else {
                    diasCounter.classList.add('hidden');
                }
            } else {
                diasCounter.classList.add('hidden');
            }
        }
    }

    fechaInicio.addEventListener('change', function() {
        fechaFin.min = this.value;
        calcularDias();
    });

    fechaFin.addEventListener('change', calcularDias);
});
</script>
@endsection
