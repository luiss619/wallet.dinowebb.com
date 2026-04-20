@forelse($categories as $category)
    <tr>
        <td class="text-muted fs-xs">{{ $category->id }}</td>
        <td class="fw-medium">{{ $category->name }}</td>
        <td>
            @if($category->status)
                <span class="badge badge-soft-success fs-xxs">Active</span>
            @else
                <span class="badge badge-soft-danger fs-xxs">Inactive</span>
            @endif
        </td>
        <td>
            <div class="d-flex justify-content-center gap-1">
                <button class="btn btn-light btn-icon btn-sm rounded-circle"
                    data-edit-url="{{ route('categories.show', $category) }}"
                    data-update-url="{{ route('categories.update', $category) }}"
                    title="Edit">
                    <i class="ti ti-edit fs-lg"></i>
                </button>
                <form action="{{ route('categories.destroy', $category) }}" method="POST"
                    data-delete-form>
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
        <td colspan="4" class="text-center text-muted py-4">No categories found.</td>
    </tr>
@endforelse
