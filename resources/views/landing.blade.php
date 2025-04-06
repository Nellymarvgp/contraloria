<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contraloría Municipal de Independencia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-900 text-white">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="Logo CMI" class="h-20">
                    <div class="ml-4">
                        <h1 class="text-2xl font-bold">Contraloría Municipal</h1>
                        <h2 class="text-lg">Municipio Independencia - Yaracuy</h2>
                    </div>
                </div>
                <nav class="hidden md:block">
                    <ul class="flex space-x-6">
                        <li><a href="#inicio" class="hover:text-yellow-300">Inicio</a></li>
                        <li><a href="#sistema" class="hover:text-yellow-300">Sistema</a></li>
                        <li><a href="#historia" class="hover:text-yellow-300">Historia</a></li>
                        <li><a href="#noticias" class="hover:text-yellow-300">Noticias</a></li>
                        <li><a href="#ubicacion" class="hover:text-yellow-300">Ubicación</a></li>
                        <li><a href="{{ route('login') }}" class="bg-yellow-500 text-blue-900 px-4 py-2 rounded-lg hover:bg-yellow-400">
                            Iniciar Sesión
                        </a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="inicio" class="relative bg-blue-900 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/baner.png') }}" alt="Sistema de Gestión" class="w-full h-full object-cover opacity-20">
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">Sistema Integral de Gestión Municipal</h1>
                    <p class="text-xl mb-8">Innovación y eficiencia en la gestión de recursos humanos y control fiscal del Municipio Independencia.</p>
                    <div class="flex space-x-4">
                        <a href="{{ route('register') }}" class="bg-yellow-500 text-blue-900 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-yellow-400 transition duration-300">
                            Registrarse
                        </a>
                        <a href="#sistema" class="border-2 border-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-white hover:text-blue-900 transition duration-300">
                            Conoce más
                        </a>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <!-- Sistema Section -->
    <section id="sistema" class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-blue-900">Sistema de Gestión</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Gestión de Personal</h3>
                    <p class="text-gray-600">Control eficiente de empleados, horarios, cargos y departamentos. Seguimiento detallado del personal.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Estadísticas y Reportes</h3>
                    <p class="text-gray-600">Generación de informes detallados y análisis estadísticos para una mejor toma de decisiones.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Control y Seguridad</h3>
                    <p class="text-gray-600">Sistema seguro con roles y permisos, garantizando la integridad de la información.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Historia Section -->
    <section id="historia" class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-blue-900">Nuestra Historia</h2>
            <div class="max-w-4xl mx-auto prose prose-lg">
                <p class="mb-6">La Contraloría Municipal de Independencia fue creada mediante Ordenanza de la Contraloría del Municipio Independencia del Estado Yaracuy, Gaceta Municipal N° 140 de fecha 05 de Julio de 2006, funcionando como un órgano de control en el marco del artículo 176 de la Constitución de la República Bolivariana de Venezuela.</p>
                
                <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                    <h3 class="text-xl font-semibold mb-4">Evolución y Liderazgo</h3>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Inicialmente dirigida por la Lcda. Derlis Cuevas como contralora municipal encargada</li>
                        <li>Posteriormente asumió el Lcdo. Orlando Rivas hasta 2014</li>
                        <li>Desde 2014, el Lcdo. Esp. y Abogado José Pastor Pérez Tovar lidera la institución</li>
                    </ul>
                </div>

                <p class="mb-6">Durante su gestión, se han realizado importantes cambios estructurales y organizativos, incluyendo:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Rediseño del reglamento interno</li>
                    <li>Nuevos manuales administrativos y operativos</li>
                    <li>Implementación del programa Contraloría escolar</li>
                    <li>Apoyo a los consejos comunales</li>
                    <li>Capacitación en materia de contraloría social</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Noticias Section -->
    <section id="noticias" class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-blue-900">Últimas Noticias</h2>
            <div class="grid md:grid-cols-3 gap-8">
                @forelse($noticias as $noticia)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        @if($noticia->imagen)
                            <img src="{{ asset('storage/' . $noticia->imagen) }}" alt="{{ $noticia->titulo }}" class="w-full h-48 object-cover">
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-2">{{ $noticia->titulo }}</h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($noticia->contenido, 150) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">{{ $noticia->created_at->format('d/m/Y') }}</span>
                                <a href="{{ route('noticias.show', $noticia->slug) }}" class="text-blue-600 hover:text-blue-800">
                                    Leer más
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-3">
                        <p class="text-center text-gray-600">No hay noticias publicadas en este momento.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Ubicación Section -->
    <section id="ubicacion" class="bg-gray-100 py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-blue-900">Ubicación</h2>
            <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">
                <div class="flex items-start space-x-4">
                    <i class="fas fa-map-marker-alt text-3xl text-blue-900"></i>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Dirección</h3>
                        <p class="text-gray-600">Av Libertador entre calles 22 y 23</p>
                        <p class="text-gray-600">Edificio Sandro, Piso 2</p>
                        <p class="text-gray-600">Oficinas 2.7 y 2.8</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-900 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-center md:text-left mb-4 md:mb-0">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="Logo CMI" class="h-16 mx-auto md:mx-0">
                    <p class="mt-2">Contraloría Municipal de Independencia</p>
                </div>
                <div class="text-center md:text-right">
                    <p> {{ date('Y') }} Todos los derechos reservados</p>
                    <p>Estado Yaracuy, Venezuela</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
