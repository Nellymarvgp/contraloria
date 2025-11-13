@extends('layouts.dashboard')

@section('title', 'Crear Remuneración')

@section('header', 'Crear Remuneración')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="py-4 px-6 bg-gray-100 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Nueva Remuneración</h2>
    </div>

    <div class="py-4 px-6">
        <form action="{{ route('remuneraciones.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="tipo_personal" class="block text-gray-700 font-medium mb-2">Tipo de Personal</label>
                <select name="tipo_personal" id="tipo_personal" 
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('tipo_personal') border-red-500 @enderror">
                    <option value="">Seleccione un tipo</option>
                    <option value="administracion_publica" {{ old('tipo_personal') == 'administracion_publica' ? 'selected' : '' }}>Funcionarios y funcionarias</option>
                    <option value="obreros" {{ old('tipo_personal') == 'obreros' ? 'selected' : '' }}>Personal obrero</option>
                </select>
                @error('tipo_personal')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="funcionarios_fields" style="display: none;">
                <div class="mb-4">
                    <label for="nivel_rango_id" class="block text-gray-700 font-medium mb-2">Nivel de Rango</label>
                    <select name="nivel_rango_id" id="nivel_rango_id" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('nivel_rango_id') border-red-500 @enderror">
                        <option value="">Seleccione un nivel</option>
                        @foreach($nivelesRangos as $nivel)
                            <option value="{{ $nivel->id }}" {{ old('nivel_rango_id') == $nivel->id ? 'selected' : '' }}>
                                {{ $nivel->descripcion }}
                            </option>
                        @endforeach
                    </select>
                    @error('nivel_rango_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="tipo_cargo" class="block text-gray-700 font-medium mb-2">Tipo de Cargo</label>
                    <select name="tipo_cargo" id="tipo_cargo"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('tipo_cargo') border-red-500 @enderror">
                        <option value="">Seleccione un tipo</option>
                        <option value="bachiller" {{ old('tipo_cargo') == 'bachiller' ? 'selected' : '' }}>Bachiller</option>
                        <option value="tecnico_superior" {{ old('tipo_cargo') == 'tecnico_superior' ? 'selected' : '' }}>Técnico Superior Universitario</option>
                        <option value="profesional_universitario" {{ old('tipo_cargo') == 'profesional_universitario' ? 'selected' : '' }}>Profesional Universitario</option>
                    </select>
                    @error('tipo_cargo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="grupo_cargo_id" class="block text-gray-700 font-medium mb-2">Grupo o Clase de Cargo</label>
                    <select name="grupo_cargo_id" id="grupo_cargo_id" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('grupo_cargo_id') border-red-500 @enderror">
                        <option value="">Seleccione un grupo</option>
                        @foreach($gruposCargos as $grupo)
                            <option value="{{ $grupo->id }}" data-categoria="{{ $grupo->categoria }}" {{ old('grupo_cargo_id') == $grupo->id ? 'selected' : '' }}>
                                {{ $grupo->descripcion }}
                            </option>
                        @endforeach
                    </select>
                    @error('grupo_cargo_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <script>
                    // Mapeo de tipo_cargo a categoria
                    const cargoCategoriaMap = {
                        'bachiller': 'administrativo_bachiller',
                        'tecnico_superior': 'tecnico_superior',
                        'profesional_universitario': 'profesional_universitario'
                    };
                    document.getElementById('tipo_cargo').addEventListener('change', function() {
                        const tipo = this.value;
                        const categoria = cargoCategoriaMap[tipo] || null;
                        const grupoSelect = document.getElementById('grupo_cargo_id');
                        for (let opt of grupoSelect.options) {
                            if (!opt.value) continue; // skip placeholder
                            if (!categoria || opt.getAttribute('data-categoria') === categoria) {
                                opt.style.display = '';
                            } else {
                                opt.style.display = 'none';
                            }
                        }
                        grupoSelect.value = '';
                    });
                    // Al cargar la página, aplicar el filtro si hay valor
                    window.addEventListener('DOMContentLoaded', function() {
                        const tipoCargo = document.getElementById('tipo_cargo').value;
                        if(tipoCargo) {
                            const event = new Event('change');
                            document.getElementById('tipo_cargo').dispatchEvent(event);
                        }
                    });
                </script>
                <div class="mb-4">
                    <label for="valor" class="block text-gray-700 font-medium mb-2">Valor</label>
                    <input type="number" step="0.01" name="valor" id="valor" value="{{ old('valor') }}" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('valor') border-red-500 @enderror">
                    @error('valor')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div id="obrero_fields" style="display: none;">
                <div class="mb-4">
                    <label for="clasificacion" class="block text-gray-700 font-medium mb-2">Clasificación</label>
                    <select name="clasificacion" id="clasificacion" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('clasificacion') border-red-500 @enderror">
                        <option value="">Seleccione una clasificación</option>
                        <option value="no_calificados" {{ old('clasificacion') == 'no_calificados' ? 'selected' : '' }}>No calificados</option>
                        <option value="calificados" {{ old('clasificacion') == 'calificados' ? 'selected' : '' }}>Calificados</option>
                        <option value="supervisor" {{ old('clasificacion') == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                    </select>
                    @error('clasificacion')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="grado" class="block text-gray-700 font-medium mb-2">Grado</label>
                    <select name="grado" id="grado" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('grado') border-red-500 @enderror">
                        <option value="">Seleccione un grado</option>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('grado') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    @error('grado')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="valor" class="block text-gray-700 font-medium mb-2">Valor</label>
                    <input type="number" step="0.01" name="valor" id="valor_obrero" value="{{ old('valor') }}" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('valor') border-red-500 @enderror">
                    @error('valor')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <script>

                 function toggleFields() {
                    const tipo = document.getElementById('tipo_personal').value;

                     const funcionariosFields = document.getElementById('funcionarios_fields');
                    const obreroFields = document.getElementById('obrero_fields');

                     const valorFuncionario = document.getElementById('valor');
                     const valorObrero = document.getElementById('valor_obrero');

                     // Mostrar/ocultar bloques
                     funcionariosFields.style.display = tipo === 'administracion_publica' ? '' : 'none';
                    obreroFields.style.display = tipo === 'obreros' ? '' : 'none';

                    // Activar solo el campo relevante
                    valorFuncionario.disabled = tipo !== 'administracion_publica';
                    valorObrero.disabled = tipo !== 'obreros';
                }


            window.addEventListener('DOMContentLoaded', toggleFields);
            document.getElementById('tipo_personal').addEventListener('change', toggleFields);
        </script>

            
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="estado" id="estado" checked
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-gray-700">Activo</span>
                </label>
            </div>
            
            <div class="flex justify-end">
                <a href="{{ route('remuneraciones.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded mr-2">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
