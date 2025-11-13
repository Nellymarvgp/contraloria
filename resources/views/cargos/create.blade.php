@extends('layouts.dashboard')

@section('title', 'Crear Cargo')
@section('header', 'Crear Nuevo Cargo')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="{{ route('cargos.store') }}" method="POST" id="createCargoForm" novalidate>
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
                <label class="block text-gray-700 text-sm font-bold mb-2" for="tipo_cargo">
                    Cargo
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tipo_cargo') border-red-500 @enderror"
                    id="tipo_cargo" name="tipo_cargo" required>
                    <option value="" disabled selected>Seleccione un tipo de cargo</option>
                    <option value="Alto funcionario" {{ old('tipo_cargo') == 'Alto funcionario' ? 'selected' : '' }}>Alto funcionario</option>
                    <option value="Alto Nivel" {{ old('tipo_cargo') == 'Alto Nivel' ? 'selected' : '' }}>Alto Nivel</option>
                    <option value="Empleado" {{ old('tipo_cargo') == 'Empleado' ? 'selected' : '' }}>Empleado</option>
                    <option value="Obrero" {{ old('tipo_cargo') == 'Obrero' ? 'selected' : '' }}>Obrero</option>
                </select>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="tipo-cargo-error"></p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">
                    Nombre
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nombre') border-red-500 @enderror"
                    id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" required>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="nombre-error"></p>
            </div>
            
         
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="descripcion">
                    Descripción
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('descripcion') border-red-500 @enderror"
                    id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Crear Cargo
                </button>
                <a href="{{ route('cargos.index') }}" class="text-gray-600 hover:text-gray-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createCargoForm');
    const nombreInput = document.getElementById('nombre');
    const nombreError = document.getElementById('nombre-error');

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
    
    const tipoCargoInput = document.getElementById('tipo_cargo');
    const tipoCargoError = document.getElementById('tipo-cargo-error');
    
    function validateTipoCargo() {
        if (tipoCargoInput.value === '') {
            tipoCargoInput.classList.add('border-red-500');
            tipoCargoError.textContent = 'El tipo de cargo es requerido';
            tipoCargoError.classList.remove('hidden');
            return false;
        }
        tipoCargoInput.classList.remove('border-red-500');
        tipoCargoError.classList.add('hidden');
        return true;
    }

    nombreInput.addEventListener('blur', validateNombre);
    nombreInput.addEventListener('input', validateNombre);
    tipoCargoInput.addEventListener('change', validateTipoCargo);

    form.addEventListener('submit', function(e) {
        const isNombreValid = validateNombre();
        const isTipoCargoValid = validateTipoCargo();
        
        if (!isNombreValid || !isTipoCargoValid) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
