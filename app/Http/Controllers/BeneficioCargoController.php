<?php

namespace App\Http\Controllers;

use App\Models\Beneficio;
use App\Models\BeneficioCargo;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BeneficioCargoController extends Controller
{
    public function index()
    {
        $beneficiosCargo = BeneficioCargo::with('beneficio')->get();
        $beneficios = Beneficio::all();

        return view('config.beneficios_cargo.index', compact('beneficiosCargo', 'beneficios'));
    }

    public function create()
    {
        $beneficios = Beneficio::all();
        $cargos = Cargo::all();

        return view('config.beneficios_cargo.create', compact('beneficios', 'cargos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'beneficio_id' => 'required|exists:beneficios,id',
            'cargo' => 'required|string|max:255',
            'porcentaje' => 'nullable|numeric',
            'valor' => 'nullable|numeric',
        ]);

        BeneficioCargo::create($validated);

        return redirect()->route('beneficios-cargo.index')
            ->with('success', 'Beneficio por cargo creado correctamente.');
    }

    public function storeBeneficio(Request $request)
    {
        $validated = $request->validate([
            'beneficio' => 'required|string|max:255',
            'fecha_beneficio' => 'nullable|integer|min:1|max:31',
        ]);

        // Convertir el día recibido a una fecha dummy (solo se usa el día luego en la nómina)
        $data = [
            'beneficio' => $validated['beneficio'],
            'fecha_beneficio' => null,
        ];

        if (!empty($validated['fecha_beneficio'])) {
            $dia = (int) $validated['fecha_beneficio'];
            $data['fecha_beneficio'] = Carbon::create(2000, 1, $dia)->toDateString();
        }

        // Verificación adicional por si existen diferencias de mayúsculas/minúsculas
        if (Beneficio::whereRaw('LOWER(beneficio) = ?', [mb_strtolower($validated['beneficio'])])->exists()) {
            return redirect()->route('beneficios-cargo.index')
                ->with('error', 'Ya existe un beneficio con ese mismo nombre.');
        }

        Beneficio::create($data);

        return redirect()->route('beneficios-cargo.index')
            ->with('success', 'Beneficio creado correctamente.');
    }

    public function edit(BeneficioCargo $beneficios_cargo)
    {
        $beneficios = Beneficio::all();
        $cargos = Cargo::all();

        return view('config.beneficios_cargo.edit', [
            'beneficioCargo' => $beneficios_cargo,
            'beneficios' => $beneficios,
            'cargos' => $cargos,
        ]);
    }

    public function update(Request $request, BeneficioCargo $beneficios_cargo)
    {
        $validated = $request->validate([
            'beneficio_id' => 'required|exists:beneficios,id',
            'cargo' => 'required|string|max:255',
            'porcentaje' => 'nullable|numeric',
            'valor' => 'nullable|numeric',
        ]);

        $beneficios_cargo->update($validated);

        return redirect()->route('beneficios-cargo.index')
            ->with('success', 'Beneficio por cargo actualizado correctamente.');
    }

    public function destroy(BeneficioCargo $beneficios_cargo)
    {
        $beneficios_cargo->delete();

        return redirect()->route('beneficios-cargo.index')
            ->with('success', 'Beneficio por cargo eliminado correctamente.');
    }

    public function editBeneficio(Beneficio $beneficio)
    {
        return view('config.beneficios.edit', compact('beneficio'));
    }

    public function updateBeneficio(Request $request, Beneficio $beneficio)
    {
        $validated = $request->validate([
            'beneficio' => 'required|string|max:255',
            'fecha_beneficio' => 'nullable|integer|min:1|max:31',
        ]);

        $data = [
            'beneficio' => $validated['beneficio'],
            'fecha_beneficio' => null,
        ];

        if (!empty($validated['fecha_beneficio'])) {
            $dia = (int) $validated['fecha_beneficio'];
            $data['fecha_beneficio'] = Carbon::create(2000, 1, $dia)->toDateString();
        }

        $beneficio->update($data);

        return redirect()->route('beneficios-cargo.index')
            ->with('success', 'Beneficio actualizado correctamente.');
    }

    public function destroyBeneficio(Beneficio $beneficio)
    {
        $beneficio->delete();

        return redirect()->route('beneficios-cargo.index')
            ->with('success', 'Beneficio eliminado correctamente.');
    }
}
