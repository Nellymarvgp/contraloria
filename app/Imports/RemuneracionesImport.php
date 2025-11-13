<?php

namespace App\Imports;

use App\Models\Remuneracion;
use App\Models\NivelRango;
use App\Models\GrupoCargo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class RemuneracionesImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Find nivel_rango by descripcion
        $nivelRango = NivelRango::where('descripcion', $row['nivel_rango'])->first();
        if (!$nivelRango) {
            return null;
        }

        // Find grupo_cargo by descripcion
        $grupoCargo = GrupoCargo::where('descripcion', $row['grupo_cargo'])->first();
        if (!$grupoCargo) {
            return null;
        }

        // Map tipo_cargo from text to value
        $tipoCargo = $this->mapTipoCargo($row['tipo_cargo']);
        if (!$tipoCargo) {
            return null;
        }

        // Map tipo_personal from text to value
        $tipoPersonal = $this->mapTipoPersonal($row['tipo_personal']);
        if (!$tipoPersonal) {
            return null;
        }

        return new Remuneracion([
            'nivel_rango_id' => $nivelRango->id,
            'grupo_cargo_id' => $grupoCargo->id,
            'tipo_cargo' => $tipoCargo,
            'tipo_personal' => $tipoPersonal,
            'valor' => $row['valor'],
            'estado' => isset($row['estado']) ? ($row['estado'] == 'Activo' ? 1 : 0) : 1,
        ]);
    }

    /**
     * Map tipo_cargo from text to value
     */
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

    /**
     * Map tipo_personal from text to value
     */
    private function mapTipoPersonal($text)
    {
        $map = [
            'obreros' => 'obreros',
            'administración pública' => 'administracion_publica',
            'administracion publica' => 'administracion_publica',
        ];

        $text = strtolower(trim($text));
        return $map[$text] ?? null;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'nivel_rango' => 'required',
            'grupo_cargo' => 'required',
            'tipo_cargo' => 'required',
            'tipo_personal' => 'required',
            'valor' => 'required|numeric',
        ];
    }
}
