@extends('layouts.dashboard')

@section('title', 'Recibos')
@section('header', 'Mis Recibos de Nómina')

@section('content')
<div class="bg-white shadow rounded-lg p-6 space-y-8">
    <div>
        <h2 class="text-xl font-semibold mb-4">Recibos de Nómina</h2>

        @if($detalles->isEmpty())
            <p class="text-gray-600">No se encontraron recibos de nómina asociados a tu usuario.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nómina</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periodo</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($detalles as $detalle)
                            @if(!$detalle->nomina)
                                @continue
                            @endif
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    {{ $detalle->nomina->descripcion ?? ('Nómina #' . $detalle->nomina->id) }}
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    {{ $detalle->nomina->fecha_inicio->format('d/m/Y') }} - {{ $detalle->nomina->fecha_fin->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    <a href="{{ route('recibos.show', $detalle) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded hover:bg-blue-700">
                                        <i class="fas fa-file-pdf mr-1"></i> Visualizar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div>
        <h2 class="text-xl font-semibold mb-4">Pagos de Vacaciones</h2>

        @if($pagosVacaciones->isEmpty())
            <p class="text-gray-600">No se encontraron pagos de vacaciones registrados.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periodo</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de pago</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pagosVacaciones as $pago)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $pago->periodo }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($pago->monto, 2, ',', '.') }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $pago->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    <a href="{{ route('recibos.vacaciones.show', $pago) }}" class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700">
                                        <i class="fas fa-file-pdf mr-1"></i> Visualizar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
