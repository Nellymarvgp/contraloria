@extends('layouts.dashboard')

@section('title', 'Editar Empleado')
@section('header', 'Editar Empleado')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="{{ route('empleados.update', $empleado) }}" method="POST" id="editEmpleadoForm" novalidate>
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
            
            <!-- INFORMACIÓN PERSONAL -->
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Información Personal</h3>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Cédula del Empleado
                </label>
                <div class="py-2 px-3 bg-gray-100 rounded">
                    {{ $empleado->cedula }}
                </div>
            </div>

            <!-- SECCIÓN DE CARGO Y CLASIFICACIÓN -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="tipo_cargo">
                        Tipo de Cargo
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tipo_cargo') border-red-500 @enderror"
                        id="tipo_cargo" name="tipo_cargo" onchange="filtrarGruposCargo()">
                        <option value="">Seleccione un tipo de cargo</option>
                        @foreach($tiposCargo as $value => $label)
                            <option value="{{ $value }}" {{ old('tipo_cargo', $empleado->tipo_cargo) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-red-500 text-xs italic mt-1 hidden" id="tipo-cargo-error"></p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="cargo_id">
                        Cargo
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('cargo_id') border-red-500 @enderror"
                        id="cargo_id" name="cargo_id" required>
                        <option value="">Seleccione un cargo</option>
                        @foreach($cargos as $cargo)
                            <option value="{{ $cargo->id }}" {{ old('cargo_id', $empleado->cargo_id) == $cargo->id ? 'selected' : '' }}>
                                {{ $cargo->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-red-500 text-xs italic mt-1 hidden" id="cargo-error"></p>
                </div>
            </div>

            <!-- INFORMACIÓN LABORAL Y CLASIFICACIÓN -->
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200 mt-6">Información Laboral y Clasificación</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="departamento_id">
                        Departamento
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('departamento_id') border-red-500 @enderror"
                        id="departamento_id" name="departamento_id" required>
                        <option value="">Seleccione un departamento</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id }}" {{ old('departamento_id', $empleado->departamento_id) == $departamento->id ? 'selected' : '' }}>
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
                            <option value="{{ $horario->id }}" {{ old('horario_id', $empleado->horario_id) == $horario->id ? 'selected' : '' }}>
                                {{ $horario->nombre }} ({{ $horario->hora_entrada }} - {{ $horario->hora_salida }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-red-500 text-xs italic mt-1 hidden" id="horario-error"></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="estado_id">
                        Estado
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('estado_id') border-red-500 @enderror"
                        id="estado_id" name="estado_id" required>
                        <option value="">Seleccione un estado</option>
                        @foreach($estados as $estado)
                            <option value="{{ $estado->id }}" {{ old('estado_id', $empleado->estado_id) == $estado->id ? 'selected' : '' }}
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
                        id="salario" type="number" name="salario" value="{{ old('salario', $empleado->salario) }}" step="0.01" min="0" required>
                    <p class="text-red-500 text-xs italic mt-1 hidden" id="salario-error"></p>
                </div>
            </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="fecha_ingreso">
                        Fecha de Ingreso
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('fecha_ingreso') border-red-500 @enderror"
                        id="fecha_ingreso" type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso', $empleado->fecha_ingreso->format('Y-m-d')) }}" required>
                    <p class="text-red-500 text-xs italic mt-1 hidden" id="fecha-ingreso-error"></p>
                </div>
                
                <div class="mb-4">
                    <!-- Espacio reservado para mantener el layout del grid -->
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="grupo_cargo_id">
                        Grupo o Clase de Cargo
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('grupo_cargo_id') border-red-500 @enderror"
                        id="grupo_cargo_id" name="grupo_cargo_id">
                        <option value="">Seleccione un grupo de cargo</option>
                        @foreach($gruposCargos as $grupo)
                            <option value="{{ $grupo->id }}" data-tipo-cargo="{{ $grupo->tipo_cargo }}" {{ old('grupo_cargo_id', $empleado->grupo_cargo_id) == $grupo->id ? 'selected' : '' }}>
                                {{ $grupo->nombre }} - {{ $grupo->descripcion }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-red-500 text-xs italic mt-1 hidden" id="grupo-cargo-error"></p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nivel_rango_id">
                        Nivel de Rango
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nivel_rango_id') border-red-500 @enderror"
                        id="nivel_rango_id" name="nivel_rango_id">
                        <option value="">Seleccione un nivel de rango</option>
                        @foreach($nivelesRangos as $nivel)
                            <option value="{{ $nivel->id }}" {{ old('nivel_rango_id', $empleado->nivel_rango_id) == $nivel->id ? 'selected' : '' }}>
                                {{ $nivel->nombre }} - {{ $nivel->descripcion }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-red-500 text-xs italic mt-1 hidden" id="nivel-rango-error"></p>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="observaciones">
                    Observaciones
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('observaciones') border-red-500 @enderror"
                    id="observaciones" name="observaciones" rows="3">{{ old('observaciones', $empleado->observaciones) }}</textarea>
            </div>

            <!-- REMUNERACIONES -->
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200 mt-6">Remuneraciones y Clasificación</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="prima_antiguedad_id">
                        Prima de Antigüedad
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('prima_antiguedad_id') border-red-500 @enderror"
                        id="prima_antiguedad_id" name="prima_antiguedad_id">
                        <option value="">Seleccione una prima de antigüedad</option>
                        @foreach($primasAntiguedad as $prima)
                            <option value="{{ $prima->id }}" {{ old('prima_antiguedad_id', $empleado->prima_antiguedad_id) == $prima->id ? 'selected' : '' }}>
                                {{ $prima->nombre }} - {{ $prima->porcentaje }}%
                            </option>
                        @endforeach
                    </select>
                    <p class="text-red-500 text-xs italic mt-1 hidden" id="prima-antiguedad-error"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="prima_profesionalizacion_id">
                        Prima de Profesionalización
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('prima_profesionalizacion_id') border-red-500 @enderror"
                        id="prima_profesionalizacion_id" name="prima_profesionalizacion_id">
                        <option value="">Seleccione una prima de profesionalización</option>
                        @foreach($primasProfesionalizacion as $prima)
                            <option value="{{ $prima->id }}" {{ old('prima_profesionalizacion_id', $empleado->prima_profesionalizacion_id) == $prima->id ? 'selected' : '' }}>
                                {{ $prima->nombre }} - {{ $prima->porcentaje }}%
                            </option>
                        @endforeach
                    </select>
                    <p class="text-red-500 text-xs italic mt-1 hidden" id="prima-profesionalizacion-error"></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Espacio reservado para mantener el layout del grid -->
                <div class="mb-4"></div>
                <div class="mb-4"></div>
            </div>

            <!-- SECCIÓN DE HIJOS Y BENEFICIOS -->
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200 mt-6">Hijos y Beneficios</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">¿Tiene hijos?</label>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" id="tiene_hijos" name="tiene_hijos" value="1" {{ old('tiene_hijos', $empleado->tiene_hijos) ? 'checked' : '' }}>
                            <span class="ml-2">Sí</span>
                        </label>
                    </div>
                </div>
                
                <div class="mb-4" id="cantidad_hijos_div" style="{{ old('tiene_hijos', $empleado->tiene_hijos) ? '' : 'display: none;' }}">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="cantidad_hijos">Cantidad de hijos</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('cantidad_hijos') border-red-500 @enderror" 
                           id="cantidad_hijos" name="cantidad_hijos" type="number" min="1" value="{{ old('cantidad_hijos', $empleado->cantidad_hijos) }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Beneficios personalizados</label>
                <div id="beneficios-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                    {{-- Los beneficios se cargarán dinámicamente según el cargo seleccionado --}}
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Deducciones personalizadas</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                    @foreach($deducciones as $deduccion)
                        <label class="inline-flex items-center p-2 bg-gray-50 hover:bg-gray-100 rounded">
                            <input type="checkbox" name="deducciones[]" value="{{ $deduccion->id }}"
                                {{ (is_array(old('deducciones', $empleado->deducciones->pluck('id')->toArray())) && in_array($deduccion->id, old('deducciones', $empleado->deducciones->pluck('id')->toArray()))) ? 'checked' : '' }}>
                            <span class="ml-2">{{ $deduccion->nombre }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Actualizar Empleado
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
    // Manejo de la visibilidad del campo cantidad de hijos
    const tieneHijos = document.getElementById('tiene_hijos');
    const cantidadHijosDiv = document.getElementById('cantidad_hijos_div');
    const cantidadHijosInput = document.getElementById('cantidad_hijos');
    
    function mostrarCantidadHijos() {
        if (tieneHijos.checked) {
            cantidadHijosDiv.style.display = '';
            cantidadHijosInput.required = true;
        } else {
            cantidadHijosDiv.style.display = 'none';
            cantidadHijosInput.value = '';
            cantidadHijosInput.required = false;
        }
    }
    
    tieneHijos.addEventListener('change', mostrarCantidadHijos);
    mostrarCantidadHijos();
    
    // Filtrado dinámico de grupos de cargo según el tipo seleccionado
    const tipoCargo = document.getElementById('tipo_cargo');
    const grupoCargo = document.getElementById('grupo_cargo_id');
    
    function filtrarGruposCargo() {
        const tipoSeleccionado = tipoCargo.value;
        const opciones = grupoCargo.querySelectorAll('option');
        
        opciones.forEach(opcion => {
            if (opcion.value === '') {
                opcion.style.display = ''; // Siempre mostrar la opción vacía
            } else {
                // Verificamos si la opción tiene el atributo data-tipo-cargo
                const tipoGrupo = opcion.getAttribute('data-tipo-cargo');
                
                // Si no hay tipo seleccionado o coincide con el tipo del grupo, mostrar la opción
                if (!tipoSeleccionado || tipoGrupo === tipoSeleccionado) {
                    opcion.style.display = '';
                } else {
                    opcion.style.display = 'none';
                }
            }
        });
        
        // Si la opción seleccionada ya no está visible, seleccionar la opción vacía
        if (grupoCargo.selectedOptions.length && grupoCargo.selectedOptions[0].style.display === 'none') {
            grupoCargo.value = '';
        }
    }
    
    tipoCargo.addEventListener('change', filtrarGruposCargo);
    // Ejecutar el filtro al cargar la página
    filtrarGruposCargo();

    // Cargar beneficios por cargo
    function cargarBeneficiosPorCargo() {
        const cargoSelect = document.getElementById('cargo_id');
        const container = document.getElementById('beneficios-container');
        if (!cargoSelect || !container) return;

        const cargoId = cargoSelect.value;
        container.innerHTML = '';

        if (!cargoId) {
            return;
        }

        fetch(`/empleados/beneficios-por-cargo/${cargoId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener beneficios por cargo');
                }
                return response.json();
            })
            .then(data => {
                if (!Array.isArray(data)) return;
                const seleccionados = @json(old('beneficios', $empleado->beneficios->pluck('id')->toArray()));
                data.forEach(beneficio => {
                    const label = document.createElement('label');
                    label.className = 'inline-flex items-center p-2 bg-gray-50 hover:bg-gray-100 rounded';

                    const input = document.createElement('input');
                    input.type = 'checkbox';
                    input.name = 'beneficios[]';
                    input.value = beneficio.id;
                    input.setAttribute('form', 'editEmpleadoForm');
                    if (Array.isArray(seleccionados) && seleccionados.includes(beneficio.id)) {
                        input.checked = true;
                    }

                    const span = document.createElement('span');
                    span.className = 'ml-2';
                    span.textContent = beneficio.beneficio;

                    label.appendChild(input);
                    label.appendChild(span);
                    container.appendChild(label);
                });
            })
            .catch(error => {
                console.error(error);
            });
    }

    const cargoSelect = document.getElementById('cargo_id');
    if (cargoSelect) {
        cargoSelect.addEventListener('change', cargarBeneficiosPorCargo);
        if (cargoSelect.value) {
            cargarBeneficiosPorCargo();
        }
    }
    
    // Validación de formulario
    const form = document.getElementById('editEmpleadoForm');
    const requiredFields = {
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
