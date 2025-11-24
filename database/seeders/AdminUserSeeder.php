<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Crea el usuario admin si no existe ya por cédula o email
        User::updateOrCreate(
            [
                'cedula' => '000000',
            ],
            [
                'nombre' => 'Admin',
                'apellido' => 'Sitno',
                'email' => 'admin@sitno.com',
                'password' => Hash::make('sitno123.'),
                'role_id' => 1,
            ]
        );
    }
}
