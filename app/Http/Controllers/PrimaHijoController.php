<?php

namespace App\Http\Controllers;

use App\Models\PrimaHijo;
use Illuminate\Http\Request;

class PrimaHijoController extends Controller
{
    public function index()
    {
        $primas = PrimaHijo::all();
        return view('prima-hijo.index', compact('primas'));
    }

    public function create()
    {
        return view('prima-hijo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'hijos' => 'required|integer|min:1',
            'porcentaje' => 'required|numeric|min:0|max:100',
        ]);

        PrimaHijo::create([
            'hijos' => $request->hijos,
            'porcentaje' => $request->porcentaje,
            'estado' => $request->has('estado') ? 1 : 0,
        ]);

        return redirect()->route('prima-hijo.index')
            ->with('success', 'Prima por hijo creada correctamente.');
    }

    public function edit(string $id)
    {
        $prima = PrimaHijo::findOrFail($id);
        return view('prima-hijo.edit', compact('prima'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'hijos' => 'required|integer|min:1',
            'porcentaje' => 'required|numeric|min:0|max:100',
        ]);

        $prima = PrimaHijo::findOrFail($id);
        $prima->update([
            'hijos' => $request->hijos,
            'porcentaje' => $request->porcentaje,
            'estado' => $request->has('estado') ? 1 : 0,
        ]);

        return redirect()->route('prima-hijo.index')
            ->with('success', 'Prima por hijo actualizada correctamente.');
    }

    public function destroy(string $id)
    {
        $prima = PrimaHijo::findOrFail($id);
        $prima->delete();

        return redirect()->route('prima-hijo.index')
            ->with('success', 'Prima por hijo eliminada correctamente.');
    }
}
