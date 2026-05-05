@forelse($movements as $mov)
    <tr>
        <td class="text-muted fs-xs">{{ $mov->id }}</td>
        <td>{{ $mov->date->format('d/m/Y') }}</td>
        <td>
            @if($mov->quantity > 0)
                <span class="text-success fw-semibold">+{{ number_format($mov->quantity, 2) }}</span>
            @else
                <span class="text-danger fw-semibold">{{ number_format($mov->quantity, 2) }}</span>
            @endif
        </td>
        <td>
            @if($mov->type == 1)
                <span class="badge badge-soft-warning fs-xxs">Transferencia</span>
            @elseif($mov->type == 2)
                <span class="badge badge-soft-info fs-xxs">Ahorro</span>
            @elseif($mov->type == 3)
                <span class="badge fs-xxs" style="background:#6c757d; color:#fff;">Paso</span>
            @else
                <span class="badge badge-soft-primary fs-xxs">Normal</span>
            @endif
        </td>
        <td>{{ $mov->account?->name ?? '—' }}</td>
        <td>{{ $mov->service?->name ?? '—' }}</td>
        <td class="text-truncate" style="max-width:200px;">{{ $mov->description ?? '—' }}</td>
        <td>
            @if($mov->status)
                <span class="badge badge-soft-success fs-xxs">Activo</span>
            @else
                <span class="badge badge-soft-danger fs-xxs">Inactivo</span>
            @endif
        </td>
        <td>
            <div class="d-flex justify-content-center gap-1">
                <button class="btn btn-light btn-icon btn-sm rounded-circle"
                    data-edit-url="{{ route('movements.show', $mov) }}"
                    data-update-url="{{ route('movements.update', $mov) }}"
                    title="Editar">
                    <i class="ti ti-edit fs-lg"></i>
                </button>
                <form action="{{ route('movements.destroy', $mov) }}" method="POST"
                    data-delete-form>
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-icon btn-sm rounded-circle" title="Eliminar">
                        <i class="ti ti-trash fs-lg"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="text-center text-muted py-4">No se encontraron movimientos.</td>
    </tr>
@endforelse
