<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::create([
            'nombre' => 'Administrador',
            'permisos' => json_encode(['all']),
        ]);

        Role::create([
            'nombre' => 'Empleado',
            'permisos' => json_encode(['view_profile', 'edit_profile']),
        ]);
    }
}
