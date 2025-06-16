@extends('layouts.dashboard')

@section('title', 'Empleados')
@section('header', 'Gestión de Empleados')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Lista de Empleados</h2>
        <a href="{{ route('empleados.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-plus mr-2"></i>Nuevo Empleado
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="bg-gray-200 text-gray-700 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Cédula</th>
                    <th class="py-3 px-6 text-left">Nombre</th>
                    <th class="py-3 px-6 text-left">Cargo</th>
                    <th class="py-3 px-6 text-left">Departamento</th>
                    <th class="py-3 px-6 text-left">Tipo Cargo</th>
                    <th class="py-3 px-6 text-left">Nivel/Grupo</th>
                    <th class="py-3 px-6 text-left">Estado</th>
                    <th class="py-3 px-6 text-left">Salario</th>
                    <th class="py-3 px-6 text-left">Hijos</th>
                    <th class="py-3 px-6 text-left">Beneficios</th>
                    <th class="py-3 px-6 text-left">Deducciones</th>
                    <th class="py-3 px-6 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @foreach($empleados as $empleado)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6">{{ $empleado->cedula }}</td>
                        <td class="py-3 px-6">{{ $empleado->user->nombre }} {{ $empleado->user->apellido }}</td>
                        <td class="py-3 px-6">{{ $empleado->cargo->nombre }}</td>
                        <td class="py-3 px-6">{{ $empleado->departamento->nombre }}</td>
                        <td class="py-3 px-6">
                            @if($empleado->tipo_cargo)
                                @php
                                    $tiposCargo = [
                                        'administrativo' => 'Administrativo',
                                        'tecnico_superior' => 'Técnico Superior Universitario',
                                        'profesional_universitario' => 'Profesional Universitario'
                                    ];
                                @endphp
                                {{ $tiposCargo[$empleado->tipo_cargo] ?? $empleado->tipo_cargo }}
                            @else
                                --
                            @endif
                        </td>
                        <td class="py-3 px-6">
                            <div>
                                @if($empleado->nivelRango)
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded">{{ $empleado->nivelRango->nombre }}</span>
                                @endif
                                @if($empleado->grupoCargo)
                                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2 py-0.5 rounded mt-1 inline-block">{{ $empleado->grupoCargo->nombre }}</span>
                                @endif
                                @if(!$empleado->nivelRango && !$empleado->grupoCargo)
                                    --
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-6">
                            <span class="px-2 py-1 rounded-full text-xs" style="background-color: {{ $empleado->estado->color }}; color: {{ $empleado->estado->color === '#FFFFFF' ? '#000000' : '#FFFFFF' }}">
                                {{ $empleado->estado->nombre }}
                            </span>
                        </td>
                        <td class="py-3 px-6">{{ number_format($empleado->salario, 2) }}</td>
                        <td class="py-3 px-6">
                            @if($empleado->tiene_hijos)
                                <span class="font-semibold">Sí</span> ({{ $empleado->cantidad_hijos }})
                            @else
                                No
                            @endif
                        </td>
                        <td class="py-3 px-6">
                            @if($empleado->beneficios && $empleado->beneficios->count())
                                <ul class="list-disc pl-4">
                                    @foreach($empleado->beneficios as $b)
                                        <li>{{ $b->nombre }}</li>
                                    @endforeach
                                </ul>
                            @else
                                --
                            @endif
                        </td>
                        <td class="py-3 px-6">
                            @if($empleado->deducciones && $empleado->deducciones->count())
                                <ul class="list-disc pl-4">
                                    @foreach($empleado->deducciones as $d)
                                        <li>{{ $d->nombre }}</li>
                                    @endforeach
                                </ul>
                            @else
                                --
                            @endif
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center;">
                                <a href="{{ route('empleados.show', $empleado) }}" class="text-green-600 hover:text-green-800 mx-2" title="Ver Detalle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('empleados.edit', $empleado) }}" class="text-blue-500 hover:text-blue-700 mx-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('empleados.destroy', $empleado) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de que desea eliminar este empleado?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 mx-2">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $empleados->links() }}
    </div>
</div>
@endsection
