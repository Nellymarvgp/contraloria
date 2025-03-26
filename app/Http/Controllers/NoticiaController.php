<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NoticiaController extends Controller
{
    public function index()
    {
        $noticias = Noticia::where('publicado', true)
            ->orderBy('created_at', 'desc')
            ->paginate(9);
        return view('noticias.index', compact('noticias'));
    }

    public function create()
    {
        return view('noticias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|max:255',
            'contenido' => 'required',
            'imagen' => 'nullable|image|max:2048',
            'publicado' => 'boolean'
        ]);

        $noticia = new Noticia($request->all());
        $noticia->user_id = auth()->id();
        $noticia->slug = Str::slug($request->titulo);

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('noticias', 'public');
            $noticia->imagen = $path;
        }

        $noticia->save();

        return redirect()->route('noticias.index')
            ->with('success', 'Noticia creada exitosamente.');
    }

    public function show($slug)
    {
        $noticia = Noticia::where('slug', $slug)->firstOrFail();
        return view('noticias.show', compact('noticia'));
    }

    public function edit(Noticia $noticia)
    {
        return view('noticias.edit', compact('noticia'));
    }

    public function update(Request $request, Noticia $noticia)
    {
        $request->validate([
            'titulo' => 'required|max:255',
            'contenido' => 'required',
            'imagen' => 'nullable|image|max:2048',
            'publicado' => 'boolean'
        ]);

        if ($request->titulo !== $noticia->titulo) {
            $noticia->slug = Str::slug($request->titulo);
        }

        if ($request->hasFile('imagen')) {
            if ($noticia->imagen) {
                Storage::disk('public')->delete($noticia->imagen);
            }
            $path = $request->file('imagen')->store('noticias', 'public');
            $noticia->imagen = $path;
        }

        $noticia->update($request->except('imagen'));

        return redirect()->route('noticias.index')
            ->with('success', 'Noticia actualizada exitosamente.');
    }

    public function destroy(Noticia $noticia)
    {
        if ($noticia->imagen) {
            Storage::disk('public')->delete($noticia->imagen);
        }
        
        $noticia->delete();

        return redirect()->route('noticias.index')
            ->with('success', 'Noticia eliminada exitosamente.');
    }
}
