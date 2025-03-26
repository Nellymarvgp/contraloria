@extends('layouts.dashboard')

@section('title', 'Editar Noticia')
@section('header', 'Editar Noticia')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="{{ route('noticias.update', $noticia) }}" method="POST" enctype="multipart/form-data" id="editNoticiaForm">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="titulo">
                    Título
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('titulo') border-red-500 @enderror"
                    id="titulo" type="text" name="titulo" value="{{ old('titulo', $noticia->titulo) }}" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="contenido">
                    Contenido
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('contenido') border-red-500 @enderror"
                    id="contenido" name="contenido" rows="6" required>{{ old('contenido', $noticia->contenido) }}</textarea>
            </div>

            @if($noticia->imagen)
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Imagen Actual
                    </label>
                    <img src="{{ asset('storage/' . $noticia->imagen) }}" 
                         alt="{{ $noticia->titulo }}"
                         class="w-48 h-48 object-cover rounded">
                </div>
            @endif

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="imagen">
                    Nueva Imagen
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('imagen') border-red-500 @enderror"
                    id="imagen" type="file" name="imagen" accept="image/*">
                <p class="text-sm text-gray-500 mt-1">Formatos permitidos: JPG, PNG. Tamaño máximo: 2MB</p>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="publicado" value="1" class="form-checkbox h-5 w-5 text-blue-600" 
                           {{ old('publicado', $noticia->publicado) ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Publicado</span>
                </label>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Actualizar Noticia
                </button>
                <a href="{{ route('noticias.index') }}" class="text-gray-600 hover:text-gray-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('editNoticiaForm').addEventListener('submit', function(e) {
    const titulo = document.getElementById('titulo').value.trim();
    const contenido = document.getElementById('contenido').value.trim();
    
    if (!titulo) {
        e.preventDefault();
        alert('El título es requerido');
        return;
    }
    
    if (!contenido) {
        e.preventDefault();
        alert('El contenido es requerido');
        return;
    }
});
</script>
@endsection
