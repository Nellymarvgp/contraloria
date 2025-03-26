@extends('layouts.dashboard')

@section('title', 'Horarios')
@section('header', 'Gestión de Horarios')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Lista de Horarios</h2>
        <a href="{{ route('horarios.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-plus mr-2"></i>Nuevo Horario
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
                    <th class="py-3 px-6 text-left">Nombre</th>
                    <th class="py-3 px-6 text-left">Hora Entrada</th>
                    <th class="py-3 px-6 text-left">Hora Salida</th>
                    <th class="py-3 px-6 text-left">Descripción</th>
                    <th class="py-3 px-6 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @foreach($horarios as $horario)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6">{{ $horario->nombre }}</td>
                        <td class="py-3 px-6">{{ \Carbon\Carbon::parse($horario->hora_entrada)->format('h:i A') }}</td>
                        <td class="py-3 px-6">{{ \Carbon\Carbon::parse($horario->hora_salida)->format('h:i A') }}</td>
                        <td class="py-3 px-6">{{ $horario->descripcion ?? 'N/A' }}</td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center">
                                <a href="{{ route('horarios.edit', $horario) }}" class="text-blue-500 hover:text-blue-700 mx-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('horarios.destroy', $horario) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de que desea eliminar este horario?');">
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
        {{ $horarios->links() }}
    </div>
</div>
@endsection
