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
        <td>{{ $mov->account?->name ?? '—' }}</td>
        <td>{{ $mov->service?->name ?? '—' }}</td>
        <td>{{ $mov->category?->name ?? '—' }}</td>
        <td class="text-truncate" style="max-width:200px;">{{ $mov->description ?? '—' }}</td>
        <td>
            @if($mov->status)
                <span class="badge badge-soft-success fs-xxs">Active</span>
            @else
                <span class="badge badge-soft-danger fs-xxs">Inactive</span>
            @endif
        </td>
        <td>
            <div class="d-flex justify-content-center gap-1">
                <button class="btn btn-light btn-icon btn-sm rounded-circle"
                    data-edit-url="{{ route('movements.show', $mov) }}"
                    data-update-url="{{ route('movements.update', $mov) }}"
                    title="Edit">
                    <i class="ti ti-edit fs-lg"></i>
                </button>
                <form action="{{ route('movements.destroy', $mov) }}" method="POST"
                    onsubmit="return confirm('Delete this movement?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-light btn-icon btn-sm rounded-circle" title="Delete">
                        <i class="ti ti-trash fs-lg"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="text-center text-muted py-4">No movements found.</td>
    </tr>
@endforelse
