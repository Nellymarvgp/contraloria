@extends('layouts.dashboard')

@section('title', 'Gestión de Noticias')
@section('header', 'Gestión de Noticias')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Lista de Noticias</h2>
        <a href="{{ route('noticias.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-plus mr-2"></i>Nueva Noticia
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-700 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Título</th>
                    <th class="py-3 px-6 text-left">Autor</th>
                    <th class="py-3 px-6 text-center">Estado</th>
                    <th class="py-3 px-6 text-center">Fecha</th>
                    <th class="py-3 px-6 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @foreach($noticias as $noticia)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left">
                            <div class="flex items-center">
                                @if($noticia->imagen)
                                    <div class="w-10 h-10 mr-3">
                                        <img src="{{ asset('storage/' . $noticia->imagen) }}" 
                                             alt="{{ $noticia->titulo }}"
                                             class="w-10 h-10 rounded-full object-cover">
                                    </div>
                                @endif
                                <span>{{ $noticia->titulo }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-6 text-left">{{ $noticia->user->name }}</td>
                        <td class="py-3 px-6 text-center">
                            <span class="px-3 py-1 rounded-full text-xs {{ $noticia->publicado ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                {{ $noticia->publicado ? 'Publicado' : 'Borrador' }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            {{ $noticia->created_at->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center">
                                <a href="{{ route('noticias.edit', $noticia) }}" 
                                   class="text-blue-500 hover:text-blue-700 mx-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('noticias.destroy', $noticia) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('¿Está seguro de que desea eliminar esta noticia?');">
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
        {{ $noticias->links() }}
    </div>
</div>
@endsection
