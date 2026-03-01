@forelse($subcategories as $sub)
    <tr>
        <td class="fw-medium">{{ $sub->name }}</td>
        <td>{{ $sub->category?->name ?? '—' }}</td>
        <td>
            @if($sub->status)
                <span class="badge badge-soft-success fs-xxs">Active</span>
            @else
                <span class="badge badge-soft-danger fs-xxs">Inactive</span>
            @endif
        </td>
        <td>
            <div class="d-flex justify-content-center gap-1">
                <button class="btn btn-light btn-icon btn-sm rounded-circle"
                    data-edit-url="{{ route('subcategories.show', $sub) }}"
                    data-update-url="{{ route('subcategories.update', $sub) }}"
                    title="Edit">
                    <i class="ti ti-edit fs-lg"></i>
                </button>
                <form action="{{ route('subcategories.destroy', $sub) }}" method="POST"
                    onsubmit="return confirm('Delete this subcategory?')">
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
        <td colspan="4" class="text-center text-muted py-4">No subcategories found.</td>
    </tr>
@endforelse
