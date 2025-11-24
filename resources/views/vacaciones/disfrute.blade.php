@extends('layouts.dashboard')

@section('title', 'Vacaciones por Disfrute')
@section('header', 'Vacaciones por Disfrute')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Resumen de Vacaciones por Disfrute</h2>
        <a href="{{ route('vacaciones.index') }}" class="text-blue-600 hover:text-blue-800">&larr; Volver a solicitudes</a>
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

    @if($resumen->isEmpty())
        <p class="text-gray-600">No hay registros de vacaciones por disfrute asignados.</p>
    @else
        <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
            <table class="min-w-full bg-white" id="disfruteTable">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Cédula</th>
                        <th class="py-3 px-6 text-left">Nombre</th>
                        <th class="py-3 px-6 text-left">Días Asignados</th>
                        <th class="py-3 px-6 text-left">Días Tomados</th>
                        <th class="py-3 px-6 text-left">Días Restantes</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @foreach($resumen as $item)
                        @php
                            $empleado = $item['empleado'];
                        @endphp
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6">{{ $empleado->cedula }}</td>
                            <td class="py-3 px-6">{{ $empleado->user->nombre ?? '' }} {{ $empleado->user->apellido ?? '' }}</td>
                            <td class="py-3 px-6">{{ $item['dias_asignados'] }}</td>
                            <td class="py-3 px-6">{{ $item['dias_tomados'] }}</td>
                            <td class="py-3 px-6 font-semibold {{ $item['dias_restantes'] > 0 ? 'text-green-700' : 'text-gray-500' }}">{{ $item['dias_restantes'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

@push('datatable-scripts')
<script>
$(document).ready(function() {
    $('#disfruteTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        responsive: true
    });
});
</script>
@endpush
