@extends('layouts.dashboard')

@section('title', $noticia->titulo)
@section('header', $noticia->titulo)

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @if($noticia->imagen)
            <div class="mb-6">
                <img src="{{ asset('storage/' . $noticia->imagen) }}" 
                     alt="{{ $noticia->titulo }}"
                     class="w-full h-64 object-cover rounded-lg">
            </div>
        @endif

        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="text-sm text-gray-600">
                        <p>Por: {{ $noticia->user->name }}</p>
                        <p>Publicado: {{ $noticia->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @if(auth()->user()->can('update', $noticia))
                    <a href="{{ route('noticias.edit', $noticia) }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Editar Noticia
                    </a>
                @endif
            </div>

            <div class="prose max-w-none">
                {!! nl2br(e($noticia->contenido)) !!}
            </div>
        </div>

        <div class="mt-8 pt-6 border-t">
            <a href="{{ route('noticias.index') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Volver a Noticias
            </a>
        </div>
    </div>
</div>
@endsection
