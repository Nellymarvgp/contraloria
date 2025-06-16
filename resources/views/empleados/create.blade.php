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
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                    @foreach($deducciones as $beneficio)
                        @if($beneficio->tipo === 'beneficio')
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="beneficios[]" value="{{ $beneficio->id }}" {{ (is_array(old('beneficios')) && in_array($beneficio->id, old('beneficios', []))) ? 'checked' : '' }}>
                            <span class="ml-2">{{ $beneficio->nombre }}</span>
                        </label>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Deducciones tipo Beneficio</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                    @foreach($deducciones as $deduccion)
                        @if($deduccion->tipo === 'deduccion')
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="deducciones[]" value="{{ $deduccion->id }}" {{ (is_array(old('deducciones')) && in_array($deduccion->id, old('deducciones', []))) ? 'checked' : '' }}>
                            <span class="ml-2">{{ $deduccion->nombre }}</span>
                        </label>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Deducciones tipo Parámetro</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                    @foreach($deducciones as $deduccion)
                        @if($deduccion->tipo === 'parametro')
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="deducciones[]" value="{{ $deduccion->id }}" {{ (is_array(old('deducciones')) && in_array($deduccion->id, old('deducciones', []))) ? 'checked' : '' }}>
                            <span class="ml-2">{{ $deduccion->nombre }}</span>
                        </label>
                        @endif
                    @endforeach
                </div>
            </div>

        

            <h3 class="text-lg font-semibold text-gray-700 mb-4">Información de Remuneraciones y Clasificación</h3>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="prima_antiguedad_id">
                    Antigüedad
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('prima_antiguedad_id') border-red-500 @enderror"
                    id="prima_antiguedad_id" name="prima_antiguedad_id">
                    <option value="">Antigüedad</option>
                    @foreach($primasAntiguedad as $prima)
                        <option value="{{ $prima->id }}" {{ old('prima_antiguedad_id') == $prima->id ? 'selected' : '' }}>
                            {{ $prima->anios }} años
                        </option>
                    @endforeach
                </select>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="prima-antiguedad-error"></p>
            </div>

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
            </div>
            
            <div class="mb-4" id="grupo_cargo_div" style="display:none;">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="grupo_cargo_id">Grupo de Cargo</label>
                <select id="grupo_cargo_id" name="grupo_cargo_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                    <option value="">Seleccione grupo</option>
                    @foreach($gruposCargos as $grupo)
                        <option value="{{ $grupo->id }}" {{ old('grupo_cargo_id') == $grupo->id ? 'selected' : '' }}>{{ $grupo->descripcion }}</option>
                    @endforeach
                </select>
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
    
    if(tipo === 'administracion_publica') {
        filtrarGrupoCargo(); // Filtrar grupos de cargo basado en el tipo de cargo
    }
}

