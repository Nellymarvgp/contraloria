@extends('layouts.dashboard')

@section('title', 'Vacaciones por Pagar')
@section('header', 'Vacaciones por Pagar')

@section('content')
<div x-data="{
        openModal: false,
        empleadoNombre: '',
        periodoPagar: '',
        departamento: '',
        cargo: '',
        montoPagar: '',
        diasBase: 15,
        diasAdicionales: 0,
        totalDias: 15,
        setFromButton(el) {
            const sueldo = parseFloat(el.dataset.sueldo || '0');
            const fechaIngreso = el.dataset.fechaingreso ? new Date(el.dataset.fechaingreso) : null;
            const currentYear = new Date().getFullYear();
            
            // Calcular años de servicio
            let aniosServicio = 0;
            if (fechaIngreso) {
                const diffInMs = Date.now() - fechaIngreso.getTime();
                const ageDate = new Date(diffInMs);
                aniosServicio = Math.abs(ageDate.getUTCFullYear() - 1970);
            }
            
            // Calcular días adicionales: 1 día por cada 5 años completos de servicio
            this.diasAdicionales = 0;
            if (aniosServicio > 5) {
                this.diasAdicionales = Math.floor((aniosServicio - 1) / 5);
            }
            
            this.totalDias = this.diasBase + this.diasAdicionales;
            
            this.empleadoNombre = el.dataset.nombre || '';
            this.departamento = el.dataset.departamento || '';
            this.cargo = el.dataset.cargo || '';
            this.periodoPagar = (currentYear - 1) + ' - ' + currentYear;
            this.montoPagar = (sueldo / 30 * this.totalDias).toFixed(2);
            
            this.openModal = true;
            this.$nextTick(() => {
                if (this.$refs.pagarForm) {
                    this.$refs.pagarForm.action = el.dataset.action;
                }
            });
        }
    }" class="mb-6 flex flex-col">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-600">Listado de empleados con vacaciones pendientes de pago</p>
        </div>
        <a href="{{ route('vacaciones.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center transition duration-150">
            <i class="fas fa-arrow-left mr-2"></i> Volver a solicitudes
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200" id="vacacionesPorPagarTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empleado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cédula</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cargo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departamento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($empleados as $empleado)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ optional($empleado->user)->nombre }} {{ optional($empleado->user)->apellido }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $empleado->cedula }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ optional($empleado->cargo)->nombre ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ optional($empleado->departamento)->nombre ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button
                            type="button"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs inline-flex items-center"
                            data-action="{{ route('vacaciones.pagar_pendiente', $empleado) }}"
                            data-nombre="{{ trim((optional($empleado->user)->nombre . ' ' . optional($empleado->user)->apellido)) }}"
                            data-departamento="{{ optional($empleado->departamento)->nombre ?? 'N/A' }}"
                            data-cargo="{{ optional($empleado->cargo)->nombre ?? 'N/A' }}"
                            data-sueldo="{{ $empleado->salario }}"
                            data-fechaingreso="{{ $empleado->fecha_ingreso }}"
                            @click="setFromButton($event.currentTarget)"
                        >
                            <i class="fas fa-money-bill-wave mr-1"></i> Pagar
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">
                        No hay empleados con vacaciones pendientes de pago.
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <!-- Modal pago de vacaciones -->
<div
    x-show="openModal"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
>
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Pago de Vacaciones</h3>
            <button type="button" class="text-gray-500 hover:text-gray-700" @click="openModal = false">&times;</button>
        </div>

        <form method="POST" x-ref="pagarForm" target="_blank" @submit="openModal = false; setTimeout(() => window.location.reload(), 800)">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <p class="mt-1 text-sm text-gray-900" x-text="empleadoNombre"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Periodo a Pagar</label>
                    <p class="mt-1 text-sm text-gray-900" x-text="periodoPagar"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Departamento</label>
                    <p class="mt-1 text-sm text-gray-900" x-text="departamento"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Cargo</label>
                    <p class="mt-1 text-sm text-gray-900" x-text="cargo"></p>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Días base:</span>
                        <span class="text-sm text-gray-900" x-text="diasBase + ' días'"></span>
                    </div>
                    <div class="flex justify-between" x-show="diasAdicionales > 0">
                        <span class="text-sm font-medium text-gray-700">Días adicionales:</span>
                        <span class="text-sm text-gray-900" x-text="diasAdicionales + ' días'"></span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
                        <span class="text-sm font-medium text-gray-700">Total días:</span>
                        <span class="text-sm font-semibold text-gray-900" x-text="totalDias + ' días'"></span>
                    </div>
                    <div class="flex justify-between pt-2">
                        <span class="text-sm font-medium text-gray-700">Monto a pagar:</span>
                        <span class="text-sm font-semibold text-gray-900" x-text="'Bs. ' + parseFloat(montoPagar).toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        (Sueldo / 30) × Días = (Bs. <span x-text="(parseFloat(montoPagar) / totalDias * 30).toFixed(2)"></span> / 30) × <span x-text="totalDias"></span> días
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50" @click="openModal = false">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">Confirmar pago</button>
            </div>
        </form>
    </div>
</div>

@push('datatable-scripts')
<script>
$(document).ready(function() {
    $('#vacacionesPorPagarTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        responsive: true
    });
});
</script>
@endpush
@endsection
