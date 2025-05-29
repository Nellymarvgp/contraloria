@extends('layouts.dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Nuevo Parámetro de Nómina</h2>
                </div>

                <form action="{{ route('payroll-parameters.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="codigo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código</label>
                            <input type="text" name="codigo" id="codigo" value="{{ old('codigo') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('codigo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('nombre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="campo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Campo</label>
                            <select name="campo" id="campo"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Seleccione un campo</option>
                                <option value="txt_1" {{ old('campo') == 'txt_1' ? 'selected' : '' }}>TXT1</option>
                                <option value="txt_2" {{ old('campo') == 'txt_2' ? 'selected' : '' }}>TXT2</option>
                                <option value="txt_3" {{ old('campo') == 'txt_3' ? 'selected' : '' }}>TXT3</option>
                                <option value="txt_4" {{ old('campo') == 'txt_4' ? 'selected' : '' }}>TXT4</option>
                                <option value="txt_5" {{ old('campo') == 'txt_5' ? 'selected' : '' }}>TXT5</option>
                                <option value="txt_6" {{ old('campo') == 'txt_6' ? 'selected' : '' }}>TXT6</option>
                                <option value="txt_7" {{ old('campo') == 'txt_7' ? 'selected' : '' }}>TXT7</option>
                                <option value="txt_8" {{ old('campo') == 'txt_8' ? 'selected' : '' }}>TXT8</option>
                                <option value="txt_9" {{ old('campo') == 'txt_9' ? 'selected' : '' }}>TXT9</option>
                                <option value="txt_10" {{ old('campo') == 'txt_10' ? 'selected' : '' }}>TXT10</option>
                            </select>
                            @error('campo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="valor_defecto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valor por Defecto</label>
                            <input type="number" name="valor_defecto" id="valor_defecto" value="{{ old('valor_defecto') }}" required step="0.01" min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('valor_defecto')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="activo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                            <select name="activo" id="activo"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="1" {{ old('activo', '1') == '1' ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ old('activo') == '0' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('activo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('payroll-parameters.index') }}" 
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition">
                            Cancelar
                        </a>
                        <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
