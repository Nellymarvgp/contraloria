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
                <label class="block text-gray-700 text-sm font-bold mb-2" for="fecha_ingreso">
                    Fecha de Ingreso
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('fecha_ingreso') border-red-500 @enderror"
                    id="fecha_ingreso" type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso', date('Y-m-d')) }}" required>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="fecha-ingreso-error"></p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="anios_antiguedad">
                    Años de Antigüedad
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-100" id="anios_antiguedad" type="number" name="anios_antiguedad" value="{{ old('anios_antiguedad') }}" readonly>
            </div>


            <h3 class="text-lg font-semibold text-gray-700 mb-4">Información de Remuneraciones y Clasificación</h3>

            <!-- La Prima de Profesionalización se asigna automáticamente según el Tipo de Cargo -->
            <input type="hidden" id="prima_profesionalizacion_id" name="prima_profesionalizacion_id" value="">

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="tipo_personal">Tipo de Personal</label>
                <select id="tipo_personal" name="tipo_personal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Seleccione tipo de personal</option>
                    <option value="administracion_publica">Administración Pública</option>
                    <option value="obreros">Obrero</option>
                </select>
            </div>
            <div class="mb-4" id="nivel_rango_div" style="display:none;">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nivel_rango_id">Nivel de Rango</label>
                <select id="nivel_rango_id" name="nivel_rango_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                    <option value="">Seleccione nivel</option>
                    @foreach($nivelesRangos as $nivel)
                        <option value="{{ $nivel->id }}" {{ old('nivel_rango_id') == $nivel->id ? 'selected' : '' }}>{{ $nivel->descripcion }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4" id="tipo_cargo_div" style="display:none;">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="tipo_cargo">Tipo de Cargo</label>
                <select id="tipo_cargo" name="tipo_cargo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                    <option value="">Seleccione tipo de cargo</option>
                    <option value="bachiller">Bachiller</option>
                    <option value="tecnico_superior">Técnico Superior Universitario</option>
                    <option value="profesional_universitario">Profesional Universitario</option>
                </select>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="tipo_cargo-error"></p>
            </div>
            
            <div class="mb-4" id="grupo_cargo_div" style="display:none;">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="grupo_cargo_id">Grupo de Cargo</label>
                <select id="grupo_cargo_id" name="grupo_cargo_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" {{ empty($gruposCargos) ? 'disabled' : '' }}>
    <option value="">Seleccione un grupo de cargo</option>
    @foreach($gruposCargos as $grupo)
        <option value="{{ $grupo->id }}" {{ old('grupo_cargo_id') == $grupo->id ? 'selected' : '' }}>{{ $grupo->descripcion }}</option>
    @endforeach
</select>
@if(empty($gruposCargos) || $gruposCargos->count() == 0)
    <p class="text-sm text-gray-500 mt-1">Seleccione primero un tipo de cargo para ver los grupos disponibles.</p>
@endif
            </div>
            <div class="mb-4" id="clasificacion_div" style="display:none;">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="clasificacion">Clasificación</label>
                <select id="clasificacion" name="clasificacion" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                    <option value="">Seleccione clasificación</option>
                    <option value="no_calificados">No Calificado</option>
                    <option value="calificados">Calificado</option>
                    <option value="supervisor">Supervisor</option>
                </select>
            </div>
            <div class="mb-4" id="grado_div" style="display:none;">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="grado">Grado</label>
                <select id="grado" name="grado" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                    <option value="">Seleccione grado</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
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
            <!-- CAMPO DE SALARIO SOLO LECTURA -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="salario">Salario</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-100" id="salario" type="number" name="salario" value="{{ old('salario') }}" step="0.01" min="0" readonly>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="salario-error"></p>
            </div>
            <!-- NUEVOS CAMPOS: HIJOS -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">¿Tiene hijos?</label>
                <input type="checkbox" id="tiene_hijos" name="tiene_hijos" value="1" {{ old('tiene_hijos') ? 'checked' : '' }}>
            </div>
            <div class="mb-4" id="cantidad_hijos_div" style="display: none;">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="cantidad_hijos">Cantidad de hijos</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="cantidad_hijos" name="cantidad_hijos" type="number" min="1" value="{{ old('cantidad_hijos') }}">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Beneficios personalizados</label>
                <div id="beneficios-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                    {{-- Los beneficios se cargarán dinámicamente según el cargo seleccionado --}}
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Deducciones</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                    @foreach($deducciones as $deduccion)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="deducciones[]" value="{{ $deduccion->id }}" {{ (is_array(old('deducciones')) && in_array($deduccion->id, old('deducciones', []))) ? 'checked' : '' }}>
                            <span class="ml-2">{{ $deduccion->nombre }}</span>
                        </label>
                    @endforeach
                </div>
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

            <!-- NUEVOS SELECTS DINÁMICOS PARA REMUNERACIÓN -->

<!-- SCRIPT DINÁMICO PARA OBTENER SUELDO -->
<script> 
function mostrarOcultarCampos() {
    const tipo = document.getElementById('tipo_personal').value;
    document.getElementById('nivel_rango_div').style.display = tipo === 'administracion_publica' ? '' : 'none';
    document.getElementById('tipo_cargo_div').style.display = tipo === 'administracion_publica' ? '' : 'none';
    document.getElementById('grupo_cargo_div').style.display = tipo === 'administracion_publica' ? '' : 'none';
    document.getElementById('clasificacion_div').style.display = tipo === 'obreros' ? '' : 'none';
    document.getElementById('grado_div').style.display = tipo === 'obreros' ? '' : 'none';
    
    // Limpiar el select de grupo_cargo y el campo salario al cambiar el tipo
    if (tipo === 'administracion_publica') {
        document.getElementById('grupo_cargo_id').innerHTML = '<option value="">Seleccione un grupo</option>';
        document.getElementById('salario').value = '';
    }
    if (tipo === 'obreros') {
        document.getElementById('salario').value = '';
    }
}

// Filtrar grupos de cargo según el tipo seleccionado
function filtrarGruposPorTipo() {
    const tipoCargo = document.getElementById('tipo_cargo').value;
    const grupoCargoSelect = document.getElementById('grupo_cargo_id');
    
    // Limpiar el select de grupo de cargo y el campo salario
    grupoCargoSelect.innerHTML = '<option value="">Seleccione un grupo</option>';
    document.getElementById('salario').value = '';
    
    // Si no hay tipo de cargo seleccionado, terminamos
    if (!tipoCargo) {
        grupoCargoSelect.disabled = true;
        return;
    }
    
    grupoCargoSelect.disabled = true; // Deshabilitar mientras carga
    
    // Realizar una solicitud AJAX para obtener los grupos según el tipo
    fetch(`/grupos-por-tipo/${tipoCargo}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener grupos: ' + response.status);
            }
            return response.json();
        })
        .then(grupos => {
            // Habilitar el select
            grupoCargoSelect.disabled = false;
            
            // Si no hay grupos, terminamos
            if (grupos.length === 0) {
                return;
            }
            
            // Agregar las opciones al select
            grupos.forEach(grupo => {
                const option = document.createElement('option');
                option.value = grupo.id;
                option.textContent = grupo.descripcion;
                grupoCargoSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            grupoCargoSelect.disabled = false;
        });
}

// Obtener el salario según el grupo de cargo seleccionado
function obtenerSalarioPorGrupo() {
    const grupoId = document.getElementById('grupo_cargo_id').value;
    const salarioInput = document.getElementById('salario');
    
    // Limpiar el campo salario
    salarioInput.value = '';
    
    // Si no hay grupo seleccionado, terminamos
    if (!grupoId) {
        return;
    }
    
    // Realizar una solicitud AJAX para obtener el salario
    fetch(`/remuneracion-por-grupo/${grupoId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener salario: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.valor) {
                salarioInput.value = data.valor;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Obtener el salario para obreros según clasificación y grado
function obtenerSalarioObrero() {
    const tipo = document.getElementById('tipo_personal').value;
    if (tipo !== 'obreros') return;
    const clasificacion = document.getElementById('clasificacion').value;
    const grado = document.getElementById('grado').value;
    const salarioInput = document.getElementById('salario');
    salarioInput.value = '';
    if (!clasificacion || !grado) {
        return;
    }
    const params = new URLSearchParams({
        tipo_personal: 'obreros',
        clasificacion: clasificacion,
        grado: grado
    });
    fetch(`/remuneracion?${params.toString()}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener salario: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.valor) {
                salarioInput.value = data.valor;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Calcular años de antigüedad a partir de la fecha de ingreso
function calcularAntiguedad() {
    const fechaIngresoInput = document.getElementById('fecha_ingreso');
    const antiguedadInput = document.getElementById('anios_antiguedad');

    if (!fechaIngresoInput || !antiguedadInput) return;

    const valor = fechaIngresoInput.value;
    if (!valor) {
        antiguedadInput.value = '';
        return;
    }

    const hoy = new Date();
    const fechaIngreso = new Date(valor);

    if (isNaN(fechaIngreso.getTime())) {
        antiguedadInput.value = '';
        return;
    }

    let anios = hoy.getFullYear() - fechaIngreso.getFullYear();
    const mesActual = hoy.getMonth();
    const diaActual = hoy.getDate();
    const mesIngreso = fechaIngreso.getMonth();
    const diaIngreso = fechaIngreso.getDate();

    if (mesActual < mesIngreso || (mesActual === mesIngreso && diaActual < diaIngreso)) {
        anios--;
    }

    if (anios < 0) {
        antiguedadInput.value = '';
        return;
    }

    antiguedadInput.value = anios;
}

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
            data.forEach(beneficio => {
                const label = document.createElement('label');
                label.className = 'inline-flex items-center';

                const input = document.createElement('input');
                input.type = 'checkbox';
                input.name = 'beneficios[]';
                input.value = beneficio.id;

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

// Configurar los event listeners cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar/ocultar campos según el tipo de personal inicial
    mostrarOcultarCampos();
    
    // Event listener para tipo_personal
    document.getElementById('tipo_personal').addEventListener('change', mostrarOcultarCampos);
    
    // Event listener para tipo_cargo
    document.getElementById('tipo_cargo').addEventListener('change', filtrarGruposPorTipo);
    
    // Event listener para grupo_cargo_id
    document.getElementById('grupo_cargo_id').addEventListener('change', obtenerSalarioPorGrupo);
    
    // Event listeners para obreros
    document.getElementById('clasificacion').addEventListener('change', obtenerSalarioObrero);
    document.getElementById('grado').addEventListener('change', obtenerSalarioObrero);
    
    // Event listener para cantidad de hijos
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

    // Calcular la antigüedad inicial y al cambiar la fecha de ingreso
    const fechaIngresoInput = document.getElementById('fecha_ingreso');
    if (fechaIngresoInput) {
        calcularAntiguedad();
        fechaIngresoInput.addEventListener('change', calcularAntiguedad);
    }

    // Cargar beneficios cuando se cambie el cargo
    const cargoSelect = document.getElementById('cargo_id');
    if (cargoSelect) {
        cargoSelect.addEventListener('change', cargarBeneficiosPorCargo);
        // Intentar cargar si ya viene un cargo seleccionado (old value)
        if (cargoSelect.value) {
            cargarBeneficiosPorCargo();
        }
    }
});
</script>


@endsection
