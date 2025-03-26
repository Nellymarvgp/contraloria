@extends('layouts.dashboard')

@section('title', 'Editar Usuario')
@section('header', 'Editar Usuario')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="{{ route('users.update', $user->id) }}" method="POST" id="editUserForm" novalidate>
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="cedula">
                    Cédula
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('cedula') border-red-500 @enderror"
                    id="cedula" type="text" name="cedula" value="{{ old('cedula', $user->cedula) }}" required>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="cedula-error"></p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">
                    Nombre
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nombre') border-red-500 @enderror"
                    id="nombre" type="text" name="nombre" value="{{ old('nombre', $user->nombre) }}" required>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="nombre-error"></p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="apellido">
                    Apellido
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('apellido') border-red-500 @enderror"
                    id="apellido" type="text" name="apellido" value="{{ old('apellido', $user->apellido) }}" required>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="apellido-error"></p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror"
                    id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="email-error"></p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Contraseña <span class="text-sm font-normal text-gray-500">(dejar en blanco para mantener la actual)</span>
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror"
                    id="password" type="password" name="password">
                <p class="text-red-500 text-xs italic mt-1 hidden" id="password-error"></p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">
                    Confirmar Contraseña
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="password_confirmation" type="password" name="password_confirmation">
                <p class="text-red-500 text-xs italic mt-1 hidden" id="password-confirmation-error"></p>
            </div>

            @if(auth()->user()->isAdmin())
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="role_id">
                    Rol
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('role_id') border-red-500 @enderror"
                    id="role_id" name="role_id" required>
                    <option value="">Seleccione un rol</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                            {{ $role->nombre }}
                        </option>
                    @endforeach
                </select>
                <p class="text-red-500 text-xs italic mt-1 hidden" id="role-error"></p>
            </div>
            @endif

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Actualizar Usuario
                </button>
                <a href="{{ auth()->user()->isAdmin() ? route('users.index') : route('dashboard') }}" class="text-gray-600 hover:text-gray-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editUserForm');
    const inputs = {
        cedula: {
            element: document.getElementById('cedula'),
            error: document.getElementById('cedula-error'),
            regex: /^[0-9]+$/,
            message: 'La cédula solo debe contener números'
        },
        nombre: {
            element: document.getElementById('nombre'),
            error: document.getElementById('nombre-error'),
            regex: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/,
            message: 'El nombre solo debe contener letras'
        },
        apellido: {
            element: document.getElementById('apellido'),
            error: document.getElementById('apellido-error'),
            regex: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/,
            message: 'El apellido solo debe contener letras'
        },
        email: {
            element: document.getElementById('email'),
            error: document.getElementById('email-error'),
            regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            message: 'Ingrese un correo electrónico válido'
        }
    };

    // Real-time validation
    Object.keys(inputs).forEach(key => {
        const input = inputs[key];
        
        input.element.addEventListener('input', function() {
            validateField(key);
        });

        input.element.addEventListener('blur', function() {
            validateField(key);
        });
    });

    function validateField(fieldName) {
        const field = inputs[fieldName];
        const value = field.element.value.trim();
        
        if (value === '') {
            showError(field, 'Este campo es requerido');
            return false;
        }

        if (!field.regex.test(value)) {
            showError(field, field.message);
            return false;
        }

        hideError(field);
        return true;
    }

    function showError(field, message) {
        field.element.classList.add('border-red-500');
        field.error.textContent = message;
        field.error.classList.remove('hidden');
    }

    function hideError(field) {
        field.element.classList.remove('border-red-500');
        field.error.classList.add('hidden');
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Validate all fields
        Object.keys(inputs).forEach(key => {
            if (!validateField(key)) {
                isValid = false;
            }
        });

        // Password validation (only if password is being changed)
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirmation');
        const passwordError = document.getElementById('password-error');
        const passwordConfirmError = document.getElementById('password-confirmation-error');

        if (password.value !== '') {
            if (password.value.length < 8) {
                showError({element: password, error: passwordError}, 'La contraseña debe tener al menos 8 caracteres');
                isValid = false;
            } else if (password.value !== passwordConfirm.value) {
                showError({element: passwordConfirm, error: passwordConfirmError}, 'Las contraseñas no coinciden');
                isValid = false;
            }
        }

        // Role validation (only if role field exists)
        const role = document.getElementById('role_id');
        if (role) {
            const roleError = document.getElementById('role-error');
            if (!role.value) {
                showError({element: role, error: roleError}, 'Seleccione un rol');
                isValid = false;
            }
        }

        if (!isValid) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
