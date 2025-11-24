@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<!-- Acciones Rápidas -->
<div>
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @if(auth()->user()->isAdmin())
        <a href="{{ route('users.create') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                    <i class="fas fa-user-plus text-xl text-blue-500"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-medium text-gray-900">Nuevo Usuario</h4>
                    <p class="text-gray-500">Registrar un nuevo usuario</p>
                </div>
            </div>
        </a>
        @endif

        <a href="{{ route('users.edit', ['user' => auth()->id()]) }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                    <i class="fas fa-user-edit text-xl text-green-500"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-medium text-gray-900">Mi Perfil</h4>
                    <p class="text-gray-500">Actualizar información personal</p>
                </div>
            </div>
        </a>

        @if(auth()->user()->isAdmin())
        <a href="#" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                    <i class="fas fa-file-invoice-dollar text-xl text-purple-500"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-medium text-gray-900">Nueva Nómina</h4>
                    <p class="text-gray-500">Generar nómina del periodo</p>
                </div>
            </div>
        </a>
        @endif
    </div>
</div>
@endsection
