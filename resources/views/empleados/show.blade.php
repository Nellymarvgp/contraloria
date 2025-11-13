@extends('layouts.dashboard')

@section('title', 'Detalle de Empleado')
@section('header', 'Detalle de Empleado')

@section('content')
<div class="max-w-3xl mx-auto py-6">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">{{ $empleado->user->nombre }} {{ $empleado->user->apellido }}</h2>
        <dl class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
            <div>
                <dt class="font-semibold text-gray-700">Cédula</dt>
                <dd>{{ $empleado->cedula }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-700">Cargo</dt>
                <dd>{{ $empleado->cargo->nombre }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-700">Departamento</dt>
                <dd>{{ $empleado->departamento->nombre }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-700">Horario</dt>
                <dd>{{ $empleado->horario->nombre }} ({{ $empleado->horario->hora_entrada }} - {{ $empleado->horario->hora_salida }})</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-700">Estado</dt>
                <dd><span class="px-2 py-1 rounded-full text-xs" style="background-color: {{ $empleado->estado->color }}; color: {{ $empleado->estado->color === '#FFFFFF' ? '#000000' : '#FFFFFF' }}">{{ $empleado->estado->nombre }}</span></dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-700">Salario</dt>
                <dd>{{ number_format($empleado->salario, 2) }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-700">Fecha de Ingreso</dt>
                <dd>{{ $empleado->fecha_ingreso->format('d/m/Y') }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-700">¿Tiene hijos?</dt>
                <dd>
                    @if($empleado->tiene_hijos)
                        Sí ({{ $empleado->cantidad_hijos }})
                    @else
                        No
                    @endif
                </dd>
            </div>
        </dl>
        <div class="mb-4">
            <h3 class="font-semibold text-gray-700 mb-2">Beneficios Personalizados</h3>
            @if($empleado->beneficios && $empleado->beneficios->count())
                <ul class="list-disc pl-6">
                    @foreach($empleado->beneficios as $b)
                        <li>{{ $b->nombre }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">Ninguno</p>
            @endif
        </div>
        <div class="mb-4">
            <h3 class="font-semibold text-gray-700 mb-2">Deducciones Personalizadas</h3>
            @if($empleado->deducciones && $empleado->deducciones->count())
                <ul class="list-disc pl-6">
                    @foreach($empleado->deducciones as $d)
                        <li>{{ $d->nombre }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">Ninguna</p>
            @endif
        </div>
        <div class="mb-4">
            <h3 class="font-semibold text-gray-700 mb-2">Observaciones</h3>
            <p>{{ $empleado->observaciones ?? '-' }}</p>
        </div>
        <div class="flex justify-end">
            <a href="{{ route('empleados.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Volver</a>
            <a href="{{ route('empleados.edit', $empleado) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Editar</a>
        </div>
    </div>
</div>
@endsection
