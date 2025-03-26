@extends('layouts.dashboard')

@section('title', 'Crear Empleado')
@section('header', 'Crear Nuevo Empleado')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="{{ route('empleados.store') }}" method="POST" id="createEmpleadoForm" novalidate>
            @csrf

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
                <label class="block text-gray-700 text-sm font-bold mb-2" for="cedula">
                    Usuario (Cédula)
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('cedula') border-red-500 @enderror"
                    id="cedula" name="cedula" required>
                    <option value="">Seleccione un usuario</option>
                    @foreach($users as $user)
                        <option value="{{ $user->cedula }}" {{ old('cedula') == $user->cedula ? 'selected' : '' }}>
                            {{ $user->cedula }} - {{ $user->nombre }} {{ $user->apellido }}
                        </option>
                    @endforeach
                </select>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="cedula-error"></p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="cargo_id">
                    Cargo
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('cargo_id') border-red-500 @enderror"
                    id="cargo_id" name="cargo_id" required>
                    <option value="">Seleccione un cargo</option>
                    @foreach($cargos as $cargo)
                        <option value="{{ $cargo->id }}" {{ old('cargo_id') == $cargo->id ? 'selected' : '' }}>
                            {{ $cargo->nombre }}
                        </option>
                    @endforeach
                </select>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="cargo-error"></p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="departamento_id">
                    Departamento
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('departamento_id') border-red-500 @enderror"
                    id="departamento_id" name="departamento_id" required>
                    <option value="">Seleccione un departamento</option>
                    @foreach($departamentos as $departamento)
                        <option value="{{ $departamento->id }}" {{ old('departamento_id') == $departamento->id ? 'selected' : '' }}>
                            {{ $departamento->nombre }}
                        </option>
                    @endforeach
                </select>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="departamento-error"></p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="horario_id">
                    Horario
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('horario_id') border-red-500 @enderror"
                    id="horario_id" name="horario_id" required>
                    <option value="">Seleccione un horario</option>
                    @foreach($horarios as $horario)
                        <option value="{{ $horario->id }}" {{ old('horario_id') == $horario->id ? 'selected' : '' }}>
                            {{ $horario->nombre }} ({{ $horario->hora_entrada }} - {{ $horario->hora_salida }})
                        </option>
                    @endforeach
                </select>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="horario-error"></p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="estado_id">
                    Estado
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('estado_id') border-red-500 @enderror"
                    id="estado_id" name="estado_id" required>
                    <option value="">Seleccione un estado</option>
                    @foreach($estados as $estado)
                        <option value="{{ $estado->id }}" {{ old('estado_id') == $estado->id ? 'selected' : '' }}
                            style="background-color: {{ $estado->color }}; color: {{ $estado->color === '#FFFFFF' ? '#000000' : '#FFFFFF' }}">
                            {{ $estado->nombre }}
                        </option>
                    @endforeach
                </select>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="estado-error"></p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="salario">
                    Salario
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('salario') border-red-500 @enderror"
                    id="salario" type="number" name="salario" value="{{ old('salario') }}" step="0.01" min="0" required>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="salario-error"></p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="fecha_ingreso">
                    Fecha de Ingreso
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('fecha_ingreso') border-red-500 @enderror"
                    id="fecha_ingreso" type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso', date('Y-m-d')) }}" required>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="fecha-ingreso-error"></p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="observaciones">
                    Observaciones
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('observaciones') border-red-500 @enderror"
                    id="observaciones" name="observaciones" rows="3">{{ old('observaciones') }}</textarea>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Crear Empleado
                </button>
                <a href="{{ route('empleados.index') }}" class="text-gray-600 hover:text-gray-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createEmpleadoForm');
    const requiredFields = {
        cedula: {
            element: document.getElementById('cedula'),
            error: document.getElementById('cedula-error'),
            message: 'Debe seleccionar un usuario'
        },
        cargo_id: {
            element: document.getElementById('cargo_id'),
            error: document.getElementById('cargo-error'),
            message: 'Debe seleccionar un cargo'
        },
        departamento_id: {
            element: document.getElementById('departamento_id'),
            error: document.getElementById('departamento-error'),
            message: 'Debe seleccionar un departamento'
        },
        horario_id: {
            element: document.getElementById('horario_id'),
            error: document.getElementById('horario-error'),
            message: 'Debe seleccionar un horario'
        },
        estado_id: {
            element: document.getElementById('estado_id'),
            error: document.getElementById('estado-error'),
            message: 'Debe seleccionar un estado'
        },
        salario: {
            element: document.getElementById('salario'),
            error: document.getElementById('salario-error'),
            message: 'Debe ingresar un salario válido',
            validate: (value) => !isNaN(value) && parseFloat(value) > 0
        },
        fecha_ingreso: {
            element: document.getElementById('fecha_ingreso'),
            error: document.getElementById('fecha-ingreso-error'),
            message: 'Debe seleccionar una fecha de ingreso',
            validate: (value) => value !== ''
        }
    };

    // Real-time validation
    Object.keys(requiredFields).forEach(key => {
        const field = requiredFields[key];
        
        field.element.addEventListener('change', function() {
            validateField(key);
        });

        field.element.addEventListener('blur', function() {
            validateField(key);
        });
    });

    function validateField(fieldName) {
        const field = requiredFields[fieldName];
        const value = field.element.value.trim();
        
        if (value === '') {
            showError(field, field.message);
            return false;
        }

        if (field.validate && !field.validate(value)) {
            showError(field, field.message);
            return false;
        }

        hideError(field);
        return true;
    }

    function showError(field, message) {
        field.element.classList.add('border-red-500');
        field.error.textContent = message;
        field.error.classList.remove('hidden');
    }

    function hideError(field) {
        field.element.classList.remove('border-red-500');
        field.error.classList.add('hidden');
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Validate all fields
        Object.keys(requiredFields).forEach(key => {
            if (!validateField(key)) {
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
