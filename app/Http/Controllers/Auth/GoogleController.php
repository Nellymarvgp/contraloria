<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->with(['verify' => false])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->with(['verify' => false])
                ->user();
            
            // Primero buscar por google_id
            $user = User::where('google_id', $googleUser->id)->first();

            // Si no se encuentra, buscar por email
            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();
                
                // Si existe el usuario con ese email, actualizar su google_id
                if ($user) {
                    $user->update([
                        'google_id' => $googleUser->id
                    ]);
                } else {
                    // Si no existe el usuario, crear uno nuevo
                    $empleadoRole = Role::where('nombre', 'Empleado')->first();
                    if (!$empleadoRole) {
                        throw new Exception('No se encontró el rol de Empleado');
                    }

                    // Generar una cédula temporal única
                    $tempCedula = 'G' . substr($googleUser->id, -8);
                    
                    // Separar el nombre completo
                    $fullName = $googleUser->name;
                    $nameParts = explode(' ', $fullName);
                    $nombre = $nameParts[0];
                    $apellido = count($nameParts) > 1 ? end($nameParts) : '';

                    // Crear nuevo usuario
                    $user = User::create([
                        'cedula' => $tempCedula,
                        'nombre' => $nombre,
                        'apellido' => $apellido,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'role_id' => $empleadoRole->id,
                        'password' => bcrypt(Str::random(16))
                    ]);
                }
            }

            Auth::login($user);
            return redirect()->route('dashboard');

        } catch (Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Error al iniciar sesión con Google: ' . $e->getMessage());
        }
    }
}
