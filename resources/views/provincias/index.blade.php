@extends('layouts.travelix')

@section('content')

<div class="container my-5">
    <h1 class="text-center mb-4" style="color:white;">Listado de Provincias</h1>

    <div class="text-end mb-3">
        <a href="{{ route('provincias.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Nueva Provincia
        </a>
    </div>

    <div class="table-responsive" style="background: rgba(141, 79, 255, 0.16); padding:20px; border-radius:12px; backdrop-filter: blur(3px);">
        <table class="table table-hover table-bordered align-middle" id="provincias-table">
            <thead style="background-color: rgba(0,0,0,0.6); color:white;">
                <tr>
                    <th>Nombre</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($provincias as $provincia)
                <tr>
                    <td>{{ $provincia->Nombre }}</td>
                    <td class="text-center">
                        <a href="{{ route('provincias.edit', $provincia->Id) }}" class="btn btn-sm btn-primary mb-1">
                            Editar
                        </a>

                        <form action="{{ route('provincias.destroy', $provincia->Id) }}" method="POST" class="d-inline-block mb-1">
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
                    <td colspan="2" class="text-center text-white">No hay provincias registradas.</td>
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
                    'La provincia no fue eliminada.',
                    'info'
                );
            }
        });
    });

    // Inicializar DataTable
    $('#provincias-table').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/2.3.1/i18n/es-ES.json' },
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });
});
</script>

@endsection
