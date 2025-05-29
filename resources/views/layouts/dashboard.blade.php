<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Nómina - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col md:flex-row" x-data="{ sidebarOpen: false, adminOpen: false }">
        <!-- Mobile menu button -->
        <div class="md:hidden bg-gray-800 p-4">
            <button @click="sidebarOpen = !sidebarOpen" class="text-white">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Sidebar -->
        <div :class="{'hidden': !sidebarOpen}" class="md:block bg-gray-800 text-white w-full md:w-64 py-4 flex-shrink-0">
            <div class="px-4">
                <h1 class="text-2xl font-bold mb-8">SisNómina</h1>
            </div>
            <nav class="mt-4">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>
                @if(auth()->user()->isAdmin())
                <!-- Administración Dropdown -->
                <div class="relative">
                    <button @click="adminOpen = !adminOpen" class="w-full flex items-center px-4 py-2 hover:bg-gray-700 {{ request()->routeIs(['users.*', 'cargos.*', 'departamentos.*', 'horarios.*', 'estados.*', 'prima-antiguedad.*', 'prima-profesionalizacion.*', 'niveles-rangos.*', 'grupos-cargos.*', 'remuneraciones.*']) ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-cogs mr-2"></i>
                        <span>Administración</span>
                        <i class="fas fa-chevron-down ml-auto" :class="{'transform rotate-180': adminOpen}"></i>
                    </button>
                    <div x-show="adminOpen" class="pl-4">
                        <a href="{{ route('users.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('users.*') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-users mr-2"></i> Usuarios
                        </a>
                        <a href="{{ route('cargos.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('cargos.*') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-briefcase mr-2"></i> Cargos
                        </a>
                        <a href="{{ route('departamentos.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('departamentos.*') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-building mr-2"></i> Departamentos
                        </a>
                        <a href="{{ route('horarios.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('horarios.*') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-clock mr-2"></i> Horarios
                        </a>
                        <a href="{{ route('estados.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('estados.*') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-toggle-on mr-2"></i> Estados
                        </a>
                        <a href="{{ route('prima-antiguedad.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('prima-antiguedad.*') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-hourglass mr-2"></i> Prima de Antigüedad
                        </a>
                        <a href="{{ route('prima-profesionalizacion.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('prima-profesionalizacion.*') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-graduation-cap mr-2"></i> Prima de Profesionalización
                        </a>
                        <a href="{{ route('niveles-rangos.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('niveles-rangos.*') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-layer-group mr-2"></i> Niveles de Rangos
                        </a>
                        <a href="{{ route('grupos-cargos.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('grupos-cargos.*') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-object-group mr-2"></i> Grupos o Clases de Cargo
                        </a>
                        <a href="{{ route('remuneraciones.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('remuneraciones.*') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-money-bill-alt mr-2"></i> Remuneraciones
                        </a>
                        <a href="{{ route('deducciones.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('deducciones.*') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-minus-circle mr-2"></i> Deducciones
                        </a>
                    </div>
                </div>
                <a href="{{ route('empleados.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('empleados.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-user-tie mr-2"></i> Empleados
                </a>
                <a href="{{ route('nominas.index') }}" class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('nominas.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-money-bill-wave mr-2"></i> Nómina
                </a>
                <a href="#" class="block px-4 py-2 hover:bg-gray-700">
                    <i class="fas fa-chart-bar mr-2"></i> Reportes
                </a>
                @endif
            </nav>
        </div>

        <!-- Content -->
        <div class="flex-1">
            <!-- Top Navigation -->
            <div class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-800">@yield('header')</h2>
                        </div>
                        <div class="flex items-center">
                            <div class="relative" x-data="{ open: false }">
                                <div class="flex items-center">
                                    <button @click="open = !open" class="flex items-center text-gray-700 hover:text-gray-900 focus:outline-none">
                                        <span class="hidden md:inline mr-2">{{ auth()->user()->nombre }}</span>
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    <a href="{{ route('users.edit', ['user' => auth()->id()]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i> Mi Perfil
                                    </a>
                                    <form action="{{ route('logout') }}" method="POST" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                    {{ session('error') }}
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    <script src="//unpkg.com/alpinejs" defer></script>
    @stack('scripts')
</body>
</html>
