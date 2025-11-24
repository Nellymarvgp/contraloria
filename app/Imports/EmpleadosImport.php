<?php

namespace App\Imports;

use App\Models\Empleado;
use App\Models\NivelRango;
use App\Models\GrupoCargo;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class EmpleadosImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        try {
            // Buscar o crear el usuario asociado
            $user = User::firstOrCreate([
                'email' => $row['email'],
            ], [
                'nombre' => $row['nombre'],
                'apellido' => $row['apellido'],
                'cedula' => $row['cedula'],
                'password' => Hash::make(Str::random(10)), // Contraseña aleatoria, puedes notificar luego
                'role_id' => $row['role_id'] ?? 2, // Rol por defecto (2 = Usuario normal)
            ]);

            // Determinar los IDs de nivel_rango y grupo_cargo
            // Primero intenta usar el ID si está presente en el archivo
            $nivel_rango_id = null;
            if (isset($row['nivel_rango_id']) && !empty($row['nivel_rango_id'])) {
                $nivel_rango_id = $row['nivel_rango_id'];
            } 
            // Si no hay ID pero sí un nombre, buscar por nombre
            elseif (!empty($row['nivel_rango'])) {
                $nivelRango = NivelRango::where('nombre', 'like', '%' . $row['nivel_rango'] . '%')
                    ->orWhere('descripcion', 'like', '%' . $row['nivel_rango'] . '%')
                    ->first();
                $nivel_rango_id = $nivelRango ? $nivelRango->id : null;
            }
            
            // Igual para grupo_cargo
            $grupo_cargo_id = null;
            if (isset($row['grupo_cargo_id']) && !empty($row['grupo_cargo_id'])) {
                $grupo_cargo_id = $row['grupo_cargo_id'];
            } 
            elseif (!empty($row['grupo_cargo'])) {
                $grupoCargo = GrupoCargo::where('nombre', 'like', '%' . $row['grupo_cargo'] . '%')
                    ->orWhere('descripcion', 'like', '%' . $row['grupo_cargo'] . '%')
                    ->first();
                $grupo_cargo_id = $grupoCargo ? $grupoCargo->id : null;
            }

            // Determinar fecha de ingreso segura
            $fechaIngreso = isset($row['fecha_ingreso']) && !empty($row['fecha_ingreso'])
                ? Carbon::parse($row['fecha_ingreso'])
                : Carbon::now();

            // Calcular tiempo de antigüedad en años
            $tiempoAntiguedad = $fechaIngreso->diffInYears(Carbon::now());

            // Crear el empleado asociado al usuario con valores seguros
            return new Empleado([
                'cedula' => $row['cedula'],
                'cargo_id' => $row['cargo_id'] ?? 1, // Asignar valor por defecto
                'departamento_id' => $row['departamento_id'] ?? 1, // Asignar valor por defecto
                'horario_id' => $row['horario_id'] ?? 1, // Asignar valor por defecto
                'estado_id' => $row['estado_id'] ?? 1,
                'salario' => $row['salario'] ?? 0,
                'fecha_ingreso' => $fechaIngreso->format('Y-m-d'),
                'tiempo_antiguedad' => $tiempoAntiguedad,
                'observaciones' => $row['observaciones'] ?? null,
                'prima_antiguedad_id' => $row['prima_antiguedad_id'] ?? null,
                'prima_profesionalizacion_id' => $row['prima_profesionalizacion_id'] ?? null,
                'nivel_rango_id' => $nivel_rango_id,
                'grupo_cargo_id' => $grupo_cargo_id,
                'tipo_cargo' => $this->mapTipoCargo($row['tipo_cargo'] ?? ''),
                'tiene_hijos' => $row['tiene_hijos'] ?? 0,
                'cantidad_hijos' => $row['cantidad_hijos'] ?? 0,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al importar empleado: ' . $e->getMessage());
            return null; // Ignorar esta fila en caso de error
        }
    }

    private function mapTipoCargo($text)
    {
        if (empty($text)) {
            return 'administrativo'; // valor por defecto
        }
        
        $map = [
            'administrativo' => 'administrativo',
            'admin' => 'administrativo',
            'técnico superior universitario' => 'tecnico_superior',
            'tecnico superior universitario' => 'tecnico_superior',
            'tecnico superior' => 'tecnico_superior',
            'tsu' => 'tecnico_superior',
            'profesional universitario' => 'profesional_universitario',
            'profesional' => 'profesional_universitario',
            'universitario' => 'profesional_universitario',
        ];
        
        $text = strtolower(trim($text));
        return array_key_exists($text, $map) ? $map[$text] : 'administrativo';
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required',
            'apellido' => 'required',
            'cedula' => 'required',
            'email' => 'required|email',
            // Hacemos estos campos opcionales
            'nivel_rango' => 'nullable',
            'grupo_cargo' => 'nullable',
            'tipo_cargo' => 'nullable',
            'cargo_id' => 'nullable',
            'departamento_id' => 'nullable',
            'horario_id' => 'nullable',
        ];
    }
}
