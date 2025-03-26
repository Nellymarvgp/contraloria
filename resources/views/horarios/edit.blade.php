@extends('layouts.dashboard')

@section('title', 'Editar Horario')
@section('header', 'Editar Horario')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="{{ route('horarios.update', $horario) }}" method="POST" id="editHorarioForm" novalidate>
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">
                    Nombre
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nombre') border-red-500 @enderror"
                    id="nombre" type="text" name="nombre" value="{{ old('nombre', $horario->nombre) }}" required>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="nombre-error"></p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="hora_entrada">
                    Hora de Entrada
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('hora_entrada') border-red-500 @enderror"
                    id="hora_entrada" type="time" name="hora_entrada" value="{{ old('hora_entrada', \Carbon\Carbon::parse($horario->hora_entrada)->format('H:i')) }}" required>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="hora-entrada-error"></p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="hora_salida">
                    Hora de Salida
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('hora_salida') border-red-500 @enderror"
                    id="hora_salida" type="time" name="hora_salida" value="{{ old('hora_salida', \Carbon\Carbon::parse($horario->hora_salida)->format('H:i')) }}" required>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="hora-salida-error"></p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="descripcion">
                    Descripción
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('descripcion') border-red-500 @enderror"
                    id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $horario->descripcion) }}</textarea>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Actualizar Horario
                </button>
                <a href="{{ route('horarios.index') }}" class="text-gray-600 hover:text-gray-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editHorarioForm');
    const nombreInput = document.getElementById('nombre');
    const horaEntradaInput = document.getElementById('hora_entrada');
    const horaSalidaInput = document.getElementById('hora_salida');
    const nombreError = document.getElementById('nombre-error');
    const horaEntradaError = document.getElementById('hora-entrada-error');
    const horaSalidaError = document.getElementById('hora-salida-error');

    function validateNombre() {
        if (nombreInput.value.trim() === '') {
            nombreInput.classList.add('border-red-500');
            nombreError.textContent = 'El nombre es requerido';
            nombreError.classList.remove('hidden');
            return false;
        }
        nombreInput.classList.remove('border-red-500');
        nombreError.classList.add('hidden');
        return true;
    }

    function validateHoraEntrada() {
        if (horaEntradaInput.value === '') {
            horaEntradaInput.classList.add('border-red-500');
            horaEntradaError.textContent = 'La hora de entrada es requerida';
            horaEntradaError.classList.remove('hidden');
            return false;
        }
        horaEntradaInput.classList.remove('border-red-500');
        horaEntradaError.classList.add('hidden');
        return true;
    }

    function validateHoraSalida() {
        if (horaSalidaInput.value === '') {
            horaSalidaInput.classList.add('border-red-500');
            horaSalidaError.textContent = 'La hora de salida es requerida';
            horaSalidaError.classList.remove('hidden');
            return false;
        }
        if (horaEntradaInput.value && horaSalidaInput.value <= horaEntradaInput.value) {
            horaSalidaInput.classList.add('border-red-500');
            horaSalidaError.textContent = 'La hora de salida debe ser posterior a la hora de entrada';
            horaSalidaError.classList.remove('hidden');
            return false;
        }
        horaSalidaInput.classList.remove('border-red-500');
        horaSalidaError.classList.add('hidden');
        return true;
    }

    nombreInput.addEventListener('blur', validateNombre);
    nombreInput.addEventListener('input', validateNombre);
    horaEntradaInput.addEventListener('blur', validateHoraEntrada);
    horaEntradaInput.addEventListener('input', validateHoraEntrada);
    horaSalidaInput.addEventListener('blur', validateHoraSalida);
    horaSalidaInput.addEventListener('input', validateHoraSalida);
    horaEntradaInput.addEventListener('change', validateHoraSalida);

    form.addEventListener('submit', function(e) {
        if (!validateNombre() || !validateHoraEntrada() || !validateHoraSalida()) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
