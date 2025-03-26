@extends('layouts.dashboard')

@section('title', 'Editar Departamento')
@section('header', 'Editar Departamento')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="{{ route('departamentos.update', $departamento) }}" method="POST" id="editDepartamentoForm" novalidate>
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
                    id="nombre" type="text" name="nombre" value="{{ old('nombre', $departamento->nombre) }}" required>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="nombre-error"></p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="descripcion">
                    Descripción
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('descripcion') border-red-500 @enderror"
                    id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $departamento->descripcion) }}</textarea>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Actualizar Departamento
                </button>
                <a href="{{ route('departamentos.index') }}" class="text-gray-600 hover:text-gray-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editDepartamentoForm');
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

    nombreInput.addEventListener('blur', validateNombre);
    nombreInput.addEventListener('input', validateNombre);

    form.addEventListener('submit', function(e) {
        if (!validateNombre()) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
