@extends('layouts.dashboard')

@section('title', 'Detalle de Nómina')
@section('header', 'Detalle de Nómina: ' . $nomina->descripcion)

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById('detalleModal' + id).classList.remove('hidden');
    }
</script>
@endpush

@section('content')
<div class="flex justify-end space-x-2 mb-4">
    <a href="{{ route('nominas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Volver
    </a>
    <a href="{{ route('nominas.exportPdf', $nomina) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center">
        <i class="fas fa-file-pdf mr-2"></i> Exportar PDF
    </a>
    <a href="{{ route('nominas.descargar.recibos', $nomina->id) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center" target="_blank">
        <i class="fas fa-file-pdf mr-2"></i> Descargar todos los recibos
    </a>
</div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <div class="flex">
                <div class="py-1"><i class="fas fa-check-circle mr-2"></i></div>
                <div>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
            <div class="flex">
                <div class="py-1"><i class="fas fa-exclamation-circle mr-2"></i></div>
                <div>
                    <p class="text-sm">{{ session('warning') }}</p>
                </div>
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <div class="flex">
                <div class="py-1"><i class="fas fa-exclamation-triangle mr-2"></i></div>
                <div>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4" role="alert">
            <div class="flex">
                <div class="py-1"><i class="fas fa-info-circle mr-2"></i></div>
                <div>
                    {{ session('info') }}
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b">
                <h3 class="text-lg font-medium text-gray-900">Información General</h3>
            </div>
            <div class="p-4">
                <table class="min-w-full">
                    <tr class="border-b">
                        <th class="py-2 text-left text-sm font-medium text-gray-700 w-2/5">ID:</th>
                        <td class="py-2 text-sm text-gray-900">{{ $nomina->id }}</td>
                    </tr>
                    <tr class="border-b">
                        <th class="py-2 text-left text-sm font-medium text-gray-700">Estado:</th>
                        <td class="py-2 text-sm text-gray-900">
                            @if($nomina->estado == 'borrador')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Borrador</span>
                            @elseif($nomina->estado == 'aprobada')
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Aprobada</span>
                            @elseif($nomina->estado == 'pagada')
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Pagada</span>
                            @endif
                        </td>
                    </tr>
                    <tr class="border-b">
                        <th class="py-2 text-left text-sm font-medium text-gray-700">Periodo:</th>
                        <td class="py-2 text-sm text-gray-900">{{ $nomina->fecha_inicio->format('d/m/Y') }} - {{ $nomina->fecha_fin->format('d/m/Y') }}</td>
                    </tr>
                    <tr class="border-b">
                        <th class="py-2 text-left text-sm font-medium text-gray-700">Despacho:</th>
                        <td class="py-2 text-sm text-gray-900">{{ $nomina->despacho ?: 'No especificado' }}</td>
                    </tr>
                    <tr class="border-b">
                        <th class="py-2 text-left text-sm font-medium text-gray-700">Creada:</th>
                        <td class="py-2 text-sm text-gray-900">{{ $nomina->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="py-2 text-left text-sm font-medium text-gray-700">Total:</th>
                        <td class="py-2 text-sm font-bold text-gray-900">{{ number_format($nomina->total_monto, 2, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
            @if($nomina->estado == 'borrador')
            <div class="bg-gray-50 px-4 py-3 border-t">
                @if($nomina->detalles->isEmpty())
                    <a href="{{ route('nominas.generate', $nomina) }}" class="w-full flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md">
                        <i class="fas fa-calculator mr-2"></i> Generar Cálculos
                    </a>
                @else
                    <form action="{{ route('nominas.changeStatus', $nomina) }}" method="POST">
                        @csrf
                        <input type="hidden" name="estado" value="aprobada">
                        <button type="submit" class="w-full flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md" onclick="return confirm('¿Estás seguro de aprobar esta nómina?')">
                            <i class="fas fa-check-circle mr-2"></i> Aprobar Nómina
                        </button>
                    </form>
                @endif
            </div>
            @elseif($nomina->estado == 'aprobada')
            <div class="bg-gray-50 px-4 py-3 border-t">
                <form action="{{ route('nominas.changeStatus', $nomina) }}" method="POST">
                    @csrf
                    <input type="hidden" name="estado" value="pagada">
                    <button type="submit" class="w-full flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md" onclick="return confirm('¿Estás seguro de marcar esta nómina como pagada?')">
                        <i class="fas fa-money-bill-wave mr-2"></i> Marcar como Pagada
                    </button>
                </form>
            </div>
            @endif
        </div>
        
        <div class="col-span-2 bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b">
                <h3 class="text-lg font-medium text-gray-900">Resumen</h3>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="bg-gray-50 rounded-lg p-4 text-center border">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Total Empleados</h4>
                        <p class="text-2xl font-bold text-gray-900">{{ $nomina->detalles->count() }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 text-center border">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Total Asignaciones</h4>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($nomina->detalles->sum('total'), 2, ',', '.') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 text-center border">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Total Deducciones</h4>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($nomina->detalles->sum('ret_ivss') + $nomina->detalles->sum('ret_pie') + $nomina->detalles->sum('ret_lph') + $nomina->detalles->sum('ret_fpj'), 2, ',', '.') }}</p>
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-medium text-blue-800">Total Neto a Pagar:</span>
                        <span class="text-2xl font-bold text-blue-800">{{ number_format($nomina->total_monto, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-gray-50 px-4 py-3 border-b">
            <h3 class="text-lg font-medium text-gray-900">Detalles de la Nómina</h3>
        </div>
        <div class="p-4">
            @if($nomina->detalles->isEmpty())
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-0">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                No hay detalles disponibles. Utilice el botón "Generar Cálculos" para crear los detalles de la nómina.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <!-- NOMINA ORDINARIA -->
                <div class="mb-6">
                    <div class="bg-blue-600 text-white px-4 py-3 text-center">
                        <h2 class="text-xl font-bold uppercase">NÓMINA ORDINARIA</h2>
                        <p class="text-sm">{{ $nomina->despacho }}</p>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead>
                                <tr class="bg-green-100">
                                    <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border align-middle">NOMBRE(S) Y APELLIDO(S)</th>
                                    <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border align-middle">N° DE<br>CÉDULA</th>
                                    <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border align-middle">SUELDO<br>BÁSICO</th>
                                    <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border align-middle">PRIMA DE<br>PROFESIO<br>NALIZACIÓN</th>
                                    <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border align-middle">PRIMA<br>DE<br>ANTIGÜEDAD</th>
                                    <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border align-middle">PRIMA<br>POR HIJO</th>
                                    <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border align-middle">Comida</th>
                                    <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border align-middle">Otras<br>primas</th>
                                    <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border align-middle">RET IVSS</th>
                                    <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border align-middle">RET PIE</th>
                                    <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border align-middle">RET LPH</th>
                                    <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border align-middle">RET FPJ</th>
                                    <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border align-middle">ORDINARIA</th>
                                    <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border align-middle">INCENTIVO</th>
                                    <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border align-middle">FERIADO</th>
                                    <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border align-middle">GASTOS DE<br>REPRESENT<br>ACION</th>
                                    <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border align-middle">TOTAL</th>
                                </tr>
                                <tr class="bg-green-100"></tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($nomina->detalles as $index => $detalle)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 cursor-pointer" onclick="openModal({{ $detalle->id }})">
                                        <td class="px-3 py-2 text-sm text-gray-900 border">{{ $detalle->empleado->user->name ?? 'N/A' }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-center border">{{ $detalle->empleado->cedula }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->sueldo_basico, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->prima_profesionalizacion, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->prima_antiguedad, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->prima_por_hijo, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->comida, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->otras_primas, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->ret_ivss, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->ret_pie, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->ret_lph, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->ret_fpj, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->ordinaria, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->incentivo, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->feriado, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->gastos_representacion, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm font-bold text-gray-900 text-right border bg-yellow-50">{{ number_format($detalle->total, 2, '.', ',') }}</td>
                                    </tr>
                                @endforeach
                                <!-- Total row -->
                                <tr class="bg-gray-100 font-bold">
                                    <td colspan="2" class="px-3 py-2 text-sm text-right border">TOTALES:</td>
                                    <td class="px-3 py-2 text-sm text-right border">{{ number_format($nomina->detalles->sum('sueldo_basico'), 2, '.', ',') }}</td>
                                    <td class="px-3 py-2 text-sm text-right border">{{ number_format($nomina->detalles->sum('prima_profesionalizacion'), 2, '.', ',') }}</td>
                                    <td class="px-3 py-2 text-sm text-right border">{{ number_format($nomina->detalles->sum('prima_antiguedad'), 2, '.', ',') }}</td>
                                    <td class="px-3 py-2 text-sm text-right border">{{ number_format($nomina->detalles->sum('prima_por_hijo'), 2, '.', ',') }}</td>
                                    <td class="px-3 py-2 text-sm text-right border">{{ number_format($nomina->detalles->sum('comida'), 2, '.', ',') }}</td>
                                    <td class="px-3 py-2 text-sm text-right border">{{ number_format($nomina->detalles->sum('otras_primas'), 2, '.', ',') }}</td>
                                    <td class="px-3 py-2 text-sm text-right border">{{ number_format($nomina->detalles->sum('ret_ivss'), 2, '.', ',') }}</td>
                                    <td class="px-3 py-2 text-sm text-right border">{{ number_format($nomina->detalles->sum('ret_pie'), 2, '.', ',') }}</td>
                                    <td class="px-3 py-2 text-sm text-right border">{{ number_format($nomina->detalles->sum('ret_lph'), 2, '.', ',') }}</td>
                                    <td class="px-3 py-2 text-sm text-right border">{{ number_format($nomina->detalles->sum('ret_fpj'), 2, '.', ',') }}</td>
                                    <td class="px-3 py-2 text-sm text-right border">{{ number_format($nomina->detalles->sum('ordinaria'), 2, '.', ',') }}</td>
                                    <td class="px-3 py-2 text-sm text-right border">{{ number_format($nomina->detalles->sum('incentivo'), 2, '.', ',') }}</td>
                                    <td class="px-3 py-2 text-sm text-right border">{{ number_format($nomina->detalles->sum('feriado'), 2, '.', ',') }}</td>
                                    <td class="px-3 py-2 text-sm text-right border">{{ number_format($nomina->detalles->sum('gastos_representacion'), 2, '.', ',') }}</td>
                                    <td class="px-3 py-2 text-sm text-right border bg-yellow-100">{{ number_format($nomina->detalles->sum('total'), 2, '.', ',') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- TXT Fields Table -->
                <div class="mb-6">
                    <div class="bg-gray-700 text-white px-4 py-3 text-center">
                        <h2 class="text-lg font-bold">CAMPOS ADICIONALES</h2>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border">TXT 1</th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border">TXT 2</th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border">TXT 3</th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border">TXT 4</th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border">TXT 5</th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border">TXT 6</th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border">TXT 7</th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border">TXT 8</th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border">TXT 9</th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border">TXT 10</th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider text-center border">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($nomina->detalles as $index => $detalle)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100">
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->txt_1, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->txt_2, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->txt_3, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->txt_4, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->txt_5, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->txt_6, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->txt_7, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->txt_8, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->txt_9, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 text-right border">{{ number_format($detalle->txt_10, 2, '.', ',') }}</td>
                                        <td class="px-3 py-2 text-sm font-bold text-gray-900 text-right border">{{ number_format($detalle->total, 2, '.', ',') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center" onclick="window.print()">
                        <i class="fas fa-print mr-2"></i> Imprimir Nómina
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal para cada detalle con Tailwind CSS -->
    @foreach($nomina->detalles as $detalle)
        <div id="detalleModal{{ $detalle->id }}" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="detalleModalLabel{{ $detalle->id }}" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Modal backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                
                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <!-- Modal header -->
                    <div class="bg-blue-600 px-4 py-3 sm:px-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg leading-6 font-medium text-white" id="detalleModalLabel{{ $detalle->id }}">
                                Detalle de Nómina - {{ $detalle->empleado->user->name ?? 'N/A' }}
                            </h3>
                            <button type="button" class="bg-blue-600 rounded-md text-white hover:text-gray-200 focus:outline-none" onclick="document.getElementById('detalleModal{{ $detalle->id }}').classList.add('hidden')">
                                <span class="sr-only">Cerrar</span>
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Modal body -->
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-700 mb-2"><span class="font-semibold">Cédula:</span> {{ $detalle->empleado->cedula }}</p>
                                <p class="text-sm text-gray-700 mb-2"><span class="font-semibold">Cargo:</span> {{ $detalle->empleado->cargo->nombre ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-700 mb-2"><span class="font-semibold">Departamento:</span> {{ $detalle->empleado->departamento->nombre ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-700 mb-2"><span class="font-semibold">Fecha Ingreso:</span> {{ $detalle->empleado->fecha_ingreso->format('d/m/Y') }}</p>
                                <p class="text-sm text-gray-700 mb-2"><span class="font-semibold">Tipo de Cargo:</span> 
                                    @if($detalle->empleado->tipo_cargo == 'administrativo')
                                        Administrativo
                                    @elseif($detalle->empleado->tipo_cargo == 'tecnico_superior')
                                        Técnico Superior Universitario
                                    @elseif($detalle->empleado->tipo_cargo == 'profesional_universitario')
                                        Profesional Universitario
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p class="text-sm text-gray-700 mb-2"><span class="font-semibold">Sueldo Básico:</span> {{ number_format($detalle->sueldo_basico, 2, ',', '.') }}</p>
                            </div>
                        </div>

                        <h4 class="text-base font-semibold text-gray-900 mb-3 border-b pb-2">Detalle de Valores</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Concepto</th>
                                        <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Valor</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm text-gray-900">Sueldo Básico</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 text-right">{{ number_format($detalle->sueldo_basico, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm text-gray-900">Prima de Profesionalización</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 text-right">{{ number_format($detalle->prima_profesionalizacion, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm text-gray-900">Prima de Antigüedad</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 text-right">{{ number_format($detalle->prima_antiguedad, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm text-gray-900">Prima por Hijo</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 text-right">{{ number_format($detalle->prima_por_hijo, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm text-gray-900">Comida</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 text-right">{{ number_format($detalle->comida, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm text-gray-900">Otras Primas</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 text-right">{{ number_format($detalle->otras_primas, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="bg-red-50 hover:bg-red-100">
                                        <td class="px-4 py-2 text-sm text-red-700">Retención IVSS</td>
                                        <td class="px-4 py-2 text-sm text-red-700 text-right">{{ number_format($detalle->ret_ivss, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="bg-red-50 hover:bg-red-100">
                                        <td class="px-4 py-2 text-sm text-red-700">Retención PIE</td>
                                        <td class="px-4 py-2 text-sm text-red-700 text-right">{{ number_format($detalle->ret_pie, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="bg-red-50 hover:bg-red-100">
                                        <td class="px-4 py-2 text-sm text-red-700">Retención LPH</td>
                                        <td class="px-4 py-2 text-sm text-red-700 text-right">{{ number_format($detalle->ret_lph, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="bg-red-50 hover:bg-red-100">
                                        <td class="px-4 py-2 text-sm text-red-700">Retención FPJ</td>
                                        <td class="px-4 py-2 text-sm text-red-700 text-right">{{ number_format($detalle->ret_fpj, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm text-gray-900">Ordinaria</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 text-right">{{ number_format($detalle->ordinaria, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm text-gray-900">Incentivo</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 text-right">{{ number_format($detalle->incentivo, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm text-gray-900">Feriado</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 text-right">{{ number_format($detalle->feriado, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm text-gray-900">Gastos de Representación</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 text-right">{{ number_format($detalle->gastos_representacion, 2, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-100 font-semibold">
                                        <th class="px-4 py-2 text-sm text-gray-900 text-right">Total:</th>
                                        <th class="px-4 py-2 text-sm text-gray-900 text-right">{{ number_format($detalle->total, 2, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Modal footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-gray-800 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="document.getElementById('detalleModal{{ $detalle->id }}').classList.add('hidden')">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