function filtrarGrupoCargo() {
    const tipoCargo = document.getElementById('tipo_cargo').value;
    const grupoCargoSelect = document.getElementById('grupo_cargo_id');
    
    // Guardar el valor seleccionado actual si existe
    const valorSeleccionado = grupoCargoSelect.value;
    
    // Mostrar todas las opciones primero
    Array.from(grupoCargoSelect.options).forEach(option => {
        if(option.value !== '') {
            option.style.display = '';
        }
    });
    
    // Si no hay tipo de cargo seleccionado, dejamos todas las opciones visibles
    if(!tipoCargo) return;
    
    // Realizar una solicitud AJAX para obtener los grupos de cargo según el tipo seleccionado
    fetch(`/api/grupos-cargo/por-tipo/${tipoCargo}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener grupos de cargo');
            }
            return response.json();
        })
        .then(gruposPermitidos => {
            // Si la API devuelve datos, filtrar según los IDs recibidos
            if (gruposPermitidos && gruposPermitidos.length) {
                filtrarOpciones(gruposPermitidos);
            } else {
                // Si no hay datos de la API, usamos la lógica predeterminada
                const mapaGrupos = {
                    'bachiller': ['1', '2'],
                    'tecnico_superior': ['2', '3'],
                    'profesional_universitario': ['3', '4', '5']
                };
                
                const gruposPermitidosPorTipo = mapaGrupos[tipoCargo] || [];
                filtrarOpciones(gruposPermitidosPorTipo);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // En caso de error, mostramos todas las opciones
            Array.from(grupoCargoSelect.options).forEach(option => {
                if(option.value !== '') {
                    option.style.display = '';
                }
            });
        });
    
    function filtrarOpciones(gruposPermitidos) {
        // Ocultar todas las opciones primero excepto la opción vacía
        Array.from(grupoCargoSelect.options).forEach(option => {
            if(option.value !== '') {
                option.style.display = 'none';
            }
        });
        
        // Mostrar solo las opciones permitidas
        Array.from(grupoCargoSelect.options).forEach(option => {
            if(gruposPermitidos.includes(option.value)) {
                option.style.display = '';
            }
        });
        
        // Resetear la selección si el valor anterior ya no está disponible
        if(valorSeleccionado) {
            const opcionAun = Array.from(grupoCargoSelect.options).find(o => 
                o.value === valorSeleccionado && o.style.display !== 'none');
            if(!opcionAun) {
                grupoCargoSelect.value = '';
            }
        }
        
        // Si solo hay una opción disponible (además de la opción vacía), seleccionarla automáticamente
        const opcionesVisibles = Array.from(grupoCargoSelect.options).filter(o => 
            o.value !== '' && o.style.display !== 'none');
        if(opcionesVisibles.length === 1 && grupoCargoSelect.value === '') {
            grupoCargoSelect.value = opcionesVisibles[0].value;
        }
    }

}
function obtenerSueldo() {
    console.log('Calculando salario...');
    const tipo = document.getElementById('tipo_personal').value;
    let params = { tipo_personal: tipo };
    if(tipo === 'administracion_publica') {
        params.nivel_rango_id = document.getElementById('nivel_rango_id').value;
        params.grupo_cargo_id = document.getElementById('grupo_cargo_id').value;
        params.tipo_cargo = document.getElementById('tipo_cargo').value;
    } else if(tipo === 'obreros') {
        params.clasificacion = document.getElementById('clasificacion').value;
        params.grado = document.getElementById('grado').value;
    }
    
    console.log('Parámetros para cálculo de salario:', params);
    
    // Solo hace la petición si los selects requeridos están llenos
    let valid = tipo && (
        (tipo === 'administracion_publica' && params.nivel_rango_id && params.grupo_cargo_id && params.tipo_cargo) || 
        (tipo === 'obreros' && params.clasificacion && params.grado)
    );
    
    if(!valid) { 
        console.log('Faltan campos obligatorios para calcular el salario');
        document.getElementById('salario').value = ''; 
        return; 
    }
    
    console.log('Consultando API para obtener salario...');
    fetch('/api/remuneracion/obtener?' + new URLSearchParams(params))
        .then(res => {
            if (!res.ok) {
                throw new Error('Error en la respuesta de la API: ' + res.status);
            }
            return res.json();
        })
        .then(data => {
            console.log('Respuesta de API:', data);
            if(data && data.valor !== undefined) {
                document.getElementById('salario').value = data.valor;
                console.log('Salario actualizado:', data.valor);
            } else {
                document.getElementById('salario').value = '';
                console.log('No se recibieron datos de salario válidos');
            }
        })
        .catch(error => { 
            console.error('Error al obtener salario:', error); 
            document.getElementById('salario').value = ''; 
        });
}
document.addEventListener('DOMContentLoaded', function() {
    mostrarOcultarCampos();
    
    // Cuando cambia el tipo de personal
    document.getElementById('tipo_personal').addEventListener('change', function() {
        mostrarOcultarCampos();
        // El cálculo de salario se retrasa para dar tiempo a que se muestren/oculten campos
        setTimeout(obtenerSueldo, 100);
    });
    
    // Filtrar grupo de cargo según tipo de cargo
    document.getElementById('tipo_cargo').addEventListener('change', function() {
        filtrarGrupoCargo();
        // El cálculo de salario se retrasa para dar tiempo al filtrado de grupos
        setTimeout(obtenerSueldo, 300);
    });
    
    // Agregar listeners específicos con mayor prioridad
    const grupoCargo = document.getElementById('grupo_cargo_id');
    if(grupoCargo) {
        grupoCargo.addEventListener('change', function() {
            console.log('Grupo de cargo cambiado a:', this.value);
            obtenerSueldo();
        });
    }
    
    // Para todos los demás campos relevantes
    ['nivel_rango_id','clasificacion','grado'].forEach(id => {
        let el = document.getElementById(id);
        if(el) {
            el.addEventListener('change', function() {
                console.log(`Campo ${id} cambiado a:`, this.value);
                obtenerSueldo();
            });
        }
    });
    
    // Prima de antigüedad afecta al salario
    const primaAntiguedad = document.getElementById('prima_antiguedad_id');
    if(primaAntiguedad) {
        primaAntiguedad.addEventListener('change', function() {
            console.log('Prima de antigüedad cambiada a:', this.value);
            obtenerSueldo();
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
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
});

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
