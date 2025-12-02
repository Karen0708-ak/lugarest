@extends('layouts.travelix')

@section('content')

<div class="container my-5">
    <h1 class="text-center mb-4" style="color:white;">Listado de Lugares Turísticos</h1>

    <div class="text-end mb-3">
        <a href="{{ route('lugarest.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Nuevo Lugar
        </a>
    </div>

    <div class="table-responsive" style="background: rgba(141, 79, 255, 0.16); padding:20px; border-radius:12px; backdrop-filter: blur(5px);">
        <table class="table table-hover table-bordered align-middle" id="lugares-table">
            <thead style="background-color: rgba(0, 0, 0, 0.85); color:white;">
                <tr>
                    <th>Nombre</th>
                    <th>Provincia</th>
                    <th>Tipo de Atracción</th>
                    <th>Latitud</th>
                    <th>Longitud</th>
                    <th>Año Creación</th>
                    <th>Accesibilidad</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lugares as $lugar)
                <tr>
                    <td>{{ $lugar->NombreLugar }}</td>
                    <td>{{ $lugar->provincia->Nombre ?? 'N/A' }}</td>
                    <td>{{ $lugar->tipo->Nombre ?? 'N/A' }}</td>
                    <td>{{ $lugar->Latitud }}</td>
                    <td>{{ $lugar->Longitud }}</td>
                    <td>{{ $lugar->AnioCreacion }}</td>
                    <td>{{ $lugar->Accesibilidad }}</td>
                    <td class="text-center">
                        <a href="{{ route('lugarest.edit', $lugar->IdLugar) }}" class="btn btn-sm btn-primary mb-1">
                            Editar
                        </a>

                        <form action="{{ route('lugarest.destroy', $lugar->IdLugar) }}" method="POST" class="d-inline-block mb-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger btn-eliminar">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-white">No hay lugares registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    // Confirmación de eliminación
    $('.btn-eliminar').on('click', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            } else {
                Swal.fire(
                    'Cancelado',
                    'El lugar turístico no fue eliminado.',
                    'info'
                );
            }
        });
    });

    // Inicializar DataTable
    $('#lugares-table').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/2.3.1/i18n/es-ES.json' },
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });
});
</script>

@endsection
