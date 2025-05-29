@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Nueva Nómina</h1>
                <a href="{{ route('nominas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('nominas.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input type="text" class="form-control @error('descripcion') is-invalid @enderror" 
                                id="descripcion" name="descripcion" value="{{ old('descripcion') }}" 
                                placeholder="Ej: Nómina Quincena 1 - Abril 2025" required>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="despacho" class="form-label">Despacho</label>
                            <input type="text" class="form-control @error('despacho') is-invalid @enderror" 
                                id="despacho" name="despacho" value="{{ old('despacho') }}" 
                                placeholder="Ej: Contraloría General">
                            @error('despacho')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                    <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror" 
                                        id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
                                    @error('fecha_inicio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                    <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror" 
                                        id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}" required>
                                    @error('fecha_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Después de crear la nómina, podrá generar los cálculos automáticamente para todos los empleados activos.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Crear Nómina
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
