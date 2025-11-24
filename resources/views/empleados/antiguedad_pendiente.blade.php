@extends('layouts.dashboard')

@section('title', 'Antigüedad Pendiente')
@section('header', 'Empleados con Antigüedad Pendiente')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Empleados que cumplieron año de servicio</h2>
        <a href="{{ route('empleados.index') }}" class="text-blue-600 hover:text-blue-800">&larr; Volver a empleados</a>
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

    @if($pendientes->isEmpty())
        <p class="text-gray-600">No hay empleados con antigüedad pendiente de actualizar.</p>
    @else
        <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Cédula</th>
                        <th class="py-3 px-6 text-left">Nombre</th>
                        <th class="py-3 px-6 text-left">Fecha Ingreso</th>
                        <th class="py-3 px-6 text-left">Años Reales</th>
                        <th class="py-3 px-6 text-left">Años Registrados</th>
                        <th class="py-3 px-6 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @foreach($pendientes as $empleado)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6">{{ $empleado->cedula }}</td>
                            <td class="py-3 px-6">{{ $empleado->user->nombre ?? '' }} {{ $empleado->user->apellido ?? '' }}</td>
                            <td class="py-3 px-6">{{ optional($empleado->fecha_ingreso)->format('d/m/Y') }}</td>
                            <td class="py-3 px-6">{{ (int) $empleado->anios_reales }}</td>
                            <td class="py-3 px-6">{{ $empleado->tiempo_antiguedad ?? 0 }}</td>
                            <td class="py-3 px-6 text-center">
                                <form action="{{ route('empleados.actualizar.antiguedad', $empleado) }}" method="POST" onsubmit="return confirm('¿Actualizar antigüedad y días por disfrute para este empleado?');">
                                    @csrf
                                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-semibold py-1 px-3 rounded">
                                        Actualizar Antigüedad
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
