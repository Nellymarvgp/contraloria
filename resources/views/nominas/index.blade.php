@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Nóminas</h1>
        <a href="{{ route('nominas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Nueva nómina
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($nominas->isEmpty())
                <div class="alert alert-info mb-0">
                    No hay nóminas registradas. Cree una nueva nómina para empezar.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Descripción</th>
                                <th>Período</th>
                                <th>Estado</th>
                                <th>Total</th>
                                <th>Fecha de creación</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nominas as $nomina)
                                <tr>
                                    <td>{{ $nomina->id }}</td>
                                    <td>{{ $nomina->descripcion }}</td>
                                    <td>{{ $nomina->fecha_inicio->format('d/m/Y') }} - {{ $nomina->fecha_fin->format('d/m/Y') }}</td>
                                    <td>
                                        @if($nomina->estado == 'borrador')
                                            <span class="badge bg-warning text-dark">Borrador</span>
                                        @elseif($nomina->estado == 'aprobada')
                                            <span class="badge bg-success">Aprobada</span>
                                        @elseif($nomina->estado == 'pagada')
                                            <span class="badge bg-info">Pagada</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($nomina->total_monto, 2, ',', '.') }}</td>
                                    <td>{{ $nomina->created_at->format('d/m/Y') }}</td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="{{ route('nominas.show', $nomina) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($nomina->estado == 'borrador')
                                                <form action="{{ route('nominas.destroy', $nomina) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta nómina?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $nominas->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
