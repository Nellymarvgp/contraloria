<?php

namespace App\Imports;

use App\Models\Empleado;
use App\Models\NivelRango;
use App\Models\GrupoCargo;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmpleadosImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Find nivel_rango by descripcion
        $nivelRango = NivelRango::where('descripcion', $row['nivel_rango'])->first();
        // Find grupo_cargo by descripcion
        $grupoCargo = GrupoCargo::where('descripcion', $row['grupo_cargo'])->first();

        return new Empleado([
            'nombre' => $row['nombre'],
            'apellido' => $row['apellido'],
            'cedula' => $row['cedula'],
            'email' => $row['email'],
            'nivel_rango_id' => $nivelRango ? $nivelRango->id : null,
            'grupo_cargo_id' => $grupoCargo ? $grupoCargo->id : null,
            'tipo_cargo' => $this->mapTipoCargo($row['tipo_cargo'] ?? null),
            'estado' => isset($row['estado']) ? ($row['estado'] == 'Activo' ? 1 : 0) : 1,
            // Add more fields as needed
        ]);
    }

    private function mapTipoCargo($text)
    {
        $map = [
            'administrativo' => 'administrativo',
            'técnico superior universitario' => 'tecnico_superior',
            'tecnico superior universitario' => 'tecnico_superior',
            'profesional universitario' => 'profesional_universitario',
        ];
        $text = strtolower(trim($text));
        return $map[$text] ?? null;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required',
            'apellido' => 'required',
            'cedula' => 'required',
            'email' => 'required|email',
            'nivel_rango' => 'required',
            'grupo_cargo' => 'required',
            'tipo_cargo' => 'required',
        ];
    }
}
